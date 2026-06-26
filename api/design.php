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
			'action' => '#EDF6FD',
			'accent' => '#FFFBCC',
			'border' => '#CCCCCC',
			'muted' => '#777777',
			'text-main' => '#1E1E1E',
			'text-secondary' => '#777777'
		);
	}

	/**
	 * Return the live palette: hardcoded base overridden by any user Branding saves.
	 * colors.main overrides existing keys; colors.palette adds custom ones.
	 *
	 * @since 6.0
	 */

	public function active_palette() {
		$palette = $this->palette();
		$colors = md_setting( 'colors' );

		if ( ! empty( $colors['palette'] ) )
			foreach ( $colors['palette'] as $key => $data )
				if ( ! empty( $data['hex'] ) )
					$palette[$key] = $data['hex'];

		if ( ! empty( $colors['custom'] ) )
			foreach ( $colors['custom'] as $data )
				if ( ! empty( $data['name'] ) && ! empty( $data['hex'] ) ) {
					$key = sanitize_key( $data['name'] );
					$palette[$key] = $data['hex'];
				}

		return $palette;
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

		$values['colors'] = $this->resolve_colors( $values['colors'] );

		return $values;
	}

	/**
	 * Colors can be selected as hex codes or inherited text strings that pick
	 * pre-defined color groups - this resolves them into hex codes for use in CSS.
	 *
	 * @since 6.0
	 */

	private function resolve_colors( $colors =array() ) {
		$palette = apply_filters( 'md_color_palette', array() );

		foreach ( $colors as $group => $fields ) {
			if ( ! is_array( $fields ) )
				continue;

			foreach ( $fields as $key => $value ) {
				if ( ! is_array( $value ) || count( $value ) !== 1 )
					continue;

				if ( ! empty( $value['inherit'] ) && isset( $palette[$value['inherit']] ) )
					$colors[$group][$key] = $palette[$value['inherit']];
				elseif ( isset( $value['hex'] ) )
					$colors[$group][$key] = $value['hex'];
				else
					$colors[$group][$key] = '';
			}
		}

		return $colors;
	}

	/**
	 * Register color palette in Blocks Editor.
	 *
	 * since 4.9
	 */

	public function editor_colors() {
		$colors = array();
		$values = $this->values();
		$keys = array(
			'primary' => __( 'Primary', 'md' ),
			'secondary' => __( 'Secondary', 'md' ),
			'tertiary' => __( 'Tertiary', 'md' ),
			'action' => __( 'Action', 'md' ),
			'accent' => __( 'Accent', 'md' ),
			'text-main' => __( 'Text', 'md' ),
			'text-secondary' => __( 'Secondary Text', 'md' ),
			'links' => __( 'Links', 'md' ),
			'button' => __( 'Button', 'md' ),
			'button-secondary' => __( 'Button Secondary', 'md' ),
			'white' => array(
				'name' => __( 'White', 'md' ),
				'color' => '#FFFFFF'
			)
		);

		foreach ( $keys as $key => $group ) {
			if ( is_array( $group ) ) {
				$color = $group['color'];
				$name = $group['name'];
			}
			else {
				$color = $values['colors']['site'][$key];
				$name = $group;
			}
			$colors[] = array(
				'name' => $name,
				'slug' => $key,
				'color' => $color
			);
		}

		return $colors;
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

		// colors

		$colors = $this->palette();
		$primary_color = md_setting( array( 'colors', 'site', 'primary' ), $colors['primary'] );
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
					'bg_color' => $colors['background'],
					'primary' => $colors['primary'],
					'secondary' => $colors['secondary'],
					'tertiary' => $colors['tertiary'],
					'action' => $colors['action'],
					'accent' => $colors['accent'],
					'text-main' => $colors['text-main'],
					'text-secondary' => $colors['text-secondary'],
					'links' => $primary_color,
					'links-secondary' => $colors['muted'],
					'button' => '#22A340',
					'button-text' => '#FFFFFF',
					'button-secondary' => '#999999',
					'button-secondary-text' => '#FFFFFF',
					'headline' => $colors['text-main'],
					'headline-links' => $colors['text-main']
				),
				'header' => array(
					'bg_color' => '',
					'border_color' => $colors['border'],
					'color' => $colors['text-main']
				),
				'menu' => array(
					'links' => $colors['text-secondary'],
					'hover' => $primary_color,
					'active' => $primary_color
				),
				'submenu' => array(
					'bg_color' => '#FFFFFF',
					'links' => $colors['text-secondary']
				),
				'content' => array(
					'body_color' => $colors['surface'],
					'bg_color' => '#FFFFFF',
					'border_color' => $colors['border'],
					'page_cover' => 'rgba(0, 0, 0, 0.5)'
				),
				'sidebar' => array(
					'text' => $colors['text-secondary'],
					'title' => $colors['text-main'],
					'title_link' => $colors['text-main'],
					'links' => $colors['text-secondary']
				),
				'footer' => array(
					'bg_color' => '',
					'border_color' => $colors['border'],
					'text' => $colors['text-main'],
					'title' => $colors['text-main'],
					'title_link' => $colors['text-main'],
					'links' => $colors['muted']
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
					'color' => $colors['text-main'],
					'font_size' => array(
						'desktop' => $site_title
					),
					'line_height' => array(
						'desktop' => round( $site_title * 1.1 )
					)
				),
				'site_tagline' => array(
					'color' => $colors['muted']
				)
			)
		);
	}

}