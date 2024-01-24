<?php
/**
 * Create Site Design admin page.
 *
 * @since 5.0
 */

class md_typography extends md_api {

	/**
	 * Actions, filters, and properties.
	 *
	 * @since 5.0
	 */

	public function actions() {
		// Build Google Fonts URL after save.
		if ( isset( $_GET['settings-updated'] ) && md_web_fonts( 'google' ) ) {
			$option = md_setting();
			$option['typography']['google_fonts'] = md_google_fonts();

			update_option( 'marketers_delight', $option );
		}
	}

	/**
	 * Register admin page.
	 *
	 * @since 5.0
	 */

	public function register() {
		$fields = array();
		$groups = array( 'body', 'h1', 'h2', 'h3', 'h4', 'h5', 'sidebar', 'sidebar_title', 'footer', 'footer_title' );

		foreach ( $groups as $group ) {
			$fields[$group] = $this->fields->data->typography();

			if ( $group == 'body' ) {
				$sanitize = new md_sanitize;
				$fields[$group]['bold'] = array(
					'type' => 'select',
					'options' => array_keys( $sanitize->_font_weights )
				);
			}
		}

		$fields['google_fonts']['type'] = 'text';

		return array(
			'admin_page' => array(
				'name' => __( 'Typography', 'md' ),
				'parent' => 'md_settings',
				'order' => 10,
				'fields' => $fields
			)
		);
	}

	/**
	 * Create admin settings fields.
	 *
	 * @since 5.0
	 */

	public function admin_page() {
		$design = new md_design;
		$values = $design->values();
		$defaults = $design->defaults();
		$defaults = $defaults['typography'];

		include( 'templates/typography-settings.php' );
	}

}

new md_typography;
