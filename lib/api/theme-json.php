<?php
/**
 * Dynamic theme.json generator that creates block editor configuration
 * based on Marketers Delight settings.
 *
 * @since 6.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Generate theme.json data from MD settings.
 *
 * @since 6.0.0
 * @return array Theme.json compatible data structure
 */
function md_generate_theme_json_data() {
	$design = new md_design();
	$values = $design->values();

	$colors = $values['colors']['site'] ?? [];
	$content_colors = $values['colors']['content'] ?? [];
	$typography = $values['typography'];
	$content = $values['content'] ?? [];

	// Content widths (matching attributes.php)
	$post_width = $values['colors']['width']['post'] ?? 850;
	$site_width = $values['colors']['width']['site'] ?? 1024;
	$content_width = $values['colors']['width']['content_width'] ?? 1024;
	$sidebar_width = $values['colors']['width']['sidebar'] ?? 300;

	// Typography values (matching attributes.php calculations)
	$body_font_size = $typography['body']['font_size']['desktop'] ?? 18;
	$body_line_height = $typography['body']['line_height']['desktop'] ?? 30;
	$body_font_family = $typography['body']['font_family'] ?? 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
	$heading_font_family = ! empty( $typography['h1']['font_family'] ) ? $typography['h1']['font_family'] : $body_font_family;

	// Spacing calculations (matching css.php logic)
	$single_px = $body_line_height;
	$small_px = round( $single_px / 6 );
	$third_px = round( $single_px / 3 );
	$half_px = round( $single_px / 2 );
	$mid_px = $single_px + $half_px;
	$double_px = round( $single_px * 2 );
	$triple_px = round( $single_px * 3 );
	$quad_px = round( $single_px * 4 );

	// Convert to rem for theme.json
	$single_rem = round( $single_px / 16, 4 ) . 'rem';
	$small_rem = round( $small_px / 16, 4 ) . 'rem';
	$third_rem = round( $third_px / 16, 4 ) . 'rem';
	$half_rem = round( $half_px / 16, 4 ) . 'rem';
	$mid_rem = round( $mid_px / 16, 4 ) . 'rem';
	$double_rem = round( $double_px / 16, 4 ) . 'rem';
	$triple_rem = round( $triple_px / 16, 4 ) . 'rem';
	$quad_rem = round( $quad_px / 16, 4 ) . 'rem';

	// Font size calculations (matching attributes.php)
	$fs_base = round( $body_font_size / 16, 4 );
	$fs_xs = round( ( $body_font_size * 0.75 ) / 16, 4 );
	$fs_s = round( ( $body_font_size * 0.875 ) / 16, 4 );
	$fs_m = round( ( $typography['h5']['font_size']['desktop'] ?? 20 ) / 16, 4 );
	$fs_l = round( ( $typography['h4']['font_size']['desktop'] ?? 22 ) / 16, 4 );
	$fs_xl = round( ( $typography['h3']['font_size']['desktop'] ?? 26 ) / 16, 4 );
	$fs_2xl = round( ( $typography['h2']['font_size']['desktop'] ?? 32 ) / 16, 4 );
	$fs_3xl = round( ( $typography['h1']['font_size']['desktop'] ?? 42 ) / 16, 4 );
	$fs_4xl = round( ( $typography['huge']['font_size']['desktop'] ?? 56 ) / 16, 4 );

	// Line height calculation
	$lh_base = round( $body_line_height / $body_font_size, 3 );

	// Build color palette from MD settings (matching attributes.php CSS variables)
	$color_palette = [
		[ 'name' => __( 'Primary', 'md' ), 'slug' => 'primary', 'color' => $colors['primary'] ?? '#C0392B' ],
		[ 'name' => __( 'Secondary', 'md' ), 'slug' => 'secondary', 'color' => $colors['secondary'] ?? '#262A5D' ],
		[ 'name' => __( 'Tertiary', 'md' ), 'slug' => 'tertiary', 'color' => $colors['tertiary'] ?? '#001e12' ],
		[ 'name' => __( 'White', 'md' ), 'slug' => 'white', 'color' => '#ffffff' ],
		[ 'name' => __( 'Text Secondary', 'md' ), 'slug' => 'text-sec', 'color' => $colors['text-sec'] ?? '#555555' ],
		[ 'name' => __( 'Headline', 'md' ), 'slug' => 'headline', 'color' => $colors['headline'] ?? '#212121' ],
		[ 'name' => __( 'Button', 'md' ), 'slug' => 'button', 'color' => $colors['button'] ?? '#22A340' ],
		[ 'name' => __( 'Background', 'md' ), 'slug' => 'background', 'color' => $colors['bg_color'] ?? '#F0F0F0' ],
		[ 'name' => __( 'Off White', 'md' ), 'slug' => 'offwhite', 'color' => '#F6F6F6' ],
		[ 'name' => __( 'Bordered', 'md' ), 'slug' => 'bordered', 'color' => '#dddddd' ],
		[ 'name' => __( 'Electric Indigo', 'md' ), 'slug' => 'electric-indigo', 'color' => '#6366F1' ],
		[ 'name' => __( 'Cyber Lime', 'md' ), 'slug' => 'cyber-lime', 'color' => '#84CC16' ],
		[ 'name' => __( 'Hot Coral', 'md' ), 'slug' => 'hot-coral', 'color' => '#FF6B6B' ],
		[ 'name' => __( 'Rose', 'md' ), 'slug' => 'rose', 'color' => '#F43F5E' ],
		[ 'name' => __( 'Amber', 'md' ), 'slug' => 'amber', 'color' => '#F59E0B' ],
		[ 'name' => __( 'Teal', 'md' ), 'slug' => 'teal', 'color' => '#14B8A6' ],
		[ 'name' => __( 'Sky', 'md' ), 'slug' => 'sky', 'color' => '#0EA5E9' ],
		[ 'name' => __( 'Violet', 'md' ), 'slug' => 'violet', 'color' => '#8B5CF6' ],

		// Dark mode / modern neutrals
		[ 'name' => __( 'Slate', 'md' ), 'slug' => 'slate', 'color' => '#64748B' ],
		[ 'name' => __( 'Midnight', 'md' ), 'slug' => 'midnight', 'color' => '#1E293B' ],
		[ 'name' => __( 'Charcoal', 'md' ), 'slug' => 'charcoal', 'color' => '#18181B' ],

		// Soft / glassmorphism-friendly
		[ 'name' => __( 'Lavender', 'md' ), 'slug' => 'lavender', 'color' => '#A78BFA' ],
		[ 'name' => __( 'Mint', 'md' ), 'slug' => 'mint', 'color' => '#6EE7B7' ],
		[ 'name' => __( 'Peach', 'md' ), 'slug' => 'peach', 'color' => '#FBBF24' ],
		[ 'name' => __( 'Blush', 'md' ), 'slug' => 'blush', 'color' => '#FDA4AF' ],
	];

	// Font families (matching attributes.php)
	$font_families = [
		[
			'fontFamily' => $body_font_family,
			'slug' => 'body',
			'name' => __( 'Body', 'md' )
		],
		[
			'fontFamily' => $heading_font_family,
			'slug' => 'heading',
			'name' => __( 'Heading', 'md' )
		],
		[
			'fontFamily' => "Georgia, Cambria, 'Times New Roman', Times, serif",
			'slug' => 'serif',
			'name' => __( 'Serif', 'md' )
		],
		[
			'fontFamily' => "Consolas, Monaco, Menlo, Courier, Verdana, sans-serif",
			'slug' => 'mono',
			'name' => __( 'Monospace', 'md' )
		]
	];

	// Font sizes using dynamic values from typography settings
	$font_sizes = [
		[ 'size' => $fs_xs . 'rem', 'slug' => 'extra-small', 'name' => __( 'Extra Small', 'md' ) ],
		[ 'size' => $fs_s . 'rem', 'slug' => 'small', 'name' => __( 'Small', 'md' ) ],
		[ 'size' => $fs_base . 'rem', 'slug' => 'base', 'name' => __( 'Base', 'md' ) ],
		[ 'size' => $fs_m . 'rem', 'slug' => 'medium', 'name' => __( 'Medium', 'md' ) ],
		[ 'size' => $fs_l . 'rem', 'slug' => 'large', 'name' => __( 'Large', 'md' ) ],
		[ 'size' => $fs_xl . 'rem', 'slug' => 'x-large', 'name' => __( 'Extra Large', 'md' ) ],
		[ 'size' => $fs_2xl . 'rem', 'slug' => 'xx-large', 'name' => __( 'Super Large', 'md' ) ],
		[ 'size' => $fs_3xl . 'rem', 'slug' => 'xxx-large', 'name' => __( 'Triple Large', 'md' ) ],
		[ 'size' => $fs_4xl . 'rem', 'slug' => 'gigantic', 'name' => __( 'Gigantic', 'md' ) ],
	];

	// Spacing sizes using dynamic values from line height (matching attributes.php)
	$spacing_sizes = [
		[ 'name' => __( 'Small', 'md' ), 'size' => $small_rem, 'slug' => 'xs' ],
		[ 'name' => __( 'Third', 'md' ), 'size' => $third_rem, 'slug' => 'third' ],
		[ 'name' => __( 'Half', 'md' ), 'size' => $half_rem, 'slug' => 's' ],
		[ 'name' => __( 'Single', 'md' ), 'size' => $single_rem, 'slug' => 'm' ],
		[ 'name' => __( 'Mid', 'md' ), 'size' => $mid_rem, 'slug' => 'l' ],
		[ 'name' => __( 'Double', 'md' ), 'size' => $double_rem, 'slug' => 'xl' ],
		[ 'name' => __( 'Triple', 'md' ), 'size' => $triple_rem, 'slug' => '2xl' ],
		[ 'name' => __( 'Quad', 'md' ), 'size' => $quad_rem, 'slug' => '3xl' ],
	];

	// Build theme.json structure
	$theme_json = [
		'$schema' => 'https://schemas.wp.org/trunk/theme.json',
		'version' => 3,
		'settings' => [
			'appearanceTools' => true,
			'border' => [
				'color' => true,
				'radius' => true,
				'style' => true,
				'width' => true
			],
			'color' => [
				'custom' => true,
				'customGradient' => true,
				'defaultDuotone' => false,
				'defaultGradients' => false,
				'defaultPalette' => false,
				'palette' => $color_palette,
				'gradients' => md_get_theme_gradients( $colors )
			],
			'layout' => [
				'contentSize' => $post_width . 'px',
				'wideSize' => $site_width . 'px'
			],
			'shadow' => [
				'defaultPresets' => false,
				'presets' => md_get_shadow_presets()
			],
			'spacing' => [
				'defaultSpacingSizes' => false,
				'blockGap' => true,
				'margin' => true,
				'padding' => true,
				'units' => [ '%', 'px', 'em', 'rem', 'vh', 'vw' ],
				'spacingSizes' => $spacing_sizes
			],
			'typography' => [
				'customFontSize' => true,
				'lineHeight' => true,
				'defaultFontSizes' => false,
				'fluid' => true,
				'fontFamilies' => $font_families,
				'fontSizes' => $font_sizes
			],
			'useRootPaddingAwareAlignments' => true
		],
		'styles' => [
			'spacing' => [
				'blockGap' => '1.2rem'
			],
			'elements' => [
				'link' => [
					'color' => [
						'text' => 'var:preset|color|primary'
					],
					':hover' => [
						'color' => [
							'text' => 'var:preset|color|secondary'
						]
					],
					':focus' => [
						'outline' => [
							'color' => 'var:preset|color|primary',
							'offset' => '2px',
							'style' => 'solid',
							'width' => '2px'
						]
					]
				],
				'caption' => [
					'typography' => [
						'fontSize' => 'var:preset|font-size|small',
						'lineHeight' => '1.4'
					],
					'color' => [
						'text' => 'var:preset|color|text-sec'
					]
				]
			],
			'blocks' => md_get_block_styles()
		]
	];

	return apply_filters( 'md_theme_json_data', $theme_json, $values );
}

