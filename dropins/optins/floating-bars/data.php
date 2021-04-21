<?php
/**
 * A kind of lame but useful class that holds data
 * for Floating Bars required across different screens.
 *
 * @since 5.0
 */

class md_floating_bars_data {

	/**
	 * List of possible Group saved fields.
	 *
	 * @since 5.0
	 */

	public function fields() {
		$fields = array(
			'name' => array( 'type' => 'text' ),
			'title' => array( 'type' => 'text' ),
			'text' => array( 'type' => 'textarea' ),
			'media_type' => array(
				'type' => 'select',
				'options' => array( 'image', 'icon' )
			),
			'image' => array(
				'type' => 'upload',
				'upload_type' => 'media'
			),
			'icon' => array(
				'type' => 'select',
				'options' => md_get_icons( 'ids' )
			),
			'cta_type' => array(
				'type' => 'select',
				'options' => array( 'button', 'email', 'html' )
			),
			'button_type' => array(
				'type' => 'select',
				'options' => array( 'url', 'popup', 'close' )
			),
			'button_text' => array( 'type' => 'text' ),
			'button_url' => array( 'type' => 'text' ),
			'button_popup' => array(
				'type' => 'select',
				'options' => md_get_popups( 'ids' )
			),
			'email_list' => array(
				'type' => 'select',
				'options' => md_email_data( array( 'show' => 'ids' ) )
			),
			'email_input' => array(
				'type' => 'checkbox',
				'options' => array( 'name' )
			),
			'email_name_label' => array( 'type' => 'text' ),
			'email_email_label' => array( 'type' => 'text' ),
			'email_submit_text' => array( 'type' => 'text' ),
			'email_form_style' => array(
				'type'    => 'checkbox',
				'options' => array( 'attached' )
			),
			'html' => array( 'type' => 'code' ),
			'html_format' => array(
				'type' => 'checkbox',
				'options' => array( 'wp' )
			),
			'locations' => array(
				'type' => 'checkbox',
				'options' => array_keys( md_optins_locations( 'ids' ) )
			),
			'position' => array(
				'type' => 'select',
				'options' => array( 'before_footer', 'top_floating', 'top_static' )
			),
			'rules' => array(
				'type' => 'select',
				'options' => array( 'logged_in', 'logged_out' )
			),
			'show' => array(
				'type' => 'select',
				'options' => array( 'percent' )
			),
			'delay' => array( 'type' => 'number' ),
			'display' => array(
				'type' => 'checkbox',
				'options' => array( 'close' )
			),
			'cookie' => array( 'type' => 'number' ),
			'layout' => array(
				'type' => 'checkbox',
				'options' => array( 'full_width' )
			),
			'content_width' => array( 'type' => 'range' ),
			'cta_width' => array( 'type' => 'range' ),
			'image_width' => array( 'type' => 'range' ),
			'image_style' => array(
				'type' => 'checkbox',
				'options' => array( 'shadow', 'breakout' )
			),
			'classes' => array( 'type' => 'text' )
		);
		return array_merge( $fields, $this->colors( 'fields' ) );
	}

	/**
	 * Organize Color options.
	 *
	 * @since 5.0
	 */

	public function colors( $sort = null ) {
		$design = new md_design;
		$defaults = $design->values();
		$colors = array(
			'bg_color' => array(
				'label' => __( 'Background Color', 'md' ),
				'color' => $defaults['colors']['site']['secondary']
			),
			'text_color' => array(
				'label' => __( 'Text Color', 'md' ),
				'color' => '#FFFFFF'
			),
			'text_sec_color' => array(
				'label' => __( 'Secondary Text Color', 'md' ),
				'color' => '#DDDDDD'
			),
			'links_color' => array(
				'label' => __( 'Links Color', 'md' ),
				'color' => $defaults['colors']['footer']['links']
			),
			'button_color' => array(
				'label' => __( 'Button Color', 'md' ),
				'color' => $defaults['colors']['site']['button']
			),
			'button_text_color' => array(
				'label' => __( 'Button Text Color', 'md' ),
				'color' => $defaults['colors']['site']['button-text']
			),
			'icon_color' => array(
				'label' => __( 'Icon Color', 'md' ),
				'color' => $defaults['colors']['site']['primary']
			),
			'icon_text_color' => array(
				'label' => __( 'Icon Text Color', 'md' ),
				'color' => '#FFFFFF'
			)
		);

		if ( $sort == 'fields' )
			foreach ( $colors as $color => $fields )
				$data[$color]['type'] = 'color';
		else
			$data = $colors;

		return $data;
	}

	/**
	 * Floating Bar admin page scripts.
	 *
	 * @since 5.0
	 */

	public function admin_scripts() { ?>
		<script>
			jQuery( document ).ready( function( $ ) {
				$( document ).on( 'change', '.floating-button-type', function( e ) {
					var val = $( this ).val(),
						parent = $( this ).parents( '.floating-button' );
					if ( val == '' ) {
						parent.find( '.floating-button-url' ).hide();
						parent.find( '.floating-button-popup' ).hide();
						parent.find( '.floating-button-html' ).hide();
					}
					if ( val == 'url' || val == 'close' ) {
						parent.find( '.floating-button-url' ).show();
						parent.find( '.floating-button-popup' ).hide();
						parent.find( '.floating-button-html' ).hide();
					}
					if ( val == 'popup' ) {
						parent.find( '.floating-button-url' ).show();
						parent.find( '.floating-button-popup' ).show();
						parent.find( '.floating-button-html' ).hide();
					}
					if ( val == 'html' ) {
						parent.find( '.floating-button-html' ).show();
						parent.find( '.floating-button-url' ).hide();
						parent.find( '.floating-button-popup' ).hide();
					}
				});
				$( document ).on( 'change', '.floating-bar-position', function( e ) {
					var val = $( this ).val(),
						parent = $( this ).parents( '.floating-bar-display' );
					if ( val !== 'before_footer' )
						parent.find( '.floating-bar-display-options' ).show();
					else
						parent.find( '.floating-bar-display-options' ).hide();
				});
			});
		</script>
	<?php }

}