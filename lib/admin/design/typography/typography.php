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
		$this->sanitize = new md_sanitize;
		$this->design = new md_design;
		$this->defaults = $this->design->defaults();
		$this->values = $this->design->values();
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
		$groups = array( 'body', 'h1', 'h2', 'h3', 'h4', 'h5', 'header', 'site_title', 'site_tagline', 'sidebar', 'sidebar_title', 'footer', 'footer_title' );
		foreach ( $groups as $group ) {
			foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) {
				$fields[$group]['font_size'][$device]['type'] = 'range';
				$fields[$group]['line_height'][$device]['type'] = 'range';
			}
			$fields[$group]['font_family']['type'] = 'text';
			$fields[$group]['font_type'] = array(
				'type' => 'select',
				'options' => array( 'default', 'google', 'typekit' )
			);
			$fields[$group]['font_weight'] = array(
				'type' => 'select',
				'options' => array_keys( $this->sanitize->_font_weights )
			);
			if ( $group == 'body' )
				$fields[$group]['bold'] = array(
					'type' => 'select',
					'options' => array_keys( $this->sanitize->_font_weights )
				);
		}
		$fields['google_fonts']['type'] = 'text';
		return array(
			'admin_page' => array(
				'name' => __( 'Fonts & Typography', 'md' ),
				'parent' => 'md_site_design',
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
		$defaults = $this->defaults['typography'];
		include( 'typography-settings.php' );
	}

}

new md_typography;