/**
 * Get gradient presets based on MD colors.
 *
 * @since 6.0.0
 * @param array $colors MD color settings
 * @return array Gradient presets
 */
function md_get_theme_gradients( $colors ) {
	$primary = $colors['primary'] ?? '#C0392B';
	$secondary = $colors['secondary'] ?? '#262A5D';

	return [
		[
			'slug' => 'primary-to-secondary',
			'gradient' => "linear-gradient(135deg, {$primary}, {$secondary})",
			'name' => __( 'Primary to Secondary', 'md' )
		],
		[
			'slug' => 'secondary-gradient',
			'gradient' => "linear-gradient(135deg, {$secondary}, {$primary})",
			'name' => __( 'Secondary Gradient', 'md' )
		],
		[
			'slug' => 'dark-overlay',
			'gradient' => 'linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.7) 100%)',
			'name' => __( 'Dark Overlay', 'md' )
		],
		[
			'slug' => 'light-overlay',
			'gradient' => 'linear-gradient(180deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.9) 100%)',
			'name' => __( 'Light Overlay', 'md' )
		],
		// Modern vibrant gradients
		[
			'slug' => 'aurora',
			'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%)',
			'name' => __( 'Aurora', 'md' )
		],
		[
			'slug' => 'sunset-glow',
			'gradient' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
			'name' => __( 'Sunset Glow', 'md' )
		],
		[
			'slug' => 'ocean-breeze',
			'gradient' => 'linear-gradient(135deg, #0ea5e9 0%, #14b8a6 100%)',
			'name' => __( 'Ocean Breeze', 'md' )
		],
		[
			'slug' => 'electric-violet',
			'gradient' => 'linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%)',
			'name' => __( 'Electric Violet', 'md' )
		],
		[
			'slug' => 'cyber-neon',
			'gradient' => 'linear-gradient(135deg, #00f5a0 0%, #00d9f5 100%)',
			'name' => __( 'Cyber Neon', 'md' )
		],
		[
			'slug' => 'fire',
			'gradient' => 'linear-gradient(135deg, #f97316 0%, #ef4444 50%, #dc2626 100%)',
			'name' => __( 'Fire', 'md' )
		],

		// Soft/pastel gradients (great for backgrounds)
		[
			'slug' => 'soft-peach',
			'gradient' => 'linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)',
			'name' => __( 'Soft Peach', 'md' )
		],
		[
			'slug' => 'lavender-mist',
			'gradient' => 'linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%)',
			'name' => __( 'Lavender Mist', 'md' )
		],
		[
			'slug' => 'mint-cream',
			'gradient' => 'linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%)',
			'name' => __( 'Mint Cream', 'md' )
		],
		[
			'slug' => 'cotton-candy',
			'gradient' => 'linear-gradient(135deg, #fbc2eb 0%, #a6c1ee 100%)',
			'name' => __( 'Cotton Candy', 'md' )
		],

		// Dark/professional gradients
		[
			'slug' => 'midnight-city',
			'gradient' => 'linear-gradient(135deg, #1e293b 0%, #0f172a 100%)',
			'name' => __( 'Midnight City', 'md' )
		],
		[
			'slug' => 'deep-space',
			'gradient' => 'linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%)',
			'name' => __( 'Deep Space', 'md' )
		],
		[
			'slug' => 'charcoal-fade',
			'gradient' => 'linear-gradient(135deg, #374151 0%, #1f2937 100%)',
			'name' => __( 'Charcoal Fade', 'md' )
		],

		// Glass/frosted overlays
		[
			'slug' => 'glass-white',
			'gradient' => 'linear-gradient(135deg, rgba(255,255,255,0.4) 0%, rgba(255,255,255,0.1) 100%)',
			'name' => __( 'Glass White', 'md' )
		],
		[
			'slug' => 'glass-dark',
			'gradient' => 'linear-gradient(135deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.1) 100%)',
			'name' => __( 'Glass Dark', 'md' )
		]
	];
}

