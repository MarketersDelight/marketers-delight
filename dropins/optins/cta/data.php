<?php
/**
 * A kind of lame but useful class that holds data
 * for Email Forms required across different screens.
 *
 * @since 5.0
 */

class md_cta_data {

	/**
	 * Save settings for each email form.
	 *
	 * @since 5.0
	 */

	public function fields() {
		$fields = array(
			'name' => array( 'type' => 'text' ),
			'title' => array( 'type' => 'text' ),
			'title_html' => array(
				'type' => 'select',
				'options' => array( 'h1', 'h2', 'h3', 'h4' )
			),
			'text' => array( 'type' => 'textarea' ),
			'image' => array(
				'type' => 'upload',
				'upload_type' => 'media'
			),
			'image_alignment' => array(
				'type' => 'select',
				'options' => array( 'alignleft', 'alignright', 'aligncenter' )
			),
			'image_width' => array( 'type' => 'range' ),
			'image_classes' => array( 'type' => 'text' ),
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
			'email_custom' => array( 'type' => 'code' ),
			'email_input' => array(
				'type' => 'checkbox',
				'options' => array( 'name' )
			),
			'email_name_label' => array( 'type' => 'text' ),
			'email_email_label' => array( 'type' => 'text' ),
			'email_submit_text' => array( 'type' => 'text' ),
			'email_form_footer' => array( 'type' => 'text' ),
			'email_form_style' => array(
				'type'    => 'checkbox',
				'options' => array( 'attached' )
			),
			'email_thank_you' => array( 'type' => 'text' ),
			'locations' => array(
				'type' => 'checkbox',
				'options' => array_keys( md_optins_locations( 'ids' ) )
			),
			'position' => array(
				'type' => 'select',
				'options' => array( 'before_html', 'before_content', 'content', 'before_sidebar', 'after_sidebar', 'before_footer' )
			),
			'rules' => array(
				'type' => 'select',
				'options' => array( 'logged_in', 'logged_out' )
			),
			'bg_image' => array(
				'type' => 'upload',
				'upload_type' => 'media',
			),
			'bg_image_style' => array(
				'type' => 'checkbox',
				'options' => array( 'auto' ),
			),
			'classes' => array( 'type' => 'text' ),
			'html' => array( 'type' => 'code' ),
			'html_format' => array(
				'type' => 'checkbox',
				'options' => array( 'wp' )
			)
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
				'color' => $defaults['colors']['site']['accent']
			),
			'text_color' => array(
				'label' => __( 'Text Color', 'md' ),
				'color' => $defaults['colors']['site']['text'],
			),
			'text_sec_color' => array(
				'label' => __( 'Secondary Text Color', 'md' ),
				'color' => $defaults['colors']['site']['text-sec'],
			),
			'button_color' => array(
				'label' => __( 'Button Color', 'md' ),
				'color' => $defaults['colors']['site']['button']
			),
			'button_text_color' => array(
				'label' => __( 'Button Text Color', 'md' ),
				'color' => $defaults['colors']['site']['button-text']
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
	 * Email admin page scripts.
	 *
	 * @since 5.0
	 */

	public function admin_scripts() { ?>
		<script>
			jQuery( document ).ready( function( $ ) {
				$( document ).on( 'change', '.md-email-list', function( e ) {
					var val = $( this ).val(),
						parent = $( this ).parents( '.cta-form' );
					if ( val === 'custom_html' ) {
						parent.find( '.md-cta-custom-html' ).show();
						parent.find( '.md-cta-fields' ).hide();
					}
					else {
						parent.find( '.md-cta-custom-html' ).hide();
						parent.find( '.md-cta-fields' ).show();
					}
				});
				$( document ).on( 'change', '.cta-button-type', function( e ) {
					var val = $( this ).val(),
						parent = $( this ).parents( '.cta-button' );
					if ( val == '' )
						parent.find( '.cta-button-group' ).hide();
					else
						parent.find( '.cta-button-group' ).show();

					if ( val == 'url' || val == 'close' ) {
						parent.find( '.cta-button-url' ).show();
						parent.find( '.cta-button-popup' ).hide();
					}
					if ( val == 'popup' ) {
						parent.find( '.cta-button-url' ).hide();
						parent.find( '.cta-button-popup' ).show();
					}
				});
			});
		</script>
	<?php }

}