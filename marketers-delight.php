<?php
/**
 * The main Marketers Delight Class that activates all WP, MD
 * and other features throughout this WordPress website.
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
		define( 'MD_VERSION', '6.0' );
		define( 'MD_THEME_NAME', 'Marketers Delight 4' );
		define( 'MD_THEME_AUTHOR', 'Alex Mangini' );
		define( 'MD_THEME_UPDATER_URL', 'https://marketersdelight.com' );
		define( 'MD_DIR', trailingslashit( get_template_directory() ) );
		define( 'MD_URL', trailingslashit( get_template_directory_uri() ) );
		define( 'MD_PLUGIN_DIR', '' );
		define( 'MD_DROPINS_DIR', MD_DIR . 'dropins/' ); #4.7
		define( 'MD_INSTALLED_DROPINS', WP_CONTENT_DIR . '/md-dropins' ); #5.3
		define( 'MD_INSTALLED_DROPINS_URL', content_url() . '/md-dropins' ); #5.3
		define( 'MD_CSS_DIR', MD_DIR . 'css/' ); #4.9.4
	}

	/**
	 * Start the entire MD library.
	 *
	 * @since 4.9.4
	 */

	public function init() {
		$this->constants();
		$this->includes();

		add_action( 'init', array( $this, 'wp_init' ) );
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'after_switch_theme', 'md_compile' );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_fonts' ), 1 );
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_fonts' ) );
		add_action( 'wp_head', array( $this, 'head' ) );
		add_action( 'wp_head', array( $this, 'head_priority' ), 5 );
		add_action( 'body_class', array( $this, 'body_class' ) );
		add_filter( 'user_contactmethods', array( $this, 'profile_fields' ) );
		add_action( 'widgets_init', array( $this, 'widgets' ) );
		add_filter( 'query_vars', array( $this, 'query_vars' ) );
	}

	/**
	 * Load all required files.
	 *
	 * @since 4.9.4
	 */

	public function includes() {
		require_once MD_DIR . 'functions/theme-functions.php';
		require_once MD_DIR . 'api/sanitize.php';
		require_once MD_DIR . 'api/design.php';
		require_once MD_DIR . 'api/css.php';
		require_once MD_DIR . 'api/js.php';
		require_once MD_DIR . 'api/data.php';
		require_once MD_DIR . 'api/fields.php';
		require_once MD_DIR . 'api/walker.php';
		require_once MD_DIR . 'api/api.php';

		require_once MD_DIR . 'functions/template-functions.php';
		require_once MD_DIR . 'functions/image-functions.php';
		require_once MD_DIR . 'functions/page-functions.php';
		require_once MD_DIR . 'functions/comment-functions.php';
		require_once MD_DIR . 'functions/loop-functions.php';
		require_once MD_DIR . 'functions/header-functions.php';
		require_once MD_DIR . 'functions/layout-functions.php';

		if ( is_admin() ) {
			require_once MD_DIR . 'api/files.php';
			require_once MD_DIR . 'api/requests.php';
			require_once MD_DIR . 'admin/admin.php';
		}

		require_once MD_DIR . 'admin/page-image.php';
		require_once MD_DIR . 'admin/page-cover.php';
		require_once MD_DIR . 'admin/page-cta.php';

		$this->dropins();

		require_once MD_DIR . 'blog.php';
		require_once MD_DIR . 'actions.php';
		include_once MD_DIR . 'functions/deprecated-functions.php';
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
		add_post_type_support( 'page', 'excerpt' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-color-palette', md_editor_colors() );

		// Register Nav Menus
		register_nav_menus( array(
			'header' => __( 'Header Menu', 'md' ),
			'header_loggedin' => __( 'Header Menu (logged-in users only)', 'md' ),
			'main_menu' => __( 'Main Menu', 'md' )
		) );

		// Enable shortcodes in widgets
		add_shortcode( 'md_template', array( $this, 'template_shortcode' ) );
		add_filter( 'widget_text', 'do_shortcode' );

		// Remove WP junk, mostly from <head>
		if ( ! md_setting( array( 'settings', 'head', 'optimize' ) ) ) {
			add_filter( 'post_class', array( $this, 'post_class' ) );
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_action( 'wp_head', 'wp_generator' );
			remove_action( 'wp_head', 'wlwmanifest_link' );
			remove_action( 'wp_head', 'rsd_link' );
			remove_action( 'wp_head', 'wp_shortlink_wp_head' );
			remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
			add_filter( 'emoji_svg_url', '__return_false' );
			add_filter( 'the_generator', '__return_false' );
		}

		// Disable REST API
		if ( md_setting( array( 'settings', 'head', 'wpjson' ) ) ) {
			remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
			remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
			remove_action( 'rest_api_init', 'wp_oembed_register_route' );
		}

		// Disable oEmbed
		if ( md_setting( array( 'settings', 'head', 'oembed' ) ) ) {
			add_filter( 'embed_oembed_discover', '__return_false' );
			remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
			remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
			remove_action( 'wp_head', 'wp_oembed_add_host_js' );
			add_filter( 'rewrite_rules_array', array( $this, 'disable_embed_rewrites' ) );
		}

		// Disable Widgets Block Editor. Make default?
		if ( md_setting( array( 'settings', 'head', 'widgets' ) ) ) {
			add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
			add_filter( 'use_widgets_block_editor', '__return_false' );
		}

		// Re-add RSS link
		add_action( 'wp_head', array( $this, 'add_rss_link' ) );
	}

	/**
	 * Run limited actions on WP init.
	 *
	 * @since 5.2.1
	 */

	public function wp_init() {
		if ( is_admin() )
			$this->activate_dropin();

		if ( isset( $_GET['md'] ) && current_user_can( 'administrator' ) )
			if ( $_GET['md'] == 'compile' )
				md_compile();
			elseif ( $_GET['md'] == 'compile_css' )
				md_compile_css();
			elseif ( $_GET['md'] == 'compile_js' )
				md_compile_js();
	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @since 4.0
	 */

	public function enqueue() {
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
			'hasAdminBar' => current_user_can( 'administrator' ) ? md_setting( array( 'dropins', 'installed', 'admin-bar', 'status', 'enable' ), false ) : false,
			'userID' => get_current_user_id()
		) );

		// Comment reply JS
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );

		// Print inline JS
		$this->inline_js();

		// Dequeue Blocks Library if necessary
		if ( ! function_exists( 'register_block_type' ) || md_setting( array( 'settings', 'head', 'blocks' ) ) ) {
//		    global $wp_styles;
//		    $wp_styles->remove('global-styles');
			wp_dequeue_style( 'wp-block-library' );
			wp_dequeue_style( 'classic-theme-styles' );
		}

		// Load stupid legacy MailerLite script
		$data = md_setting( 'integrations' );

		if ( ! empty( $data['enabled']['mailerlite'] ) )
			wp_enqueue_script( 'md-mailerlite', 'https://static.mailerlite.com/js/w/webforms.min.js', array(), '', true );
	}

	/**
 	 * Enqueue MD's integrated web font services to <head> when needed.
 	 *
 	 * @since 4.8
 	 */

	public function enqueue_fonts() {
		if ( md_setting( array( 'settings', 'webfonts', 'loader' ) ) )
			return;

		$typekit = md_setting( array( 'integrations', 'api_keys', 'typekit' ) );

		if ( md_web_fonts( 'google' ) ) {
			$url = md_setting( array( 'typography', 'google_fonts' ) );
			wp_enqueue_style( 'marketers-delight-google-fonts', $url );
		}

		if ( ! empty( $typekit['key'] ) && md_web_fonts( 'typekit' ) )
			wp_enqueue_style( 'marketers-delight-typekit', 'https://use.typekit.net/' . esc_attr( $typekit['key'] ) . '.css' );
	}

	/**
 	 * Output inline JavaScript to footer (formerly md_inline_js())
 	 *
 	 * @since 4.0
 	 */

	public function inline_js() {
		wp_add_inline_script( 'marketers-delight', "MD.triggers();" );
		wp_add_inline_script( 'marketers-delight', "MD.toggle();" );

		if ( md_setting( array( 'header', 'display', 'sticky' ) ) )
			wp_add_inline_script( 'marketers-delight', 'MD.sticky(\'.header\');' );
	}

	/**
	 * Add custom query vars to known WP.
	 *
	 * @since 6.0
	 */

	public function query_vars( $vars ) {
		$vars[] = 'filter';

		return $vars;
	}

	/**
	 * Load inline CSS if enabled from user settings.
	 *
	 * @since 4.8
	 */

	public function head() {
		if ( md_setting( array( 'settings', 'css', 'inline' ) ) )
			echo '<style type="text/css">' . get_option( 'marketers_delight_style_css' ) . "</style>\n";
	}

	/**
	 * Load high priority assets and meta to top of WP <head>.
	 *
	 * @since 5.3.2
	 */

	public function head_priority() {
		if ( md_setting( array( 'settings', 'webfonts', 'loader' ) ) )
			echo md_webfonts_loader();

		echo '<link href="' . md_font_icons_url() . '">' . "\n";
	}

	/**
	 * Load custom classes to the body tag when necessary.
	 *
	 * @since 4.0
	 */

	public function body_class( $classes ) {
		$context = is_singular() || is_404() ? 'post' : 'page';
		$cover = md_cover( $context );
		$style = md_content_style( array( 'global' => true ) );

		if ( $style )
			$classes[] = 'is-' . $style;

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

		return $classes;
	}

	/**
	 * Register custom MD widgets and areas.
	 *
	 * @since 4.0
	 */

	public function widgets() {
		// Main Sidebar
		register_sidebar( array(
			'name' => __( 'Main Sidebar', 'md' ),
			'description' => __( 'The default sidebar used around your site.', 'md' ),
			'id' => 'sidebar-main',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		) );

		// Custom Sidebars
		foreach ( md_get_sidebars() as $id => $name ) {
			register_sidebar( array(
				'name' => esc_html( $name ),
				'id' => $id,
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget' => '</section>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			) );
		}

		// Footer Columns
		foreach ( md_filter_footer_columns() as $w ) {
			register_sidebar( array(
				'name' => __( "Footer $w", 'md' ),
				'description' => sprintf( __( 'You can create up to 3 columns of content in your site\'s footer. This is column %s.', 'md' ), $w ),
				'id' => "md-footer-col-$w",
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget' => '</section>',
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

	/**
	 * Create the [md_template] shortcode, which is a quick way
	 * to render any template from the Parent/Child Theme folder
	 * and Drop-ins Library.
	 *
	 * @since 6.0
	 */

	public function template_shortcode( $atts, $content = null ) {
		ob_start(); extract( shortcode_atts( array(
			'name' => '',
			'dropin_name' => ''
		), $atts, 'md_template' ) );

		if ( isset( $atts['dropin_name'] ) )
			include md_template( 'dropins', $atts['dropin_name'], true );
		elseif ( isset( $atts['name'] ) )
			include md_template( $atts['name'], true );

		return ob_get_clean();
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
	 * Load MD Drop-ins after theme is setup. Supports old Drop-ins
	 * locations pre-MD5.3.
	 *
	 * @since 4.6
	 */

	public function dropins() {
		$dropins = md_get_dropins( 'active' );
		$old_dropins = md_setting( array( 'dropins', 'features' ), array() ); #EOL

		if ( ! empty( $dropins ) ) {
			foreach ( $dropins as $dropin )
				if ( md_has( $dropin ) )
					if ( file_exists( $file = MD_INSTALLED_DROPINS . "/$dropin/$dropin.php" ) )
						require_once( $file );
					else {
						$option = md_setting();
						unset( $option['dropins']['installed'][$dropin]['status']['enable'] );
						update_option( 'marketers_delight', $option );
					}
		}
		else {
			$old_dropins['optins'] = true;
			$old_dropins['share'] = true;
			$old_dropins['scripts'] = true;
			$old_dropins['footnotes'] = true;

			if ( isset( $old_dropins['admin_bar'] ) ) {
				unset( $old_dropins['admin_bar'] );
				$old_dropins['admin-bar'] = true;
			}

			foreach ( $old_dropins as $old_dropin => $old_dropin_val )
				if ( file_exists( $old_dropin_file = MD_DROPINS_DIR . "/$old_dropin/$old_dropin.php" ) )
					require_once $old_dropin_file;
		}
	}

	/**
	 * Run Drop-in updater actions on admin page.
	 *
	 * @since 5.4
	 */

	public function activate_dropin() {
		$page = ! empty( $_GET['page'] ) ? esc_attr( $_GET['page'] ) : false;
		$action = ! empty( $_GET['action'] ) ? esc_attr( $_GET['action'] ) : false;
		$dropin = ! empty( $_GET['dropin'] ) ? esc_attr( $_GET['dropin'] ) : false;
		$fields = md_setting( array( 'dropins', 'installed', $dropin ), false );

		if ( ! $page || $page !== 'md_dropins' || $action !== 'activate' || ! $dropin || ! $fields || ! empty( $fields['status']['enable'] ) )
			return;

		if ( ! current_user_can( 'activate_plugins' ) )
			wp_die( __( 'Sorry, you are not allowed to activate this drop-in.' ) );

		check_admin_referer( "activate-dropin_$dropin/$dropin.php" );

		md_activate_dropin( $dropin );

		wp_redirect( self_admin_url( "admin.php?page=md_dropins&dropin=$dropin&dropin_status=activated" ) );

		exit;
	}

	/**
	 * Remove oEmbed rewrite rules if enabled.
	 *
	 * @since 4.8
	 */

	public function disable_embed_rewrites( $rules ) {
		foreach ( $rules as $rule => $rewrite )
			if ( false !== strpos( $rewrite, 'embed=true' ) )
				unset( $rules[ $rule ] );

		return $rules;
	}

	/**
	 * Manually add a formatted version of the site's main RSS
	 * feed to the <head>.
	 *
	 * @since 4.8
	 */

	public function add_rss_link() {
		echo '<link rel="alternate" type="application/rss+xml" title="' . get_bloginfo( 'sitename' ) . ' Feed" href="' . get_bloginfo( 'rss2_url' ) . '">';
	}

	/**
 	 * Clean out unneeded CSS post classes.
 	 *
 	 * @since 4.1
 	 */

	public function post_class( $classes ) {
		// Remove excess WP classes
		$classes = array_diff( $classes, array(
			'format-standard',
			'hentry',
			'post-' . get_the_ID(),
			'type-' . get_post_type(),
			'status-' . get_post_status(),
			'format-' . get_post_format()
		) );

		return $classes;
	}

}

$marketers_delight = new marketers_delight;
$marketers_delight->init();