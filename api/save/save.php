<?php
/**
 * One save path for every MD setting. apply() validates a group's values
 * against its fields, merges them over what's stored and writes the result.
 *
 * The methods below follow apply() in order. Forms reach it through
 * admin/save-settings.php, and abilities and imports call it directly.
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
	 * Save one group's values, or several keyed by group. Only keys given change;
	 * null resets one. $args: dry_run, compile, merge_items and order. Returns
	 * scope, group, changed and warnings, or a WP_Error.
	 *
	 * @since 6.2
	 */

	public function apply( $scope, $group, $input, $args = array() ) {
		$args = array_merge( array(
			'dry_run' => false,
			'compile' => true,
			'merge_items' => false,
			'order' => array()
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

			if ( $args['merge_items'] )
				$values = self::merge_items( $where->read( $key ), is_array( $values ) ? $values : array(), $targets[$key]['fields'], $args['order'] );

			$saves[$key] = $this->validate->fields( is_array( $values ) ? $values : array(), $targets[$key]['fields'] );
			$warnings = array_merge( $warnings, $this->validate->warnings() );
		}

		if ( $where->type === 'post' )
			$saves = $this->post_values( $where->id, $saves, $warnings );

		// Merge each group over what's stored

		$option = $this->option( $targets );
		$stored = $where->stored( $option );
		$new = $stored;
		$changed = $this->skipped = array();

		foreach ( $saves as $key => $save ) {
			$path = isset( $targets[$key] ) ? $targets[$key]['path'] : array( $key );
			$old = $this->get_path( $stored, $path );

			if ( $where->type === 'post' && isset( $targets[$key] ) )
				$old = $where->standalone_meta( $targets[$key]['fields'], $old );

			// Values a md_post_meta_save filter adds without registered fields are stored as given

			if ( isset( $targets[$key] ) )
				$branch = $this->merge_fields( is_array( $old ) ? $old : array(), is_array( $save ) ? $save : array(), $targets[$key]['fields'] );
			else
				$branch = $save;

			if ( $where->type === 'taxonomy' || $where->is_meta() )
				$branch = $this->prune_empty( $branch );

			$new = $this->set_path( $new, $path, $branch );
			if ( $old || $branch )
				$changed = array_merge( $changed, $this->diff( $old, $branch, is_array( $group ) || ! isset( $targets[$key] ) ? "$key." : '' ) );
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
	 * Build input that changes only what's given: stored items stay, an ID
	 * updates or adds one, null removes one. $order rearranges items; IDs it
	 * omits follow.
	 *
	 * @since 6.2
	 */

	public static function merge_items( $stored, $values, $fields, $order = array() ) {
		$input = is_array( $values ) ? $values : array();
		$stored = is_array( $stored ) ? $stored : array();
		$order = is_array( $order ) ? $order : array();

		foreach ( array_keys( $order ) as $key )
			if ( ! array_key_exists( $key, $input ) && isset( $stored[$key] ) )
				$input[$key] = array();

		foreach ( $input as $key => $value ) {
			if ( ! isset( $fields[$key] ) || ! is_array( $fields[$key] ) || ! is_array( $value ) )
				continue;

			$type = isset( $fields[$key]['type'] ) ? $fields[$key]['type'] : null;
			$old = isset( $stored[$key] ) && is_array( $stored[$key] ) ? $stored[$key] : array();

			if ( $type === 'checkbox' )
				$input[$key] = array_replace( $old, $value );
			elseif ( in_array( $type, array( 'group', 'builder' ), true ) ) {
				$input[$key] = array_replace( $old, $value );

				if ( isset( $order[$key] ) && is_array( $order[$key] ) )
					$input[$key] = array_replace( array_intersect_key( array_flip( $order[$key] ), $input[$key] ), $input[$key] );
			}
			elseif ( $type === null )
				$input[$key] = self::merge_items( $old, $value, $fields[$key], isset( $order[$key] ) ? $order[$key] : array() );
		}

		return $input;
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
		$flat_before = $this->flatten( $before, $prefix );
		$flat_after = $this->flatten( $after, $prefix );
		$changed = array();

		foreach ( array_keys( $flat_before + $flat_after ) as $key ) {
			$old = array_key_exists( $key, $flat_before ) ? $flat_before[$key] : null;
			$new = array_key_exists( $key, $flat_after ) ? $flat_after[$key] : null;

			if ( $old !== $new && (string) $key !== '' )
				$changed[] = array(
					'key' => (string) $key,
					'before' => $old,
					'after' => $new
				);
		}

		return array_merge( $changed, $this->moved( $before, $after, $prefix ) );
	}

	/**
	 * List items that only changed places, which flattened values can't
	 * show, with their keys in the old and new order.
	 *
	 * @since 6.2
	 */

	private function moved( $before, $after, $prefix ) {
		if ( ! is_array( $before ) || ! is_array( $after ) )
			return array();

		$old = array_keys( $before );
		$new = array_keys( $after );
		$shared = array_values( array_intersect( $new, $old ) );
		$moved = array();

		if ( $prefix !== '' && $shared !== array_values( array_intersect( $old, $new ) ) )
			$moved[] = array(
				'key' => rtrim( $prefix, '.' ),
				'before' => $old,
				'after' => $new
			);

		foreach ( $shared as $key )
			$moved = array_merge( $moved, $this->moved( $before[$key], $after[$key], "$prefix$key." ) );

		return $moved;
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
		if ( ! $scope->is_meta() ) {
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
	 * Rebuild what's made from settings on the next request, once everything has
	 * loaded: compiled CSS/JS and rewrite rules.
	 *
	 * @since 6.2
	 */

	public function after_save() {
		update_option( 'md_compile_after_upgrade', true, false );
		delete_option( 'rewrite_rules' );
	}

}
