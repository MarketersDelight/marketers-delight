<?php
/**
 * Advanced block styling: MD's CSS replaces WordPress's block CSS, global
 * styles and layout engine, and keeps core only where a page needs it.
 *
 * Core layout output stays for row, stack and grid layouts, custom widths
 * and gaps; core block CSS stays for blocks MD doesn't style.
 *
 * @since 6.2.3
 */

class md_blocks {

	/**
	 * Hook in. The CSS and theme.json filters check the setting themselves,
	 * so saving it and compiling in one request use the new value.
	 *
	 * @since 6.2.3
	 */

	public function init() {
		add_filter( 'md_style_css_templates', array( $this, 'css_templates' ) );
		add_filter( 'md_theme_json', array( $this, 'theme_json' ) );

		if ( function_exists( 'render_block' ) && $this->enabled() ) {
			remove_filter( 'render_block', 'wp_render_layout_support_flag', 10, 2 );
			add_filter( 'render_block', array( $this, 'layout_support' ), 10, 2 );
			add_action( 'wp_print_styles', array( $this, 'core_block_styles' ), 1 );
			add_action( 'wp_print_footer_scripts', array( $this, 'core_block_styles' ), 1 );
		}
	}

	/**
	 * Whether Advanced block styling is on.
	 *
	 * @since 6.2.3
	 */

	public function enabled() {
		return (bool) md_setting( array( 'settings', 'head', 'blocks' ) );
	}

	/**
	 * Add MD's block CSS to the compiled stylesheet.
	 *
	 * @since 6.2.3
	 */

	public function css_templates( $templates ) {
		if ( $this->enabled() )
			$templates['blocks'] = locate_template( 'css/blocks.php' );

		return $templates;
	}

	/**
	 * Hide editor controls that need core's global styles: duotone filters
	 * and the image lightbox.
	 *
	 * @since 6.2.3
	 */

	public function theme_json( $json ) {
		if ( $this->enabled() ) {
			$json['settings']['color']['customDuotone'] = false;
			$json['settings']['color']['duotone'] = array();
			$json['settings']['blocks']['core/image']['lightbox']['allowEditing'] = false;
		}

		return $json;
	}

	/**
	 * Keep core's layout classes and CSS only where a block uses them: rows,
	 * stacks and grids users can edit, custom widths and gaps, child sizing.
	 *
	 * @since 6.2.3
	 */

	public function layout_support( $block_content, $block ) {
		$attrs = $block['attrs'] ?? array();
		$layout = $attrs['layout'] ?? array();
		$type = $layout['type'] ?? '';
		$result = $block_content;

		if ( ! $type && ! empty( $block['blockName'] ) ) {
			$block_type = WP_Block_Type_Registry::get_instance()->get_registered( $block['blockName'] );

			if ( $block_type && ( $block_type->supports['layout']['allowEditing'] ?? true ) )
				$type = $block_type->supports['layout']['default']['type'] ?? '';
		}

		$custom = array_intersect_key( $layout, array_flip( array( 'contentSize', 'wideSize', 'justifyContent' ) ) );

		if ( in_array( $type, array( 'flex', 'grid' ), true ) || $custom || ! empty( $attrs['style']['spacing']['blockGap'] ) || ! empty( $attrs['style']['layout'] ) )
			$result = wp_render_layout_support_flag( $block_content, $block );

		return $result;
	}

	/**
	 * Drop core's global styles and block CSS, except for blocks MD doesn't
	 * style, which WordPress loads only on pages that use them.
	 *
	 * @since 6.2.3
	 */

	public function core_block_styles() {
		$keep = apply_filters( 'md_core_block_styles', array( 'audio', 'file', 'gallery', 'latest-posts', 'media-text', 'social-links', 'styles-placeholder', 'video' ) );
		$drop = array( 'global-styles', 'block-style-variation-styles' );

		foreach ( wp_styles()->queue as $handle )
			if ( in_array( $handle, $drop, true ) || ( strpos( $handle, 'wp-block-' ) === 0 && ! in_array( substr( $handle, 9 ), $keep, true ) ) )
				wp_dequeue_style( $handle );
	}

}
