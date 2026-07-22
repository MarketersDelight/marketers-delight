<?php
/**
 * This class holds data that makes up the Design and Typography system.
 *
 * @since 4.8
 */

class md_design {

	/**
	 * Main Theme colors, form the global color palette.
	 *
	 * @since 6.0
	 */

	public static $palette = array(
		'background' => '#FFFFFF',
		'surface' => '#F0F0F0',
		'primary' => '#AE2525',
		'secondary' => '#2E2E2E',
		'tertiary' => '#DDDDDD',
		'border' => '#CCCCCC',
		'highlight' => '#FFFBCC',
		'text-main' => '#1E1E1E',
		'text-secondary' => '#777777',
		'button' => '#22A340',
		'white' => '#FFFFFF'
	);

	/**
	 * Compare hard-set default values to user admin options and
	 * return a complete list of design values to use in a
	 * dynamic CSS file.
	 *
	 * @since 4.8
	 */

	public function values() {
		$values = $this->merge_defaults( $this->defaults(), array(
			'colors' => md_setting( 'colors' ),
			'typography' => md_setting( 'typography' ),
			'header' => md_setting( 'header' ),
			'logo' => md_setting( 'logo' ),
			'sidebar' => md_setting( 'sidebars' )
		) );

		// A fun way to convert all colors into usable colors like hex code rather than inherit string

		$palette = $this->active_palette();

		array_walk_recursive( $values, function( &$value ) use ( $palette ) {
			if ( is_string( $value ) && isset( $palette[$value] ) )
				$value = $palette[$value]['hex'];
		} );

		foreach ( $palette as $key => $color )
			$values['colors']['palette'][$key] = $color['hex'];

		return $values;
	}

	/**
	 * Layer saved settings on top of the computed defaults. A saved value
	 * wins wherever it's set — but a blank/null value is treated as if the
	 * user never touched that field, so the computed default shows through
	 * instead of a blank.
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
	 * The master color list - fixed palette keys only, with user-selected
	 * overrides applied. Used to render/prefill the master palette admin
	 * fields, which custom colors don't belong in (they have their own
	 * group).
	 *
	 * @since 6.0
	 */

	public static function base_palette() {
		$palette = array();

		foreach ( self::$palette as $key => $hex )
			$palette[$key] = array(
				'hex' => $hex,
				'name' => ucwords( str_replace( '-', ' ', $key ) )
			);

		$colors = md_setting( 'colors' );

		if ( ! empty( $colors['palette'] ) )
			foreach ( $colors['palette'] as $key => $data )
				if ( ! empty( $data['hex'] ) && isset( $palette[$key] ) )
					$palette[$key]['hex'] = $data['hex'];

		return $palette;
	}

	/**
	 * The master color router - the master palette plus any custom colors
	 * layered on top, forming the full set of colors selectable/resolvable
	 * anywhere a color can be picked or referenced.
	 *
	 * @since 6.0
	 */

	public static function active_palette() {
		$palette = self::base_palette();
		$colors = md_setting( 'colors' );

		if ( ! empty( $colors['custom'] ) )
			foreach ( $colors['custom'] as $data )
				if ( ! empty( $data['name'] ) && ! empty( $data['hex'] ) ) {
					$key = sanitize_title( ! empty( $data['key'] ) ? $data['key'] : $data['name'] );
					$palette[$key] = array(
						'hex' => $data['hex'],
						'name' => $data['name']
					);
				}

		return $palette;
	}

	/**
	 * Register color palette in the Block Editor.
	 *
	 * @since 4.9
	 */

	public static function editor_colors() {
		$colors = array();

		foreach ( self::active_palette() as $key => $color )
			$colors[] = array(
				'name' => $color['name'],
				'slug' => $key,
				'color' => $color['hex']
			);

		return $colors;
	}

	/**
	 * Calculate the common spacing scale from a given line-height value.
	 *
	 * @since 6.1
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

	/**
	 * Calculate common spacing values from single line height,
	 * as desktop/mobile pairs for fluid scaling.
	 *
	 * @since 6.0
	 */

