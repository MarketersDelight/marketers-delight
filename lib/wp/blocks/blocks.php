<?php
/**
 * Register Block, enqueue assets, build templates.
 *
 * @since 4.9.3
 */

class md_blocks {

	/**
	 * Block names with callback.
	 *
	 * @since 4.9.3
	 */

	public $blocks = array(
		'email' => array(
			'callback' => 'email',
			'localize' => array( 'colors', 'email' )
		),
		'content-upgrade' => array(
			'callback' => 'content_upgrade',
			'localize' => array( 'colors', 'popups', 'icons' )
		),
		'callout' => array(
			'callback' => 'callout',
			'localize' => array( 'colors', 'popups', 'icons' )
		),
		'share-notice' => array(
			'callback' => 'share_notice',
			'localize' => array( 'colors' )
		),
		'arrow' => array(
			'callback' => 'arrow'
		)
	);

	/**
	 * Run block registration, hooks and other core actions for MD Blocks.
	 *
	 * @since 4.9.3
	 */

	public function init() {
		$this->register();
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue' ) );
		add_action( 'md_css_files', array( $this, 'css' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ), 1 );
		add_filter( 'block_categories', array( $this, 'categories' ), 10, 2 );
	}

	/**
	 * Register Blocks Types to WordPress.
	 *
	 * @since 4.9.3
	 */

	public function register() {
		foreach ( $this->blocks as $block => $fields )
			register_block_type( "marketers-delight/{$block}", array(
				'render_callback' => array( $this, $fields['callback'] )
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
			'path' => MD_DIR . 'lib/admin/css/block-editor.css'
		);
		return $files;
	}

	/**
	 * Load MD assets to Blocks Editor.
	 *
	 * @since 4.9
	 */

	public function enqueue() {
		// Load Fonts
		if ( ! md_setting( array( 'settings', 'webfonts', 'loader' ) ) )
			md_enqueue_fonts();

		// Load Blocks JS
		foreach ( $this->blocks as $block => $fields ) {
			$path = "lib/wp/blocks/{$block}.js";
			wp_enqueue_script( "md-block-{$block}", MD_URL . $path, array( 'wp-editor', 'wp-i18n', 'wp-element' ), md_ver( $path ) );
			if ( isset( $fields['localize'] ) )
				wp_localize_script( "md-block-{$block}", 'MDBlocks', $this->localized_scripts( $fields['localize'] ) );
		}

		// Load Blocks CSS
		if ( ! md_setting( array( 'settings', 'css', 'inline' ) ) ) {
			$css = 'lib/admin/css/block-editor.css';
			wp_enqueue_style( 'md-blocks', MD_URL . $css, array( 'wp-edit-blocks' ), md_ver( $css ) );
		}
		else
			wp_add_inline_style( 'wp-edit-post', get_option( 'marketers_delight_blocks_css' ) );
	}

	/**
	 * Pass dynamic data into scripts.
	 *
	 * @since 4.9
	 */

	public function localized_scripts( $data ) {
		$scripts = array();
		$popups = md_setting( array( 'popups', 'popups' ) );
		$email = md_email_data( array( 'show' => 'names', 'label' => true, 'empty_label' => true ) );

		if ( in_array( 'colors' , $data ) )
			foreach ( md_editor_colors() as $group => $fields ) {
				$scripts['colors']['slug'][$fields['slug']] = esc_attr( $fields['color'] );
				$scripts['colors']['hex'][$fields['color']] = esc_attr( $fields['slug'] );
			}

		if ( in_array( 'email', $data ) && ! empty( $email ) )
			foreach ( $email as $list => $name )
				$scripts['email'][] = array( 'label' => $name, 'value' => $list );

		if ( in_array( 'popups', $data ) && ! empty( $popups ) )
			foreach ( $popups as $popup => $fields )
				$scripts['popups'][] = array( 'label' => $fields['name'], 'value' => $popup );

		if ( in_array( 'icons', $data ) )
			foreach ( md_icons() as $icon => $fields ) {
				$label = ! empty( $fields['label'] ) ? $fields['label'] : $icon;
				$scripts['icons'][] = array( 'label' => $label, 'value' => "md-icon-$icon" );
			}
		return $scripts;
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
	 * Frontend Email template.
	 *
	 * @since 4.9
	 */

	public function email( $attributes, $content ) {
		ob_start();
		include( md_template( 'blocks/email', true ) );
		return ob_get_clean();
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

	/**
	 * Frontend Share Notice template.
	 *
	 * @since 4.9.3
	 */

	public function share_notice( $attributes, $content ) {
		ob_start();
		include( md_template( 'blocks/share-notice', true ) );
		return ob_get_clean();
	}

}
$md_blocks = new md_blocks;
$md_blocks->init();