/**
 * Get shadow presets.
 *
 * @since 6.0.0
 * @return array Shadow presets
 */
function md_get_shadow_presets() {
	return [
		[ 'name' => __( 'Small', 'md' ), 'slug' => 'sm', 'shadow' => '0 1px 2px 0 rgba(0, 0, 0, 0.05)' ],
		[ 'name' => __( 'Medium', 'md' ), 'slug' => 'md', 'shadow' => '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1)' ],
		[ 'name' => __( 'Large', 'md' ), 'slug' => 'lg', 'shadow' => '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1)' ],
		[ 'name' => __( 'Extra Large', 'md' ), 'slug' => 'xl', 'shadow' => '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1)' ],
		[ 'name' => __( 'Soft', 'md' ), 'slug' => 'soft', 'shadow' => '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)' ],
		[ 'name' => __( 'Card', 'md' ), 'slug' => 'card', 'shadow' => '0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.08)' ],
		[ 'name' => __( 'Elevated', 'md' ), 'slug' => 'elevated', 'shadow' => '0 25px 50px -12px rgba(0, 0, 0, 0.25)' ]
	];
}

/**
 * Get block-specific styles.
 *
 * @since 6.0.0
 * @return array Block styles
 */
function md_get_block_styles() {
	return [
		'core/paragraph' => [
			'typography' => [
				'lineHeight' => '1.7'
			]
		],
		'core/heading' => [
			'typography' => [
				'lineHeight' => '1.2'
			]
		],
		'core/list' => [
			'spacing' => [
				'margin' => [ 'left' => '1rem' ],
				'padding' => [ 'left' => '0.5rem' ]
			],
			'typography' => [
				'lineHeight' => '1.7'
			]
		],
		'core/button' => [
			'color' => [
				'background' => 'var:preset|color|primary',
				'text' => 'var:preset|color|white'
			],
			'border' => [
				'radius' => '0.5rem'
			],
			'typography' => [
				'fontWeight' => '600',
				'fontSize' => 'var:preset|font-size|small'
			],
			'spacing' => [
				'padding' => [
					'top' => '0.75rem',
					'bottom' => '0.75rem',
					'left' => '1.5rem',
					'right' => '1.5rem'
				]
			]
		],
		'core/group' => [
			'spacing' => [
				'padding' => [
					'left' => '1rem',
					'right' => '1rem',
					'bottom' => '1rem',
					'top' => '1rem'
				]
			]
		],
		'core/image' => [
			'spacing' => [
				'margin' => [
					'bottom' => 'var:preset|spacing|m'
				]
			],
			'border' => [
				'radius' => '0.375rem'
			]
		],
		'core/post-featured-image' => [
			'spacing' => [
				'margin' => [
					'bottom' => 'var:preset|spacing|l'
				]
			],
			'border' => [
				'radius' => '0.5rem'
			]
		],
		'core/gallery' => [
			'spacing' => [
				'blockGap' => 'var:preset|spacing|s',
				'margin' => [
					'bottom' => 'var:preset|spacing|m'
				]
			]
		],
		'core/table' => [
			'border' => [
				'color' => 'var:preset|color|bordered',
				'width' => '1px',
				'style' => 'solid'
			],
			'typography' => [
				'fontSize' => 'var:preset|font-size|small',
				'lineHeight' => '1.5'
			],
			'spacing' => [
				'margin' => [
					'bottom' => 'var:preset|spacing|m'
				]
			]
		],
		'core/pullquote' => [
			'border' => [
				'top' => [
					'color' => 'var:preset|color|primary',
					'width' => '4px',
					'style' => 'solid'
				],
				'bottom' => [
					'color' => 'var:preset|color|primary',
					'width' => '4px',
					'style' => 'solid'
				]
			],
			'spacing' => [
				'padding' => [
					'top' => 'var:preset|spacing|m',
					'bottom' => 'var:preset|spacing|m'
				],
				'margin' => [
					'top' => 'var:preset|spacing|l',
					'bottom' => 'var:preset|spacing|l'
				]
			],
			'typography' => [
				'fontSize' => 'var:preset|font-size|large',
				'fontStyle' => 'italic',
				'lineHeight' => '1.5'
			]
		],
		'core/preformatted' => [
			'color' => [
				'background' => 'var:preset|color|offwhite',
				'text' => 'var:preset|color|headline'
			],
			'typography' => [
				'fontFamily' => 'var:preset|font-family|mono',
				'fontSize' => 'var:preset|font-size|small',
				'lineHeight' => '1.6'
			],
			'spacing' => [
				'padding' => [
					'top' => '1rem',
					'bottom' => '1rem',
					'left' => '1.25rem',
					'right' => '1.25rem'
				]
			],
			'border' => [
				'radius' => '0.375rem'
			]
		],
		'core/video' => [
			'spacing' => [
				'margin' => [
					'bottom' => 'var:preset|spacing|m'
				]
			],
			'border' => [
				'radius' => '0.5rem'
			]
		],
		'core/audio' => [
			'spacing' => [
				'margin' => [
					'bottom' => 'var:preset|spacing|m'
				]
			]
		],
		'core/file' => [
			'typography' => [
				'fontSize' => 'var:preset|font-size|small'
			],
			'spacing' => [
				'margin' => [
					'bottom' => 'var:preset|spacing|s'
				]
			]
		],
		'core/embed' => [
			'spacing' => [
				'margin' => [
					'bottom' => 'var:preset|spacing|m'
				]
			]
		],
		'core/quote' => [
			'border' => [
				'left' => [
					'color' => 'var:preset|color|primary',
					'width' => '4px',
					'style' => 'solid'
				]
			],
			'spacing' => [
				'padding' => [
					'left' => '1.5rem',
					'top' => '0.5rem',
					'bottom' => '0.5rem'
				],
				'margin' => [
					'top' => '1.5rem',
					'bottom' => '1.5rem'
				]
			],
			'typography' => [
				'fontStyle' => 'italic',
				'fontSize' => 'var:preset|font-size|medium',
				'lineHeight' => '1.6'
			],
			'color' => [
				'text' => 'var:preset|color|text-sec'
			]
		],
		'core/code' => [
			'color' => [
				'background' => 'var:preset|color|offwhite',
				'text' => 'var:preset|color|headline'
			],
			'typography' => [
				'fontFamily' => 'var:preset|font-family|mono',
				'fontSize' => 'var:preset|font-size|small',
				'lineHeight' => '1.6'
			],
			'spacing' => [
				'padding' => [
					'top' => '1rem',
					'bottom' => '1rem',
					'left' => '1.25rem',
					'right' => '1.25rem'
				]
			],
			'border' => [
				'radius' => '0.5rem',
				'color' => 'var:preset|color|bordered',
				'width' => '1px',
				'style' => 'solid'
			]
		],
		'core/separator' => [
			'color' => [
				'background' => 'transparent'
			],
			'border' => [
				'width' => '0',
				'top' => [
					'width' => '2px',
					'style' => 'dashed',
					'color' => '#ddd'
				]
			],
			'spacing' => [
				'margin' => [
					'top' => 'var:preset|spacing|l',
					'bottom' => 'var:preset|spacing|l'
				]
			]
		],
		'core/columns' => [
			'spacing' => [
				'margin' => [
					'bottom' => 'var:preset|spacing|m'
				]
			]
		],
		'core/cover' => [
			'spacing' => [
				'padding' => [
					'top' => 'var:preset|spacing|xl',
					'bottom' => 'var:preset|spacing|xl',
					'left' => 'var:preset|spacing|m',
					'right' => 'var:preset|spacing|m'
				]
			]
		],
		'core/details' => [
			'border' => [
				'color' => 'var:preset|color|bordered',
				'width' => '1px',
				'style' => 'solid',
				'radius' => '0.5rem'
			],
			'spacing' => [
				'padding' => [
					'top' => '1rem',
					'bottom' => '1rem',
					'left' => '1.25rem',
					'right' => '1.25rem'
				],
				'margin' => [
					'bottom' => '0.75rem'
				]
			]
		]
	];
}