	public function spacers() {
		$values = $this->values();
		$lh_desktop = $values['typography']['body']['line_height']['desktop'];

		// Independent of typography's own desktop/mobile ratio, which shrinks
		// far too little (~9%) to matter for large spacers like quad.
		$lh_mobile = round( $lh_desktop * 0.65 );

		$desktop = $this->spacer_scale( $lh_desktop );
		$mobile = $this->spacer_scale( $lh_mobile );

		$spacers = array();

		foreach ( $desktop as $key => $value )
			$spacers[ $key ] = array( 'desktop' => $value, 'mobile' => $mobile[ $key ] );

		return $spacers;
	}

	/**
	 * Returns layout widths.
	 *
	 * @since 6.0
	 */

	public function widths() {
		$values = $this->values();
		$w = $values['colors']['width'];

		return array(
			'site_width' => $w['site'],
			'site_width_wide' => $w['site_wide'],
			'content_width' => $w['content_width'],
			'post_width' => $w['post'],
			'sidebar_width' => $w['sidebar'],
			'panel_width' => $w['panel_width']
		);
	}

	/**
	 * Default design values — merged with user settings in values().
	 *
	 * @since 4.8
	 */

	public function defaults() {
		$g = 1.618;
		$font_size = md_setting( array( 'typography', 'body', 'font_size', 'desktop' ), 20 );
		$font_size_mobile = md_setting( array( 'typography', 'body', 'font_size', 'mobile' ), round( $font_size * 0.9 ) );
		$line_height = round( $font_size * $g );
		$line_height_mobile = round( $font_size_mobile * $g );

		$h1_desktop = md_setting( array( 'typography', 'h1', 'font_size', 'desktop' ), round( $font_size * ( $g * 1.4 ) ) );

		$h1 = array(
			'desktop' => $h1_desktop,
			'mobile'  => md_setting( array( 'typography', 'h1', 'font_size', 'mobile' ), round( $h1_desktop * 0.75 ) )
		);
		$h2 = array(
			'desktop' => round( $h1['desktop'] * 0.85 ),
			'mobile' => round( $h1['desktop'] * 0.85 * 0.80 )
			);
		$h3 = array(
			'desktop' => round( $h1['desktop'] * 0.7 ),
			'mobile' => round( $h1['desktop'] * 0.7 * 0.85 )
		);
		$h4 = array(
			'desktop' => round( $h1['desktop'] * 0.6 ),
			'mobile' => round( $h1['desktop'] * 0.6 * 0.857 )
		);
		$h5 = array(
			'desktop' => round( $h1['desktop'] * 0.5 ),
			'mobile' => round( $h1['desktop'] * 0.5 * 0.857 )
		);
		$h6 = array(
			'desktop' => round( $h1['desktop'] * 0.45 ),
			'mobile' => round( $h1['desktop'] * 0.45 * 0.857 )
		);

		$site_title = md_setting( array( 'logo', 'site_title', 'font_size', 'desktop' ), $h4['desktop'] );

		$design = md_setting( array( 'colors', 'design' ) );
		$cw = md_setting( array( 'colors', 'width', 'content' ) );
		$sw = md_setting( array( 'colors', 'width', 'sidebar' ) );
		$post_width = ! empty( $cw ) ? $cw : round( 21 * $line_height );
		$gutter = ! $design ? ( $line_height + round( $line_height / 2 ) ) * 2 : 0;
		$content_width = apply_filters( 'md_filter_css_content_width', $post_width + $gutter, $post_width, $line_height );
		$sidebar_width = ! empty( $sw ) ? $sw : round( 12 * $line_height );
		$panel_width = round( 10 * $line_height );
		$site_width = round( $content_width + $sidebar_width + ( $line_height * 1.5 ) );
		$site_width_wide = $content_width + $sidebar_width + $panel_width + ( $line_height * 2 );

		return array(
			'colors' => array(
				'site' => array(
					'links' => 'primary',
					'links-secondary' => 'text-secondary',
					'button' => 'button',
					'button-text' => 'white',
					'button-secondary' => 'secondary',
					'button-secondary-text' => 'white',
					'headline' => 'text-main',
					'headline-links' => 'text-main'
				),
				'header' => array(
					'bg_color' => 'background',
					'border_color' => 'border',
					'color' => 'text-main'
				),
				'menu' => array(
					'links' => 'text-main',
					'hover' => 'primary',
					'active' => 'primary'
				),
				'submenu' => array(
					'bg_color' => 'background',
					'links' => 'text-secondary',
					'hover' => 'primary'
				),
				'content' => array(
					'body_color' => 'surface',
					'bg_color' => 'background',
					'border_color' => 'border',
					'page_cover' => '#00000080'
				),
				'sidebar' => array(
					'bg_color' => '',
					'text' => 'text-secondary',
					'title' => 'text-main',
					'title_link' => 'text-main',
					'links' => 'text-secondary'
				),
				'footer' => array(
					'bg_color' => 'background',
					'border_color' => 'border',
					'text' => 'text-main',
					'title' => 'text-main',
					'title_link' => 'text-main',
					'links' => 'text-secondary'
				),
				'width' => array(
					'site' => $site_width,
					'site_wide' => $site_width_wide,
					'content_width' => $content_width,
					'panel_width' => $panel_width,
					'post' => $post_width,
					'sidebar' => $sidebar_width
				)
			),
			'typography' => array(
				'body' => array(
					'font_size' => array(
						'desktop' => $font_size,
						'mobile' => $font_size_mobile
					),
					'line_height' => array(
						'desktop' => $line_height,
						'mobile' => $line_height_mobile
					),
					'font_family' => 'system-ui, avenir next, avenir, segoe ui, helvetica neue, helvetica, Cantarell, Ubuntu, roboto, noto, arial, sans-serif'
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
					'font_size' => array(
						'desktop' => $h1['desktop'],
						'mobile' => $h1['mobile']
					),
					'line_height' => array(
						'desktop' => round( $h1['desktop'] * 1.3 ),
						'mobile' => round( $h1['mobile'] * 1.3 )
					)
				),
				'h2' => array(
					'font_size' => array(
						'desktop' => $h2['desktop'],
						'mobile' => $h2['mobile']
					),
					'line_height' => array(
						'desktop' => round( $h2['desktop'] * 1.35 ),
						'mobile' => round( $h2['mobile'] * 1.35 )
					)
				),
				'h3' => array(
					'font_size' => array(
						'desktop' => $h3['desktop'],
						'mobile' => $h3['mobile']
					),
					'line_height' => array(
						'desktop' => round( $h3['desktop'] * 1.4 ),
						'mobile' => round( $h3['mobile'] * 1.4 )
					)
				),
				'h4' => array(
					'font_size' => array(
						'desktop' => $h4['desktop'],
						'mobile' => $h4['mobile']
					),
					'line_height' => array(
						'desktop' => round( $h4['desktop'] * 1.4 ),
						'mobile' => round( $h4['mobile'] * 1.45 )
					)
				),
				'h5' => array(
					'font_size' => array(
						'desktop' => $h5['desktop'],
						'mobile' => $h5['mobile']
					),
					'line_height' => array(
						'desktop' => round( $h5['desktop'] * 1.55 ),
						'mobile' => round( $h5['mobile'] * 1.55 )
					)
				),
				'h6' => array(
					'font_size' => array(
						'desktop' => $h6['desktop'],
						'mobile' => $h6['mobile']
					),
					'line_height' => array(
						'desktop' => round( $h6['desktop'] * 1.5 ),
						'mobile' => round( $h6['mobile'] * 1.5 )
					)
				),
				'header' => array(
					'font_size' => array( 'desktop' => $font_size ),
					'line_height' => array( 'desktop' => $line_height )
				),
				'sidebar' => array(
					'font_size'=> array( 'desktop' => round( $font_size * 0.95 ) ),
					'line_height' => array( 'desktop' => round( $line_height * 0.9 ) )
				),
				'footer' => array(
					'font_size'=> array( 'desktop' => round( $font_size * 0.95 ) ),
					'line_height' => array( 'desktop' => round( $line_height * 0.9 ) )
				)
			),
			'logo' => array(
				'site_title' => array(
					'color' => 'text-main',
					'font_size' => array( 'desktop' => $site_title ),
					'line_height' => array( 'desktop' => round( $site_title * 1.1 ) )
				),
				'site_tagline' => array(
					'color' => 'text-secondary'
				)
			)
		);
	}

