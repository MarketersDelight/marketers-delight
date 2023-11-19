<?php
/**
 * Create Header Options settings page.
 *
 * @since 5.0
 */

class md_header extends md_api {

	/**
	 * Include related files.
	 *
	 * @since 5.6
	 */

	public function includes() {
		include_once( 'header-functions.php' );
	}

	/**
	 * Create admin page with registered fields.
	 *
	 * @since 5.0
	 */

	public function register() {
		$menus = $this->_data( 'menus' );
		$link_fields = $this->fields->link_fields_data( array( 'save' => true ) );
		$builder_fields = array_merge( array(
			'type' => array( 'type' => 'text' ),
			'area' => array( 'type' => 'text' ),
			'title' => array( 'type' => 'text' ),
			'placeholder' => array( 'type' => 'text' ),
			'submenu_width' => array( 'type' => 'number' ),
			'menu' => array(
				'type' => 'select',
				'options' => $menus['ids']
			),
			'toggle' => array(
				'type' => 'checkbox',
				'options' => array( 'search' )
			)
		), $link_fields );

		return array(
			'admin_page' => array(
				'name' => __( 'Header', 'md' ),
				'parent' => 'md_settings',
				'order' => 30,
				'fields' => array(
					'display' => array(
						'type' => 'checkbox',
						'options' => array(
							'site_title',
							'site_tagline',
							'align_tagline',
							'hide_title_mobile',
							'hide_tagline_mobile'
						)
					),
					'builder' => array(
						'type' => 'builder',
						'fields' => $builder_fields
					),
					'layout' => array(
						'type' => 'radio',
						'options' => array( 'standard', 'rtl', 'flyer' )
					),
					'layout_mobile' => array(
						'type' => 'radio',
						'options' => array( 'standard', 'expanded' )
					),
					'display' => array(
						'type' => 'checkbox',
						'options' => array(
							'site_title',
							'site_tagline',
							'align_tagline',
							'hide_title_mobile',
							'hide_tagline_mobile'
						)
					)
				)
			)
		);
	}

	/**
	 * Register Builder field data to admin.
	 *
	 * @since 5.6
	 */

	public function register_builder() {
		return array(
			'type' => 'builder',
			'wrap_classes' => 'md-tabs',
			'tabs' => array(
				'header' => __( 'Header', 'md' )
			),
			'active_tab' => 'header',
			'areas' => array(
				'header' => array(
					'title' => __( 'Header', 'md' ),
					'description' => __( 'The main branding and navigation area at the top of every page.', 'md' ),
					'tab' => 'header'
				),
				'header_aside' => array(
					'title' => __( 'Header Aside', 'md' ),
					'description' => __( 'A secondary content area for the header area.', 'md' ),
					'tab' => 'header'
				),
			),
			'elements' => array(
				'link' => array(
					'title' => __( 'Link', 'md' ),
					'color' => '#2772af',
					'icon' => 'admin-links',
					'callback' => array( $this, 'link_fields' )
				),
				'search' => array(
					'title' => __( 'Search', 'md' ),
					'placeholder' => __( 'Search', 'md' ),
					'color' => '#41b141',
					'icon' => 'search',
					'callback' => array( $this, 'search_fields' )
				),
				'menu' => array(
					'title' => __( 'Menu', 'md' ),
					'icon' => 'menu',
					'callback' => array( $this, 'menu_fields' )
				)
			)
		);
	}

	/**
	 * Load header hooks to template_redirect.
	 *
	 * @since 5.6
	 */

	public function template() {
		include_once( 'templates.php' );
		$templates = new md_header_templates;
		add_action( 'md_hook_header', array( $templates, 'template' ) );
	}

	/**
	 * Build Layout admin page fields.
	 *
	 * @since 5.0
	 */

	public function admin_page() {
		$values = $this->_data( 'values' );
		$header = $values['header'];
		$defaults = $this->_data( 'defaults' );
		$header_layout = ! empty( $header['layout'] ) ? $header['layout'] : '';
		$builder_fields = $this->register_builder();

		include( 'admin/admin-page.php' );
	}

	/**
	 * Extra JS for radio toggle fields on this page.
	 *
	 * @since 5.6
	 */

	public function admin_scripts() { ?>
		<script>
			jQuery( '.md-header-layout .md-radio-check' ).change( function() {
				var settings = jQuery( '.md-header-settings' );
				if ( this.value == 'flyer' )
					settings.addClass( 'is-flyer' );
				else if ( settings.hasClass( 'is-flyer' ) )
					settings.removeClass( 'is-flyer' );
			});
		</script>
	<?php }

	/**
	 * Menu admin fields template.
	 *
	 * @since 5.6
	 */

	public function menu_fields( $group ) {
		$data = $this->_data();

		include( 'admin/menu-fields.php' );
	}

	/**
	 * Search admin fields template.
	 *
	 * @since 5.6
	 */

	public function search_fields( $group ) {
		include( 'admin/search-fields.php' );
	}

	/**
	 * Link admin fields template.
	 *
	 * @since 5.6
	 */

	public function link_fields( $group ) {
		$this->fields->link_fields( array(
			'group' => array( 'builder', $group )
		) );
	}

}

new md_header;
