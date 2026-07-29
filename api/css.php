<?php
/**
 * This class generates MD CSS files and other actions.
 *
 * @since 4.9.4
 */

class md_css {

	public $files;
	public $site_width;

	/**
	 * Set properties.
	 *
	 * @since 4.9.4
	 */

	public function __construct() {
		$this->files = $this->files();
	}

	/**
	 * A list of CSS files to generate from templates.
	 *
	 * @since 4.9
	 */

	public function files() {
		$templates = $this->css_templates();

		// All dynamically rendered CSS files

		$files = array(
			'style' => array(
				'path' => MD_DIR . 'style.css',
				'templates' => $this->style_css( $templates ),
				'style_guide' => $this->style_guide( $templates )
			),
			'classic-editor' => array(
				'path' => MD_DIR . 'compile/classic-editor.css',
				'minify' => true,
				'templates' => $this->classic_editor_css(),
				'replace' => array(
					'.format' => '.mce-content-body'
				)
			),
			'block-editor' => array(
				'path' => MD_DIR . 'compile/block-editor.css',
				'minify' => true,
				'templates' => $this->block_editor_css( $templates ),
				'replace' => array(
					'.format' => '.editor-styles-wrapper'
				)
			),
			'font-icons' => array(
				'path' => MD_DIR . 'compile/font-icons.css',
				'templates' => array(
					'font-icons' => locate_template( 'css/font-icons.php' )
				)
			)
		);

		// Generate a critical CSS file which we inline later

		if ( $this->critical_enabled() ) {
			$keys = $this->critical_keys();

			$files['critical'] = array(
				'path' => MD_DIR . 'compile/critical.css',
				'minify' => true,
				'templates' => array_intersect_key( $templates, array_flip( $keys ) )
			);
		}

		return array_merge( $files, apply_filters( 'md_css_files', array() ) );
	}

	/**
	 * Build the complete ordered list of authored CSS templates.
	 *
	 * @since 6.0
	 */

	protected function css_templates() {
		$templates = array(
			'style' => locate_template( 'css/style.php' ),
			'format' => locate_template( 'css/format.php' ),
			'buttons' => locate_template( 'css/buttons.php' ),
			'forms' => locate_template( 'css/forms.php' ),
			'ui' => locate_template( 'css/ui.php' ),
			'menus' => locate_template( 'css/menus.php' ),
			'header' => locate_template( 'css/header.php' ),
			'title' => locate_template( 'css/title.php' ),
			'layout' => locate_template( 'css/layout.php' ),
			'loop' => locate_template( 'css/loop.php' ),
			'alignments' => locate_template( 'css/alignments.php' ),
			'page' => locate_template( 'css/page.php' ),
			'comments' => locate_template( 'css/comments.php' ),
			'widgets' => locate_template( 'css/widgets.php' ),
			'panel' => locate_template( 'css/panel.php' ),
			'helpers' => locate_template( 'css/helpers.php' )
		);

		$templates = array_merge( $templates, apply_filters( 'md_dropins_css_templates', array() ) );
		$templates = apply_filters( 'md_style_css_templates', $templates );

		$child = get_stylesheet_directory() . '/style.css';

		if ( is_child_theme() && file_exists( $child ) && md_setting( array( 'settings', 'css', 'child' ) ) ) {
			$templates['child'] = $child;

			$child_dynamic = get_stylesheet_directory() . '/style.php';

			if ( file_exists( $child_dynamic ) )
				$templates['child_dynamic'] = $child_dynamic;
		}

		return $templates;
	}

	/**
	 * Remove Critical CSS templates from the frontend file when they
	 * are already rendered into the inline critical stylesheet.
	 *
	 * @since 4.9.4
	 */

	public function style_css( $templates ) {
		if ( $this->critical_enabled() )
			$templates = array_diff_key( $templates, array_flip( $this->critical_keys() ) );

		return $templates;
	}

	/**
	 * Compile the full canonical stylesheet into the Block Editor,
	 * then load editor-only corrections last.
	 *
	 * @since 6.0
	 */

	protected function block_editor_css( $templates ) {
		$templates['block-editor'] = locate_template( 'css/block-editor.php' );

		return $templates;
	}

	/**
	 * Build the intentionally limited Classic Editor stylesheet.
	 * Active Drop-ins may opt in with css/classic-editor.php.
	 *
	 * @since 6.0
	 */

	protected function classic_editor_css() {
		$templates = array(
			'classic-editor' => locate_template( 'css/classic-editor.php' )
		);

		foreach ( md_get_dropins( 'active' ) as $dropin ) {
			$dropin = sanitize_key( $dropin );
			$path = MD_INSTALLED_DROPINS . "/{$dropin}/css/classic-editor.php";

			if ( file_exists( $path ) )
				$templates["{$dropin}-classic-editor"] = $path;
		}

		return apply_filters( 'md_classic_editor_css_templates', $templates );
	}