	/**
	 * Organize colors by admin settings.
	 *
	 * @since 6.0
	 */

	public function color_groups() {
		return array(
			'site' => array(
				'links' => array(
					'label' => __( 'Links', 'md' ),
					'inherit' => 'primary',
					'section' => 'text'
				),
				'links-secondary' => array(
					'label' => __( 'Links Secondary', 'md' ),
					'inherit' => 'text-secondary',
					'section' => 'text'
				),
				'headline' => array(
					'label' => __( 'Headline', 'md' ),
					'inherit' => 'text-main',
					'section' => 'text'
				),
				'headline-links' => array(
					'label' => __( 'Headline Links', 'md' ),
					'inherit' => 'text-main',
					'section' => 'text'
				),
				'button' => array(
					'label' => __( 'Background', 'md' ),
					'inherit' => 'button',
					'section' => 'button'
				),
				'button-text' => array(
					'label' => __( 'Text', 'md' ),
					'inherit' => 'white',
					'section' => 'button'
				),
				'button-secondary' => array(
					'label' => __( 'Background', 'md' ),
					'inherit' => 'secondary',
					'section' => 'button-secondary'
				),
				'button-secondary-text' => array(
					'label' => __( 'Text', 'md' ),
					'inherit' => 'white',
					'section' => 'button-secondary'
				)
			),
			'header' => array(
				'bg_color' => array(
					'label' => __( 'Background', 'md' ),
					'inherit' => 'background'
				),
				'border_color' => array(
					'label' => __( 'Border', 'md' ),
					'inherit' => 'border'
				),
				'color' => array(
					'label' => __( 'Text', 'md' ),
					'inherit' => 'text-main'
				)
			),
			'menu' => array(
				'links' => array(
					'label' => __( 'Links', 'md' ),
					'inherit' => 'text-main'
				),
				'hover' => array(
					'label' => __( 'Links Hover', 'md' ),
					'inherit' => 'primary'
				),
				'active' => array(
					'label' => __( 'Links Active', 'md' ),
					'inherit' => 'primary'
				)
			),
			'submenu' => array(
				'bg_color' => array(
					'label' => __( 'Background', 'md' ),
					'inherit' => 'background'
				),
				'links' => array(
					'label' => __( 'Links', 'md' ),
					'inherit' => 'text-secondary'
				),
				'hover' => array(
					'label' => __( 'Links Hover', 'md' ),
					'inherit' => 'primary'
				)
			),
			'content' => array(
				'body_color' => array(
					'label' => __( 'Content Body', 'md' ),
					'inherit' => 'surface'
				),
				'bg_color' => array(
					'label' => __( 'Content Box', 'md' ),
					'inherit' => 'background'
				),
				'border_color' => array(
					'label' => __( 'Border', 'md' ),
					'inherit' => 'border'
				),
				'page_cover' => array(
					'label' => __( 'Page Cover', 'md' )
				)
			),
			'sidebar' => array(
				'bg_color' => array(
					'label' => __( 'Background', 'md' )
				),
				'text' => array(
					'label' => __( 'Text', 'md' ),
					'inherit' => 'text-secondary'
				),
				'title' => array(
					'label' => __( 'Title', 'md' ),
					'inherit' => 'text-main'
				),
				'title_link' => array(
					'label' => __( 'Title Link', 'md' ),
					'inherit' => 'text-main'
				),
				'links' => array(
					'label' => __( 'Links', 'md' ),
					'inherit' => 'text-secondary'
				)
			),
			'footer' => array(
				'bg_color' => array(
					'label' => __( 'Background', 'md' ),
					'inherit' => 'background'
				),
				'border_color' => array(
					'label' => __( 'Border', 'md' ),
					'inherit' => 'border'
				),
				'text' => array(
					'label' => __( 'Text', 'md' ),
					'inherit' => 'text-main'
				),
				'title' => array(
					'label' => __( 'Title', 'md' ),
					'inherit' => 'text-main'
				),
				'title_link' => array(
					'label' => __( 'Title Link', 'md' ),
					'inherit' => 'text-main'
				),
				'links' => array(
					'label' => __( 'Links', 'md' ),
					'inherit' => 'text-secondary'
				)
			)
		);
	}

}
