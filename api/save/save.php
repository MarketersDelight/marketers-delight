<?php
/**
 * One save path for every MD setting. apply() validates a group's values
 * against its registered fields, merges them over what's stored and
 * writes the result to the option, post, term or user it belongs to.
 *
 * The methods below follow apply() in order:
 *
 *   apply( $scope, $group, $input )
 *     -> targets()                              — where each group lives, see md_scope in api/save/scope.php
 *     -> md_validate::fields()                  — the keys given, see api/save/validate.php
 *     -> post_values()                          — post meta filter and per-box permissions
 *     -> read()                                 — what's stored now
 *     -> merge_fields() / merge_clone_items()   — the new values over it
 *     -> diff()                                 — what changed, for the result
 *     -> write()                                — update_option / update_metadata
 *     -> after_save()                           — compile CSS/JS, rebuild rewrite rules next request
 *
 * Settings, meta box, term and user forms save through
 * admin/save-settings.php, which calls apply().
 *
 * @since 6.0
 */

class md_save {

	private $validate;

	private $skipped = array();

	public function __construct() {
		$this->validate = new md_validate;
	}

	/**
	 * Save one group's values to a scope. $group can also be a list of
	 * groups, then $input is keyed by group, ex: every meta box a post
	 * form submitted. Only the keys given change, and null resets a key.
	 *
	 * dry_run : return what would change without writing anything.
	 * compile : run after_save() once the values are written.
	 *
	 * Returns array( scope, group, changed => array( key, before, after ), warnings )
	 * or a WP_Error.
	 *
	 * @since 6.2
	 */

	public function apply( $scope, $group, $input, $args = array() ) {
		$args = array_merge( array(
			'dry_run' => false,
			'compile' => true
		), (array) $args );
		$where = md_scope::parse( $scope );

		if ( is_wp_error( $where ) )
			return $where;

		if ( in_array( $where->type, array( 'post', 'term', 'user' ), true ) && ! $where->id )
			return new WP_Error( 'md_save_scope', sprintf( __( 'Saving to %s needs an ID.', 'md' ), $scope ), array( 'status' => 400 ) );

		if ( ! $where->can() )
			return new WP_Error( 'md_save_forbidden', __( 'Sorry, you are not allowed to change these settings.', 'md' ), array( 'status' => 403 ) );

		$groups = is_array( $group ) ? $group : array( $group );
		$targets = $this->targets( $where, $groups );

		if ( is_wp_error( $targets ) )
			return $targets;

		// Validate each group's values against its fields

		$saves = $warnings = array();

		foreach ( $groups as $key ) {
			$values = is_array( $group ) ? ( isset( $input[$key] ) ? $input[$key] : array() ) : $input;
			$saves[$key] = $this->validate->fields( is_array( $values ) ? $values : array(), $targets[$key]['fields'] );
			$warnings = array_merge( $warnings, $this->validate->warnings() );
		}

		if ( $where->type === 'post' )
			$saves = $this->post_values( $where->id, $saves, $warnings );

		// Merge each group over what's stored

		$option = $this->option( $targets );
		$stored = $this->read( $where, $option );
		$new = $stored;
		$changed = $this->skipped = array();

		foreach ( $saves as $key => $save ) {
			$path = isset( $targets[$key] ) ? $targets[$key]['path'] : array( $key );
			$old = $this->get_path( $stored, $path );

			if ( $where->type === 'post' && isset( $targets[$key] ) )
				$old = $this->standalone_meta( $where->id, $targets[$key]['fields'], $old );

			// Values a md_post_meta_save filter adds without registered fields are stored as given

			if ( isset( $targets[$key] ) )
				$branch = $this->merge_fields( is_array( $old ) ? $old : array(), is_array( $save ) ? $save : array(), $targets[$key]['fields'] );
			else
				$branch = $save;

			if ( $where->type === 'taxonomy' || $this->is_meta( $where ) )
				$branch = $this->prune_empty( $branch );

			$new = $this->set_path( $new, $path, $branch );
			$changed = array_merge( $changed, $this->diff( $old, $branch, is_array( $group ) ? "$key." : '' ) );
		}

		$result = array(
			'scope' => $scope,
			'group' => $group,
			'changed' => $changed,
			'warnings' => array_merge( $warnings, $this->skipped )
		);

		if ( $args['dry_run'] )
			return $result;

		$this->write( $where, $option, $new, array_keys( $saves ) );

		if ( $args['compile'] && in_array( $where->type, array( 'site', 'post_type', 'taxonomy' ), true ) )
			$this->after_save();

		return $result;
	}

