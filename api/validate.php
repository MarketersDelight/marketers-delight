<?php
/**
 * Walks a registered field schema (from md_register()) against raw input,
 * sanitizing each field via md_sanitize. Handles single fields, nested
 * groups of fields, and clone-style group/builder fields (repeaters).
 *
 * Call chain from the one public entry point:
 *   validate( $settings, $input )                  — resolves each top-level key's schema
 *     -> validate_fields( $input, $fields_schema )  — recurses per field
 *          -> validate_field( $val, $fields )       — sanitizes one field's value
 *          -> clone_groups( $groups, $item_schema ) — group/builder fields
 *               -> validate_fields( ... )            — validates each item
 *
 * md_save (api/save.php) merges the result of validate() over existing data.
 *
 * @since 6.0
 */

class md_validate {

	private $sanitize;

	public function __construct() {
		$this->sanitize = new md_sanitize;
	}

	/**
	 * Validates $input against the schema registered under $settings (e.g.
	 * 'admin_pages', 'meta_boxes', 'terms', 'user_meta' — see
	 * md_register()). A top-level key with no registered schema is
	 * dropped rather than saved, since $input is form-submitted data and
	 * every legitimate key is schema-registered by construction.
	 *
	 * @since 4.7
	 */

	public function validate( $settings, $input ) {
		$save = array();
		$data = md_register( $settings );

		foreach ( array_keys( $input ) as $key ) {
			if ( ! empty( $data[$key]['fields'] ) )
				$save[$key] = $this->validate_fields( $input[$key], $data[$key]['fields'] );
		}

		return $save;
	}

	/**
	 * Recursively validates $input against $fields_schema. A schema entry
	 * with a 'type' is one field: group/builder fields go through
	 * clone_groups(), anything else goes through validate_field(). A
	 * schema entry with no 'type' is a nested group of fields and recurses.
	 *
	 * @since 6.0
	 */

	private function validate_fields( $input, $fields_schema ) {
		$save = array();

		foreach ( $fields_schema as $key => $field ) {
			if ( ! is_array( $field ) )
				continue;

			$type = isset( $field['type'] ) ? $field['type'] : null;
			$field_input = is_array( $input ) && isset( $input[$key] ) ? $input[$key] : null;

			if ( in_array( $type, array( 'group', 'builder' ) ) ) {
				if ( ! isset( $input[$key] ) )
					continue;

				$items = $input[$key];

				if ( is_array( $items ) )
					foreach ( array_keys( $items ) as $item_key )
						if ( is_string( $item_key ) && strpos( $item_key, '{clone' ) === 0 )
							unset( $items[$item_key] );

				$item_schema = isset( $field['fields'] ) ? $field['fields'] : array();
				$save[$key] = $this->clone_groups( $items, $item_schema, isset( $field['group_key_lowercase'] ) );
			}
			elseif ( $type !== null ) {
				$validated = $this->validate_field( $field_input, $field );

				if ( $validated !== null )
					$save[$key] = $validated;
			}
			else {
				$nested = $this->validate_fields( $field_input, $field );

				if ( ! empty( $nested ) )
					$save[$key] = $nested;
			}
		}

		return $save;
	}

	/**
	 * Sanitizes one field's value based on its 'type'. Returns null if the
	 * value shouldn't be saved (e.g. an upload field with the wrong
	 * upload_type) — otherwise returns the sanitized value, including ''
	 * or false for a field that was legitimately cleared.
	 *
	 * @since 5.0
	 */

	private function validate_field( $val, $fields ) {
		$field = null;
		$type = isset( $fields['type'] ) ? $fields['type'] : '';
		$sub_options = isset( $fields['options'] ) ? $fields['options'] : array();
		$dynamic = isset( $fields['dynamic'] ) ? true : false;

		if ( $val == '' && isset( $fields['default'] ) )
			$val = $fields['default'];

		if ( in_array( $type, array( 'text', 'textarea' ) ) )
			$field = $this->sanitize->text( $val, $fields );

		if ( in_array( $type, array( 'editor', 'code' ) ) )
			$field = wp_kses_post( $val );

		if ( in_array( $type, array( 'number', 'range' ) ) )
			$field = $this->sanitize->number( $val );

		if ( in_array( $type, array( 'hidden', 'data' ) ) )
			$field = sanitize_text_field( $val );

		if ( $type == 'recursive' )
			$field = $this->sanitize->recursive( $val );

		if ( $type == 'url' )
			$field = $this->sanitize->url( $val );

		if ( $type == 'checkbox' )
			$field = $this->sanitize->checkbox( $val, $fields );

		if ( in_array( $type, array( 'select', 'radio' ) ) )
			$field = $this->sanitize->select( $val, $sub_options, $dynamic );

		if ( $type == 'upload' )
			$field = $this->sanitize->upload( $val, $fields );

		if ( $type == 'color' )
			$field = $this->sanitize->color( $val, $fields );

		return $field;
	}

	/**
	 * Validates the items of a group/builder (repeater) field. $groups is
	 * the submitted items, keyed by their (dynamically generated) item
	 * key; $item_schema is the field schema shared by every item.
	 *
	 * @since 6.0
	 */

	private function clone_groups( $groups, $item_schema, $lowercase = false ) {
		$save = array();

		foreach ( $groups as $group => $submitted ) {
			$group = (string) $group;

			if ( ! preg_match( '/^[A-Za-z0-9_-]+$/', $group ) )
				continue;

			if ( $lowercase )
				$group = strtolower( $group );

			$validated = $this->validate_fields( is_array( $submitted ) ? $submitted : array(), $item_schema );

			if ( ! empty( $validated ) )
				$save[$group] = $validated;
		}

		return $save;
	}

}
