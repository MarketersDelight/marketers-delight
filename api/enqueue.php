<?php
/**
 * Holds all CSS and JS enqueue including inline styles, fonts,
 * editor styles, and MD asset compile helpers.
 *
 * @since 6.0
 */

class md_enqueue {

	private $compiled;

	/**
	 * Set the compiled assets API.
	 *
	 * @since 6.0
	 */

	public function __construct() {
		$this->compiled = new md_compiled_assets;
	}

	/**
	 * Register asset actions and filters to expected WP hooks.
	 *
	 * @since 6.0
	 */

	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_fonts' ) );
		add_action( 'wp_head', array( $this, 'head' ) );
		add_filter( 'style_loader_tag', array( $this, 'defer_style' ), 10, 2 );
		add_filter( 'wp_preload_resources', array( $this, 'preload' ) );

		add_action( 'enqueue_block_assets', array( $this, 'enqueue_fonts' ) );
		add_filter( 'block_editor_settings_all', array( $this, 'block_editor_styles' ) );
		add_filter( 'mce_css', array( $this, 'classic_editor_styles' ) );

		add_action( 'init', array( $this, 'compile' ) );
		add_action( 'after_switch_theme', 'md_compile' );
		add_action( 'upgrader_process_complete', array( $this, 'compile_on_upgrade' ), 10, 2 );

