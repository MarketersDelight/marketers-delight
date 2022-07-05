<?php
/**
 * This class holds data that makes up the Design and
 * Typography system.
 *
 * @since 4.8
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class md_design {

	/**
	 * Set default data about current design.
	 *
	 * @since 4.8
	 */

	public function defaults() {
		// golden ratio for base calculations
		$g = 1.618;
		// default font sizes for desktop $df, tablet $tf, mobile $mf
		$font_size = md_setting( array( 'typography', 'body', 'font_size' ) );
		$df = ! empty( $font_size['desktop'] ) ? $font_size['desktop'] : 17;
		$tf = round( $df * 0.95 );
		$mf = round( $df * 0.9 );
		// set final font size values based on options or defaults
		$desktop = ! empty( $font_size['desktop'] ) ? $font_size['desktop'] : $df;
		$tablet = ! empty( $font_size['tablet'] ) ? $font_size['tablet'] : $tf;
		$mobile = ! empty( $font_size['mobile'] ) ? $font_size['mobile'] : $mf;
		// set line height for spacing measurements
		$line_height = round( $desktop * $g );
		// set headings font sizes
		$h1 = array(
			'desktop' => round( $desktop * ( $g * 1.6 ) ),
			'tablet' => round( $desktop * ( $g * 1.6 ) ),
			'mobile' => round( $desktop * ( $g * 1.2 ) )
		);
		$h2 = array(
			'desktop' => round( $h1['desktop'] * 0.8 ),
			'tablet'  => round( $h1['tablet'] * 0.8 ),
			'mobile'  => round( $h1['mobile'] * 0.8 )
		);
		$h3 = array(
			'desktop' => round( $h1['desktop'] * 0.7 ),
			'tablet'  => round( $h1['tablet'] * 0.7 ),
			'mobile'  => round( $h1['mobile'] * 0.7 )
		);
		$h4 = array(
			'desktop' => round( $h1['desktop'] * 0.6 ),
			'tablet'  => round( $h1['tablet'] * 0.6 ),
			'mobile'  => round( $h1['mobile'] * 0.6 )
		);
		$h5 = array(
			'desktop' => round( $h1['desktop'] * 0.55 ),
			'tablet'  => round( $h1['tablet'] * 0.55 ),
			'mobile'  => round( $h1['mobile'] * 0.55 )
		);
		$h6 = array(
			'desktop' => round( $h1['desktop'] * 0.45 ),
			'tablet'  => round( $h1['desktop'] * 0.45 ),
			'mobile'  => round( $h1['desktop'] * 0.45 )
		);
		// site title
		$site_title = md_setting( array( 'typography', 'site_title', 'font_size', 'desktop' ) );
		$site_title = $site_title ? $site_title : $h3['desktop'];
		// calculate site widths
		$cw = md_setting( array( 'content', 'width', 'content' ) );
		$sw = md_setting( array( 'content', 'width', 'sidebar' ) );
		$layout_style = md_setting( array( 'content', 'style' ) );
		$post_width = ! empty( $cw ) ? $cw : round( 21 * $line_height );
		$content_width = $post_width;
		if ( $layout_style == '' )
			$content_width = $post_width + ( $line_height * 4 );
		$content_width = apply_filters( 'md_filter_css_content_width', $content_width, $post_width, $line_height );
		$sidebar_width = ! empty( $sw ) ? $sw : round( 12 * $line_height );
		$site_width = $content_width + $sidebar_width;
		$gutter_width = round( ( $site_width - $post_width ) / 2 );

		// Finally, return list of default values
		return array(
			'colors' => array(
				'site' => array(
					'bg_color' => '#F0F0F0',
					'primary' => '#AE2525',
					'secondary' => '#3E3E3E',
					'tertiary' => '#DDDDDD',
					'action' => '#EDF6FD',
					'accent' => '#FFFBCC',
					'text' => '#1E1E1E',
					'text-sec' => '#777777',
					'links' => '#3285B6',
					'button' => '#22A340',
					'button-text' => '#FFFFFF',
					'button-sec' => '#999999',
					'button-sec-text' => '#FFFFFF',
					'headline' => '#1E1E1E',
					'headline-links' => '#1E1E1E'
				),
				'header' => array(
					'bg_color' => '#FFFFFF',
					'color' => '#888888',
					'site_title' => '#1E1E1E',
					'site_tagline' => '#888888',
					'menu' => array(
						'links' => '#444444',
						'hover' => '#2E2E2E',
						'active' => '#AE2525'
					),
					'submenu' => array(
						'bg_color' => '#FFFFFF',
						'links' => '#444444',
						'hover' => '#2E2E2E'
					)
				),
				'main_menu' => array(
					'bg_color' => '#DDDDDD',
					'links' => '#1E1E1E',
					'links_hover' => '#777777',
					'active' => '#1E1E1E',
					'subtext' => '#444444',
					'sub_menu' => '#FFFFFF',
					'icons' => '#1E1E1E'
				),
				'content' => array(
					'bg_color' => '#FFFFFF',
					'border_color' => '#DDDDDD'
				),
				'sidebar' => array(
					'text' => '#888888',
					'title' => '#1E1E1E',
					'links' => '#444444'
				),
				'footer' => array(
					'bg_color' => '#1E1E1E',
					'text' => '#CCCCCC',
					'title' => '#FFFFFF',
					'links' => '#FFFBCC'
				)
			),
			'typography' => array(
				'body' => array(
					'font_size' => array(
						'desktop' => $df,
						'tablet' => $tf,
						'mobile' => $mf
					),
					'line_height' => array(
						'desktop' => $line_height,
						'tablet' => round( $tablet * $g ),
						'mobile' => round( $mobile * $g )
					),
					'font_family' => 'Helvetica Neue, Helvetica, Arial, sans-serif'
				),
				'huge' => array(
					'font_size' => array(
						'desktop' => round( $h1['desktop'] * 1.5 ),
						'tablet' => round( $h1['tablet'] * 1.5 ),
						'mobile' => round( $h1['mobile'] * 1.5 )
					),
					'line_height' => array(
						'desktop' => round( $h1['desktop'] * 1.85 ),
						'tablet' => round( $h1['tablet'] * 1.85 ),
						'mobile' => round( $h1['mobile'] * 1.85 )
					)
				),
				'h1' => array(
					'font_size' => array(
						'desktop' => $h1['desktop'],
						'tablet' => $h1['tablet'],
						'mobile' => $h1['mobile']
					),
					'line_height' => array(
						'desktop' => round( $h1['desktop'] * 1.35 ),
						'tablet' => round( $h1['tablet'] * 1.35 ),
						'mobile' => round( $h1['mobile'] * 1.35 )
					)
				),
				'h2' => array(
					'font_size' => array(
						'desktop' => $h2['desktop'],
						'tablet' => $h2['tablet'],
						'mobile' => $h2['mobile']
					),
					'line_height' => array(
						'desktop' => round( $h2['desktop'] * 1.4 ),
						'tablet' => round( $h2['tablet'] * 1.4 ),
						'mobile' => round( $h2['mobile'] * 1.4 )
					)
				),
				'h3' => array(
					'font_size' => array(
						'desktop' => $h3['desktop'],
						'tablet' => $h3['tablet'],
						'mobile' => $h3['mobile']
					),
					'line_height' => array(
						'desktop' => round( $h3['desktop'] * 1.45 ),
						'tablet' => round( $h3['tablet'] * 1.45 ),
						'mobile' => round( $h3['mobile'] * 1.45 )
					)
				),
				'h4' => array(
					'font_size' => array(
						'desktop' => $h4['desktop'],
						'tablet' => $h4['tablet'],
						'mobile' => $h4['mobile']
					),
					'line_height' => array(
						'desktop' => round( $h4['desktop'] * 1.5 ),
						'tablet' => round( $h4['tablet'] * 1.5 ),
						'mobile' => round( $h4['mobile'] * 1.5 )
					)
				),
				'h5' => array(
					'font_size' => array(
						'desktop' => $h5['desktop'],
						'tablet' => $h5['tablet'],
						'mobile' => $h5['mobile']
					),
					'line_height' => array(
						'desktop' => round( $h5['desktop'] * 1.5 ),
						'tablet' => round( $h5['tablet'] * 1.5 ),
						'mobile' => round( $h5['mobile'] * 1.5 )
					)
				),
				'h6' => array(
					'font_size' => array(
						'desktop' => $h6['desktop'],
						'tablet' => $h6['tablet'],
						'mobile' => $h6['mobile']
					),
					'line_height' => array(
						'desktop' => round( $h6['desktop'] * 1.45 ),
						'tablet' => round( $h6['tablet'] * 1.45 ),
						'mobile' => round( $h6['mobile'] * 1.45 )
					)
				),
				'header' => array(
					'font_size' => array(
						'desktop' => $df,
						'tablet' => $tf,
						'mobile' => $mf
					),
					'line_height' => array(
						'desktop' => $line_height,
						'tablet' => round( $tablet * $g ),
						'mobile' => round( $mobile * $g )
					)
				),
				'site_title' => array(
					'font_size' => array(
						'desktop' => $site_title,
						'tablet'  => round( $site_title * 0.7 ),
						'mobile'  => round( $site_title * 0.7 )
					),
					'line_height' => array(
						'desktop' => round( $site_title * 1.45 ),
						'tablet' => round( $site_title * 1.45 ),
						'mobile' => round( $site_title * 1.3 )
					)
				),
				'site_tagline' => array(
					'font_size' => array(
						'desktop' => $df,
						'tablet'  => $tf,
						'mobile'  => $mf
					),
					'line_height' => array(
						'desktop' => $line_height,
						'tablet' => round( $tablet * $g ),
						'mobile' => round( $mobile * $g )
					)
				),
				'sidebar' => array(
					'font_size' => array(
						'desktop' => round( $df * 0.95 ),
						'tablet' => round( $df * 0.9 ),
						'mobile' => round( $df * 0.85 )
					),
					'line_height' => array(
						'desktop' => round( $line_height * 0.9 ),
						'tablet' => round( $line_height * 0.85 ),
						'mobile' => round( $line_height * 0.8 )
					)
				),
				'sidebar_title' => array(
					'font_size' => array(
						'desktop' => $h5['desktop'],
						'tablet' => $h5['tablet'],
						'mobile' => $h5['mobile']
					),
					'line_height' => array(
						'desktop' => round( $h5['desktop'] * 1.45 ),
						'tablet' => round( $h5['tablet'] * 1.45 ),
						'mobile' => round( $h5['mobile'] * 1.45 )
					)
				),
				'footer' => array(
					'font_size' => array(
						'desktop' => round( $df * 0.95 ),
						'tablet' => round( $df * 0.9 ),
						'mobile' => round( $df * 0.85 )
					),
					'line_height' => array(
						'desktop' => round( $line_height * 0.9 ),
						'tablet' => round( $line_height * 0.85 ),
						'mobile' => round( $line_height * 0.8 )
					)
				),
				'footer_title' => array(
					'font_size' => array(
						'desktop' => $h5['desktop'],
						'tablet' => $h5['tablet'],
						'mobile' => $h5['mobile']
					),
					'line_height' => array(
						'desktop' => round( $h5['desktop'] * 1.45 ),
						'tablet' => round( $h5['tablet'] * 1.45 ),
						'mobile' => round( $h5['mobile'] * 1.45 )
					)
				)
			),
			'header' => array(
				'spacing_top' => array(
					'desktop' => round( $line_height / 3 ),
					'tablet' => round( $line_height / 3 ),
					'mobile' => round( $line_height / 3 )
				),
				'spacing_bottom' => array(
					'desktop' => round( $line_height / 3 ),
					'tablet' => round( $line_height / 3 ),
					'mobile' => round( $line_height / 3 )
				),
				'menu' => array(
					'spacing_tb' => round( $line_height / 2 ),
					'spacing_lr' => round( $line_height / 2 )
				)
			),
			'content' => array(
				'style' => '',
				'featured_image' => array(
					'cover_color' => 'rgba(0, 0, 0, 0.5)'
				),
				'width' => array(
					'site' => $site_width,
					'content_width' => $content_width,
					'post' => $post_width,
					'sidebar' => $sidebar_width
				)
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
		$defaults = $this->defaults();
		$design = array();
		$design['colors'] = md_setting( 'colors' );
		$design['typography'] = md_setting( 'typography' );
		$design['header'] = md_setting( 'header' );
		$design['content'] = md_setting( 'content' );
		return array_replace_recursive( $defaults, $design );
	}

}