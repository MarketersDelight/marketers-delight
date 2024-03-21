<?php
/**
 * This class holds data that makes up the Design and
 * Typography system.
 *
 * @since 4.8
 */

class md_design {

	/**
	 * Set default data about current design.
	 *
	 * @since 4.8
	 */

	public function defaults() {
		$g = 1.618;
		$font_size = md_setting( array( 'typography', 'body', 'font_size', 'desktop' ), 18 );
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
			'desktop' => round( $h1['desktop'] * 0.4 )
		);
		// colors
		$colors = array(
			'primary' => '#AE2525',
			'secondary' => '#2E2E2E',
			'text' => '#1E1E1E',
			'subtext' => '#777777',
			'border' => '#CCCCCC'
		);
		$primary_color = md_setting( array( 'colors', 'site', 'primary' ), $colors['primary'] );
		$secondary_color = md_setting( array( 'colors', 'site', 'secondary' ), $colors['secondary'] );
		$site_title = md_setting( array( 'logo', 'site_title', 'font_size', 'desktop' ), $h4['desktop'] );
		// calculate site widths
		$cw = md_setting( array( 'colors', 'width', 'content' ) );
		$sw = md_setting( array( 'colors', 'width', 'sidebar' ) );
		$layout_style = md_setting( array( 'colors', 'style' ) );
		$post_width = ! empty( $cw ) ? $cw : round( 21 * $line_height );
		$content_width = $post_width;
		if ( $layout_style == '' )
			$content_width = $post_width + ( ( $line_height + round( $line_height / 2 ) ) * 2 );
		$content_width = apply_filters( 'md_filter_css_content_width', $content_width, $post_width, $line_height );
		$sidebar_width = ! empty( $sw ) ? $sw : round( 12 * $line_height );
		$site_width = $content_width + $sidebar_width;
		$site_width_full = $site_width + ( $site_width / 2 );
		$gutter_width = round( ( $site_width - $post_width ) / 2 );

		// Finally, return list of default values
		return array(
			'colors' => array(
				'site' => array(
					'bg_color' => '#F0F0F0',
					'primary' => $colors['primary'],
					'secondary' => $colors['secondary'],
					'tertiary' => '#DDDDDD',
					'action' => '#EDF6FD',
					'accent' => '#FFFBCC',
					'text' => $colors['text'],
					'text-sec' => $colors['subtext'],
					'links' => $primary_color,
					'links_sec' => $colors['subtext'],
					'button' => '#22A340',
					'button-text' => '#FFFFFF',
					'button-sec' => '#999999',
					'button-sec-text' => '#FFFFFF',
					'headline' => $colors['text'],
					'headline-links' => $colors['text']
				),
				'content' => array(
					'bg_color' => '#FFFFFF',
					'border_color' => $colors['border']
				),
				'sidebar' => array(
					'text' => '#777777',
					'title' => $colors['text'],
					'title_link' => $colors['text'],
					'links' => '#444444',
				),
				'footer' => array(
					'bg_color' => '#FFFFFF',
					'border_color' => $colors['border'],
					'text' => $colors['text'],
					'title' => $colors['text'],
					'title_link' => $colors['text'],
					'links' => $colors['subtext']
				),
				'width' => array(
					'site' => $site_width,
					'site_full' => $site_width_full,
					'content_width' => $content_width,
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
						'desktop' => round( $h6['desktop'] * 1.4 )
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
					'color' => $colors['text'],
					'font_size' => array(
						'desktop' => $site_title
					),
					'line_height' => array(
						'desktop' => round( $site_title * 1.1 )
					)
				),
				'site_tagline' => array(
					'color' => $colors['subtext']
				)
			),
			'header' => array(
				'font_size' => array(
					'desktop' => $font_size
				),
				'line_height' => array(
					'desktop' => $line_height
				),
				'bg_color' => '#FFFFFF',
				'border_color' => $colors['border'],
				'color' => $colors['text'],
				'menu' => array(
					'links' => '#444444',
					'hover' => $primary_color,
					'active' => $primary_color
				),
				'submenu' => array(
					'bg_color' => '#FFFFFF',
					'links' => '#444444'
				)
			),
			'content' => array(
				'style' => ''
			)
		);
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
			'white' => array(
				'name' => __( 'White', 'md' ),
				'color' => '#FFFFFF'
			),
			'text' => __( 'Text', 'md' ),
			'text-sec' => __( 'Secondary Text', 'md' ),
			'links' => __( 'Links', 'md' ),
			'button' => __( 'Button', 'md' ),
			'button-sec' => __( 'Button Secondary', 'md' )
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
	 * Compare hard-set default values to user options and
	 * return a complete list of design values to use in a
	 * dynamic CSS file.
	 *
	 * @since 4.8
	 */

	public function values() {
		$design = array();
		$defaults = $this->defaults();
		$design['colors'] = md_setting( 'colors' );
		$design['typography'] = md_setting( 'typography' );
		$design['header'] = md_setting( 'header' );
		$design['logo'] = md_setting( 'logo' );
		$design['sidebar'] = md_setting( 'sidebars' );

		return array_replace_recursive( $defaults, $design );
	}

}
