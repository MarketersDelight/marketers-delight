<?php
/**
 * This class generates MD CSS files and other actions.
 *
 * @since 4.9.4
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class md_css {

	public $files;

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
		return array_merge( array(
			'style' => array(
				'templates' => $this->style_css(),
				'path' => MD_DIR . 'style.css'
			)
		), apply_filters( 'md_css_files', array() ) );
	}

	/**
	 * Build list of stylesheets to include in style.css.
	 *
	 * @since 5.6
	 */

	protected function css_files() {
		$dropins = apply_filters( 'md_dropins_css_templates', array() );

		$templates = array(
			'attributes' => locate_template( 'css/attributes.php' )
		);

		if ( locate_template( 'css/fonts.php' ) )
			$templates['fonts'] = locate_template( 'css/fonts.php' );

		$templates = array_merge( $templates, array(
			'forms' => locate_template( 'css/forms.php' ),
			'buttons' => locate_template( 'css/buttons.php' ),
			'format' => locate_template( 'css/format.php' ),
			'content-box' => locate_template( 'css/content-box.php' ),
			'layout' => locate_template( 'css/layout.php' ),
			'loops' => locate_template( 'css/loops.php' ),
			'menus' => locate_template( 'css/menus.php' ),
			'header' => locate_template( 'css/header.php' ),
			'comments' => locate_template( 'css/comments.php' ),
			'widgets' => locate_template( 'css/widgets.php' ),
			'sidebar' => locate_template( 'css/sidebar.php' ),
			'footer' => locate_template( 'css/footer.php' ),
			'spacers' => locate_template( 'css/spacers.php' ),
			'design' => locate_template( 'css/design.php' )
		) );

		$templates = array_merge( $templates, $dropins );

		return apply_filters( 'md_style_css_templates', $templates );
	}

	/**
	 * Core style.css template files to load in order
	 * with Drop-ins filter.
	 *
	 * @since 4.9.4
	 */

	public function style_css() {
		$templates = $this->css_files();
		$child = get_stylesheet_directory() . '/style.css';

		if ( is_child_theme() && file_exists( $child ) && md_setting( array( 'settings', 'css', 'child' ) ) ) {
			$child_dynamic = get_stylesheet_directory() . '/style.php';
			$templates['child'] = $child;

			if ( file_exists( $child_dynamic ) )
				$templates['child_dynamic'] = $child_dynamic;
		}

		return $templates;
	}

	/**
	 * Compile CSS in the user designated manner on call.
	 *
	 * @since 4.8
	 */

	public function compile( $delete = null ) {
		$inline = md_setting( array( 'settings', 'css', 'inline' ) );

		foreach ( $this->files as $file => $fields ) {
			if ( empty( $inline ) ) {
				if ( isset( $delete ) )
					delete_option( "marketers_delight_{$file}_css" );
				$this->generate( $file );
			}
			else
				$this->save( $file );
		}

		wp_cache_flush();
	}

	/**
	 * Render CSS Templates into static CSS.
	 *
	 * @since 4.8
	 */

	public function generate( $file ) {
		$path = $this->files[$file]['path'];

		if ( file_exists( $path ) ) {
			ob_start();
			$this->templates( $file );
			$css = ob_get_clean();
			$css = $this->clean( $css );
			file_put_contents( $path, $css );
		}
	}

	/**
	 * Save CSS as option to database (only when needed).
	 *
	 * @since 4.8
	 */

	public function save( $file ) {
		$css = $this->minify( $file );
		update_option( "marketers_delight_{$file}_css", $css );
	}

	/**
	 * Remove all whitespace, line breaks, unwanted characters,
	 * and other requirements for file minification.
	 *
	 * @since 4.9.1
	 */

	public function minify( $file ) {
		ob_start();
		$this->templates( $file );
		$css = ob_get_clean();
		$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
		$s = array( "\r", "\n", "\t", ' }', '{ ', ' {', '; ', ': ', ', ', '   ' );
		$r = array( '', '', '', '}', '{', '{', ';', ':', ',', '' );
		$css = str_replace( $s, $r, $css );
		$css = $this->clean( $css );
		return $css;
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
	 * Render a list of CSS files to generate a
	 * table of contents at the top of the stylesheet.
	 *
	 * @since 5.6
	 */

	private function style_guide() {
		$c = 1;
		$style_guide = '';
		$css_files = $this->css_files();

		foreach ( $css_files as $css_group => $css_path ) {
			$style_group_name = str_replace( '-', ' ', ucwords( $css_group ) );
			$style_guide .= "\t\t{$c}. $style_group_name\n";
			$c++;
		}

		return "\n$style_guide";
	}

	/**
	 * Get CSS template.
	 *
	 * @since 4.9
	 */

	public function templates( $file ) {
		$g = 1.618;
		$design = new md_design;
		$values = $design->values();
		$theme_url = get_stylesheet_directory_uri();

		$colors = $values['colors'];
		$typography = $values['typography'];
		$header = $values['header'];
		$logo = $values['logo'];

		$site_width = $values['colors']['width']['site'];
		$site_width_full = $values['colors']['width']['site_full'];
		$logo_width = $logo['logo_width']['desktop'];
		$content_width = $values['colors']['width']['content_width'];
		$post_width = $values['colors']['width']['post'];
		$sidebar_width = $values['colors']['width']['sidebar'];

		$content_style = md_setting( array( 'content', 'style' ) );

		$font_size = $values['typography']['body']['font_size'];
		$font_family = $values['typography']['body']['font_family'];
		$line_height = $values['typography']['body']['line_height'];
		$font_weight = ! empty( $typography['body']['font_weight'] ) ? $typography['body']['font_weight'] : 'normal';
		$bold = ! empty( $typography['body']['bold'] ) ? $typography['body']['bold'] : 'bold';

		$h1 = $values['typography']['h1'];
		$h2 = $values['typography']['h2'];
		$h3 = $values['typography']['h3'];
		$h4 = $values['typography']['h4'];
		$h5 = $values['typography']['h5'];
		$h6 = $values['typography']['h6'];

		$single = $lh = $line_height['desktop'];
		$small = $lhsm = round( $single / 6 );
		$third = $lhth = round( $single / 3 );
		$half = $lhh = round( $single / 2 );
		$mid = $lhm = $single + $half;
		$double = $lhd = round( $single * 2 );
		$triple = $lht = round( $single * 3 );
		$quad = $lhq = round( $single * 4 );

		$submenu_width = md_setting( array( 'header', 'submenu_width' ), ( $double * 5 ) );
		$gutter_width = round( ( $site_width - $post_width ) / 2 );
		$breakout = ( $gutter_width / $post_width ) * 100;
		$breakout_full = ( $gutter_width / $site_width ) * 100;
		$content_block = ( $mid / $content_width ) * 100;

		$admin_bar_height = 32;
		$admin_bar_height_mobile = 46;

		$values = array_merge( $values, apply_filters( 'md_filter_css_values', $values ) );
		$style_guide = $this->style_guide();

		foreach ( $this->files[$file]['templates'] as $template => $path ) {
			if ( ! file_exists( $path ) ) continue;

			include( $path );

			echo "\n\n";
		}
	}

}
