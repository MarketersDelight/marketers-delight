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
	 * md_register()). Top-level keys without a registered field schema are
	 * discarded.
	 *
	 * @since 4.7
	 */

	public function validate( $settings, $input ) {
		$save = array();
		$data = md_register( $settings );
		$input = is_array( $input ) ? $input : array();

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
		$input = is_array( $input ) ? $input : array();

		foreach ( $fields_schema as $key => $field ) {
			$type = isset( $field['type'] ) ? $field['type'] : null;
			$field_input = isset( $input[$key] ) ? $input[$key] : null;

			if ( in_array( $type, array( 'group', 'builder' ), true ) ) {
				if ( ! isset( $input[$key] ) ) {
					if ( $type === 'builder' && array_key_exists( "{$key}_data", $input ) )
						$save[$key] = array();

					continue;
				}

				$items = $input[$key];

				if ( is_array( $items ) )
					foreach ( array_keys( $items ) as $item_key )
						if ( is_string( $item_key ) && strpos( $item_key, '{clone' ) === 0 )
							unset( $items[$item_key] );

				$save[$key] = $this->clone_groups( $items, $field['fields'], isset( $field['group_key_lowercase'] ) );
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
	 * upload_type) — otherwise returns the sanitized value. Empty results
	 * tell md_save to remove a field that was legitimately cleared.
	 *
	 * @since 5.0
	 */

	private function validate_field( $val, $fields ) {
		$type = isset( $fields['type'] ) ? $fields['type'] : '';
		$sub_options = isset( $fields['options'] ) ? $fields['options'] : array();

		switch ( $type ) {
			case 'text':
			case 'textarea':
				return $this->sanitize->text( $val, $fields );

			case 'editor':
			case 'code':
				if ( is_null( $val ) )
					return null;
				$val = is_scalar( $val ) ? (string) $val : '';
				return wp_kses_post( $val );

			case 'number':
			case 'range':
				return $this->sanitize->number( $val, $fields );

			case 'hidden':
			case 'data':
				return sanitize_text_field( $val );

			case 'url':
				return $this->sanitize->url( $val );

			case 'checkbox':
				return $this->sanitize->checkbox( $val, $fields );

			case 'select':
			case 'radio':
				return $this->sanitize->select( $val, $sub_options );

			case 'upload':
				return $this->sanitize->upload( $val, $fields );

			case 'color':
				return $this->sanitize->color( $val, $fields );
		}

		return null;
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

		if ( ! is_array( $groups ) )
			return $save;

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
