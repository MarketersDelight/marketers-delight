<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Define MD constants
define( 'MD_VERSION', '5.3' );
define( 'MD_THEME_NAME', 'Marketers Delight 4' );
define( 'MD_THEME_AUTHOR', 'Alex Mangini' );
define( 'MD_THEME_UPDATER_URL', 'https://marketersdelight.com' );
define( 'MD_DIR', trailingslashit( get_template_directory() ) );
define( 'MD_URL', trailingslashit( get_template_directory_uri() ) );
define( 'MD_PLUGIN_DIR', MD_DIR . 'lib/' );
define( 'MD_PLUGIN_URL', MD_URL . 'lib/' );
define( 'MD_DROPINS_DIR', MD_DIR . 'dropins/' ); #4.7
define( 'MD_INSTALLED_DROPINS', ABSPATH . 'wp-content/md-dropins' ); #5.3
define( 'MD_CSS_DIR', MD_DIR . 'css/' ); #4.9.4

/**
 * The main Marketers Delight Class that activates all WP, MD
 * and other features throughout this WordPress website.
 *
 * @since 4.0
 */

final class marketers_delight {

	/**
	 * Start the entire MD library.
	 *
	 * @since 4.9.4
	 */

	public function init() {
		$this->includes();
		add_action( 'init', array( $this, 'wp_init' ) );
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'after_switch_theme', 'md_compile_css' );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'wp_head', array( $this, 'head' ), 5 );
		add_filter( 'user_contactmethods', array( $this, 'profile_fields' ) );
		add_action( 'widgets_init', array( $this, 'widgets' ) );
		add_filter( 'md_post_type_meta', array( $this, 'post_types_meta' ) );
		add_filter( 'md_taxonomy_meta', array( $this, 'taxonomies_meta' ) );
	}

	/**
	 * Load all required files.
	 *
	 * @since 4.9.4
	 */

	public function includes() {
		require_once( MD_DIR . 'lib/api/hooks.php' );
		require_once( MD_DIR . 'lib/api/filters.php' );
		require_once( MD_DIR . 'lib/api/fields.php' );
		require_once( MD_DIR . 'lib/api/icons.php' );
		require_once( MD_DIR . 'lib/api/css.php' );
		require_once( MD_DIR . 'lib/api/files.php' );
		require_once( MD_DIR . 'lib/api/design.php' );
		require_once( MD_DIR . 'lib/functions/template-functions.php' );
		require_once( MD_DIR . 'lib/functions/design-functions.php' );
		require_once( MD_DIR . 'lib/api/sanitize.php' );
		require_once( MD_DIR . 'lib/functions/deprecated.php' );
		require_once( MD_DIR . 'lib/api/api.php' );
		if ( is_admin() )
			require_once( MD_DIR . 'lib/admin/admin.php' );
		require_once( MD_DIR . 'lib/functions/conditionals.php' );
		require_once( MD_DIR . 'lib/functions/build.php' );
		require_once( MD_DIR . 'lib/functions/featured-image.php' );
		$this->dropins();
		require_once( MD_DIR . 'lib/functions/classes.php' );
		require_once( MD_DROPINS_DIR . 'featured-image/featured-image.php' );
		require_once( MD_DROPINS_DIR . 'featured-video/featured-video.php' );
		require_once( MD_DIR . 'lib/wp/optimize.php' );
		require_once( MD_DIR . 'lib/wp/walker.php' );
		foreach ( array( 'accordion', 'content-spotlight', 'text-image', 'quote' ) as $widget )
			include_once( MD_DIR . "lib/wp/widgets/$widget.php" );
		if ( function_exists( 'register_block_type' ) && ! md_setting( array( 'content', 'post', 'blocks' ) ) )
			require_once( MD_DIR . 'lib/wp/blocks/blocks.php' );
		if ( md_setting( array( 'content', 'post', 'subtitle' ) ) )
			require_once( MD_DROPINS_DIR . 'subtitle.php' );
		if ( md_setting( array( 'content', 'post', 'footnotes' ) ) )
			require_once( MD_DROPINS_DIR . 'footnotes/footnotes.php' );
	}

	/**
	 * Load MD Dropins after theme is setup.
	 *
	 * @since 4.6
	 */

	public function dropins() {
		$core = md_get_dropins( 'core', 'active' );
		foreach ( $core as $dropin )
			if ( md_has( $dropin ) && file_exists( $file = MD_DROPINS_DIR . "$dropin/$dropin.php" ) )
				require_once( $file );

		$installed = md_get_dropins( 'installed', 'active' );
		foreach ( $installed as $dropin ) {
			if ( md_has( $dropin ) )
				if ( file_exists( $file = MD_INSTALLED_DROPINS . "/$dropin/$dropin.php" ) )
					require_once( $file );
				else {
					$option = md_setting();
					unset( $option['dropins']['installed'][$dropin]['status']['enable'] );
					update_option( 'marketers_delight', $option );
				}
		}
	}

	/**
	 * Include some important theme files, register menus and add
	 * support for other WordPress features.
	 *
	 * @since 4.0
	 */

	public function setup() {
		// Load Textdomain
		load_theme_textdomain( 'md' );

		// Add WordPress Features
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-color-palette', md_editor_colors() );

		// Register Nav Menus
		register_nav_menus( md_filter_register_nav_menus() );

		// Add Image Sizes
		$sizes = md_image_sizes();
		add_image_size( 'md-full', $sizes['md-full']['width'], $sizes['md-full']['height'], true );
		add_image_size( 'md-banner', $sizes['md-banner']['width'], $sizes['md-banner']['height'], true );
		add_image_size( 'md-block', $sizes['md-block']['width'], $sizes['md-block']['height'], true );
		add_image_size( 'md-image', $sizes['md-image']['width'], $sizes['md-image']['height'], true );
		add_image_size( 'md-thumbnail', $sizes['md-thumbnail']['width'], $sizes['md-thumbnail']['height'], true );
		add_image_size( 'md-book', $sizes['md-book']['width'], $sizes['md-book']['height'], true );

		// Enable shortcodes in widgets
		add_filter( 'widget_text', 'do_shortcode' );

		// Optimize <head>
		new md_optimize_wp;

		if ( class_exists( 'WooCommerce' ) && md_has( 'woocommerce' ) )
			add_theme_support( 'woocommerce' );
	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @since 4.0
	 */

	public function enqueue() {
		// Custom Fonts
		if ( ! md_setting( array( 'settings', 'webfonts', 'loader' ) ) )
			md_enqueue_fonts();

		// Load styles
		if ( ! md_setting( array( 'settings', 'css', 'inline' ) ) )
			wp_enqueue_style( 'marketers-delight', MD_URL . 'style.css', array(), md_ver( 'style.css' ) );

		if ( is_child_theme() && ! md_setting( array( 'settings', 'css', 'child' ) ) )
			wp_enqueue_style( get_option( 'stylesheet' ), get_stylesheet_uri(), array(), md_ver( 'style.css', trailingslashit( get_stylesheet_directory() ) ) );

		// Load scripts
		wp_enqueue_script( 'marketers-delight', MD_URL . 'scripts.js', array(), md_ver( 'scripts.js' ), true );
		wp_localize_script( 'marketers-delight', 'MDJS', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'marketers_delight_nonce', 'marketers_delight_nonce' ),
			'hasAdminBar' => current_user_can( 'administrator' ) ? md_setting( array( 'dropins', 'core', 'admin-bar', 'status', 'enable' ), false ) : false
		) );

		// Comment reply JS
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );

		// Dequeue Blocks Library if necessary
		if ( ! function_exists( 'register_block_type' ) || md_setting( array( 'settings', 'head', 'blocks' ) ) )
			wp_dequeue_style( 'wp-block-library' );

		// Load stupid legacy MailerLite script
		$data = md_setting( array( 'integrations' ) );
		if ( ! empty( $data['enabled']['mailerlite'] ) )
			wp_enqueue_script( 'md-mailerlite', 'https://static.mailerlite.com/js/w/webforms.min.js', array(), '', true );
	}

	/**
	 * Print inline CSS to <head>.
	 *
	 * @since 4.8
	 */

	public function head() {
		if ( md_setting( array( 'settings', 'webfonts', 'loader' ) ) )
			echo md_webfonts_loader();
		if ( md_setting( array( 'settings', 'css', 'inline' ) ) )
			echo '<style type="text/css">'.
				 	get_option( 'marketers_delight_style_css' ).
				 "</style>\n";
	}

	/**
	 * Run actions on WP init.
	 *
	 * @since 5.2.1
	 */

	public function wp_init() {
		if ( isset( $_GET['md'] ) && $_GET['md'] == 'compile_css' && current_user_can( 'administrator' ) )
			md_compile_css();
	}

	/**
	 * Register custom MD widgets and areas.
	 *
	 * @since 4.0
	 */

	public function widgets() {
		// Register custom Widgets
		register_widget( 'md_accordion_widget' );
		register_widget( 'md_content_spotlight' );
		register_widget( 'md_text_image' );
		register_widget( 'md_quote_widget' );

		// Main Sidebar
		register_sidebar( array(
			'name' => __( 'Main Sidebar', 'md' ),
			'description' => __( 'The default sidebar used around your site.', 'md' ),
			'id' => 'sidebar-main',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h3 class="sidebar-title">',
			'after_title' => '</h3>'
		) );

		// Footer Columns
		foreach ( md_filter_footer_columns() as $w ) {
			register_sidebar( array(
				'name' => __( "Footer $w", 'md' ),
				'description' => sprintf( __( 'You can create up to 3 columns of content in your site\'s footer. This is column %s.', 'md' ), $w ),
				'id' => "md-footer-col-$w",
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h3 class="footer-title">',
				'after_title' => '</h3>'
			) );
		}

		// Footer Copyright
		register_sidebar( array(
			'name' => __( 'Footer Copy', 'md' ),
			'description' => __( 'Add text and site links to the bottom of the site footer.', 'md' ),
			'id' => 'footer-copy',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="footer-title">',
			'after_title' => '</h3>'
		) );

		// Custom Sidebars
		$sidebars = md_get_sidebars();
		if ( ! empty( $sidebars ) )
			foreach ( $sidebars as $id => $name ) {
				register_sidebar( array(
					'name' => esc_html( $name ),
					'id' => $id,
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget' => '</section>',
					'before_title' => '<h3 class="sidebar-title">',
					'after_title' => '</h3>'
				) );
			}
	}

	/**
	 * Registers a Twitter user profile field. If Yoast SEO is enabled,
	 * this field will not register and we'll just use the Twitter field
	 * Yoast registers in their plugin. The user will never notice the
	 * transition or need to reinsert their Twitter username to the field.
	 *
	 * @since 4.1
	 */

	public function profile_fields( $fields ) {
		if ( ! defined( 'WPSEO_VERSION' ) )
			$fields['twitter'] = __( 'Twitter username (without @)', 'md' );

		return $fields;
	}

	/**
	 * Add MD meta options to various custom post type.
	 *
	 * @since 4.9.4
	 */

	public function post_types_meta( $post_types ) {
		if ( md_has( 'woocommerce' ) )
			$post_types[] = 'product';
		$post_types[] = 'stream';
		return $post_types;
	}

	/**
	 * Add MD meta options to various custom taxonomies.
	 *
	 * @since 5.0
	 */

	public function taxonomies_meta( $taxonomies ) {
		if ( md_has( 'stream' ) )
			$taxonomies[] = 'stream_categories';
		if ( md_has( 'bookshelf' ) )
			$taxonomies[] = 'bookshelf_categories';
		if ( md_has( 'woocommerce' ) )
			$taxonomies[] = 'product_cat';
		return $taxonomies;
	}

}
$marketers_delight = new marketers_delight;
$marketers_delight->init();