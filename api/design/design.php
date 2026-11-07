<?php
/**
 * Combine typography, layout, effects, and resolved colors into the final
 * values consumed by MD's CSS and theme.json compilers.
 *
 * @since 4.8
 */

class md_design {

	public $font_family = 'system-ui, avenir next, avenir, segoe ui, helvetica neue, helvetica, Cantarell, Ubuntu, roboto, noto, arial, sans-serif';

	private $colors;

	/**
	 * Set the color system used to build and resolve Design values.
	 *
	 * @since 6.0
	 */

	public function __construct() {
		$this->colors = new md_design_colors;
	}

	/**
	 * Merge saved design settings over the calculated defaults, then turn palette
	 * references into final colors. Typography is already resolved, so saved
	 * values can't replace its scale.
	 *
	 * @since 4.8
	 */

	public function values() {
		$values = $this->merge_defaults( $this->defaults(), array(
			'colors' => md_setting( 'colors' ),
			'header' => md_setting( 'header' ),
			'logo' => md_setting( 'logo' ),
			'sidebar' => md_setting( 'sidebars' )
		) );

		// Resolve semantic color fallbacks and palette references.

		$palette = $this->colors->active_palette();
		$values['colors'] = $this->colors->resolve( $values['colors'], $palette, md_setting( 'colors' ) );

		if ( empty( $values['logo']['site_title']['color'] ) )
			$values['logo']['site_title']['color'] = $values['colors']['header']['text_color'];

		if ( empty( $values['logo']['site_tagline']['color'] ) )
			$values['logo']['site_tagline']['color'] = $values['colors']['site']['muted_text_color'];

		foreach ( array( 'site_title', 'site_tagline' ) as $logo ) {
			$value = $values['logo'][$logo]['color'];

			if ( is_string( $value ) && isset( $palette[$value] ) )
				$values['logo'][$logo]['color'] = $palette[$value]['hex'];
		}

		foreach ( $palette as $key => $color )
			$values['colors']['palette'][$key] = $color['hex'];

		// Return final values

		return $values;
	}

	/**
	 * Layer saved settings over computed defaults. A blank or null value counts
	 * as untouched, so the default shows through.
	 *
	 * @since 6.0
	 */

	private function merge_defaults( $defaults, $override ) {
		foreach ( $override as $key => $value ) {
			if ( is_array( $value ) ) {
				if ( isset( $defaults[$key] ) && is_array( $defaults[$key] ) )
					$defaults[$key] = $this->merge_defaults( $defaults[$key], $value );
				else
					$defaults[$key] = $value;
			}
			elseif ( $value !== '' && $value !== null )
				$defaults[$key] = $value;
		}

		return $defaults;
	}

	/**
	 * Default design values, with typography already merged. Body sets the scale:
	 * line height uses the golden ratio and h2-h6 are geometric steps up to h1.
	 *
	 * @since 4.8
	 */

	public function defaults() {
		$typography = $this->typography();
		$line_height = $typography['body']['line_height']['desktop'];
		$site_title = md_setting( array( 'logo', 'site_title', 'font_size', 'desktop' ), $typography['h4']['font_size']['desktop'] );

		// Layout Widths

		$design = md_setting( array( 'colors', 'design' ) );
		$cw = md_setting( array( 'colors', 'width', 'content' ) );
		$sw = md_setting( array( 'colors', 'width', 'sidebar' ) );
		$post_width = ! empty( $cw ) ? $cw : round( 21 * $line_height );
		$gutter = ! $design ? ( $line_height + round( $line_height / 2 ) ) * 2 : 0;
		$content_width = apply_filters( 'md_filter_css_content_width', $post_width + $gutter, $post_width, $line_height );

		// Match theme.json wideSize to the frontend's percentage breakout.

		$spacers = $this->spacer_scale( $line_height );
		$alignwide_breakout = $content_width > 0 ? $spacers['quad'] / $content_width : 0;
		$alignwide_width = round( $post_width + ( $post_width * $alignwide_breakout * 2 ) );
		$sidebar_width = ! empty( $sw ) ? $sw : round( 12 * $line_height );
		$panel_width = round( 10 * $line_height );
		$site_width = round( $content_width + $sidebar_width + $spacers['single'] );
		$site_width_wide = $content_width + $sidebar_width + $panel_width + ( $line_height * 2 );

		$colors = $this->colors->defaults();
		$colors['width'] = array(
			'site' => $site_width,
			'site_wide' => $site_width_wide,
			'alignwide' => $alignwide_width,
			'content_width' => $content_width,
			'panel_width' => $panel_width,
			'post' => $post_width,
			'sidebar' => $sidebar_width
		);

		return array(
			'colors' => $colors,
			'typography' => $typography,
			'logo' => array(
				'site_title' => array(
					'color' => '',
					'font_size' => array( 'desktop' => $site_title ),
					'line_height' => array( 'desktop' => round( $site_title * 1.1 ) )
				),
				'site_tagline' => array(
					'color' => ''
				)
			),
			'header' => array(),
			'sidebar' => array()
		);
	}

