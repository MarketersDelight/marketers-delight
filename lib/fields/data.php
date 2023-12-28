<?php
/**
 * Holds frequently used data of more complex fields.
 *
 * @since 5.6
 */

class md_fields_data {

	public $sanitize;

	/**
     * Assign shared class data and other setup actions.
 	 *
 	 * @since 5.6
 	 */

	public function __construct() {
		$this->sanitize = new md_sanitize;
	}

	/**
     * A common fields structure for deploying Fonts & Typography options.
 	 *
 	 * @since 5.6
 	 */

	public function typography() {
		$fields = array();

		foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) {
			$fields['font_size'][$device]['type'] = 'range';
			$fields['line_height'][$device]['type'] = 'range';
		}

		$fields['font_family']['type'] = 'text';

		$fields['font_type'] = array(
			'type' => 'select',
			'options' => array( 'default', 'google', 'typekit' )
		);

		$fields['font_weight'] = array(
			'type' => 'select',
			'options' => array_keys( $this->sanitize->_font_weights )
		);

		return $fields;
	}

	/**
     * A collection of save fields to be pre-grouped for Page Settings.
 	 *
 	 * @since 5.6
 	 */

	public function page_settings() {
		return apply_filters( 'md_page_settings_fields', array(
			'archives_title' => array( 'type' => 'text' ),
			'archives_text' => array( 'type' => 'textarea' ),
			'page_cta' => array(
				'type' => 'select',
				'options' => array( 'links', 'custom' )
			),
			'custom_html' => array( 'type' => 'code' )
		) );
	}

	/**
	 * Collect a list of fields in a Links Group.
	 *
	 * @since 5.6
	 */

	public function links( $args = array() ) {
		$p = isset( $args['prefix'] ) ? $args['prefix'] : '';
		$group = isset( $args['group'] ) ? $args['group'] : array();

		$fields = array(
			'link_text' => array(
				'field' => "{$p}link_text",
				'save' => array( 'type' => 'text' )
			),
			'link_type' => array(
				'field' => "{$p}link_type",
				'save' => array(
					'type' => 'select',
					'options' => array( 'url', 'popup', 'phone' )
				)
			),
			'link_style' => array(
				'field' => "{$p}link_style",
				'save' => array(
					'type' => 'select',
					'options' => array( 'button' )
				)
			),
			'link_icon' => array(
				'field' => "{$p}link_icon",
				'save' => array(
					'type' => 'select',
					'options' => md_get_icons( 'ids' )
				)
			),
			'link_url' => array(
				'field' => "{$p}link_url",
				'save' => array( 'type' => 'url' )
			),
			'link_target' => array(
				'field' => "{$p}link_target",
				'save' => array(
					'type' => 'checkbox',
					'options' => array( 'new' )
				)
			),
			'link_toggle' => array(
				'field' => "{$p}link_toggle",
				'save' => array(
					'type' => 'checkbox',
					'options' => array( 'hide_label', 'hide_label_mobile' )
				)
			),
			'link_phone' => array(
				'field' => "{$p}link_phone",
				'save' => array( 'type' => 'text' )
			),
			'link_popup' => array(
				'field' => "{$p}link_popup",
				'save' => array(
					'type' => 'select',
					'options' => md_get_popups( 'ids' )
				)
			),
			'link_button_style' => array(
				'field' => "{$p}link_button_style",
				'save' => array(
					'type' => 'select',
					'options' => array( 'outline' )
				)
			),
			'link_button_color' => array(
				'field' => "{$p}link_button_color",
				'save' => array( 'type' => 'color' )
			)
		);

		if ( isset( $args['keys'] ) )
			foreach ( $fields as $key => $settings ) {
				$field_key = ! empty( $args['keys'][$key] ) ? esc_attr( $args['keys'][$key] ) : '';

				if ( $field_key )
					$fields[$key]['field'] = str_replace( $key, $field_key, $fields[$key]['field'] );
			}

		if ( $group )
			foreach ( $fields as $key => $field ) {
				$fields[$key]['field'] = (array) $field['field'];
				$fields[$key]['field'] = array_merge( $group, $fields[$key]['field'] );
			}

		if ( isset( $args['save'] ) )
			foreach ( $fields as $key => $options ) {
//				$field_val = $fields[$key]['field'];
				unset( $fields[$key] );
				$fields[$key] = $options['save'];
			}

		return $fields;
	}

}
