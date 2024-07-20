<?php
/**
 * Holds frequently used data of more complex fields.
 *
 * @since 6.0
 */

class md_fields_data {

	public $sanitize;

	/**
     * Assign shared class data and other setup actions.
 	 *
 	 * @since 6.0
 	 */

	public function __construct() {
		$this->sanitize = new md_sanitize;
	}

	/**
     * A common fields structure for deploying Fonts & Typography options.
 	 *
 	 * @since 6.0
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
	 * Collect a list of fields in a Links Group.
	 *
	 * @since 6.0
	 */

	public function links( $args = array() ) {
		$p = isset( $args['prefix'] ) ? $args['prefix'] : '';
		$group = isset( $args['group'] ) ? $args['group'] : array();

		$fields = array(
			'display' => array(
				'field' => "{$p}display",
				'save' => array(
					'type' => 'select',
					'options' => array( 'mobile', 'desktop' )
				)
			),
			'name' => array(
				'field' => 'name',
				'save' => array( 'type' => 'text' )
			),
			'text' => array(
				'field' => "{$p}text",
				'save' => array( 'type' => 'text' )
			),
			'subtitle' => array(
				'field' => "{$p}subtitle",
				'save' => array( 'type' => 'text' )
			),
			'type' => array(
				'field' => "{$p}type",
				'save' => array(
					'type' => 'select',
					'options' => array( 'url', 'popup', 'phone' )
				)
			),
			'style' => array(
				'field' => "{$p}style",
				'save' => array(
					'type' => 'select',
					'options' => array( 'button' )
				)
			),
			'color' => array(
				'field' => "{$p}color",
				'save' => array( 'type' => 'color' )
			),
			'icon' => array(
				'field' => "{$p}icon",
				'save' => array(
					'type' => 'select',
					'options' => md_get_icons( 'ids' )
				)
			),
			'url' => array(
				'field' => "{$p}url",
				'save' => array( 'type' => 'url' )
			),
			'target' => array(
				'field' => "{$p}target",
				'save' => array(
					'type' => 'checkbox',
					'options' => array( 'new' )
				)
			),
			'toggle' => array(
				'field' => "{$p}toggle",
				'save' => array(
					'type' => 'checkbox',
					'options' => array( 'hide_label', 'hide_label_mobile' )
				)
			),
			'phone' => array(
				'field' => "{$p}phone",
				'save' => array( 'type' => 'text' )
			),
			'popup' => array(
				'field' => "{$p}popup",
				'save' => array(
					'type' => 'select',
					'options' => md_get_popups( 'ids' )
				)
			),
			'size' => array(
				'field' => "{$p}size",
				'save' => array(
					'type' => 'select',
					'options' => array( 'small', 'large' )
				)
			),
			'button_style' => array(
				'field' => "{$p}button_style",
				'save' => array(
					'type' => 'checkbox',
					'options' => array( 'outline', 'frame' )
				)
			),
			'user' => array(
				'field' => "{$p}user",
				'save' => array(
					'type' => 'checkbox',
					'options' => array( 'logged_in', 'logged_out' )
				)
			)
		);

		if ( isset( $args['keys'] ) )
			foreach ( $args['keys'] as $old => $new )
				$fields[$old]['field'] = "{$p}$new";

		if ( $group )
			foreach ( $fields as $key => $field ) {
				$fields[$key]['field'] = (array) $field['field'];
				$fields[$key]['field'] = array_merge( $group, $fields[$key]['field'] );
			}

		if ( isset( $args['sort'] ) ) {
			$sort = array();

			if ( $args['sort'] =='save' ) {
				foreach ( $fields as $key => $options ) {
					$field_key = $options['field'];
					$sort[$field_key] = $options['save'];
				}
			}

			$fields = $sort;
		}

		return $fields;
	}

}
