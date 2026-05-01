<?php
/**
 * Various helper methods for use as sanitization callbacks.
 *
 * @since 4.5
 */

class md_sanitize {

	/**
	 * Add needed font weights for design controls.
	 *
	 * @since 4.8
	 */

	private $whitelist = array( 'version', 'integrations', 'popups_data', 'license', 'custom_icons' );

	public $_font_weights = array(
		'normal' => 'Regular',
		'bold' => 'Bold',
		'100' => '100',
		'200' => '200',
		'300' => '300',
		'400' => '400',
		'500' => '500',
		'600' => '600',
		'700' => '700',
		'800' => '800',
		'900' => '900'
	);

	/**
	 * Check if a value is found, including a text string 0.
	 *
	 * @since 6.0
	 */

	public function has_value( $value ) {
		return ! empty( $value ) || $value == '0';
	}

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
	 * A list of accepted title sizes from h1-h6 selectors.
	 *
	 * @since 6.0
	 */

	public function h_ids() {
		return array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'huge' );
	}

	/**
	 * Checks Font Weight controls.
	 *
	 * @since 4.8
	 */

	public function font_weights( $input ) {
		$weights = array();

		foreach ( $this->_font_weights as $weight => $label )
			$weights[] = $weight;

		return in_array( $input, $weights ) ? $input : '';
	}

	/**
	 * Checks Featured Image position settings.
	 *
	 * @since 4.5
	 * @moved 4.5.4
	 */

	public function featured_image_position( $input ) {
		return in_array( $input, array(
			'right', 'left', 'center', 'remove',
			'title_right', 'title_left', 'title_center',
			'below_headline', 'above_headline'
		) ) ? $input : '';
	}

	/**
	 * Save a generic text string and special formatted strings.
	 *
	 * @since 4.0
	 */

	public function text( $input, $fields ) {
		if ( isset( $fields['map'] ) )
			$save = $this->ids( $input );
		else
			$save = wp_kses_post( $input );

		return $save;
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

	public function checkbox( $input ) {
		if ( is_array( $input ) ) {
			$save = array();

			foreach ( $input as $check => $val )
				if ( ! empty( $val ) )
					$save[$check] = true;
		}
		else
			$save = $input == true ? true : false;

		return $save;
	}

	/**
	 * Compare input to predefined valued to save only valid select options.
	 *
	 * @since 4.7
	 */

	public function select( $input, $options, $dynamic = false ) {
		if ( is_array( $input ) ) {
			$values = array();

			foreach ( $input as $key )
				if ( in_array( $key, $options ) )
					$values[] = sanitize_text_field( $key );

			return $values;
		}
		else
			return in_array( $input, $options ) || $dynamic ? sanitize_text_field( $input ) : '';
	}

	/**
	 * Return select values for Customizer fields.
	 *
	 * @since 5.0
	 */

	public function customize_select( $input, $setting ) {
		$input = sanitize_key( $input );
		$choices = $setting->manager->get_control( $setting->id )->choices;

		return array_key_exists( $input, $choices ) ? $input : $setting->default;
	}

	/**
	 * Properly save color values to color fields as hex or RGBA.
	 *
	 * @since 4.7
	 */

	public function color( $input ) {
		if ( strpos( $input, 'rgba' ) === false )
			if ( strlen( $input ) == 7 ) // HEX
				return preg_match( '/^#[a-f0-9]{6}$/i', $input ) ? stripslashes( strip_tags( $input ) ) : '';
			elseif ( strlen( $input ) == 9 ) // HEXA
				return preg_match( '/^#[a-f0-9]{8}$/i', $input ) ? stripslashes( strip_tags( $input ) ) : '';

		sscanf( $input, 'rgba(%d,%d,%d,%f)', $r, $g, $b, $a );

		return "rgba({$r}, {$g}, {$b}, {$a})"; // RGBA
	}

	/**
	 * Escape image URL for uploaded media.
	 *
	 * @since 4.5
	 */

	public function upload( $input, $fields ) {
		if ( $fields['upload_type'] !== 'media' || empty( $input['id'] ) )
			return;

        if ( isset( $fields['multiple'] ) ) {
            $ids = explode( ',', $input['id'] );
            $save['ids'] = array_map( 'intval', $ids );
        }

        $save['id'] = sanitize_text_field( $input['id'] );

        return $save;
	}

	/**
	 * Save a list of IDs as a comma-separated text field and array list.
	 *
	 * @since 6.0
	 */

	public function ids( $input ) {
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
	 * Return terms hierarchy in data format.
	 *
	 * @since 5.3.1
	 */

	public function terms( $taxonomy = 'category' ) {
		$cats = array();
		$terms = get_terms( $taxonomy );

		foreach ( $terms as $term )
			if ( isset( $term->term_id ) )
				$cats[] = intval( $term->term_id );

		return $cats;
	}

	/**
	 * Return a save ready list of WP menus.
	 *
	 * @since 6.0
	 */

	public function menus() {
		$menus = array( 'ids' => array(), 'options' => array() );
		$nav_menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

		if ( ! empty( $nav_menus ) )
			foreach ( $nav_menus as $menu ) {
				$menu_id = intval( $menu->term_id );
				$menus['ids'][] = $menu_id;
				$menus['options'][$menu_id] = sanitize_text_field( $menu->name );
			}

		return $menus;
	}

	/**
	 * Save valid fields and merge fields on Settings API save.
	 *
	 * @since 4.0
	 */

	public function admin_save( $input ) {
		$save = $this->validate( 'admin_pages', $input );

		return array_merge( md_setting(), $save );
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

		$save = $this->validate( 'user_meta', $_POST[$option] );

		if ( $save )
			update_user_meta( $user_id, $option, $save );
		elseif ( empty( $save ) )
			delete_user_meta( $user_id, $option );
	}

	/**
	 * Saves and sanitizes term fields.
	 *
	 * @since 4.3.5
	 */

	public function term_save( $term_id ) {
		$option = 'marketers_delight';

		if ( isset( $_POST[$option] ) && isset( $_POST["{$option}_nonce"] ) && wp_verify_nonce( $_POST["{$option}_nonce"], "{$option}_nonce" ) ) {
			$save = $this->validate( 'terms', $_POST[$option] );

			if ( $save )
				update_term_meta( $term_id, $option, $save );
			elseif ( empty( $save ) )
				delete_term_meta( $term_id, $option );
		}
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
		$save = $this->validate( 'meta_boxes', $_POST[$option] );

		if ( $save )
			update_post_meta( $post_id, $option, $save );
		elseif ( $save == '' && $value )
			delete_post_meta( $post_id, $option, $value );
	}

	/**
	 * Validate and sanitize individual fields.
	 *
	 * @since 5.0
	 */

	private function validate_field( $val, $fields ) {
		$field = '';
		$type = isset( $fields['type'] ) ? $fields['type'] : '';
		$sub_options = isset( $fields['options'] ) ? $fields['options'] : array();
		$dynamic = isset( $fields['dynamic'] ) ? true : false;

		if ( $val == '' && isset( $fields['default'] ) )
			$val = $fields['default'];

		if ( in_array( $type, array( 'text', 'textarea' ) ) )
			$field = $this->text( $val, $fields );

		if ( in_array( $type, array( 'editor', 'code' ) ) )
			$field = wp_kses_post( $val );

		if ( in_array( $type, array( 'number', 'range' ) ) )
			$field = $this->number( $val );

		if ( in_array( $type, array( 'hidden', 'data' ) ) )
			$field = sanitize_text_field( $val );

		if ( $type == 'recursive' )
			$field = $this->recursive( $val );

		if ( $type == 'url' )
			$field = $this->url( $val );

		if ( $type == 'checkbox' )
			$field = $this->checkbox( $val );

		if ( in_array( $type, array( 'select', 'radio' ) ) && ( is_array( $sub_options ) || $dynamic ) )
			$field = $this->select( $val, $sub_options, $dynamic );

		if ( $type == 'upload' ) {
			if ( empty( $fields['upload_type'] ) )
				$fields['upload_type'] = 'media';

			$field = $this->upload( $val, $fields );
		}

		if ( $type == 'color' ) {
			$default = ! empty( $fields['default'] ) ? $fields['default'] : '';

			if ( $default !== $val )
				$field = $this->color( $val );
		}

		return $field;
	}

	/**
	 * Validate clone-style groups used by group/builder fields.
	 *
	 * @since 6.0
	 */

	private function clone_groups( $groups, $input, $lowercase = false, $nested = false ) {
		$save = array();

		foreach ( $groups as $group => $clone_fields ) {
			if ( $lowercase )
				$group = strtolower( $group );

			foreach ( $clone_fields as $key => $val ) {
				if ( ! $this->has_value( $val ) || empty( $input[$key] ) )
					continue;

				$input_fields = $input[$key];

				if ( $nested && isset( $input_fields['type'] ) && $input_fields['type'] == 'group' && is_array( $val ) ) {
					foreach ( $val as $subgroup_key => $subgroup_val ) {
						if ( empty( $input_fields['fields'][$subgroup_key] ) )
							continue;

						$save[$group][$key][$subgroup_key] = $this->validate_field( $subgroup_val, $input_fields['fields'][$subgroup_key] );
					}
				}
				else
					$save[$group][$key] = $this->validate_field( $val, $input_fields );
			}
		}

		return $save;
	}

	/**
	 * Build the required builder index arrays from saved builder rows.
	 *
	 * @since 6.0
	 */

	private function builder( $input ) {
		$data = $elements = $locations = array();

		foreach ( $input as $id => $fields ) {
			if ( empty( $fields['builder_type'] ) || ! isset( $fields['builder_area'] ) )
				continue;

			$builder_type = esc_attr( $fields['builder_type'] );
			$builder_area = $fields['builder_area'];
			$data[$builder_area][] = array(
				'type' => $builder_type,
				'id' => $id
			);

			$elements[$builder_type][] = $id;
			$locations[$id] = $builder_area;
		}

		return array(
			'data' => serialize( $data ),
			'elements' => serialize( $elements ),
			'locations' => serialize( $locations )
		);
	}

	/**
	 * A big function to save all fields type data safely and expectedley.
	 * Handles single level fields, clone/group, and unique builder fields.
	 *
	 * @since 4.7
	 */

	public function validate( $settings, $input ) {
		$save = array();
		$data = md_register( $settings );

		foreach ( $input as $key => $input_fields ) {
			$save[$key] = array();

			if ( ! in_array( $key, $this->whitelist ) ) {
				if ( ! empty( $data[$key]['fields'] ) )
					foreach ( $data[$key]['fields'] as $group => $group_fields ) {
						$group_input = isset( $input[$key][$group] ) ? $input[$key][$group] : null;

						if ( isset( $group_fields['type'] ) && in_array( $group_fields['type'], array( 'group', 'builder' ) ) && isset( $input[$key][$group] ) ) {
							unset( $input[$key][$group]['{clone}'] );

							$save[$key][$group] = $this->clone_groups( $input[$key][$group], $data[$key]['fields'][$group]['fields'], isset( $group_fields['group_key_lowercase'] ), true );

							if ( $group_fields['type'] == 'builder' ) {
								$builder = $this->builder( $save[$key][$group] );
								$save[$key]["{$group}_data"] = $builder['data'];
								$save[$key]["{$group}_elements"] = $builder['elements'];
								$save[$key]["{$group}_locations"] = $builder['locations'];
							}
						}
						elseif ( isset( $group_fields['type'] ) && $this->has_value( $group_input ) )
							$save[$key][$group] = $this->validate_field( $group_input, $group_fields );
						elseif ( is_array( $group_fields ) ) {
							foreach ( $group_fields as $option_name => $option_fields ) {
								$option_input = is_array( $group_input ) && isset( $group_input[$option_name] ) ? $group_input[$option_name] : null;

								if ( isset( $option_fields['type'] ) && in_array( $option_fields['type'], array( 'group', 'builder' ) ) && isset( $input[$key][$group][$option_name] ) ) {
									if ( is_array( $input[$key][$group][$option_name] ) )
										unset( $input[$key][$group][$option_name]['{clone}'] );

									$save[$key][$group][$option_name] = $this->clone_groups( $input[$key][$group][$option_name], $data[$key]['fields'][$group][$option_name]['fields'], isset( $group_fields['group_key_lowercase'] ) );
								}
								elseif ( isset( $option_fields['type'] ) && $this->has_value( $option_input ) )
									$save[$key][$group][$option_name] = $this->validate_field( $option_input, $option_fields );
								elseif ( is_array( $option_fields ) )
									foreach ( $option_fields as $val_name => $val_fields )
										if ( isset( $val_fields['type'] ) ) {
											$val_input = is_array( $option_input ) && isset( $option_input[$val_name] ) ? $option_input[$val_name] : null;

											if ( $this->has_value( $val_input ) )
												$save[$key][$group][$option_name][$val_name] = $this->validate_field( $val_input, $val_fields );
										}
							}
						}
					}
			}
			else $save[$key] = $input[$key];
		}

		return $save;
	}

}