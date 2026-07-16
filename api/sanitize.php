<?php
/**
 * Per-type field value sanitizers. Pure functions of (value, field-config)
 * to a clean, storable value — no $_POST, no WP option/meta access. See
 * validate_field() in schema-validator.php for the full type reference and
 * the null-vs-value return convention every sanitizer here follows.
 *
 * @since 6.0
 */

class md_sanitize {

	/**
	 * Run through an array down to sanitize a text field.
	 *
	 * @since 6.0
	 */

	public function recursive( $value ) {
		if ( is_array( $value ) )
			return array_map( array( $this, 'recursive' ), $value );

		return sanitize_text_field( $value );
	}

	/**
	 * Save a generic text string and special formatted strings.
	 *
	 * @since 4.0
	 */

	public function text( $input, $fields = array() ) {
		if ( isset( $fields['map'] ) )
			$save = $this->ids( $input );
		else
			$save = wp_kses_post( $input );

		return $save;
	}

	/**
	 * Save a list of IDs as a comma-separated text field and array list.
	 * Only reachable via text()'s 'map' mode — not its own field type.
	 *
	 * @since 6.0
	 */

	private function ids( $input ) {
		if ( is_array( $input ) ) {
			$value = '';
			$values = array();

			if ( isset( $input['values'] ) )
				$values = $input['values'];
			elseif ( isset( $input['value'] ) )
				$values = explode( ',', $input['value'] );

			if ( isset( $input['value'] ) )
				$value = $input['value'];
			else
				$value = join( ',', $values ) . ',';
		}
		else {
			$value = $input;
			$values = explode( ',', $value );
		}

		return array(
			'value' => sanitize_text_field( $value ),
			'values' => $values
		);
	}

	/**
	 * Ensure only a number is saved.
	 *
	 * @since 4.7
	 */

	public function number( $input ) {
		return preg_replace( '/\D/', '', $input );
	}

	/**
	 * Clean URL field for valid characters only.
	 *
	 * @since 4.7
	 */

	public function url( $input ) {
		return wp_kses_bad_protocol( $input, array( 'http', 'https' ) );
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

			if ( is_array( $input ) )
				foreach ( $input as $check => $val )
					if ( ! empty( $val ) )
						$save[$check] = true;
		}
		else $save = $input == true ? true : false;

		return $save;
	}

	/**
	 * Compare input to predefined valued to save only valid select options.
	 *
	 * @since 4.7
	 */

	public function select( $input, $options, $dynamic = false ) {
		if ( ! is_array( $options ) )
			$options = array();

		if ( is_array( $input ) ) {
			$values = array();

			foreach ( $input as $key )
				if ( in_array( $key, $options ) )
					$values[] = sanitize_text_field( $key );

			return $values;
		}
		else return in_array( $input, $options ) || $dynamic ? sanitize_text_field( $input ) : '';
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

		if ( empty( $input['id'] ) )
			return '';

        if ( isset( $fields['multiple'] ) ) {
            $ids = explode( ',', $input['id'] );
            $save['ids'] = array_map( 'intval', $ids );
        }

        $save['id'] = sanitize_text_field( $input['id'] );

        return $save;
	}

	/**
	 * Properly save color values to color fields as hex or RGBA.
	 * As of 6.0, colors can now be saved as inheritances, which is a text string.
	 *
	 * @since 4.7
	 */

	public function color( $input, $fields = array() ) {
		return is_array( $input ) ? $this->color_inherit( $input, $fields ) : $this->color_value( $input, $fields );
	}

	/**
	 * Resolve a color field submitted as an inheritance reference: an
	 * explicit hex override, or a palette key to inherit from.
	 *
	 * @since 6.0
	 */

	private function color_inherit( $input, $fields ) {
		$result = '';

		if ( ! empty( $input['hex'] ) )
			$result = $this->color_value( $input['hex'], array() );

		if ( ! $result && ! empty( $input['inherit'] ) ) {
			$palette = md_color_palette();

			if ( isset( $palette[$input['inherit']] ) ) {
				$saved = sanitize_key( $input['inherit'] );
				$is_default = ! empty( $fields['inherit'] ) && $saved === sanitize_key( $fields['inherit'] );

				$result = $is_default ? '' : $saved;
			}
		}

		return $result;
	}

	/**
	 * Resolve a color field submitted as a direct hex or rgba() string.
	 * Already matching the field's default (or blank with no default set)
	 * means there's no override to store, so it's left unsaved entirely.
	 *
	 * @since 6.0
	 */

	private function color_value( $input, $fields ) {
		$default = ! empty( $fields['default'] ) ? $fields['default'] : '';

		if ( $default === $input )
			return null;

		if ( strpos( $input, 'rgba' ) === false )
			return sanitize_hex_color( $input ) ?: '';

		sscanf( $input, 'rgba(%d,%d,%d,%f)', $r, $g, $b, $a );

		return "rgba({$r}, {$g}, {$b}, {$a})";
	}

}