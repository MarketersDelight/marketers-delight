<?php
/**
 * Create Logo Options settings page.
 *
 * @since 6.0
 */

class md_logo extends md_api {

	/**
	 * Register admin settings.
	 *
	 * @since 6.0
	 */

	public function register() {
		$typography = $this->fields->data->typography();
		$fields = array(
			'site_title' => array_merge( array(
				'text' => array(
					'type' => 'text',
					'label' => __( 'Site Title', 'md' )
				),
				'color' => array(
					'type' => 'color',
					'label' => __( 'Color', 'md' )
				)
			), $typography ),
			'site_tagline' => array_merge( array(
				'text' => array(
					'type' => 'text',
					'label' => __( 'Site Tagline', 'md' )
				),
				'color' => array(
					'type' => 'color',
					'label' => __( 'Color', 'md' )
				)
			), $typography ),
			'logo' => array(
				'type' => 'upload',
				'upload_type' => 'media',
				'label' => __( 'Logo', 'md' )
			),
			'logo_alt' => array(
				'type' => 'upload',
				'upload_type' => 'media'
			),
			'logo_width' => array(
				'desktop' => array(
					'type' => 'range',
					'max' => 500,
					'label' => sprintf( __( 'Logo Width%s', 'md' ), ' (desktop)' )
				),
				'mobile' => array(
					'type' => 'range',
					'max' => 500,
					'label' => sprintf( __( 'Logo Width%s', 'md' ), ' (mobile)' )
				)
			),
			'logo_html' => array(
				'type' => 'code',
				'label' => __( 'Logo HTML code', 'md' )
			),
			'logo_html_display' => array(
				'type' => 'checkbox',
				'label' => __( 'Custom logo', 'md' ),
				'options' => array(
					'enable' => __( 'Enable custom logo HTML', 'md' )
				)
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
		$values = $this->design()->values();
		$defaults = $this->design()->defaults();;

		include md_template( 'features', 'header/admin/logo', true );
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
