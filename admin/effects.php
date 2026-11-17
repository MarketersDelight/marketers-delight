<?php
/**
 * The Effects and Forms boxes on Design: corners, borders, shadows and fields, saved
 * to the effects design group that a style can own.
 *
 * @since 6.2.2
 */

class md_effects extends md_api {

	/**
	 * Draw the box on the Design screen.
	 *
	 * @since 6.2.2
	 */

	public function actions() {
		add_action( 'md_hook_admin_design', array( $this, 'admin_page' ) );
	}

	/**
	 * Register the effects fields.
	 *
	 * @since 6.2.2
	 */

	public function register() {
		return array(
			'admin_page' => array(
				'name' => __( 'Effects', 'md' ),
				'fields' => array(
					'border_radius' => array(
						'type' => 'range',
						'label' => __( 'Corner Radius', 'md' )
					),
					'border_width' => array(
						'type' => 'range',
						'label' => __( 'Border Width', 'md' )
					),
					'field_style' => array(
						'type' => 'select',
						'label' => __( 'Field Style', 'md' ),
						'options' => array( 'underline' )
					),
					'field_radius' => array(
						'type' => 'range',
						'label' => __( 'Field Radius', 'md' )
					),
					'field_join' => array(
						'type' => 'checkbox',
						'label' => __( 'Join', 'md' ),
						'options' => array( 'join' )
					),
					'shadow_style' => array(
						'type' => 'select',
						'label' => __( 'Shadow Style', 'md' ),
						'options' => array( 'hard', 'none' )
					),
					'shadow_color' => array(
						'type' => 'color',
						'label' => __( 'Shadow Color', 'md' )
					)
				)
			)
		);
	}

	/**
	 * The Effects box.
	 *
	 * @since 6.2.2
	 */

	public function admin_page() {
		$effects = $this->design()->effects();

		include md_template( 'admin/effects', true );
	}

}

new md_effects;
