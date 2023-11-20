<?php
/**
 * Create Logo Options settings page.
 *
 * @since 6.0
 */

class md_logo extends md_api {

	/**
	 * Include related files.
	 *
	 * @since 6.0
	 */

	public function includes() {
		include_once( 'logo-functions.php' );
	}

	/**
	 * Register admin settings.
	 *
	 * @since 6.0
	 */

	public function register() {
		$typography = array();
		$sanitize = new md_sanitize;
		$fields = array(
			'site_title_text' => array( 'type' => 'text' ),
			'site_tagline_text' => array( 'type' => 'text' ),
			'site_title_color' => array( 'type' => 'color' ),
			'site_tagline_color' => array( 'type' => 'color' ),
			'logo' => array(
				'type' => 'upload',
				'upload_type' => 'media'
			),
			'logo_alt' => array(
				'type' => 'upload',
				'upload_type' => 'media'
			),
			'logo_width' => array(
				'desktop' => array( 'type' => 'range' ),
				'tablet' => array( 'type' => 'range' ),
				'mobile' => array( 'type' => 'range' )
			),
			'logo_html' => array( 'type' => 'code' ),
			'logo_html_display' => array(
				'type' => 'checkbox',
				'options' => array( 'enable' )
			)
		);

		foreach ( array( 'site_title', 'site_tagline' ) as $group ) {
			foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) {
				$typography[$group]['font_size'][$device]['type'] = 'range';
				$typography[$group]['line_height'][$device]['type'] = 'range';
			}

			$typography[$group]['font_family']['type'] = 'text';
			$typography[$group]['font_type'] = array(
				'type' => 'select',
				'options' => array( 'default', 'google', 'typekit' )
			);
			$typography[$group]['font_weight'] = array(
				'type' => 'select',
				'options' => array_keys( $sanitize->_font_weights )
			);

			if ( $group == 'body' )
				$typography[$group]['bold'] = array(
					'type' => 'select',
					'options' => array_keys( $sanitize->_font_weights )
				);
		}

		$fields = array_merge( $fields, $typography );

		return array(
			'admin_page' => array(
				'name' => __( 'Logo', 'md' ),
				'parent' => 'md_settings',
				'order' => 20,
				'fields' => $fields
			)
		);
	}

	/**
	 * Build Layout admin page fields.
	 *
	 * @since 5.0
	 */

	public function admin_page() {
		$values = $this->_data( 'values' );
		$defaults = $this->_data( 'defaults' );

		include( 'admin-page.php' );
	}

	/**
	 * Extra JS for radio toggle fields on this page.
	 *
	 * @since 5.6
	 */

	public function admin_scripts() { ?>
		<script>
			document.getElementById( 'marketers_delight_logo_logo_html_display_enable' ).onchange = function( e ) {
				jQuery( '.md-header-logo' ).toggleClass( 'md-has-logo-html' );
			};
		</script>
	<?php }

}

new md_logo;
