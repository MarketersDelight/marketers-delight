<?php
/**
 * Register Block, enqueue assets, build templates.
 *
 * @since 4.9.3
 */

class md_block_editor {

	/**
	 * Run block registration, hooks and other core actions for MD Blocks.
	 *
	 * @since 4.9.3
	 */

	public function init() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue' ) );
		add_filter( 'md_css_files', array( $this, 'css' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ), 1 );
	}

	/**
	 * Create block-editor.css file for enqueue in Blocks Editor.
	 *
	 * @since 4.9.4
	 */

	public function css( $files ) {
		$templates = array();

		if ( locate_template( 'css/fonts.php' ) )
			$templates['fonts'] = locate_template( 'css/fonts.php' );

		$templates['blocks'] = MD_CSS_DIR . 'block-editor.php';

		$files['block-editor'] = array(
			'templates' => $templates,
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
		if ( ! md_setting( array( 'settings', 'css', 'inline' ) ) )
			wp_enqueue_style( 'md-blocks', MD_URL . 'block-editor.css', array( 'wp-edit-blocks' ), md_ver( 'block-editor.css' ) );
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

}

$md_block_editor = new md_block_editor;
$md_block_editor->init();