	/**
	 * Where each group saves in a scope. One save writes one option, so
	 * the groups must share it.
	 *
	 * @since 6.2
	 */

	private function targets( $scope, $groups ) {
		$targets = $options = array();

		foreach ( $groups as $group ) {
			$target = $scope->target( $group );

			if ( is_wp_error( $target ) )
				return $target;

			$targets[$group] = $target;
			$options[$target['option']] = true;
		}

		if ( count( $options ) > 1 )
			return new WP_Error( 'md_save_option', __( 'These settings groups are saved to different options.', 'md' ), array( 'status' => 400 ) );

		return $targets;
	}

	/**
	 * The option a save writes to. Meta scopes name marketers_delight,
	 * their meta key.
	 *
	 * @since 6.2
	 */

	private function option( $targets ) {
		foreach ( $targets as $target )
			return $target['option'];

		return 'marketers_delight';
	}

	/**
	 * Post meta boxes can be changed by other code before they save, and
	 * groups that need manage_options are left out for post editors.
	 *
	 * @since 6.2
	 */

	private function post_values( $post_id, $values, &$warnings ) {
		$registered = md_register( 'meta_boxes' );
		$values = (array) apply_filters( 'md_post_meta_save', $values, get_post( $post_id ) );

		foreach ( array_keys( $values ) as $group ) {
			if ( ! isset( $registered[$group] ) )
				continue;

			$capability = ! empty( $registered[$group]['edit_post'] ) ? 'edit_post' : 'manage_options';

			if ( ! current_user_can( $capability, $post_id ) ) {
				unset( $values[$group] );
				$warnings[] = sprintf( __( 'You are not allowed to change the `%s` settings, so they were left as they are.', 'md' ), $group );
			}
		}

		return $values;
	}

	/**
	 * Read the stored values a scope writes to. Post, term and user
	 * settings are the object's marketers_delight meta, read through the
	 * metadata API every object type shares.
	 *
	 * @since 6.2
	 */

	private function read( $scope, $option ) {
		if ( $this->is_meta( $scope ) )
			$stored = get_metadata( $scope->type, $scope->id, 'marketers_delight', true );
		else
			$stored = get_option( $option, array() );

		return is_array( $stored ) ? $stored : array();
	}

	/**
	 * Standalone post meta is stored in its own key. Read it into its
	 * group, the way the fields show it.
	 *
	 * @since 6.2
	 */

	private function standalone_meta( $post_id, $fields, $group ) {
		foreach ( $fields as $key => $field ) {
			if ( empty( $field['standalone'] ) )
				continue;

			$value = get_post_meta( $post_id, $key, true );

			if ( $value !== '' ) {
				$group = is_array( $group ) ? $group : array();
				$group[$key] = $value;
			}
		}

		return $group;
	}

	/**
	 * Traverses down as many array keys as needed to validate fields.
	 * Detects when groups/builder fields are defined and handles cloneable
	 * field scenarios, otherwise replace values outright.
	 *
	 * @since 6.0
	 */

