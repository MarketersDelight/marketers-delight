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
		$typography = $this->fields->data->typography();
		$fields = array(
			'site_title' => array_merge( array(
				'text' => array( 'type' => 'text' ),
				'color' => array( 'type' => 'color' ),
			), $typography ),
			'site_tagline' => array_merge( array(
				'text' => array( 'type' => 'text' ),
				'color' => array( 'type' => 'color' ),
			), $typography ),
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

		return array(
			'admin_page' => array(
				'name' => __( 'Logo', 'md' ),
				'parent' => 'md_settings',
				'admin_header' => true,
				'hide_tab' => true,
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
	 * @since 6.0
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