		if ( ! function_exists( 'register_block_type' ) || md_setting( array( 'settings', 'head', 'blocks' ) ) ) {
			remove_filter( 'render_block', 'wp_render_layout_support_flag', 10, 2 );
			add_filter( 'should_load_separate_core_block_assets', '__return_false' );
		}
	}

	/**
	 * Enqueue frontend styles, scripts, and localized script data.
	 *
	 * @since 4.0
	 */

	public function enqueue() {

		// Enqueue main stylesheet (style.css)

		if ( ! md_setting( array( 'settings', 'css', 'inline' ) ) )
			wp_enqueue_style( 'marketers-delight', $this->compiled->url( 'style.css' ), array(), $this->compiled->version( 'style.css' ) );

		// Load child theme style, if not merged into main style.css

		if ( is_child_theme() && ! md_setting( array( 'settings', 'css', 'child' ) ) )
			wp_enqueue_style( get_option( 'stylesheet' ), get_stylesheet_uri(), array(), md_ver( 'style.css', trailingslashit( get_stylesheet_directory() ) ) );

		// Register and load JS files

		wp_register_script( 'marketers-delight', $this->compiled->url( 'scripts.js' ), array(), $this->compiled->version( 'scripts.js' ), array(
			'in_footer' => true
		) );
		wp_enqueue_script( 'marketers-delight' );
		wp_localize_script( 'marketers-delight', 'MDJS', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'marketers_delight_nonce', 'marketers_delight_nonce' ),
			'hasAdminBar' => current_user_can( 'manage_options' ) ? md_has( 'admin-bar' ) : false,
			'userID' => get_current_user_id()
		) );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );

		// Print inline JS

		$this->inline_js();

		// Block scripts and styles

		if ( ! function_exists( 'register_block_type' ) || md_setting( array( 'settings', 'head', 'blocks' ) ) ) {
			wp_dequeue_style( 'wp-block-library' );
			wp_dequeue_style( 'global-styles' );
			wp_dequeue_style( 'block-style-variation-styles' );
		}

		// Legacy integration, move to Optins

		$data = md_setting( 'integrations' );

		if ( ! empty( $data['enabled']['mailerlite'] ) )
			wp_enqueue_script( 'md-mailerlite', 'https://static.mailerlite.com/js/w/webforms.min.js', array(), '', true );
	}

	/**
	 * Print configured critical or fully inline theme CSS.
	 *
	 * @since 4.8
	 */

	public function head() {

		// Print critical css to <head>

		if ( md_setting( array( 'settings', 'css', 'critical' ) ) ) {
			$css = $this->compiled->read( 'critical.css' );

			if ( ! empty( $css ) )
				echo '<style id="md-critical-css">' . $css . "</style>\n";
		}

		// Print style.css as inline, if enabled

		if ( md_setting( array( 'settings', 'css', 'inline' ) ) )
			echo '<style type="text/css">' . get_option( 'marketers_delight_style_css' ) . "</style>\n";
	}

	/**
	 * Enqueue configured Google Fonts and Adobe Fonts stylesheets.
	 *
	 * @since 4.8
	 */

	public function enqueue_fonts() {
		$typekit = md_setting( array( 'integrations', 'api_keys', 'typekit' ) );

		if ( md_web_fonts( 'google' ) )
			wp_enqueue_style( 'marketers-delight-google-fonts', md_google_fonts() );

		if ( ! empty( $typekit['key'] ) && md_web_fonts( 'typekit' ) )
			wp_enqueue_style( 'marketers-delight-typekit', 'https://use.typekit.net/' . esc_attr( $typekit['key'] ) . '.css' );
	}

	/**
	 * Load scripts from the MD library.
	 *
	 * @since 4.0
	 */

	public function inline_js() {
		wp_add_inline_script( 'marketers-delight', "MD.triggers();" );
		wp_add_inline_script( 'marketers-delight', "MD.toggle();" );

		if ( has_action( 'md_hook_js_onscroll' ) )
			wp_add_inline_script( 'marketers-delight', "MD.onScroll();" );

		if ( md_has_panel() )
			wp_add_inline_script( 'marketers-delight', "MD.closeOverlay( 'panel', '.has-panel' );" );
	}

	/**
	 * Add the compiled theme stylesheet to Block Editor canvases.
	 *
	 * @since 6.0
	 */

	public function block_editor_styles( $settings ) {
		$file = 'block-editor.css';

		if ( ! $this->compiled->exists( $file ) )
			return $settings;

		if ( empty( $settings['styles'] ) )
			$settings['styles'] = array();

		$url = add_query_arg( 'ver', $this->compiled->version( $file ), set_url_scheme( $this->compiled->url( $file ) ) );
		$settings['styles'][] = array(
			'css' => '@import url("' . esc_url_raw( $url ) . '");',
			'__unstableType' => 'theme',
			'isGlobalStyles' => false
		);

		return $settings;
	}

	/**
	 * Add compiled content styles to the Classic Editor.
	 *
	 * @since 6.0
	 */

	public function classic_editor_styles( $stylesheets ) {
		$editor_styles = array();

		foreach ( array( 'font-icons.css', 'classic-editor.css' ) as $file )
			if ( $this->compiled->exists( $file ) )
				$editor_styles[] = add_query_arg( 'ver', $this->compiled->version( $file ), $this->compiled->url( $file ) );

		return trim( $stylesheets . ',' . implode( ',', $editor_styles ), ' ,' );
	}

	/**
	 * Defer selected stylesheets when Critical CSS is enabled.
	 *
	 * @since 6.0
	 */

	public function defer_style( $tag, $handle ) {
		$deferred = apply_filters( 'md_deferred_styles', array( 'marketers-delight' ) );

		if (
			is_admin() ||
			! md_setting( array( 'settings', 'css', 'critical' ) ) ||
			! $this->compiled->exists( 'critical.css' ) ||
			! in_array( $handle, $deferred )
		)
			return $tag;

		if ( ! preg_match( '/href=([\'\"])(.*?)\1/', $tag, $href ) )
			return $tag;

		return '<link rel="preload" as="style" href="' . esc_url( $href[2] ) . '" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n"
			. '<noscript>' . $tag . '</noscript>';
	}

	/**
	 * Preload the compiled icon font used by the theme.
	 *
	 * @since 6.0
	 */

	public function preload( $urls ) {
		$urls[] = array(
			'href' => md_font_icons_url(),
			'as' => 'font',
			'type' => 'font/woff2',
			'crossorigin' => ''
		);

		return $urls;
	}

	/**
	 * Run an authorized manual asset compilation request.
	 *
	 * @since 6.0
	 */

	public function compile() {
		if ( ! isset( $_GET['md'] ) || ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( $_GET['_wpnonce'] ?? '', 'md_compile' ) )
			return;

		$compile = sanitize_key( wp_unslash( $_GET['md'] ) );

		if ( $compile === 'compile' )
			md_compile();
		elseif ( $compile === 'compile_css' )
			md_compile_css();
		elseif ( $compile === 'compile_js' )
			md_compile_js();
	}

	/**
	 * Recompile assets after the active theme is installed or upgraded.
	 *
	 * @since 6.0
	 */

	public function compile_on_upgrade( $upgrader, $options ) {
		if ( ( $options['type'] ?? '' ) !== 'theme' )
			return;

		$themes = (array) ( $options['themes'] ?? array() );
		$themes[] = $upgrader->result['destination_name'] ?? '';

		if ( in_array( get_template(), $themes, true ) )
			md_compile();
	}
}
