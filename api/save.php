<?php
/**
 * WP hook callbacks that save validated settings to their target: the
 * marketers_delight option (or a custom option key), post meta, term meta,
 * or user meta. The only class in this pipeline that touches
 * get_option()/update_option()/post-meta/etc. directly.
 *
 * Call chain for the admin-settings path (admin_save/admin_save_custom):
 *   admin_save( $input )                                — WP Settings API hook
 *     -> validator->validate( 'admin_pages', $input )    — see api/validate.php's call-chain map
 *     -> merge_settings( $old, $save )                   — admin_save() only; wraps merge_recursive()
 *          -> merge_recursive( $old, $save )             — resolves each top-level key's schema
 *               -> merge_fields( $old, $save, $schema )  — the recursive walker; dispatches per key
 *                    -> merge_clone_items( ... )          — group/builder: merge each item...
 *                         -> merge_fields( ... )          — ...by recursing back into the walker
 * admin_save_custom() skips merge_settings()'s taxonomy-group handling and
 * calls merge_recursive() directly — same walker underneath either way.
 *
 * meta_save()/term_save()/user_meta_save() are separate, simpler entry
 * points (post meta, term meta, user meta) — each just validates and writes
 * directly, without going through the merge chain above at all.
 *
 * @since 6.0
 */

class md_save {

	private $validator;

	/**
	 * @since 6.0
	 */

	public function __construct( $validator ) {
		$this->validator = $validator;
	}

	/**
	 * Save valid fields and merge fields on Settings API save.
	 * Make room for taxonomy admin setting workaround in 6.0
	 *
	 * @since 4.0
	 */

	public function admin_save( $input ) {
		if ( ! empty( $_POST['md_save_taxonomy_post_type'] ) && ! empty( $_POST['md_save_taxonomy'] ) )
			return $this->save_taxonomy( $input, md_setting() );

		$settings = md_setting();
		$save = $this->validator->validate( 'admin_pages', $input );

		return $this->merge_settings( $settings, $save );
	}

	/**
	 * Run Settings API data through validation when called from a different
	 * option key from custom child theme/dropin developers not using the
	 * marketers_delight key.
	 *
	 * @since 6.0
	 */

	public function admin_save_custom( $input ) {
		$option = isset( $_POST['option_page'] ) ? sanitize_key( $_POST['option_page'] ) : '';

		if ( ! empty( $_POST['md_save_taxonomy_post_type'] ) && ! empty( $_POST['md_save_taxonomy'] ) )
			return $this->save_taxonomy( $input, get_option( $option, array() ) );

		$settings = get_option( $option, array() );
		$save = $this->validator->validate( 'admin_pages', $input );

		return $this->merge_recursive( $settings, $save );
	}

	/**
	 * Merge $save over $old using the admin_pages schema to decide how: a
	 * top-level key with no registered schema (version, license,
	 * integrations, icons, custom_icons — programmatic data) is replaced
	 * wholesale, matching how validate() passes those through unvalidated.
	 * A key with a schema is merged field-by-field via merge_fields().
	 *
	 * @since 6.0
	 */

	private function merge_recursive( $old, $save ) {
		$schema = md_register( 'admin_pages' );

		foreach ( $save as $key => $value ) {
			if ( ! empty( $schema[$key]['fields'] ) && is_array( $value ) && isset( $old[$key] ) && is_array( $old[$key] ) )
				$old[$key] = $this->merge_fields( $old[$key], $value, $schema[$key]['fields'] );
			else
				$old[$key] = $value;
		}

		return $old;
	}

	/**
	 * Merge $save over $old using $fields_schema — same schema-node rule as
	 * md_validate::validate_fields() (its docblock has the full reasoning,
	 * including why type=>'group'/'builder' is always a repeater at any
	 * depth, never a fixed sub-object). The merge-specific difference: any
	 * typed leaf field replaces wholesale, even array-shaped values,
	 * since array_replace_recursive's "only overlay matching keys" can't
	 * express that a key was removed from within it. A type-less node
	 * recurses, using its own value as the next level's $fields_schema, so
	 * a sibling leaf survives if one field elsewhere fails to validate.
	 *
	 * @since 6.0
	 */