	private function merge_fields( $old, $save, $fields_schema ) {
		foreach ( $save as $key => $value ) {
			$field = isset( $fields_schema[$key] ) ? $fields_schema[$key] : null;
			$type = is_array( $field ) && isset( $field['type'] ) ? $field['type'] : null;

			// Raw code is executable. Keep an existing value when another editor saves.
			if ( $type === 'code' && ( ! current_user_can( 'manage_options' ) || ! current_user_can( 'unfiltered_html' ) ) ) {
				if ( $value !== ( $old[$key] ?? null ) && ! $this->is_empty_value( $value ) )
					$this->skipped[] = sprintf( __( '`%s` is code, which needs an administrator who can add unfiltered HTML. It was left as it is.', 'md' ), $key );

				continue;
			}

			if ( ! empty( $field['code_options'] ) && ( ! current_user_can( 'manage_options' ) || ! current_user_can( 'unfiltered_html' ) ) &&
				( in_array( $value, $field['code_options'], true ) || in_array( $old[$key] ?? null, $field['code_options'], true ) ) )
				continue;

			if ( in_array( $type, array( 'group', 'builder' ), true ) && is_array( $value ) ) {
				$item_schema = isset( $field['fields'] ) ? $field['fields'] : array();
				$merged = $this->merge_clone_items( isset( $old[$key] ) && is_array( $old[$key] ) ? $old[$key] : array(), $value, $item_schema );

				if ( empty( $merged ) ) {
					if ( ! empty( $field['preserve_empty'] ) )
						$old[$key] = $merged;
					else
						unset( $old[$key] );
				}
				else
					$old[$key] = $merged;
			}
			elseif ( $type !== null || ! is_array( $field ) || ! is_array( $value ) ) {
				if ( $this->is_empty_value( $value ) )
					unset( $old[$key] );
				else
					$old[$key] = $value;
			}
			else {
				$merged = $this->merge_fields(
					isset( $old[$key] ) && is_array( $old[$key] ) ? $old[$key] : array(),
					$value,
					$field
				);

				if ( empty( $merged ) )
					unset( $old[$key] );
				else
					$old[$key] = $merged;
			}
		}

		return $old;
	}

	/**
	 * Merge fields from group/builder fields to retain presence and order
	 * as these fields are repeatable/sortable.
	 *
	 * @since 6.0
	 */

	private function merge_clone_items( $old, $save, $item_schema ) {
		$merged = array();

		foreach ( $save as $item_key => $item_value ) {
			$item = is_array( $item_value ) ? $this->merge_fields(
				isset( $old[$item_key] ) && is_array( $old[$item_key] ) ? $old[$item_key] : array(),
				$item_value,
				$item_schema
			) : $item_value;

			if ( ! $this->is_empty_value( $item ) )
				$merged[$item_key] = $item;
		}

		return $merged;
	}

	/**
	 * Recursively remove empty values after a settings or meta merge.
	 *
	 * @since 6.0
	 */

	private function prune_empty( $value ) {
		if ( ! is_array( $value ) )
			return $value;

		foreach ( $value as $key => $item ) {
			$item = $this->prune_empty( $item );

			if ( $this->is_empty_value( $item ) )
				unset( $value[$key] );
			else
				$value[$key] = $item;
		}

		return $value;
	}

	/**
	 * Values with no stored meaning are deletion signals. Use exact
	 * comparisons so valid zero values remain saved.
	 *
	 * @since 6.0
	 */

	private function is_empty_value( $value ) {
		return $value === '' || $value === false || $value === null || $value === array();
	}

	/**
	 * Get the value at a list of keys, or null when it isn't stored.
	 *
	 * @since 6.2
	 */

	private function get_path( $value, $path ) {
		foreach ( $path as $key ) {
			if ( ! is_array( $value ) || ! array_key_exists( $key, $value ) )
				return null;

			$value = $value[$key];
		}

		return $value;
	}

	/**
	 * Set the value at a list of keys. An empty value removes the key,
	 * and any parent it leaves empty.
	 *
	 * @since 6.2
	 */

	private function set_path( $array, $path, $value ) {
		$key = array_shift( $path );
		$array = is_array( $array ) ? $array : array();

		if ( ! empty( $path ) )
			$value = $this->set_path( isset( $array[$key] ) ? $array[$key] : array(), $path, $value );

		if ( $this->is_empty_value( $value ) )
			unset( $array[$key] );
		else
			$array[$key] = $value;

		return $array;
	}

