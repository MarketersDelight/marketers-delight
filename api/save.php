<?php
/**
 * Houses the save functions across various settings screen types.
 *
 * Call chain for the Admin Settings API:
 *   admin_save( $input )                                — WP Settings API hook
 *     -> validate->validate( 'admin_pages', $input )     — see api/validate.php
 *     -> merge_settings( $old, $save )
 *          -> merge_recursive( $old, $save )             — top-level keys
 *               -> merge_fields( $old, $save, $schema )  — recurses per field
 *                    -> merge_clone_items( ... )         — group/builder fields
 *                         -> merge_fields( ... )          — merges each item
 *
 * admin_save_custom works the same for a custom option key.
 * Meta, term, and user saves use their own registered field schemas.
 *
 * @since 6.0
 */

class md_save {

	private $validate;

	public function __construct() {
		$this->validate = new md_validate;
	}

	/**
	 * Save the main option on Settings API save.
	 *
	 * @since 4.0
	 */

	public function admin_save( $input ) {
		$settings = get_option( 'marketers_delight', array() );
		$settings = is_array( $settings ) ? $settings : array();
		$input = is_array( $input ) ? $input : array();

		if ( ! empty( $_POST['md_save_taxonomy_post_type'] ) && ! empty( $_POST['md_save_taxonomy'] ) )
			return $this->save_taxonomy( $input, $settings );

		if ( ! $this->is_settings_save( 'marketers_delight' ) )
			return $input;

		$input = $this->settings_input( $input, 'marketers_delight' );
		$save = $this->validate->validate( 'admin_pages', $input );

		return $this->merge_settings( $settings, $save );
	}

	/**
	 * Same as admin_save(), for a custom option key registered by a child
	 * theme or dropin instead of the main option key.
	 *
	 * @since 6.0
	 */

	public function admin_save_custom( $input ) {
		$option = isset( $_POST['option_page'] ) ? sanitize_key( $_POST['option_page'] ) : '';
		$input = is_array( $input ) ? $input : array();

		if ( ! empty( $_POST['md_save_taxonomy_post_type'] ) && ! empty( $_POST['md_save_taxonomy'] ) )
			return $this->save_taxonomy( $input, get_option( $option, array() ) );

		if ( ! $this->is_settings_save( $option ) )
			return $input;

		$settings = get_option( $option, array() );
		$settings = is_array( $settings ) ? $settings : array();
		$input = $this->settings_input( $input, $option );
		$save = $this->validate->validate( 'admin_pages', $input );

		$settings = $this->merge_recursive( $settings, $save );

		return apply_filters( "md_save_{$option}", $settings );
	}

	/**
	 * Only registered top-level settings may come from an options.php form.
	 * Programmatic option updates can contain internal data branches that do
	 * not have admin fields and are validated by their own write handlers.
	 *
	 * @since 6.0
	 */

	private function is_settings_save( $option ) {
		$option_page = isset( $_POST['option_page'] ) ? sanitize_key( $_POST['option_page'] ) : '';
		$action = isset( $_POST['action'] ) ? sanitize_key( $_POST['action'] ) : '';

		return $option_page === $option && $action === 'update';
	}

	/**
	 * Limit an options.php submission to fields owned by its option.
	 *
	 * @since 6.0
	 */

	private function settings_input( $input, $option ) {
		$registered = md_register( 'admin_pages' );
		$allowed = array();

		foreach ( $registered as $key => $settings ) {
			$settings_option = isset( $settings['_option'] ) ? $settings['_option'] : 'marketers_delight';

			if ( $settings_option === $option && ! empty( $settings['fields'] ) )
				$allowed[$key] = true;
		}

		return array_intersect_key( $input, $allowed );
	}

	/**
	 * Standard swapping of old data to new, with special handling so
	 * settings pages that share top level keys across different forms
	 * don't empty on save (eventual AJAX saving will simplify this).
	 *
	 * @since 6.0
	 */

	private function merge_settings( $old, $save ) {
		$new = $this->merge_recursive( $old, $save );
		$groups = apply_filters( 'md_taxonomy_groups', array() );

		foreach ( $groups as $group_id => $group ) {
			$group_id = md_clean_id( $group_id );

			if ( ! isset( $save[$group_id] ) )
				continue;

			foreach ( array_keys( $group ) as $child_slug )
				if ( isset( $old[$group_id][$child_slug] ) )
					$new[$group_id][$child_slug] = $old[$group_id][$child_slug];
		}

		return $new;
	}

	/**
	 * Merge save into old at top level of array. A key with registered
	 * fields is merged recursively, otherwise data is overwritten.
	 *
	 * @since 6.0
	 */

