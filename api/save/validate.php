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
 * md_save::apply() (api/save/save.php) merges the result over existing data.
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

	const FIELD_KEYS = array(
		'type',
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
		'tokens'
	);

	private $sanitize;

	private $warnings = array();

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
	 * Recursively validates the keys $input gives against $fields_schema,
	 * in the schema's order. A key left out isn't touched; forms post every
	 * field they draw (unchecked boxes post an empty value), so a missing
	 * key only means that field wasn't on the screen. null resets a key.
	 * A schema entry with a 'type' is one field: group/builder fields go
	 * through clone_groups(), anything else through validate_field(). A
	 * schema entry with no 'type' is a nested group of fields and recurses.
	 *
	 * @since 6.0
	 */

	private function validate_fields( $input, $fields_schema, $path = array() ) {
		$save = array();
		$input = is_array( $input ) ? $input : array();

		foreach ( array_diff_key( $input, $fields_schema ) as $key => $value )
			$this->warnings[] = sprintf( __( 'Unknown setting %s was skipped.', 'md' ), implode( '.', array_merge( $path, array( $key ) ) ) );

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
				else
					$this->warnings[] = sprintf( __( 'Setting %s has a value that can\'t be saved and was skipped.', 'md' ), $name );
			}
			elseif ( ! is_array( $field_input ) )
				$this->warnings[] = sprintf( __( 'Setting %s takes a set of values and was skipped.', 'md' ), $name );
			else {
				$nested = $this->validate_fields( $field_input, $field, array_merge( $path, array( $key ) ) );

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

	/**
	 * JSON Schema for a set of fields: an object with a property per
	 * field, nested sets of fields becoming nested objects. Each type's
	 * schema matches what validate_field() saves for it.
	 *
	 * @since 6.2
	 */

	public function schema( $fields ) {
		$properties = array();

		foreach ( $fields as $key => $field ) {
			if ( ! is_array( $field ) )
				continue;

			$property = isset( $field['type'] ) ? $this->schema_field( $field ) : $this->schema( $field );

			if ( $property )
				$properties[$key] = $property;
		}

		return array(
			'type' => 'object',
			'properties' => $properties,
			'additionalProperties' => false
		);
	}

	/**
	 * Schema for one field, by the values md_validate saves for its type.
	 * Types md_validate doesn't save have no schema.
	 *
	 * @since 6.2
	 */

	private function schema_field( $field ) {
		$options = isset( $field['options'] ) && is_array( $field['options'] ) ? $field['options'] : array();
		$choices = md_sanitize::choices( $options );
		$labels = array_map( 'wp_strip_all_tags', md_sanitize::labels( $options ) );

		switch ( $field['type'] ) {
			case 'text':
			case 'textarea':
				$schema = array( 'type' => 'string' );
				break;

			case 'font':
				$schema = array(
					'type' => 'string',
					'description' => __( 'A CSS font-family value, ex: Georgia, serif.', 'md' )
				);
				break;

			case 'code':
				$schema = array(
					'type' => 'string',
					'description' => __( 'Raw code, saved only for administrators who can post unfiltered HTML.', 'md' )
				);
				break;

			case 'url':
				$schema = array( 'type' => 'string', 'format' => 'uri' );
				break;

			case 'number':
			case 'range':
				$schema = array( 'type' => 'number' );

				if ( isset( $field['min'] ) && is_numeric( $field['min'] ) )
					$schema['minimum'] = $field['min'] + 0;

				if ( isset( $field['max'] ) && is_numeric( $field['max'] ) )
					$schema['maximum'] = $field['max'] + 0;

				if ( isset( $field['step'] ) && is_numeric( $field['step'] ) && $field['step'] > 0 && empty( $field['min'] ) )
					$schema['multipleOf'] = $field['step'] + 0;
				break;

			case 'checkbox':
				if ( empty( $options ) ) {
					$schema = array( 'type' => 'boolean' );
					break;
				}

				$schema = array(
					'type' => 'object',
					'description' => __( 'true turns an option on, 0 turns it off over an inherited setting.', 'md' ),
					'properties' => array(),
					'additionalProperties' => false
				);

				foreach ( $choices as $choice ) {
					$schema['properties'][$choice] = array( 'type' => array( 'boolean', 'integer' ), 'enum' => array( true, false, 0, 1 ) );

					if ( isset( $labels[$choice] ) )
						$schema['properties'][$choice]['title'] = $labels[$choice];
				}

				$labels = array();
				break;

			case 'select':
			case 'radio':
				$schema = array( 'type' => 'string', 'enum' => array_values( array_unique( array_merge( array( '' ), $choices ) ) ) );
				break;

			case 'upload':
				if ( ! empty( $field['upload_type'] ) && $field['upload_type'] !== 'media' )
					return null;

				$schema = array(
					'type' => 'object',
					'description' => __( 'A Media Library attachment ID.', 'md' ),
					'properties' => array(
						'id' => isset( $field['multiple'] ) ?
							array( 'type' => 'string', 'description' => __( 'Comma-separated attachment IDs.', 'md' ) ) :
							array( 'type' => 'integer', 'minimum' => 1 )
					),
					'required' => array( 'id' )
				);
				break;

			case 'color':
				$schema = array(
					'type' => 'string',
					'description' => __( 'A hex color, an rgba() color or a palette key.', 'md' )
				);
				break;

			case 'group':
			case 'builder':
				$item = $this->schema( isset( $field['fields'] ) && is_array( $field['fields'] ) ? $field['fields'] : array() );
				$schema = array(
					'type' => 'object',
					'description' => $field['type'] === 'builder' ?
						__( 'Builder rows keyed by row ID, in order.', 'md' ) :
						__( 'Items keyed by item ID, in order.', 'md' ),
					'patternProperties' => array( '^[A-Za-z0-9_-]+$' => $item ),
					'additionalProperties' => false
				);
				break;

			default:
				return null;
		}

		if ( $labels )
			$schema['x-labels'] = $labels;

		if ( array_key_exists( 'default', $field ) )
			$schema['default'] = $field['default'];

		if ( ! empty( $field['label'] ) )
			$schema = array_merge( array( 'title' => wp_strip_all_tags( $field['label'] ) ), $schema );

		return $schema;
	}

}