	private function merge_fields( $old, $save, $fields_schema ) {
		foreach ( $save as $key => $value ) {
			$field = isset( $fields_schema[$key] ) ? $fields_schema[$key] : null;
			$type = is_array( $field ) && isset( $field['type'] ) ? $field['type'] : null;

			if ( in_array( $type, array( 'group', 'builder' ) ) && is_array( $value ) ) {
				$item_schema = isset( $field['fields'] ) ? $field['fields'] : array();
				$old[$key] = $this->merge_clone_items( isset( $old[$key] ) && is_array( $old[$key] ) ? $old[$key] : array(), $value, $item_schema );
			}
			elseif ( $type !== null || ! is_array( $field ) || ! is_array( $value ) || ! isset( $old[$key] ) || ! is_array( $old[$key] ) )
				$old[$key] = $value;
			else
				$old[$key] = $this->merge_fields( $old[$key], $value, $field );
		}

		return $old;
	}

	/**
	 * Merge a clone-style group/builder field's items. The set and order of
	 * item keys in $save is authoritative — fixes deleting/reordering items,
	 * since every clone item is dynamically keyed and the whole list is
	 * always resubmitted together. But each item's OWN fields are merged
	 * individually against $old's version of that same item (via the item's
	 * fixed field schema, shared across every dynamically-keyed item) — so a
	 * field not resubmitted for a given item (display-only data set
	 * programmatically elsewhere, not part of this page's editable form)
	 * survives instead of being wiped, exactly like any other sibling field.
	 *
	 * @since 6.0
	 */

	private function merge_clone_items( $old, $save, $item_schema ) {
		$merged = array();

		foreach ( $save as $item_key => $item_value ) {
			$merged[$item_key] = ( isset( $old[$item_key] ) && is_array( $old[$item_key] ) && is_array( $item_value ) )
				? $this->merge_fields( $old[$item_key], $item_value, $item_schema )
				: $item_value;
		}

		return $merged;
	}

	/**
	 * Merge validated settings over existing, a safe check if combining
	 * settings from other pages, such as taxonomy options.
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
	 * Save taxonomy group options, derived from admin settings, over
	 * $settings — the marketers_delight option for admin_save(), or a
	 * custom option key for admin_save_custom().
	 *
	 * @since 6.0
	 */

	private function save_taxonomy( $input, $settings ) {
		$post_type = sanitize_key( $_POST['md_save_taxonomy_post_type'] );
		$taxonomy = sanitize_key( $_POST['md_save_taxonomy'] );
		$group = isset( $input[$post_type][$taxonomy] ) ? $input[$post_type][$taxonomy] : array();
		$options = $this->validator->validate( 'admin_pages', array( $post_type => $group ) );
		$settings[$post_type][$taxonomy] = $options[$post_type];

		return $settings;
	}

	/**
	 * Saves all types of post meta fields.
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
		$save = $this->validator->validate( 'meta_boxes', $_POST[$option] );
		$save = apply_filters( 'md_post_meta_save', $save, $post );

		if ( $save )
			update_post_meta( $post_id, $option, $save );
		elseif ( $save == '' && $value )
			delete_post_meta( $post_id, $option, $value );
	}

	/**
	 * Saves and sanitizes term fields.
	 *
	 * @since 4.3.5
	 */

	public function term_save( $term_id ) {
		$option = 'marketers_delight';

		if ( isset( $_POST[$option] ) && isset( $_POST["{$option}_nonce"] ) && wp_verify_nonce( $_POST["{$option}_nonce"], "{$option}_nonce" ) ) {
			$save = $this->validator->validate( 'terms', $_POST[$option] );

			if ( $save )
				update_term_meta( $term_id, $option, $save );
			elseif ( empty( $save ) )
				delete_term_meta( $term_id, $option );
		}
	}

	/**
	 * Saves and sanitizes user meta fields.
	 *
	 * @since 5.3.1
	 */

	public function user_meta_save( $user_id, $old_meta ) {
		$option = 'marketers_delight';

		if ( isset( $_POST["{$option}_nonce"] ) && ! wp_verify_nonce( $_POST["{$option}_nonce"], "{$option}_nonce" ) || empty( $_POST[$option] ) )
			return;

		$save = $this->validator->validate( 'user_meta', $_POST[$option] );

		if ( $save )
			update_user_meta( $user_id, $option, $save );
		elseif ( empty( $save ) )
			delete_user_meta( $user_id, $option );
	}

}