<?php
/**
 * The main MD class performs high-level theme responsibilities like
 * loading theme files, setting constants, and registering WordPress APIs.
 *
 * @since 4.0
 */

final class marketers_delight {

	/**
	 * Setup directory constants for ease of use throughout theme.
	 *
	 * @since 4.9.4
	 */

	public function constants() {
		define( 'MD_VERSION', $this->theme_version() );
		define( 'MD_THEME_NAME', 'Marketers Delight' );
		define( 'MD_THEME_AUTHOR', 'Alex Mangini' );
		define( 'MD_THEME_UPDATER_URL', 'https://marketersdelight.com' );
		define( 'MD_DIR', trailingslashit( get_template_directory() ) );
		define( 'MD_URL', trailingslashit( get_template_directory_uri() ) );
		define( 'MD_PLUGIN_DIR', '' );
		define( 'MD_INSTALLED_DROPINS', WP_CONTENT_DIR . '/md-dropins' ); #5.3
		define( 'MD_INSTALLED_DROPINS_URL', content_url() . '/md-dropins' ); #5.3
		define( 'MD_CSS_DIR', MD_DIR . 'css/' ); #4.9.4
	}

	/**
	 * Get the installed parent theme version directly from its stylesheet.
	 *
	 * @since 6.0
	 */

	private function theme_version() {
		$theme = get_file_data( get_template_directory() . '/style.css', array(
			'version' => 'Version'
		) );

		if ( ! empty( $theme['version'] ) )
			return $theme['version'];

		return wp_get_theme( get_template() )->get( 'Version' );
	}

	/**
	 * Load all required files.
	 *
	 * @since 4.9.4
	 */

	public function includes() {
		require_once MD_DIR . 'functions/option-functions.php';
		require_once MD_DIR . 'functions/meta-functions.php';
		require_once MD_DIR . 'functions/dropin-functions.php';
		require_once MD_DIR . 'functions/asset-functions.php';
		require_once MD_DIR . 'api/compile.php';
		require_once MD_DIR . 'api/enqueue.php';
		require_once MD_DIR . 'api/collections/api.php';
		require_once MD_DIR . 'api/save/sanitize.php';
		require_once MD_DIR . 'api/save/validate.php';
		require_once MD_DIR . 'api/save/save.php';
		require_once MD_DIR . 'api/colors.php';
		require_once MD_DIR . 'api/design.php';
		require_once MD_DIR . 'api/css.php';
		require_once MD_DIR . 'api/theme-json.php';
		require_once MD_DIR . 'api/js.php';
		require_once MD_DIR . 'api/fields/data.php';
		require_once MD_DIR . 'api/fields/render.php';
		require_once MD_DIR . 'api/fields/fields.php';
		require_once MD_DIR . 'api/walker.php';
		require_once MD_DIR . 'api/api.php';

		require_once MD_DIR . 'functions/link-functions.php';
		require_once MD_DIR . 'functions/template-functions.php';
		require_once MD_DIR . 'functions/page-functions.php';

		if ( is_admin() ) {
			require_once MD_DIR . 'api/files.php';
			require_once MD_DIR . 'api/requests.php';
			require_once MD_DIR . 'admin/admin.php';
		}

		$this->features();
		$this->dropins();

		require_once MD_DIR . 'functions/actions.php';
		include_once MD_DIR . 'functions/deprecated-functions.php';
	}

	/**
	 * Load built-in MD features in dependency order.
	 *
	 * @since 6.0
	 */

	public function features() {
		require_once MD_DIR . 'features/layout/functions.php';
		require_once MD_DIR . 'features/integrations/functions.php';
		require_once MD_DIR . 'features/featured-media/functions.php';
		require_once MD_DIR . 'features/page-cover/functions.php';
		require_once MD_DIR . 'features/page-title/functions.php';
		require_once MD_DIR . 'features/page-title/document-title.php';
		require_once MD_DIR . 'features/wordpress.php';
		require_once MD_DIR . 'features/page-cta/functions.php';
		require_once MD_DIR . 'features/byline/functions.php';
		require_once MD_DIR . 'features/header/functions.php';
		require_once MD_DIR . 'features/loop/functions.php';
		require_once MD_DIR . 'features/loop/content.php';
		require_once MD_DIR . 'features/loop/comments.php';

		if ( is_admin() ) {
			require_once MD_DIR . 'features/integrations/admin.php';
			require_once MD_DIR . 'features/page-title/admin.php';
			require_once MD_DIR . 'features/byline/admin.php';
			require_once MD_DIR . 'features/header/admin.php';
			require_once MD_DIR . 'features/header/logo.php';
			require_once MD_DIR . 'features/layout/admin.php';
			require_once MD_DIR . 'features/featured-media/admin.php';
			require_once MD_DIR . 'features/page-cover/admin.php';
			require_once MD_DIR . 'features/page-cta/admin.php';
			require_once MD_DIR . 'features/loop/admin.php';
			require_once MD_DIR . 'features/archive/admin.php';
		}

		require_once MD_DIR . 'features/archive/archive-meta.php';
		require_once MD_DIR . 'features/archive/archive-sections.php';
		require_once MD_DIR . 'features/blog.php';
	}

