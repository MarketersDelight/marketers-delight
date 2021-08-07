<?php
/**
 * Various helper methods for use as sanitization callbacks.
 *
 * @since 4.5
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class md_sanitize {

	/**
	 * Assign properties.
	 *
	 * @since 5.0
	 */

	public function __construct() {
		$this->values = $this->values();
	}

	/**
	 * Allow only the following HTML tags + attributes
	 * on validation.
	 *
	 * @since 4.0
	 */

	public $_allowed_html = array(
		'div' => array(
			'class' => array(),
			'id' => array(),
			'style' => array()
		),
		'p' => array(
			'class' => array(),
			'id' => array(),
			'style' => array()
		),
		'ul' => array(
			'class' => array(),
			'id' => array(),
			'style' => array()
		),
		'ol' => array(
			'class' => array(),
			'id' => array(),
			'style' => array()
		),
		'li' => array(
			'class' => array(),
			'id' => array(),
			'style' => array()
		),
		'a' => array(
			'href' => array(),
			'class' => array(),
			'id' => array(),
			'target' => array()
		),
		'span' => array(
			'class' => array(),
			'id' => array(),
			'style' => array()
		),
		'img' => array(
			'src' => array(),
			'alt' => array(),
			'height' => array(),
			'width' => array(),
			'class' => array(),
			'id' => array()
		),
		'mark' => array(
			'class' => array(),
			'id' => array(),
			'style' => array()
		),
		'acronym' => array(
			'title' => array()
		),
		'br' => array(),
		'b' => array(),
		'strong' => array(
			'class' => array()
		),
		'i' => array(
			'class' => array()
		),
		'em' => array(
			'class' => array()
		),
		'small' => array(
			'class' => array(),
			'style' => array()
		),
		's' => array(),
		'code' => array()
	);

	/**
	 * Add needed font weights for design controls.
	 *
	 * @since 4.8
	 */

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
	 * Settings that are often reused with the same values.
	 *
	 * @since 5.0
	 */

	public function values() {
		return array(
			'content_box' => array(
				'content_sidebar' => __( 'Content / Sidebar', 'md' ),
				'sidebar_content' => __( 'Sidebar / Content', 'md' )
			),
			'featured_image' => array(
				'right' => __( 'Right, text wrap', 'md' ),
				'left' => __( 'Left, text wrap', 'md' ),
				'center' => __( 'Center, no text wrap', 'md' ),
				'below_headline' => __( 'Full-width, below headline', 'md' ),
				'above_headline' => __( 'Full-width, above headline', 'md' ),
				'headline_cover' => __( 'Headline cover', 'md' ),
				'header_cover' => __( 'Header cover', 'md' ),
				'header_cover_full' => __( 'Full Header cover', 'md' ),
				'remove' => __( 'Hide image', 'md' )
			),
			'alignment' => array( 'alignleft', 'alignright', 'aligncenter' )
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
				$cats[] = esc_attr( $term->term_id );
		return $cats;
	}

	/**
	 * Run text field through native WP function.
	 *
	 * @since 4.5
	 */

	public function text( $input ) {
		return wp_kses( $input, $this->_allowed_html );
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
	 * Ensure we are saving an email address.
	 *
	 * @since 4.5
	 */

	public function email( $input ) {
		return sanitize_email( $input );
	}

	/**
	 * Escape image URL for uploaded media.
	 *
	 * @since 4.5
	 */

	public function upload( $input, $upload_type ) {
		if ( $upload_type == 'media' )
			$save = array(
				'id' => esc_attr( $input['id'] ),
				'url' => esc_url( $input['url'] )
			);
		return $save;
	}

	/**
	 * Properly save color values to color fields as hex or RGBA.
	 *
	 * @since 4.7
	 */

	public function color( $input ) {
		if ( strpos( $input, 'rgba' ) === false )
			return preg_match( '/^#[a-f0-9]{6}$/i', $input ) ? stripslashes( strip_tags( $input ) ) : '';
		sscanf( $input, 'rgba(%d,%d,%d,%f)', $r, $g, $b, $a );
		return "rgba({$r},{$g},{$b},{$a})";
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

	public function select( $input, $options ) {
		return in_array( $input, $options ) ? $input : '';
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
	 * Checks Content Box Customizer settings.
	 *
	 * @since 4.0
	 * @moved 4.5.4
	 */

	public function content_box( $input ) {
		return in_array( $input, array( 'content_sidebar', 'sidebar_content' ) ) ? $input : '';
	}

	/**
	 * Checks Featured Image position settings.
	 *
	 * @since 4.5
	 * @moved 4.5.4
	 */

	public function featured_image_position( $input ) {
		return in_array( $input, array( 'right', 'left', 'center', 'below_headline', 'above_headline', 'headline_cover', 'header_cover', 'header_cover_full', 'remove' ) ) ? $input : '';
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
			delete_user_meta( $term_id, $option );
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
	 * An ugly function (how can this be ade recursive?), but one thorough enough
	 * to properly validate and sanitize multiple levels of nested options.
	 * Sets up data and feeds option value to validate_field() method and then
	 * builds full options array for save.
	 *
	 * @since 4.7
	 */

	public function validate( $settings, $input ) {
		$save = array();
		$data = md_register( $settings );
		$whitelist = array( 'version', 'integrations', 'popups_data', 'license', 'custom_icons' );

		foreach ( $input as $key => $input_fields ) {
			$save[$key] = array();
			if ( ! in_array( $key, $whitelist ) ) {
				if ( ! empty( $data[$key]['fields'] ) )
					foreach ( $data[$key]['fields'] as $group => $group_fields ) {
						if ( isset( $group_fields['type'] ) && $group_fields['type'] == 'group' && isset( $input[$key][$group] ) ) {
							unset( $input[$key][$group]['{clone}'] );
							foreach ( $input[$key][$group] as $clone_group => $clone_fields ) {
								if ( isset( $group_fields['group_key_lowercase'] ) )
									$clone_group = strtolower( $clone_group );
								foreach ( $clone_fields as $clone_key => $clone_val )
									if ( ! empty( $clone_val ) || $clone_val == '0' )
										$save[$key][$group][$clone_group][$clone_key] = $this->validate_field( $clone_val, $data[$key]['fields'][$group]['fields'][$clone_key] );
							}
						}
						elseif ( isset( $group_fields['type'] ) && ( ! empty( $input[$key][$group] ) || ( ! empty( $input[$key][$group] ) && $input[$key][$group] == '0' ) ) )
							$save[$key][$group] = $this->validate_field( $input[$key][$group], $group_fields );
						else
							foreach ( $group_fields as $option_name => $option_fields )
								if ( isset( $option_fields['type'] ) && ! empty( $input[$key][$group][$option_name] ) )
									$save[$key][$group][$option_name] = $this->validate_field( $input[$key][$group][$option_name], $option_fields );
								elseif ( is_array( $option_fields ) )
									foreach ( $option_fields as $val_name => $val_fields )
										if ( isset( $val_fields['type'] ) && ! empty( $input[$key][$group][$option_name][$val_name] ) )
											$save[$key][$group][$option_name][$val_name] = $this->validate_field( $input[$key][$group][$option_name][$val_name], $val_fields );
					}
			}
			else
				$save[$key] = $input[$key];
		}

		return $save;
	}

	/**
	 * Validate and sanitize individual fields.
	 *
	 * @since 5.0
	 */

	public function validate_field( $val, $fields ) {
		$field = '';
		$type = isset( $fields['type'] ) ? $fields['type'] : '';
		$sub_options = isset( $fields['options'] ) ? $fields['options'] : '';

		if ( $val == '' && isset( $fields['default'] ) )
			$val = $fields['default'];

		if ( in_array( $type, array( 'text', 'textarea', 'editor', 'hidden' ) ) )
			$field = $this->text( $val );

		if ( $type == 'number' || $type == 'range' )
			$field = $this->number( $val );

		if ( in_array( $type, array( 'code', 'data' ) ) )
			$field = $val;

		if ( $type == 'url' )
			$field = $this->url( $val );

		if ( $type == 'checkbox' )
			$field = $this->checkbox( $val );

		if ( in_array( $type, array( 'select', 'radio' ) ) && is_array( $sub_options ) )
			$field = $this->select( $val, $sub_options );

		if ( $type == 'upload' ) {
			$upload_type = isset( $fields['upload_type'] ) ? $fields['upload_type'] : '';
			$field = $this->upload( $val, $upload_type );
		}

		if ( $type == 'color' )
			$field = $this->color( $val );

		return $field;
	}

}