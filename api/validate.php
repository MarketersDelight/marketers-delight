<?php
/**
 * Walks a registered field schema (from md_register()) against raw input,
 * dispatching each field to md_sanitize. Handles single-level
 * fields, nested group/option fields, and clone-style group/builder
 * fields (repeaters).
 *
 * Call chain from the one public entry point:
 *   validate( $settings, $input )                     — resolves each top-level key's schema
 *     -> validate_fields( $input, $fields_schema )     — the recursive walker; dispatches per key
 *          -> validate_field( $val, $fields )          — leaf: sanitize one field's value
 *          -> clone_groups( $groups, $item_schema )    — group/builder: validate each item...
 *               -> validate_fields( ... )              — ...by recursing back into the walker
 *
 * md_save mirrors this exact shape one level up the pipeline (validate,
 * then merge the result over existing data) — see the call-chain map at
 * the top of api/save.php.
 *
 * @since 6.0
 */

class md_validate {

	private $sanitize;
 
	/**
	 * @since 6.0
	 */

	public function __construct( $sanitize ) {
		$this->sanitize = $sanitize;
	}

	/**
	 * Validate and sanitize individual fields.
	 *
	 * Supported 'type' values and the keys each one reads from $fields:
	 *   text, textarea    - map (bool, routes through ids() for comma-list fields)
	 *   editor, code       - (none)
	 *   number, range      - (none)
	 *   hidden, data       - (none)
	 *   recursive          - (none) — for values with no field schema of their own,
	 *                        see the note on validate() re: schema-less top-level keys
	 *   url                - (none)
	 *   checkbox           - (none)
	 *   select, radio      - options (array of valid values), dynamic (bool, skips
	 *                        the options whitelist entirely — use sparingly, see select())
	 *   upload             - upload_type (defaults to 'media'), multiple (bool)
	 *   color              - inherit, default (both used to detect "already at
	 *                        default, don't store an explicit override")
	 * Every type also honors 'default': if $val is empty and 'default' is set,
	 * the default is substituted before sanitizing.
	 *
	 * Return convention: null means "no value, don't save this field" (the
	 * caller omits it from $save, which — combined with a recursive merge —
	 * leaves whatever was already stored untouched). Any other return value,
	 * including '', false, or an empty array, is a real, storable value —
	 * blank is a legitimate saved state for every field type, including
	 * "cleared" or "reset to default" (clearing a field and saving should
	 * persist as blank, not silently revert to whatever was there before).
	 * null is reserved for genuinely rejected input that isn't applicable at
	 * all — e.g. upload() when the field's upload_type doesn't match.
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
	 * Recursively validate $input against $fields_schema at any depth, using
	 * the same rule md_save::merge_fields() uses to walk the schema back
	 * down: a schema node with its own 'type' is a leaf field (dispatched to
	 * validate_field()); a node with no 'type' is a plain structural
	 * grouping whose own keys ARE the next level's schema, so it recurses
	 * using itself as the next $fields_schema.
	 *
	 * type=>'group'/'builder' is ALWAYS a dynamically-keyed repeater
	 * (dispatched to clone_groups()), at any depth — including a clone
	 * item's own fields, so a repeater can be nested inside another
	 * repeater's item (e.g. a floating bar's "links" — see
	 * optins/floating-bars/floating-bars.php). A single fixed embedded
	 * sub-object (not a repeater) is expressed as a typeless structural
	 * grouping instead — there is no separate "nested group" concept.
	 *
	 * A typed leaf's value is written only when validate_field() returns
	 * non-null (omit = leave old value alone on merge). A group/builder
	 * field's cloned result is ALWAYS written, even when empty (deleting
	 * every item is itself a real, storable value — see clone_groups()). A
	 * typeless structural node's recursed result is written only when
	 * non-empty, so an entirely-unvalidated substructure doesn't get
	 * vivified into an empty array where the original input never had the
	 * key at all.
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
					unset( $items['{clone}'] );

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
	 * Validate clone-style groups used by group/builder fields. $groups is
	 * the submitted item dictionary (item_key => item's submitted field
	 * values, '{clone}' already stripped by the caller); $item_schema is the
	 * fixed field schema shared by every item. Each item's fields are
	 * validated via validate_fields() — the same recursive walker used
	 * everywhere else — so an item's own fields may themselves include a
	 * nested group/builder repeater or a typeless structural grouping to
	 * any depth.
	 *
	 * @since 6.0
	 */

	private function clone_groups( $groups, $item_schema, $lowercase = false ) {
		$save = array();

		foreach ( $groups as $group => $submitted ) {
			if ( $lowercase )
				$group = strtolower( $group );

			$validated = $this->validate_fields( is_array( $submitted ) ? $submitted : array(), $item_schema );

			if ( ! empty( $validated ) )
				$save[$group] = $validated;
		}

		return $save;
	}

	/**
	 * A big function to save all fields type data safely and expectedley.
	 * Handles single level fields, clone/group, and unique builder fields.
	 *
	 * Top-level keys with no registered field schema (e.g. version, license,
	 * integrations, icons, custom_icons — programmatic data, not form-driven)
	 * are saved as-is, unvalidated. Whoever writes to one of these keys is
	 * responsible for sanitizing it themselves — see recursive() for the
	 * manual sanitize integrations.php uses for the 'integrations' key.
	 *
	 * @since 4.7
	 */

	public function validate( $settings, $input ) {
		$save = array();
		$data = md_register( $settings );

		foreach ( array_keys( $input ) as $key ) {
			if ( ! empty( $data[$key]['fields'] ) )
				$save[$key] = $this->validate_fields( $input[$key], $data[$key]['fields'] );
			else
				$save[$key] = $input[$key];
		}

		return $save;
	}

}