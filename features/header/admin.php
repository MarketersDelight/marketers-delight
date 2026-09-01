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
	 * Get elements available to the Header builder.
	 *
	 * @since 6.0
	 */

	public function builder_items() {
		return apply_filters( 'md_header_builder_elements', array(
			'link' => array(
				'title' => __( 'Link', 'md' ),
				'subtitle' => true,
				'color' => '#2772af',
				'icon' => 'admin-links',
				'callback' => array( $this->fields, 'builder_link' )
			),
			'search' => array(
				'title' => __( 'Search', 'md' ),
				'placeholder' => __( 'Search', 'md' ),
				'color' => '#41b141',
				'icon' => 'search',
				'callback' => array( $this->fields, 'builder_search' )
			),
			'menu' => array(
				'title' => __( 'Menu', 'md' ),
				'icon' => 'menu',
				'callback' => array( $this->fields, 'builder_menu' )
			)
		) );
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

		include md_template( 'features', 'header/admin/header', true );
	}

}

new md_header;