/**
 * Write theme.json file from MD settings.
 *
 * @since 6.0.0
 * @return bool|int False on failure, bytes written on success
 */
function md_write_theme_json() {
	$data = md_generate_theme_json_data();
	$json = wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );

	$file = get_template_directory() . '/theme.json';

	return file_put_contents( $file, $json );
}

/**
 * Regenerate theme.json on settings save.
 *
 * @since 6.0.0
 */
function md_regenerate_theme_json() {
	md_write_theme_json();
}
add_action( 'update_option_marketers_delight', 'md_regenerate_theme_json' );

/**
 * Manual theme.json generation via query parameter.
 * Usage: Add ?md_compile_theme_json to any admin URL
 *
 * @since 6.0.0
 */
function md_manual_compile_theme_json() {
	if ( ! isset( $_GET['md_compile_theme_json'] ) ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have permission to perform this action.', 'md' ) );
	}

	$result = md_write_theme_json();

	if ( $result !== false ) {
		add_action( 'admin_notices', function () use ( $result ) {
			echo '<div class="notice notice-success is-dismissible"><p>';
			printf( __( 'theme.json regenerated successfully (%d bytes written).', 'md' ), $result );
			echo '</p></div>';
		});
	} else {
		add_action( 'admin_notices', function () {
			echo '<div class="notice notice-error is-dismissible"><p>';
			_e( 'Failed to regenerate theme.json. Check file permissions.', 'md' );
			echo '</p></div>';
		});
	}
}
add_action( 'admin_init', 'md_manual_compile_theme_json' );