	/**
	 * List what changed between two branches, one entry per value by its
	 * dotted key, ex: layout.content.full.
	 *
	 * @since 6.2
	 */

	private function diff( $before, $after, $prefix = '' ) {
		$before = $this->flatten( $before, $prefix );
		$after = $this->flatten( $after, $prefix );
		$changed = array();

		foreach ( array_keys( $before + $after ) as $key ) {
			$old = array_key_exists( $key, $before ) ? $before[$key] : null;
			$new = array_key_exists( $key, $after ) ? $after[$key] : null;

			if ( $old !== $new )
				$changed[] = array(
					'key' => (string) $key,
					'before' => $old,
					'after' => $new
				);
		}

		return $changed;
	}

	/**
	 * Flatten nested values to dotted keys: array( 'a' => array( 'b' => 1 ) )
	 * becomes array( 'a.b' => 1 ).
	 *
	 * @since 6.2
	 */

	private function flatten( $value, $prefix = '' ) {
		if ( is_null( $value ) )
			return array();

		if ( ! is_array( $value ) || empty( $value ) )
			return array( rtrim( $prefix, '.' ) => $value );

		$flat = array();

		foreach ( $value as $key => $item )
			$flat += $this->flatten( $item, "$prefix$key." );

		return $flat;
	}

	/**
	 * Write the merged values: site, post type and taxonomy settings to
	 * their option, post, term and user settings to the object's
	 * marketers_delight meta, deleted once nothing is left.
	 *
	 * @since 6.2
	 */

	private function write( $scope, $option, $new, $groups ) {
		if ( ! $this->is_meta( $scope ) ) {
			update_option( $option, apply_filters( "md_save_{$option}", $new ) );
			return;
		}

		if ( $scope->type === 'post' )
			$new = $this->save_standalone_meta( $scope->id, $new, $groups );

		$new = $this->prune_empty( $new );

		if ( $new )
			update_metadata( $scope->type, $scope->id, 'marketers_delight', wp_slash( $new ) );
		else
			delete_metadata( $scope->type, $scope->id, 'marketers_delight' );
	}

	/**
	 * Post, term and user settings are meta; the rest are options.
	 *
	 * @since 6.2
	 */

	private function is_meta( $scope ) {
		return in_array( $scope->type, array( 'post', 'term', 'user' ), true );
	}

	/**
	 * For maximum query compatibility, we may want to save standalone
	 * meta keys outside of the single post_meta key we usually save to,
	 * so a simple standalone => true flag does just that.
	 *
	 * @since 6.0
	 */

	private function save_standalone_meta( $post_id, $save, $groups ) {
		foreach ( md_register( 'meta_boxes' ) as $id => $meta_box ) {
			$capability = ! empty( $meta_box['edit_post'] ) ? 'edit_post' : 'manage_options';
			if ( empty( $meta_box['fields'] ) || ! current_user_can( $capability, $post_id ) )
				continue;

			foreach ( $meta_box['fields'] as $key => $field ) {
				if ( empty( $field['standalone'] ) )
					continue;

				// A saved group without the key had it cleared

				if ( ! isset( $save[$id] ) || ! is_array( $save[$id] ) || ! array_key_exists( $key, $save[$id] ) ) {
					if ( in_array( $id, $groups, true ) )
						delete_post_meta( $post_id, $key );

					continue;
				}

				if ( $save[$id][$key] === '' )
					delete_post_meta( $post_id, $key );
				else
					update_post_meta( $post_id, $key, wp_slash( $save[$id][$key] ) );

				unset( $save[$id][$key] );
			}
		}

		return $save;
	}

	/**
	 * Rebuild what's made from settings on the next request, once
	 * Drop-ins, post types and fields have loaded with the new settings:
	 * the compiled CSS/JS (see md_enqueue::compile_after_upgrade()) and
	 * the rewrite rules, which WordPress rebuilds when they're missing.
	 *
	 * @since 6.2
	 */

	public function after_save() {
		update_option( 'md_compile_after_upgrade', true, false );
		delete_option( 'rewrite_rules' );
	}

}
