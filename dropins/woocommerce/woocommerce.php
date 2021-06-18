<?php
/**
 * Add admin page and custom WooCommerce settings panel.
 *
 * @since 4.9.4
 */

class md_woocommerce extends md_api {

	/**
	 * Include WooCommerce files.
	 *
	 * @since 5.0
	 */

	public function includes() {
		require_once( 'templates.php' );
	}

	/**
	 * Run actions and filters.
	 *
	 * @since 4.9.4
	 */

	public function actions() {
		$this->woo = md_setting( array( 'woocommerce' ) );
		add_action( 'md_filter_dequeue_scripts', array( $this, 'dequeue' ) );
		add_filter( 'md_filter_sidebars_post_types', array( $this, 'sidebars' ) );
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'md_woocommerce' && isset( $_GET['settings-updated'] ) ) {
			add_action( 'admin_init', array( $this, 'utilities' ) );
			flush_rewrite_rules();
		}
	}

	/**
	 * Register admin page and related settings.
	 *
	 * @since 4.9.4
	 */

	public function register() {
		return array(
			'admin_page' => array(
				'name' => __( 'WooCommerce', 'md' ),
				'parent' => 'md_settings',
				'fields' => array(
					'settings' => array(
						'type' => 'checkbox',
						'options' => array(
							'enable_archives_sidebar',
							'enable_single_sidebar',
							'header_cart',
							'remove_css',
							'minimal_checkout',
							'remove_order_comments',
							'phone_optional'
						)
					),
					'sale_label' => array( 'type' => 'text' )
				)
			)
		);
	}

	/**
	 * Create admin settings.
	 *
	 * @since 4.9.4
	 */

	public function admin_page() {
		include( 'admin-page.php' );
	}

	/**
	 * Call the CSS compile function to rebuild MD's CSS based on
	 * user input, and set the delete option value to true. Should be
	 * a core MD API method.
	 *
	 * @since 4.9.4
	 */

	public function utilities() {
		md_compile_css( true );
	}

	/**
	 * Load CSS template to style.css.
	 *
	 * @since 4.9.4
	 */

	public function css( $templates ) {
		if ( empty( $this->woo['settings']['remove_css'] ) )
			$templates['woocommerce'] = md_css( 'dropins', 'woocommerce/css', true );
		return $templates;
	}

	/**
	 * Dequeue WooCommerce scripts and styles if requested.
	 *
	 * @since 4.9.4
	 */

	public function dequeue( $scripts ) {
		$scripts['woocommerce'] = array(
			'label' => __( '<b>Remove</b> WooCommerce scripts and styles', 'md' ),
			'styles' => array(
				'woocommerce-layout', 'woocommerce-smallscreen', 'woocommerce-general', 'wc-block-style'
			),
			'scripts' => array(
				'wc-add-to-cart', 'jquery-blockui', 'jquery-placeholder', 'woocommerce', 'jquery-cookie', 'wc-cart-fragments'
			)
		);
		return $scripts;
	}

	/**
	 * Add WooCommerce archives and single pages to MD Custom Sidebars.
	 *
	 * @since 4.9.4
	 */

	public function sidebars( $sidebars ) {
		if ( ! empty( $this->woo['settings']['enable_archives_sidebar'] ) )
			$sidebars['product']['archive'] = true;
		if ( ! empty( $this->woo['settings']['enable_single_sidebar'] ) )
			$sidebars['product']['single'] = true;
		return $sidebars;
	}

}

new md_woocommerce;