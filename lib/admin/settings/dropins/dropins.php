<?php
/**
 * Create Drop-ins settings panel.
 *
 * @since 5.2.1
 */

class md_dropins extends md_api {

	/**
	 * List of Dropins
	 */

	public $dropins = array(
		'docs' => array(
			'name' => 'Documentation',
			'author' => 'Alex',
			'version' => '1.0',
			'url' => 'https://marketersdelight.com/dropins/docs/',
			'image' => 'https://marketersdelight.com/wp-content/uploads/2020/12/docs-library-wordpress.jpg',
			'callback' => 'md_docs'
		),
		'accordion' => array(
			'name' => 'Accordion Widget',
			'author' => 'Alex',
			'version' => '1.0',
			'url' => 'https://marketersdelight.com/dropins/accordion-category-widget/',
			'image' => 'https://marketersdelight.com/wp-content/uploads/2020/12/accordion-widget-wordpress.jpg',
			'callback' => 'md_accordion'
		),
		'glossary' => array(
			'name' => 'Glossary',
			'author' => 'Alex',
			'version' => '1.2.1',
			'url' => 'https://marketersdelight.com/dropins/glossary/',
			'image' => 'https://marketersdelight.com/wp-content/uploads/2019/11/glossary-dropin-wordpress-marketers-delight.png',
			'callback' => 'md_glossary'
		),
		'beacon' => array(
			'name' => 'Beacon',
			'author' => 'Alex',
			'version' => '1.1',
			'url' => 'https://marketersdelight.com/dropins/beacon/',
			'image' => 'https://marketersdelight.com/wp-content/uploads/2019/11/beacon-wordpress.jpg',
			'callback' => 'md_beacon'
		),
		'testimonials' => array(
			'name' => 'Testimonials',
			'author' => 'Alex',
			'version' => '1.2',
			'url' => 'https://marketersdelight.com/dropins/testimonials/',
			'image' => 'https://marketersdelight.com/wp-content/uploads/2019/04/wordpress-testimonials.jpg',
			'callback' => 'md_testimonials'
		),
		'related-posts' => array(
			'name' => 'Related Posts',
			'author' => 'Alex',
			'version' => '1.0.1',
			'url' => 'https://marketersdelight.com/dropins/related-posts/',
			'image' => 'https://marketersdelight.com/wp-content/uploads/2019/05/related-posts-wordpress.jpg',
			'callback' => 'md_related_posts'
		),
		'page-blocks' => array(
			'name' => 'Page Blocks',
			'author' => 'Alex',
			'version' => '1.1',
			'url' => 'https://marketersdelight.com/dropins/page-blocks/',
			'image' => 'https://marketersdelight.com/wp-content/uploads/2019/05/page-blocks-content-editor.jpg',
			'callback' => 'md_page_blocks'
		),
		'gallery-blocks' => array(
			'name' => 'Gallery Blocks',
			'author' => 'Alex',
			'version' => '1.1.3',
			'url' => 'https://marketersdelight.com/dropins/gallery-blocks/',
			'image' => 'https://marketersdelight.com/wp-content/uploads/2019/05/gallery-blocks-dropin.jpg',
			'callback' => 'md_gallery_blocks'
		)
	);

	/**
	 * List of Theme Mods
	 */

	public $mods = array(
		'maintenance_mode' => array(
			'name' => 'Simple Maintenance Mode',
			'author' => 'Alex',
			'url' => 'https://mdforums.org/threads/simple-maintenance-mode-page.4532/'
		),
		'editor_badge' => array(
			'name' => 'Add badge to Blocks Editor',
			'author' => 'gauravtiwari',
			'url' => 'https://mdforums.org/threads/badge-in-wherever-you-want-in-gutenberg-editor.4546/'
		),
		'current_menu_item' => array(
			'name' => 'Add active class to archive menu links',
			'author' => 'Alex',
			'url' => 'https://mdforums.org/threads/badge-in-wherever-you-want-in-gutenberg-editor.4546/'
		),
		'conditional_nav_button' => array(
			'name' => 'Add conditional button to nav menu',
			'author' => 'Alex',
			'url' => 'https://mdforums.org/threads/add-conditional-button-to-nav-menu.4530/'
		)
	);

	/**
	 * An array of Dropins to enable/disable.
	 *
	 * @since 4.9.4
	 */

	public function options() {
		$options = array(
			'admin_bar' => sprintf( __( 'Enable <b>MD Admin Bar</b> [%s]', 'md' ), '<a href="https://marketersdelight.com/dropins/admin-bar/" target="_blank">?</a>' ),
			'subtitle' => __( 'Enable <b>Subtitle</b>', 'md' ),
			'stream' => sprintf( __( 'Enable <b>Stream</b> [%s]', 'md' ), '<a href="https://marketersdelight.com/marketers-delight-492/" target="_blank">?</a>' ),
			'bookshelf' => sprintf( __( 'Enable <b>Bookshelf</b> [%s]', 'md' ), '<a href="https://marketersdelight.com/books/" target="_blank">?</a>' ),
			'share' => __( 'Disable <b>Share</b>', 'md' ),
			'tracking_scripts' => __( 'Disable <b>Tracking Scripts</b>', 'md' ),
			'blocks' => sprintf( __( 'Disable <b>Gutenberg Blocks</b> [%s]', 'md' ), '<a href="https://marketersdelight.com/gutenberg-content-marketing-blocks/" target="_blank">?</a>' ),
			'footnotes' => __( 'Disable <b>Footnotes</b>', 'md' ),
			'main_menu' => __( 'Disable <b>Main Menu</b>', 'md' )
		);

		if ( class_exists( 'WooCommerce' ) )
			$options['woocommerce'] = sprintf( __( 'Enable <b>WooCommerce</b> [%s]', 'md' ), '<a href="https://marketersdelight.com/woocommerce/" target="_blank">?</a>' );

		return $options;
	}

	/**
	 * Register admin page.
	 *
	 * @since 5.2.1
	 */

	public function register() {
		return array(
			'admin_page' => array(
				'name' => __( 'Dropins', 'md' ),
				'admin_header' => true,
				'fields' => array(
					'features' => array(
						'type' => 'checkbox',
						'options' => array(
							'blocks',
							'stream',
							'bookshelf',
							'share',
							'main_menu',
							'admin_bar',
							'subtitle',
							'footnotes',
							'tracking_scripts',
							'woocommerce'
						)
					)
				)
			)
		);
	}


	/**
	 * Build admin fields.
	 *
	 * @since 5.2.1
	 */

	public function admin_page() {
		$dropins = $this->dropins;
		$mods = $this->mods;
		$options = $this->options();
		include( 'admin-page.php' );
	}

}

new md_dropins;