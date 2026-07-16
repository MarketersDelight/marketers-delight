<?php
/**
 * Create Header Options settings page.
 *
 * @since 5.0
 */

class md_header extends md_api {

	/**
	 * Create admin page with registered fields.
	 *
	 * @since 5.0
	 */

	public function register() {
		$menus = $this->fields->data->menus();
		$links = $this->fields->data->links( array( 'sort' => 'save' ) );
		$builder = array_merge( array(
			'builder_type' => array( 'type' => 'text' ),
			'builder_area' => array( 'type' => 'text' ),
			'name' => array( 'type' => 'text' ),
			'placeholder' => array( 'type' => 'text' ),
			'submit_text' => array( 'type' => 'text' ),
			'submenu_width' => array( 'type' => 'number' ),
			'menu' => array(
				'type' => 'select',
				'options' => $menus['ids']
			),


			'button_text' => array( 'type' => 'text' ),
			'button_url' => array( 'type' => 'url' ),



			'toggle' => array(
				'type' => 'checkbox',
				'options' => array( 'search' )
			)
		), $links );

		return array(
			'admin_page' => array(
				'name' => __( 'Header', 'md' ),
				'parent' => 'md_settings',
				'hide_tab' => true,
				'admin_header' => true,
				'fields' => array(
					'builder' => array(
						'type' => 'builder',
						'fields' => $builder
					),
					'layout' => array(
						'type' => 'radio',
						'options' => array( 'left', 'right', 'center' )
					),
					'layout_mobile' => array(
						'type' => 'radio',
						'options' => array( 'standard', 'expanded' )
					),
					'display' => array(
						'type' => 'checkbox',
						'options' => array( 'sticky', 'site_title', 'site_tagline', 'align_tagline', 'align_logo', 'hide_title_mobile', 'hide_tagline_mobile' )
					)
				)
			)
		);
	}

	/**
	 * Build Layout admin page fields.
	 *
	 * @since 5.0
	 */

	public function admin_page() {
		$values = $this->design()->values();
		$header = $values['header'];
		$defaults = $this->design()->defaults();

		include md_template( 'admin/header', true );
	}

}

new md_header;