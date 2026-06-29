<?php
/**
 * This class holds data that makes up the Design and Typography system.
 *
 * @since 4.8
 */

class md_design {

	/**
	 * Run actions on class init.
	 *
	 * @since 6.0
	 */

	public function __construct() {
		add_filter( 'md_color_palette', array( $this, 'active_palette' ) );
	}

	/**
	 * Register color palette in Blocks Editor.
	 *
	 * since 4.9
	 */

	public function editor_colors() {
		$colors = array();

		foreach ( $this->active_palette() as $key => $color )
			$colors[] = array(
				'name' => $color['name'],
				'slug' => $key,
				'color' => $color['hex']
			);

		return $colors;
	}

	/**
	 * Compare hard-set default values to user admin options and
	 * return a complete list of design values to use in a
	 * dynamic CSS file.
	 *
	 * @since 4.8
	 */

	public function values() {
		$values = array_replace_recursive( $this->defaults(), array(
			'colors' => md_setting( 'colors' ),
			'typography' => md_setting( 'typography' ),
			'header' => md_setting( 'header' ),
			'logo' => md_setting( 'logo' ),
			'sidebar' => md_setting( 'sidebars' )
		) );

		// A fun way to resolve colors into usable formats

		$palette = $this->active_palette();

		array_walk_recursive( $values, function( &$value ) use ( $palette ) {
			if ( is_string( $value ) && isset( $palette[$value] ) )
				$value = $palette[$value]['hex'];
		} );

		foreach ( $palette as $key => $color )
			$values['colors']['palette'][$key] = $color['hex'];

		// Return values for use in CSS templates

		return $values;
	}

	/**
	 * Return the live palette: hardcoded base overridden by any user Branding saves.
	 * colors.main overrides existing keys; colors.palette adds custom ones.
	 *
	 * @since 6.0
	 */

	public function active_palette() {
		$palette = array();

		foreach ( $this->palette() as $key => $hex )
			$palette[$key] = array(
				'hex' => $hex,
				'name' => ucwords( str_replace( '-', ' ', $key ) )
			);

		$colors = md_setting( 'colors' );

		if ( ! empty( $colors['palette'] ) )
			foreach ( $colors['palette'] as $key => $data )
				if ( ! empty( $data['hex'] ) && isset( $palette[$key] ) )
					$palette[$key]['hex'] = $data['hex'];

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
	 * The main color palette of the design.
	 *
	 * @since 6.0
	 */

	public function palette() {
		return array(
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
	}

	/**
	 * Single source of truth for all user-configurable color fields.
	 * Drives admin field registration and admin UI labels.
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

	/**
	 * Set default data about current design.
	 *
	 * @since 4.8
	 */

	public function defaults() {
		$g = 1.618;
		$font_size = md_setting( array( 'typography', 'body', 'font_size', 'desktop' ), 19 );
		$mobile = md_setting( array( 'typography', 'body', 'font_size', 'mobile' ), round( $font_size * 0.9 ) );
		$line_height = round( $font_size * $g );
		$h1 = array(
			'desktop' => md_setting( array( 'typography', 'h1', 'font_size', 'desktop' ), round( $font_size * ( $g * 1.4 ) ) ),
			'mobile' => md_setting( array( 'typography', 'h1', 'font_size', 'mobile' ), round( $font_size * ( $g * 1.2 ) ) )
		);
		$h2 = array(
			'desktop' => round( $h1['desktop'] * 0.85 ),
			'mobile'  => round( $h1['mobile'] * 0.85 )
		);
		$h3 = array(
			'desktop' => round( $h1['desktop'] * 0.7 ),
			'mobile'  => round( $h1['mobile'] * 0.7 )
		);
		$h4 = array(
			'desktop' => round( $h1['desktop'] * 0.6 ),
			'mobile'  => round( $h1['mobile'] * 0.6 )
		);
		$h5 = array(
			'desktop' => round( $h1['desktop'] * 0.5 )
		);
		$h6 = array(
			'desktop' => round( $h1['desktop'] * 0.45 )
		);

		$site_title = md_setting( array( 'logo', 'site_title', 'font_size', 'desktop' ), $h4['desktop'] );

		// calculate layout widths

		$design = md_setting( array( 'colors', 'design' ) );
		$cw = md_setting( array( 'colors', 'width', 'content' ) );
		$sw = md_setting( array( 'colors', 'width', 'sidebar' ) );

		$post_width = ! empty( $cw ) ? $cw : round( 21 * $line_height );
		$gutter = ! $design ? ( $line_height + round( $line_height / 2 ) ) * 2 : 0;
		$content_width = $post_width + $gutter;
		$content_width = apply_filters( 'md_filter_css_content_width', $content_width, $post_width, $line_height );
		$sidebar_width = ! empty( $sw ) ? $sw : round( 12 * $line_height );
		$panel_width = round( 10 * $line_height );

		$site_width = round( $content_width + $sidebar_width + ( $line_height * 1.5 ) ); #add $line_height to account for gap
		$site_width_wide = $content_width + $sidebar_width + $panel_width + ( $line_height * 2 );

		// Finally, return list of default values

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
						'mobile' => $mobile
					),
					'line_height' => array(
						'desktop' => $line_height,
						'mobile' => round( $mobile * $g )
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
						'desktop' => $h5['desktop']
					),
					'line_height' => array(
						'desktop' => round( $h5['desktop'] * 1.55 )
					)
				),
				'h6' => array(
					'font_size' => array(
						'desktop' => $h6['desktop']
					),
					'line_height' => array(
						'desktop' => round( $h6['desktop'] * 1.5 )
					)
				),
				'header' => array(
					'font_size' => array(
						'desktop' => $font_size
					),
					'line_height' => array(
						'desktop' => $line_height
					)
				),
				'sidebar' => array(
					'font_size' => array(
						'desktop' => round( $font_size * 0.95 )
					),
					'line_height' => array(
						'desktop' => round( $line_height * 0.9 )
					)
				),
				'footer' => array(
					'font_size' => array(
						'desktop' => round( $font_size * 0.95 )
					),
					'line_height' => array(
						'desktop' => round( $line_height * 0.9 )
					)
				)
			),
			'logo' => array(
				'site_title' => array(
					'color' => 'text-main',
					'font_size' => array(
						'desktop' => $site_title
					),
					'line_height' => array(
						'desktop' => round( $site_title * 1.1 )
					)
				),
				'site_tagline' => array(
					'color' => 'text-secondary'
				)
			)
		);
	}

}