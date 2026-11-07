<?php
/**
 * Checks raw input against a group's registered fields, sanitizing each with
 * md_sanitize. Handles single fields, nested groups and repeaters.
 *
 * fields() is the one way in. md_save::apply() merges what it returns over
 * what's stored, so nothing here writes to the database.
 *
 * @since 6.0
 */

class md_validate {

	/**
	 * The keys a field's registration may use: what the setting is, the
	 * values it takes and how it's stored. How a field draws (description,
	 * placeholder, classes, rows, unit...) belongs in its template.
	 *
	 * @since 6.2
	 */

	public $field_keys = array(
		'type',
		'internal',
		'label',
		'options',
		'default',
		'min',
		'max',
		'step',
		'upload_type',
		'multiple',
		'palette',
		'hex_only',
		'fields',
		'standalone',
		'show_in_rest',
		'code_options',
		'preserve_empty',
		'group_key_lowercase',
		'tokens',
		'ref'
	);

	private $sanitize;

	private $warnings = array();

	public function __construct() {
		$this->sanitize = new md_sanitize;
	}

	/**
	 * Validates one group's $input against its own fields, ex: the fields
	 * md_save::apply() found for a scope and group.
	 *
	 * @since 6.2
	 */

	public function fields( $input, $fields_schema ) {
		$this->warnings = array();

		return $this->validate_fields( $input, is_array( $fields_schema ) ? $fields_schema : array() );
	}

	/**
	 * What the last fields() call skipped: unknown keys and values that
	 * don't validate.
	 *
	 * @since 6.2
	 */

	public function warnings() {
		return $this->warnings;
	}

	/**
	 * Validate the keys $input gives, in the schema's order. A missing key isn't
	 * touched and null resets one. A schema entry with no 'type' is a nested
	 * group.
	 *
	 * @since 6.0
	 */

	private function validate_fields( $input, $fields_schema, $path = array() ) {
		$save = array();
		$input = is_array( $input ) ? $input : array();

		foreach ( array_diff_key( $input, $fields_schema ) as $key => $value )
			$this->warnings[] = sprintf( __( '`%s` isn\'t a setting on this site.', 'md' ), implode( '.', array_merge( $path, array( $key ) ) ) );

		foreach ( $fields_schema as $key => $field ) {
			if ( ! array_key_exists( $key, $input ) || ! is_array( $field ) )
				continue;

			$type = isset( $field['type'] ) ? $field['type'] : null;
			$field_input = $input[$key];
			$name = implode( '.', array_merge( $path, array( $key ) ) );

			if ( is_null( $field_input ) ) {
				$save[$key] = null;
				continue;
			}

			if ( in_array( $type, array( 'group', 'builder' ), true ) ) {
				$items = $field_input;

				if ( is_array( $items ) )
					foreach ( array_keys( $items ) as $item_key )
						if ( is_string( $item_key ) && strpos( $item_key, '{clone' ) === 0 )
							unset( $items[$item_key] );

				$save[$key] = $this->clone_groups( $items, isset( $field['fields'] ) ? $field['fields'] : array(), isset( $field['group_key_lowercase'] ) );
			}
			elseif ( $type !== null ) {
				$validated = $this->validate_field( $field_input, $field );

				if ( $validated !== null )
					$save[$key] = $validated;

				$given = is_array( $field_input ) ? array_filter( $field_input ) : $field_input;
				$rejected = in_array( $validated, array( '', array() ), true ) && ! in_array( $given, array( '', false, 0, '0', array() ), true );

				if ( $validated === null || $rejected )
					$this->warnings[] = sprintf( __( '`%s` has a value this site can\'t use.', 'md' ), $name );
			}
			elseif ( ! is_array( $field_input ) )
				$this->warnings[] = sprintf( __( '`%s` needs a set of values.', 'md' ), $name );
			else {
				$nested = $this->validate_fields( $field_input, $field, array_merge( $path, array( $key ) ) );

				if ( ! empty( $nested ) )
					$save[$key] = $nested;
			}
		}

		return $save;
	}

	/**
	 * Sanitize one field by its type. Returns null when it shouldn't be saved,
	 * ex: an upload of the wrong type. An empty result removes a cleared field.
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
				if ( is_null( $val ) )
					return null;
				$val = is_scalar( $val ) ? (string) $val : '';
				return wp_kses_post( $val );

			case 'code':
				if ( is_null( $val ) )
					return null;

				return is_scalar( $val ) ? (string) $val : '';

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
				return $this->sanitize->select( $val, md_sanitize::choices( $sub_options ) );

			case 'upload':
				return $this->sanitize->upload( $val, $fields );

			case 'color':
				return $this->sanitize->color( $val, $fields );

			case 'font':
				return $this->sanitize->font( $val );
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
