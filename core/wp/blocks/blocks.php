<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register Block, enqueue assets, build templates.
 *
 * @since 4.9.3
 */

class md_blocks {

	/**
	 * Block names with callbacks.
	 *
	 * @since 4.9.3
	 */

	public function blocks() {
		$blocks = array(
			'arrow' => array(
				'callback' => array( $this, 'arrow' )
			),
			'content-upgrade' => array(
				'callback' => array( $this, 'content_upgrade' ),
				'localize' => array( 'block_colors', 'popups', 'icons' )
			),
			'callout' => array(
				'callback' => array( $this, 'callout' ),
				'localize' => array( 'block_colors', 'popups', 'icons' )
			)
		);
		return apply_filters( 'md_filter_blocks', $blocks );
	}

	/**
	 * Run block registration, hooks and other core actions for MD Blocks.
	 *
	 * @since 4.9.3
	 */

	public function init() {
		$this->register();
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue' ) );
		add_filter( 'md_css_files', array( $this, 'css' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ), 1 );
		add_filter( 'block_categories_all', array( $this, 'categories' ), 10, 2 );
	}

	/**
	 * Register Blocks Types to WordPress.
	 *
	 * @since 4.9.3
	 */

	public function register() {
		foreach ( $this->blocks() as $block => $fields )
			register_block_type( "marketers-delight/{$block}", array(
				'render_callback' => $fields['callback']
			) );
	}

	/**
	 * Create MD Blocks category.
	 *
	 * @since 4.9
	 */

	public function categories( $categories ) {
		return array_merge( $categories, array( array(
			'slug' => 'marketers-delight',
			'title' => __( 'Marketers Delight', 'md' )
		) ) );
	}

	/**
	 * Create blocks.css file for enqueue in Blocks Editor.
	 *
	 * @since 4.9.4
	 */

	public function css( $files ) {
		$files['block-editor'] = array(
			'templates' => array(
				'blocks' => MD_CSS_DIR . 'block-editor.php'
			),
			'path' => MD_DIR . 'block-editor.css'
		);
		return $files;
	}

	/**
	 * Load MD assets to Blocks Editor.
	 *
	 * @since 4.9
	 */

	public function enqueue() {

		md_compile();


		// Load Fonts
		if ( ! md_setting( array( 'settings', 'webfonts', 'loader' ) ) )
			md_enqueue_fonts();

		// Load Blocks JS
		foreach ( $this->blocks() as $block => $fields ) {
			$dir_url = isset( $fields['dropins'] ) ? trailingslashit( MD_INSTALLED_DROPINS_URL ) : MD_URL;
			$dir = isset( $fields['dropins'] ) ? trailingslashit( MD_INSTALLED_DROPINS ) : null;
			$path = isset( $fields['path'] ) ? $fields['path'] : "core/wp/blocks/{$block}.js";
			wp_enqueue_script( "md-block-{$block}", "{$dir_url}$path", array( 'marketers-delight', 'wp-editor', 'wp-i18n', 'wp-element' ), md_ver( $path, $dir ) );
			if ( isset( $fields['localize'] ) )
				wp_localize_script( "md-block-{$block}", 'MDBlocks', md_localize_scripts( $fields['localize'] ) );
		}

		// Load Blocks CSS
		if ( ! md_setting( array( 'settings', 'css', 'inline' ) ) ) {
			$css = 'block-editor.css';
			wp_enqueue_style( 'md-blocks', MD_URL . $css, array( 'wp-edit-blocks' ), md_ver( $css ) );
		}
		else
			wp_add_inline_style( 'wp-edit-post', get_option( 'marketers_delight_block-editor_css' ) );
	}

	/**
	 * If enabled, load Webfonts to Blocks.
	 *
	 * @since 4.9
	 */

	public function admin_head() {
		$screen = get_current_screen();
		if ( $screen->base == 'post' && md_setting( array( 'settings', 'webfonts', 'loader' ) ) )
			echo md_webfonts_loader();
	}

	/**
	 * Frontend Content Upgrade template.
	 *
	 * @since 4.9.3
	 */

	public function content_upgrade( $attributes, $content ) {
		ob_start();
		include( md_template( 'blocks/content-upgrade', true ) );
		return ob_get_clean();
	}

	/**
	 * Frontend Callout template.
	 *
	 * @since 4.9.3
	 */

	public function callout( $attributes, $content ) {
		ob_start();
		include( md_template( 'blocks/callout', true ) );
		return ob_get_clean();
	}

}

$md_blocks = new md_blocks;
$md_blocks->init();