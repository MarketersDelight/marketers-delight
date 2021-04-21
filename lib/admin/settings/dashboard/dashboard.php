<?php
/**
 * Settings admin panel.
 *
 * @since 4.3
 */

class md_settings extends md_api {

	/**
	 * Register admin page.
	 *
	 * @since 5.0
	 */

	public function register() {
		return array(
			'admin_page' => array(
				'toplevel' => true,
				'name' => __( 'Marketers Delight', 'md' ),
				'admin_header' => true,
				'tab_name' => __( 'Settings', 'md' ),
				'icon' => 'dashicons-marketers-delight',
				'fields' => array(
					'license_key' => array( 'type' => 'text' ),
					'404_page' => array( 'type' => 'number' ),
					'header_scripts' => array( 'type' => 'code' ),
					'footer_scripts' => array( 'type' => 'code' ),
					'head' => array(
						'type' => 'checkbox',
						'options' => array( 'blocks', 'optimize', 'wpjson', 'oembed' )
					),
					'css' => array(
						'type' => 'checkbox',
						'options' => array( 'inline', 'child' )
					),
					'webfonts' => array(
						'type' => 'checkbox',
						'options' => array( 'loader' )
					)
				)
			)
		);
	}

	/**
	 * Build admin fields.
	 *
	 * @since 4.5
	 */

	public function admin_page() {
		$page404 = $this->fields->get_field( array( 'settings', '404_page' ) );
		include( 'templates/admin-page.php' );
	}

}

new md_settings;