/**
 * Schedule weekly theme.json regeneration.
 *
 * @since 6.0.0
 */
function md_schedule_theme_json_regeneration() {
	if ( ! wp_next_scheduled( 'md_weekly_theme_json_regeneration' ) ) {
		wp_schedule_event( time(), 'weekly', 'md_weekly_theme_json_regeneration' );
	}
}
add_action( 'after_setup_theme', 'md_schedule_theme_json_regeneration' );

/**
 * Weekly cron callback to regenerate theme.json.
 *
 * @since 6.0.0
 */
function md_cron_regenerate_theme_json() {
	md_write_theme_json();
}
add_action( 'md_weekly_theme_json_regeneration', 'md_cron_regenerate_theme_json' );

/**
 * Clear scheduled event on theme switch.
 *
 * @since 6.0.0
 */
function md_clear_theme_json_schedule() {
	$timestamp = wp_next_scheduled( 'md_weekly_theme_json_regeneration' );
	if ( $timestamp ) {
		wp_unschedule_event( $timestamp, 'md_weekly_theme_json_regeneration' );
	}
}
add_action( 'switch_theme', 'md_clear_theme_json_schedule' );

/**
 * Get theme.json data for use in PHP.
 *
 * @since 6.0.0
 * @return array Theme.json data
 */
function md_get_theme_json() {
	static $data = null;

	if ( $data === null ) {
		$file = get_template_directory() . '/theme.json';

		if ( file_exists( $file ) ) {
			$contents = file_get_contents( $file );
			$data = json_decode( $contents, true );
		}

		if ( ! $data ) {
			$data = md_generate_theme_json_data();
		}
	}

	return $data;
}