	/**
	 * Load active MD Drop-ins after theme is setup.
	 *
	 * @since 4.6
	 */

	public function dropins() {
		$dropins = md_get_dropins( 'active' );

		if ( empty( $dropins ) )
			return;

		foreach ( $dropins as $dropin )
			if ( md_has( $dropin ) )
				if ( file_exists( $file = MD_INSTALLED_DROPINS . "/$dropin/$dropin.php" ) ) {
					md_load_dropin_textdomain( $dropin );
					require_once( $file );
				}
				else {
					$dropins = md_dropins_setting();
					unset( $dropins['installed'][$dropin]['status']['enable'] );
					md_update_dropins( $dropins );
				}

		// Refresh cached defaults after all active Drop-ins loaded

		md_setting_defaults( true );
	}

	/**
	 * Start the entire MD library.
	 *
	 * @since 4.9.4
	 */

	public function init() {
		$this->constants();
		$this->includes();

		$enqueue = new md_enqueue;
		$enqueue->init();

		$theme_json = new md_theme_json;
		$theme_json->init();

		$collections = new md_collections_api;
		$collections->init();

		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'body_class', array( $this, 'body_class' ) );
		add_filter( 'user_contactmethods', array( $this, 'profile_fields' ) );
		add_action( 'widgets_init', array( $this, 'widgets' ) );
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
		add_theme_support( 'editor-styles' );
		add_post_type_support( 'page', 'excerpt' );

		// Register Nav Menus
		register_nav_menus( array(
			'header' => __( 'Header Menu', 'md' ),
			'header_loggedin' => __( 'Header Menu (logged-in users only)', 'md' ),
			'main_menu' => __( 'Main Menu', 'md' )
		) );

		// Enable shortcodes in widgets
		add_shortcode( 'md_template', 'md_template_shortcode' );
		add_filter( 'widget_text', 'do_shortcode' );

		// Theme modifications to WordPress
		md_wordpress_setup();
	}

	/**
	 * Load custom classes to the body tag when necessary.
	 *
	 * @since 4.0
	 */

	public function body_class( $classes ) {
		$context = is_singular() || is_404() ? 'post' : 'page';
		$cover = md_cover( $context );
		$loop = md_get_loop();

		$classes[] = 'is-' . $loop['style'] . '-style';

		if ( md_has_panel() ) {
			$show_on_right = md_get_layout_toggle( array( 'panel', 'alt' ) );
			$closed = md_get_layout_toggle( array( 'panel', 'close' ) );

			$classes[] = 'has-panel';
			$classes[] = 'panel-' . ( $show_on_right ? 'right' : 'left' );

			if ( ! $closed )
				$classes[] = 'toggle-panel';
		}

		if ( ! empty( $cover['position'] ) && in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) ) {
			$classes[] = 'header-cover';

			if ( $cover['position'] == 'header_cover_full' ) {
				$classes[] = 'full-cover';

				if ( isset( $cover['display']['alternate'] ) )
					$classes[] = 'cover-alt';
				else
					$classes[] = 'cover-text';
			}
		}

		// Remove excess WP classes
		$classes = array_diff( $classes, array(
			'single-format-standard',
			'single-format-' . get_post_format()
		) );

		return $classes;
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
	 * Register custom MD widgets and areas.
	 *
	 * @since 4.0
	 */

	public function widgets() {
		// Main Sidebar
		register_sidebar( array(
			'name' => __( 'Sidebar: Default', 'md' ),
			'description' => __( 'The default sidebar used around your site.', 'md' ),
			'id' => 'sidebar-main',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		) );

		// Custom Sidebars
		foreach ( md_layout_areas( 'sidebar' ) as $id => $name )
			register_sidebar( array(
				'name' => sprintf( __( 'Sidebar: %s', 'md' ), esc_html( $name ) ),
				'id' => $id,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			) );

		// Main Panel
		register_sidebar( array(
			'name' => __( 'Panel: Default', 'md' ),
			'description' => __( 'The default panel used around your site.', 'md' ),
			'id' => 'panel-main',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		) );

		// Custom Panels
		foreach ( md_layout_areas( 'panel' ) as $id => $name )
			register_sidebar( array(
				'name' => sprintf( __( 'Panel: %s', 'md' ), esc_html( $name ) ),
				'id' => $id,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h3 class="widget-title">',
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
				'before_title' => '<h3 class="widget-title">',
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
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		) );

	}

}

$marketers_delight = new marketers_delight;
$marketers_delight->init();