	/**
	 * Whether the Critical CSS feature is enabled.
	 *
	 * @since 6.0
	 */

	protected function critical_enabled() {
		return md_setting( array( 'settings', 'css', 'critical' ) );
	}

	/**
	 * The CSS template parts to inline as Critical CSS.
	 *
	 * @since 6.0
	 */

	protected function critical_keys() {
		return apply_filters( 'md_critical_css_templates', array(
			'style', 'header', 'menus', 'buttons', 'layout', 'title', 'page'
		) );
	}

	/**
	 * Compile CSS in the user designated manner on call.
	 *
	 * @since 4.8
	 */

	public function compile( $delete = null ) {
		$inline = md_setting( array( 'settings', 'css', 'inline' ) );

		foreach ( $this->files as $file => $fields ) {
			if ( $file === 'style' ) {
				if ( ! empty( $inline ) ) {
					$this->save( $file );
					continue;
				}

				if ( isset( $delete ) )
					delete_option( 'marketers_delight_style_css' );
			}

			$this->generate( $file );
		}

		if ( ! $this->critical_enabled() ) {
			$critical = MD_DIR . 'compile/critical.css';

			if ( file_exists( $critical ) )
				file_put_contents( $critical, '' );
		}

		$theme_json = new md_theme_json;
		$theme_json->generate();

		wp_cache_flush();
	}

	/**
	 * Render CSS Templates into static CSS.
	 *
	 * @since 4.8
	 */

	public function generate( $file ) {
		$path = $this->files[$file]['path'];

		if ( ! file_exists( $path ) )
			return;

		if ( ! empty( $this->files[$file]['minify'] ) ) {
			file_put_contents( $path, $this->minify( $file ) );
			return;
		}

		$css = $this->clean( $this->render( $file ) );

		file_put_contents( $path, $css );
	}

	/**
	 * Save CSS as option to database (only when needed).
	 *
	 * @since 4.8
	 */

	public function save( $file ) {
		$css = $this->minify( $file );

		update_option( "marketers_delight_{$file}_css", $css, false );
	}

	/**
	 * Remove all whitespace, line breaks, unwanted characters,
	 * and other requirements for file minification.
	 *
	 * @since 4.9.1
	 */

	public function minify( $file ) {
		$css = $this->render( $file );
		$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
		$s = array( "\r", "\n", "\t", ' }', '{ ', ' {', '; ', ': ', ', ', '   ' );
		$r = array( '', '', '', '}', '{', '{', ';', ':', ',', '' );

		$css = str_replace( $s, $r, $css );
		$css = $this->clean( $css );

		return $css;
	}

	/**
	 * Render and adapt all CSS templates registered to a file.
	 *
	 * @since 6.0
	 */

	private function render( $file ) {
		ob_start();

		$this->templates( $file );

		return $this->replace( ob_get_clean(), $file );
	}

	/**
	 * Render the CSS templates registered to one output file.
	 *
	 * @since 4.9
	 */

