<?php
/**
 * Per-type field value sanitizers. Pure functions of (value, field-config)
 * to a clean, storable value — no $_POST, no WP option/meta access. Called
 * from md_validate::validate_field() (api/validate.php), one method per
 * field type. Most return null when there's no value to save, and the
 * sanitized value otherwise. Empty results are clearing signals removed by
 * md_save rather than values intended for storage.
 *
 * @since 6.0
 */

class md_sanitize {

	/**
	 * Save a generic text string and special formatted strings.
	 *
	 * @since 4.0
	 */

	public function text( $input, $fields = array() ) {
		if ( is_null( $input ) )
			return null;

		if ( is_array( $input ) ) {
			$input = array_filter( $input, 'is_scalar' );
			$input = implode( ', ', $input );
		}

		if ( ! is_scalar( $input ) )
			$input = '';

		return wp_kses_post( $input );
	}

	/**
	 * Ensure only a valid integer or decimal is saved.
	 *
	 * @since 4.7
	 */

	public function number( $input, $fields = array() ) {
		if ( is_null( $input ) )
			return null;

		if ( ! is_scalar( $input ) )
			return null;

		$input = trim( (string) $input );

		if ( $input === '' )
			return '';

		if ( ! is_numeric( $input ) )
			return null;

		$number = $input + 0;

		if ( isset( $fields['min'] ) && is_numeric( $fields['min'] ) && $number < $fields['min'] )
			return null;

		if ( isset( $fields['max'] ) && is_numeric( $fields['max'] ) && $number > $fields['max'] )
			return null;

		if ( isset( $fields['step'] ) && is_numeric( $fields['step'] ) && $fields['step'] > 0 ) {
			$base = isset( $fields['min'] ) && is_numeric( $fields['min'] ) ? (float) $fields['min'] : 0;
			$steps = ( $number - $base ) / (float) $fields['step'];

			if ( abs( $steps - round( $steps ) ) > 0.0000001 )
				return null;
		}

		return $number;
	}

	/**
	 * Clean URL field for valid characters only.
	 *
	 * @since 4.7
	 */

	public function url( $input ) {
		if ( is_null( $input ) )
			return null;

		return wp_kses_bad_protocol( is_scalar( $input ) ? (string) $input : '', array( 'http', 'https' ) );
	}

	/**
	 * A checkbox can only have 2 possible return values.
	 * Lock results to '' or true.
	 *
	 * @since 4.5
	 */

	public function checkbox( $input, $fields = array() ) {
		if ( isset( $fields['options'] ) ) {
			$save = array();
			$options = $fields['options'];
			$option_keys = array_keys( $options );
			$is_list = $option_keys === ( empty( $options ) ? array() : range( 0, count( $options ) - 1 ) );
			$allowed = array_map( 'strval', $is_list ? $options : $option_keys );

			if ( is_array( $input ) )
				foreach ( $input as $check => $val )
					if ( ! empty( $val ) && in_array( (string) $check, $allowed, true ) )
						$save[$check] = true;
		}
		else $save = (bool) $input;

		return $save;
	}

	/**
	 * Compare input to predefined valued to save only valid select options.
	 *
	 * @since 4.7
	 */

	public function select( $input, $options ) {
		$multiple = is_array( $input );
		$input = $multiple ? $input : array( $input );
		$options = array_map( 'strval', $options );
		$save = array();

		foreach ( $input as $value ) {
			if ( ! is_scalar( $value ) )
				continue;

			$value = sanitize_text_field( (string) $value );

			if ( in_array( $value, $options, true ) )
				$save[] = $value;
		}

		return $multiple ? $save : ( $save[0] ?? '' );
	}

	/**
	 * Escape image URL for uploaded media.
	 *
	 * @since 4.5
	 */

	public function upload( $input, $fields ) {
		$upload_type = ! empty( $fields['upload_type'] ) ? $fields['upload_type'] : 'media';

		if ( $upload_type !== 'media' )
			return null;

		if ( ! is_array( $input ) || empty( $input['id'] ) )
			return '';

		if ( isset( $fields['multiple'] ) ) {
			$ids = explode( ',', $input['id'] );
			$ids = array_values( array_filter( array_map( 'intval', $ids ) ) );

			if ( empty( $ids ) )
				return '';

			$save['id'] = implode( ',', $ids );
			$save['ids'] = $ids;
		}
		else {
			$id = $this->number( $input['id'] );

			if ( $id === '' || is_null( $id ) )
				return '';

			$save['id'] = $id;
		}

		return $save;
	}

	/**
	 * Save color values as explicit default, palette, or custom selections.
	 *
	 * @since 4.7
	 */

	public function color( $input, $fields = array() ) {
		if ( is_array( $input ) )
			return $this->color_selection( $input, $fields );

		if ( is_scalar( $input ) && isset( md_color_palette()[$input] ) )
			return sanitize_key( $input );

		return $this->color_value( $input, $fields );
	}

	/**
	 * Resolve an explicit color field mode: configured default, palette
	 * reference, or custom color value.
	 *
	 * @since 6.0
	 */

	private function color_selection( $input, $fields ) {
		$mode = ! empty( $input['mode'] ) ? sanitize_key( $input['mode'] ) : '';

		if ( $mode === 'default' )
			return '';

		if ( $mode === 'palette' ) {
			$key = ! empty( $input['palette'] ) ? sanitize_key( $input['palette'] ) : '';
			$palette = md_color_palette();

			if ( ! isset( $palette[$key] ) )
				return '';

			$is_default = ! empty( $fields['palette'] ) && $key === sanitize_key( $fields['palette'] );

			return $is_default ? '' : $key;
		}

		if ( $mode === 'custom' )
			return $this->color_value( isset( $input['custom'] ) ? $input['custom'] : '', $fields );

		return '';
	}

	/**
	 * Resolve a color field submitted as a direct hex or rgba() string.
	 * Already matching the field's default (or blank with no default set)
	 * means there's no override to store, so it's left unsaved entirely.
	 *
	 * @since 6.0
	 */

	private function color_value( $input, $fields ) {
		if ( is_null( $input ) )
			return null;

		$input = is_scalar( $input ) ? (string) $input : '';
		$default = ! empty( $fields['default'] ) ? $fields['default'] : '';

		if ( $default === $input )
			return '';

		$hex = ltrim( $input, '#' );

		if ( ctype_xdigit( $hex ) && in_array( strlen( $hex ), array( 3, 4, 6, 8 ), true ) )
			return '#' . strtolower( $hex );

		if ( ! preg_match( '/^rgba\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*([0-9]*\.?[0-9]+)\s*\)$/', $input, $rgba ) )
			return sanitize_hex_color( $input ) ?: '';

		$r = intval( $rgba[1] );
		$g = intval( $rgba[2] );
		$b = intval( $rgba[3] );
		$a = floatval( $rgba[4] );

		if ( $r > 255 || $g > 255 || $b > 255 || $a > 1 )
			return '';

		return "rgba({$r}, {$g}, {$b}, {$a})";
	}

}
