<?php
/**
 * Holds frequently used data of more complex fields.
 *
 * @since 6.0
 */

class md_fields_data {

	public $sanitize;
	public $values;

	/**
     * Assign shared class data and other setup actions.
 	 *
 	 * @since 6.0
 	 */

	public function __construct() {
		$this->sanitize = new md_sanitize;
		$this->values = $this->values();
	}

	/**
	 * A generic list of commonly referenced option values.
	 *
	 * @since 5.0
	 */

	public function values() {
		return array(
			'featured_image' => array(
				'title_right' => __( 'Right, title wrap', 'md' ),
				'title_left' => __( 'Left, title wrap', 'md' ),
				'title_center' => __( 'Center, title wrap', 'md' ),
				'right' => __( 'Right, content wrap', 'md' ),
				'left' => __( 'Left, content wrap', 'md' ),
				'center' => __( 'Center, no content wrap', 'md' ),
				'above_headline' => __( 'Before headline', 'md' ),
				'below_headline' => __( 'After headline', 'md' ),
				'remove' => __( 'Don\'t show', 'md' )
			)
		);
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
     * A collection of save fields to be pre-grouped for Page Settings.
 	 *
 	 * @since 6.0
 	 */

	public function page_fields() {
		return apply_filters( 'md_page_settings_fields', array(
			'archives_title' => array( 'type' => 'text' ),
			'archives_text' => array( 'type' => 'textarea' )
		) );
	}

	/**
	 * Returns save field definitions for visibility conditions.
	 *
	 * @since 6.0
	 */

	public function visibility_condition() {
		$conditions = $visibility = array();
		$registered = apply_filters( 'md_visibility_conditions', array(
			'logged_in' => array(
				'label' => __( 'Logged in users only', 'md' ),
				'check' => function() {
					return is_user_logged_in();
				} ),
			'logged_out' => array(
				'label' => __( 'Logged out users only', 'md' ),
				'check' => function() {
					return ! is_user_logged_in();
				} ),
			'desktop' => array(
				'label' => __( 'Show on desktop only', 'md' ),
				'class' => 'show-desktop'
			),
			'mobile' => array(
				'label' => __( 'Show on mobile only', 'md' ),
				'class' => 'show-mobile'
			)
		) );

		foreach ( $registered as $key => $item ) {
			if ( isset( $item['check'] ) )
				$conditions[] = $key;

			if ( isset( $item['class'] ) )
				$visibility[] = $key;
		}

		return array(
			'condition' => array(
				'type' => 'checkbox',
				'options' => $conditions
			),
			'visibility' => array(
				'type' => 'checkbox',
				'options' => $visibility
			)
		);
	}

	/**
	 * Collect a list of fields in a Links Group.
	 *
	 * @since 6.0
	 */

	public function links( $args = array() ) {
		$group = isset( $args['group'] ) ? $args['group'] : array();
		$vc    = $this->visibility_condition();
		$fields = array(
			'display' => array(
				'field' => 'display',
				'save'  => $vc['visibility']
			),
			'name' => array(
				'field' => 'name',
				'save' => array( 'type' => 'text' )
			),
			'text' => array(
				'field' => 'text',
				'save' => array( 'type' => 'text' )
			),
			'subtitle' => array(
				'field' => 'subtitle',
				'save' => array( 'type' => 'text' )
			),
			'type' => array(
				'field' => 'type',
				'save' => array(
					'type' => 'select',
					'options' => array( 'url', 'popup', 'phone' )
				)
			),
			'style' => array(
				'field' => 'style',
				'save' => array(
					'type' => 'select',
					'options' => array( 'button' )
				)
			),
			'color' => array(
				'field' => 'color',
				'save' => array( 'type' => 'color' )
			),
			'icon' => array(
				'field' => 'icon',
				'save' => array(
					'type' => 'select',
					'options' => md_get_icons( 'ids' )
				)
			),
			'url' => array(
				'field' => 'url',
				'save' => array( 'type' => 'url' )
			),
			'settings' => array(
				'field' => 'settings',
				'save' => array(
					'type' => 'checkbox',
					'options' => array( 'new', 'icon_end' )
				)
			),
			'toggle' => array(
				'field' => 'toggle',
				'save' => array(
					'type' => 'checkbox',
					'options' => array( 'hide_label', 'hide_label_mobile' )
				)
			),
			'phone' => array(
				'field' => 'phone',
				'save' => array( 'type' => 'text' )
			),
			'popup' => array(
				'field' => 'popup',
				'save' => array(
					'type' => 'select',
					'options' => md_get_popups( 'ids' )
				)
			),
			'size' => array(
				'field' => 'size',
				'save' => array(
					'type' => 'select',
					'options' => array( 'small', 'large' )
				)
			),
			'button_style' => array(
				'field' => 'button_style',
				'save' => array(
					'type' => 'checkbox',
					'options' => array( 'outline', 'frame' )
				)
			),
			'user' => array(
				'field' => 'user',
				'save'  => $vc['condition']
			)
		);

		if ( $group )
			foreach ( $fields as $key => $options ) {
				$fields[$key]['field'] = (array) $key;
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