	public function templates( $file ) {
		$g = 1.618;
		$design = new md_design;
		$values = $design->values();
		$theme_url = get_stylesheet_directory_uri();
		$queries = array( 600 => 'mobile' );

		$colors = $values['colors'];
		$typography = $values['typography'];
		$fonts = $design->fonts( $values );
		$effects = $design->effects();
		$header = $values['header'];
		$logo = $values['logo'];
		$sidebar = $values['sidebar'];

		$widths = $design->widths( $values );
		$site_width = $this->site_width = $widths['site_width'];
		$site_width_wide = $widths['site_width_wide'];
		$content_width = $widths['content_width'];
		$post_width = $widths['post_width'];
		$sidebar_width = $widths['sidebar_width'];
		$panel_width = $widths['panel_width'];

		$font_size = $values['typography']['body']['font_size'];
		$font_family = $fonts['body']['font_family'];
		$line_height = $values['typography']['body']['line_height'];
		$font_weight = $fonts['body']['font_weight'];
		$bold = $fonts['body']['bold'];

		$h1 = $values['typography']['h1'];
		$h2 = $values['typography']['h2'];
		$h3 = $values['typography']['h3'];
		$h4 = $values['typography']['h4'];
		$h5 = $values['typography']['h5'];
		$h6 = $values['typography']['h6'];

		$h1_font_family = $fonts['heading']['font_family'];
		$h1_font_weight = $fonts['heading']['font_weight'];

		$spacers = $design->spacers( $values );
		$small = $spacers['small']['desktop'];
		$third = $spacers['third']['desktop'];
		$half = $spacers['half']['desktop'];
		$single = $spacers['single']['desktop'];
		$mid = $spacers['mid']['desktop'];
		$double = $spacers['double']['desktop'];
		$triple = $spacers['triple']['desktop'];
		$quad = $spacers['quad']['desktop'];
		$alignwide_breakout = $content_width > 0 ? ( $quad / $content_width ) * 100 : 0;

		$submenu_width = md_setting( array( 'header', 'submenu_width' ), ( $double * 5 ) );
		$gutter_width = round( ( $site_width - $post_width ) / 2 );
		$breakout = ( $gutter_width / $post_width ) * 100;
		$breakout_full = ( $gutter_width / $site_width ) * 100;

		$values = array_merge( $values, apply_filters( 'md_filter_css_values', $values ) );
		$style_guide = $this->files['style']['style_guide'] ?? '';

		$cover_colors = array(
			'default' => array(
				'class' => '',
				'color' => '#fff',
				'border' => 'rgba(255, 255, 255, 0.3)'
			),
			'alt' => array(
				'class' => '.alt',
				'color' => $colors['header'],
				'border' => 'rgba(0, 0, 0, 0.2)'
			)
		);

		foreach ( $this->files[$file]['templates'] as $template => $fields ) {
			$path = is_array( $fields ) ? $fields['path'] : $fields;
			$data = is_array( $fields ) ? ( $fields['data'] ?? null ) : null;

			if ( ! file_exists( $path ) )
				continue;

			include $path;

			echo "\n\n";
		}

		if ( in_array( $file, array( 'style', 'block-editor', 'font-icons' ) ) )
			$this->icons_css();
	}

	/**
	 * Clean up CSS before saving.
	 *
	 * @since 4.9.4
	 */

	public function clean( $css ) {
		$css = str_replace( array( '<style type="text/css">', '<style type=\'text/css\'>', '<style>', '</style>' ), '', $css );
		$css = preg_replace( "/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $css );

		return trim( $css );
	}

	/**
	 * Apply file-level string swaps to rendered CSS.
	 *
	 * @since 6.0
	 */

	private function replace( $css, $file ) {
		if ( empty( $this->files[$file]['replace'] ) )
			return $css;

		return str_replace( array_keys( $this->files[$file]['replace'] ), $this->files[$file]['replace'], $css );
	}

	/**
	 * Calculate fluid clamp CSS via two-point linear interpolation between
	 * a floor value/width and a desktop value/width. Omitting floor_width
	 * derives it the same way the original formula implicitly did (floor
	 * * site_width / desktop); pass it explicitly to pin the floor at an
	 * exact, chosen viewport width instead.
	 *
	 * @since 6.0
	 */

	public function fluid( $desktop, $floor, $floor_width = null, $desktop_width = null ) {
		if ( empty( $floor ) || $floor >= $desktop )
			return "{$desktop}px";

		$desktop_width = $desktop_width ?: $this->site_width;
		$floor_width = $floor_width ?: ( $floor * $desktop_width / $desktop );

		$vw = round( ( $desktop - $floor ) / ( $desktop_width - $floor_width ) * 100, 6 );
		$px = round( $floor - ( $vw * $floor_width / 100 ), 5 );
		$preferred = $px ? "{$px}px + {$vw}vw" : "{$vw}vw";

		return "clamp({$floor}px, {$preferred}, {$desktop}px)";
	}

	/**
	 * Render a list of CSS files to generate a
	 * table of contents at the top of the stylesheet.
	 *
	 * @since 6.0
	 */

	private function style_guide( $css_files ) {
		$c = 1;
		$style_guide = '';

		foreach ( $css_files as $css_group => $css_path ) {
			$style_group_name = str_replace( '-', ' ', ucwords( $css_group ) );
			$style_guide .= "\t\t{$c}. $style_group_name\n";

			$c++;
		}

		return "\n$style_guide";
	}

	/**
	 * Print icons CSS styles by class names.
	 *
	 * @since 6.0
	 */

	private function icons_css() {
		foreach ( md_icons() as $icon => $fields ) {
			if ( ! isset( $fields['unicode'] ) )
				continue;

			$selectors = '';

			if ( isset( $fields['classes'] ) )
				foreach ( $fields['classes'] as $selector )
					$selectors .= ",{$selector}:before";

			echo '.md-icon-' . esc_attr( $icon ) . ":before{$selectors}{content:'\\" . esc_attr( $fields['unicode'] ) . '\'}';
		}
	}

}