	/**
	 * Calculate typography defaults with relationships. The primary numbers
	 * are body font size and h1 font size, all with possible user overrides.
	 * The numbers 0-6 are treated as font hierarchy, 0 = body, ..., 6 = h6.
	 *
	 * @since 6.0
	 */

	private function typography() {
		$g = 1.618;
		$typography = (array) md_setting( 'typography' );

		// Font size / line height

		$min_font_size = 16;
		$font_size = 20;

		if ( isset( $typography['body']['font_size']['desktop'] ) )
			$font_size = (int) $typography['body']['font_size']['desktop'];

		if ( $font_size > 0 )
			$font_size = max( $font_size, $min_font_size );
		else
			$font_size = 20;

		$default_font_size_mobile = max( round( $font_size * 0.9 ), $min_font_size );
		$font_size_mobile = isset( $typography['body']['font_size']['mobile'] ) ? (int) $typography['body']['font_size']['mobile'] : $default_font_size_mobile;
		$font_size_mobile = $font_size_mobile > 0 ? max( $font_size_mobile, $min_font_size ) : $default_font_size_mobile;

		$line_height = round( $font_size * $g );
		$line_height_mobile = round( $font_size_mobile * $g );

		// Headings

		$steps = 6;
		$h1_desktop = isset( $typography['h1']['font_size']['desktop'] ) ? (int) $typography['h1']['font_size']['desktop'] : round( $font_size * ( $g * 1.4 ) );
		$h1_desktop = max( $h1_desktop, $font_size + $steps );
		$h1_mobile = isset( $typography['h1']['font_size']['mobile'] ) ? (int) $typography['h1']['font_size']['mobile'] : round( $h1_desktop * 0.75 );
		$h1_mobile = max( $h1_mobile, $font_size_mobile + $steps );

		$h1 = array(
			'desktop' => $h1_desktop,
			'mobile' => $h1_mobile
		);

		$step_ratio = pow( $h1['desktop'] / $font_size, 1 / $steps );
		$step_ratio_mobile = pow( $h1['mobile'] / $font_size_mobile, 1 / $steps );

		// Saved heading sizes replace their scale steps, so line heights follow them

		$scale = array(
			'h2' => array( 'step' => 5, 'desktop' => 1.35, 'mobile' => 1.35 ),
			'h3' => array( 'step' => 4, 'desktop' => 1.4, 'mobile' => 1.4 ),
			'h4' => array( 'step' => 3, 'desktop' => 1.45, 'mobile' => 1.45 ),
			'h5' => array( 'step' => 2, 'desktop' => 1.5, 'mobile' => 1.5 ),
			'h6' => array( 'step' => 1, 'desktop' => 1.55, 'mobile' => 1.55 )
		);
		$headings = array();

		foreach ( $scale as $type => $heading ) {
			$sizes = $this->saved_font_size( $typography, $type, array(
				'desktop' => round( $font_size * pow( $step_ratio, $heading['step'] ) ),
				'mobile' => round( $font_size_mobile * pow( $step_ratio_mobile, $heading['step'] ) )
			) );

			$headings[$type] = array(
				'font_size' => $sizes,
				'line_height' => array(
					'desktop' => round( $sizes['desktop'] * $heading['desktop'] ),
					'mobile' => round( $sizes['mobile'] * $heading['mobile'] )
				)
			);
		}

		$typography['body']['font_size']['desktop'] = $font_size;
		$typography['body']['font_size']['mobile'] = $font_size_mobile;
		$typography['h1']['font_size']['desktop'] = $h1_desktop;
		$typography['h1']['font_size']['mobile'] = $h1_mobile;

		// Build typography list

		$base = array(
			'body' => array(
				'font_size' => array(
					'desktop' => $font_size,
					'mobile' => $font_size_mobile
				),
				'line_height' => array(
					'desktop' => $line_height,
					'mobile' => $line_height_mobile
				),
				'font_family' => $this->font_family
			),
			'huge' => array(
				'font_size' => array(
					'desktop' => round( $h1['desktop'] * 1.5 ),
					'mobile' => round( $h1['mobile'] * 1.25 )
				),
				'line_height' => array(
					'desktop' => round( $h1['desktop'] * 2 ),
					'mobile' => round( $h1['mobile'] * 1.5 )
				)
			),
			'h1' => array(
				'font_size' => $h1,
				'line_height' => array(
					'desktop' => round( $h1['desktop'] * 1.3 ),
					'mobile' => round( $h1['mobile'] * 1.3 )
				)
			)
		);

		$areas = array(
			'header' => array(
				'font_size' => array( 'desktop' => $font_size ),
				'line_height' => array( 'desktop' => $line_height )
			),
			'sidebar' => array(
				'font_size' => array( 'desktop' => round( $font_size * 0.9 ) ),
				'line_height' => array( 'desktop' => round( $line_height * 0.85 ) )
			),
			'footer' => array(
				'font_size' => array( 'desktop' => round( $font_size * 0.95 ) ),
				'line_height' => array( 'desktop' => round( $line_height * 0.9 ) )
			)
		);

		return $this->merge_defaults( array_merge( $base, $headings, $areas ), $typography );
	}