	private function merge_recursive( $old, $save, $settings = 'admin_pages' ) {
		$schema = md_register( $settings );
		$old = is_array( $old ) ? $old : array();

		foreach ( $save as $key => $value ) {
			if ( ! empty( $schema[$key]['fields'] ) && is_array( $value ) ) {
				$merged = $this->merge_fields(
					isset( $old[$key] ) && is_array( $old[$key] ) ? $old[$key] : array(),
					$value,
					$schema[$key]['fields']
				);

				if ( empty( $merged ) )
					unset( $old[$key] );
				else
					$old[$key] = $merged;
			}
			elseif ( $this->is_empty_value( $value ) )
				unset( $old[$key] );
			else
				$old[$key] = $value;
		}

		return $old;
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

			if ( in_array( $type, array( 'group', 'builder' ), true ) && is_array( $value ) ) {
				$item_schema = isset( $field['fields'] ) ? $field['fields'] : array();
				$merged = $this->merge_clone_items( isset( $old[$key] ) && is_array( $old[$key] ) ? $old[$key] : array(), $value, $item_schema );

				if ( empty( $merged ) )
					unset( $old[$key] );
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
			$item = is_array( $item_value )
				? $this->merge_fields(
					isset( $old[$item_key] ) && is_array( $old[$item_key] ) ? $old[$item_key] : array(),
					$item_value,
					$item_schema
				)
				: $item_value;

			if ( ! $this->is_empty_value( $item ) )
				$merged[$item_key] = $item;
		}

		return $merged;
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
	 * Taxonomy settings pages are attached to main settings pages
	 * and come from a different form, so detect that and save onto
	 * the same key level admin page settings group.
	 *
	 * @since 6.0
	 */

	private function save_taxonomy( $input, $settings ) {
		$settings = is_array( $settings ) ? $settings : array();
		$post_type = sanitize_key( $_POST['md_save_taxonomy_post_type'] );
		$taxonomy = sanitize_key( $_POST['md_save_taxonomy'] );
		$group = isset( $input[$post_type][$taxonomy] ) ? $input[$post_type][$taxonomy] : array();
		$options = $this->validate->validate( 'admin_pages', array( $post_type => $group ) );
		$options = isset( $options[$post_type] ) ? $this->prune_empty( $options[$post_type] ) : array();

		if ( ! empty( $options ) )
			$settings[$post_type][$taxonomy] = $options;
		else {
			unset( $settings[$post_type][$taxonomy] );

			if ( empty( $settings[$post_type] ) )
				unset( $settings[$post_type] );
		}

		return $settings;
	}

	/**
	 * Save custom post meta fields.
	 *
	 * @since 4.0
	 */

	public function meta_save( $post_id, $post ) {
		$option = 'marketers_delight';

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		if ( ! isset( $_POST["{$option}_nonce"] ) || ! wp_verify_nonce( $_POST["{$option}_nonce"], "{$option}_nonce" ) )
			return $post_id;

		if ( ! current_user_can( get_post_type_object( $post->post_type )->cap->edit_post, $post_id ) )
			return $post_id;

		if ( ! isset( $_POST[$option] ) )
			return $post_id;

		$value = get_post_meta( $post_id, $option, true );
		$value = is_array( $value ) ? $value : array();
		$save = $this->validate->validate( 'meta_boxes', $_POST[$option] );
		$save = apply_filters( 'md_post_meta_save', $save, $post );
		$save = $this->merge_recursive( $value, $save, 'meta_boxes' );
		$save = $this->prune_empty( $save );

		if ( $save )
			update_post_meta( $post_id, $option, $save );
		elseif ( $value )
			delete_post_meta( $post_id, $option );
	}

	/**
	 * Saves term meta fields.
	 *
	 * @since 4.3.5
	 */

	public function term_save( $term_id ) {
		$option = 'marketers_delight';

		if ( ! current_user_can( 'edit_term', $term_id ) )
			return;

		if ( isset( $_POST[$option] ) && isset( $_POST["{$option}_nonce"] ) && wp_verify_nonce( $_POST["{$option}_nonce"], "{$option}_nonce" ) ) {
			$value = get_term_meta( $term_id, $option, true );
			$value = is_array( $value ) ? $value : array();
			$save = $this->validate->validate( 'terms', $_POST[$option] );
			$save = $this->merge_recursive( $value, $save, 'terms' );
			$save = $this->prune_empty( $save );

			if ( $save )
				update_term_meta( $term_id, $option, $save );
			else
				delete_term_meta( $term_id, $option );
		}
	}

	/**
	 * Saves user meta fields.
	 *
	 * @since 5.3.1
	 */

	public function user_meta_save( $user_id, $old_meta ) {
		$option = 'marketers_delight';

		if ( ! current_user_can( 'edit_user', $user_id ) )
			return;

		if ( ! isset( $_POST[$option] ) || empty( $_POST["{$option}_nonce"] ) || ! wp_verify_nonce( $_POST["{$option}_nonce"], "{$option}_nonce" ) )
			return;

		$value = get_user_meta( $user_id, $option, true );
		$value = is_array( $value ) ? $value : array();
		$save = $this->validate->validate( 'user_meta', $_POST[$option] );
		$save = $this->merge_recursive( $value, $save, 'user_meta' );
		$save = $this->prune_empty( $save );

		if ( $save )
			update_user_meta( $user_id, $option, $save );
		else
			delete_user_meta( $user_id, $option );
	}

}