	/**
	 * A heading's saved font size on each device, else its scale size.
	 *
	 * @since 6.1
	 */

	private function saved_font_size( $typography, $type, $sizes ) {
		foreach ( $sizes as $device => $size )
			if ( ! empty( $typography[$type]['font_size'][$device] ) )
				$sizes[$device] = (int) $typography[$type]['font_size'][$device];

		return $sizes;
	}

	/**
	 * Set body and heading font properties, other than size/line height.
	 *
	 * @since 6.0
	 */

	public function fonts( $values ) {
		$body = $values['typography']['body'];
		$bold = ! empty( $body['bold'] ) ? $body['bold'] : 'bold';

		// Body

		$fonts['body'] = array(
			'font_family' => $body['font_family'],
			'font_weight' => ! empty( $body['font_weight'] ) ? $body['font_weight'] : 'normal',
			'bold' => $bold
		);

		// Headings

		$heading = $values['typography']['h1'];
		$heading_font_family = ! empty( $heading['font_family'] ) ? $heading['font_family'] : $body['font_family'];
		$heading_font_weight = ! empty( $heading['font_weight'] ) ? $heading['font_weight'] : $bold;

		$fonts['heading'] = array(
			'font_family' => $heading_font_family,
			'font_weight' => $heading_font_weight
		);

		foreach ( array( 'huge', 'h2', 'h3', 'h4', 'h5', 'h6' ) as $type ) {
			$font = $values['typography'][$type];
			$override = array();

			if ( ! empty( $font['font_family'] ) && $font['font_family'] !== $heading_font_family )
				$override['font_family'] = $font['font_family'];

			if ( ! empty( $font['font_weight'] ) && $font['font_weight'] !== $heading_font_weight )
				$override['font_weight'] = $font['font_weight'];

			if ( ! empty( $override ) )
				$fonts['heading_overrides'][$type] = $override;
		}

		// Return font properties

		return $fonts;
	}

	/**
	 * Return shared border radius and box shadow effects.
	 *
	 * @since 6.0
	 */

	public function effects() {
		return array(
			'border_radius' => '8px',
			'box_shadow' => array(
				'default' => '0 2px 8px rgba(0, 0, 0, 0.15)',
				'small' => '0 1px 3px rgba(0, 0, 0, 0.15)',
				'medium' => '0 4px 16px rgba(0, 0, 0, 0.12)',
				'large' => '0 8px 32px rgba(0, 0, 0, 0.15)',
				'huge' => '0 16px 48px rgba(0, 0, 0, 0.20)'
			)
		);
	}

	/**
	 * Returns layout widths.
	 *
	 * @since 6.0
	 */

	public function widths( $values ) {
		$w = $values['colors']['width'];

		return array(
			'site_width' => $w['site'],
			'site_width_wide' => $w['site_wide'],
			'alignwide_width' => $w['alignwide'],
			'content_width' => $w['content_width'],
			'post_width' => $w['post'],
			'sidebar_width' => $w['sidebar'],
			'panel_width' => $w['panel_width']
		);
	}

	/**
	 * Calculate common spacing values from single line height,
	 * as desktop/mobile pairs for fluid scaling.
	 *
	 * @since 6.0
	 */

	public function spacers( $values ) {
		$lh_desktop = $values['typography']['body']['line_height']['desktop'];
		$lh_mobile = round( $lh_desktop * 0.65 );

		$desktop = $this->spacer_scale( $lh_desktop );
		$mobile = $this->spacer_scale( $lh_mobile );

		$spacers = array();

		foreach ( $desktop as $key => $value )
			$spacers[$key] = array( 'desktop' => $value, 'mobile' => $mobile[$key] );

		return $spacers;
	}

	/**
	 * Calculate the common spacing scale from a given line-height value.
	 *
	 * @since 6.0
	 */

	private function spacer_scale( $single ) {
		$half = round( $single / 2 );

		return array(
			'small' => round( $single / 6 ),
			'third' => round( $single / 3 ),
			'half' => $half,
			'single' => $single,
			'mid' => $single + $half,
			'double' => round( $single * 2 ),
			'triple' => round( $single * 3 ),
			'quad' => round( $single * 4 )
		);
	}

}
