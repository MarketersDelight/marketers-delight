<?php
/**
 * CSS Optimizer - Conditional CSS loading to reduce unused CSS
 *
 * This class splits CSS into categories and loads them conditionally
 * based on page context, reducing the total CSS loaded per page.
 *
 * @since 6.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class md_css_optimizer {

	/**
	 * CSS categories and their templates
	 */
	private $categories = [];

	/**
	 * Compiled CSS cache
	 */
	private $cache = [];

	/**
	 * Initialize the optimizer
	 */
	public function __construct() {
		$this->define_categories();

		// Hook into WordPress
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_optimized_css' ], 5 );
		add_action( 'wp_head', [ $this, 'inline_critical_css' ], 1 );
		add_filter( 'style_loader_tag', [ $this, 'defer_non_critical_css' ], 10, 4 );
	}

	/**
	 * Define CSS categories and their loading conditions
	 */
	private function define_categories() {
		$this->categories = [
			// Critical CSS - always loaded inline in <head>
			'critical' => [
				'templates' => [ 'attributes', 'layout' ],
				'condition' => '__return_true',
				'inline' => true,
				'priority' => 1
			],

			// Typography - always needed but can be deferred
			'typography' => [
				'templates' => [ 'format' ],
				'condition' => '__return_true',
				'inline' => false,
				'priority' => 2
			],

			// Forms - only on pages with forms
			'forms' => [
				'templates' => [ 'forms', 'buttons' ],
				'condition' => [ $this, 'needs_forms' ],
				'inline' => false,
				'priority' => 3
			],

			// Header - always needed
			'header' => [
				'templates' => [ 'header' ],
				'condition' => '__return_true',
				'inline' => false,
				'priority' => 2
			],

			// Menus - only if menus exist
			'menus' => [
				'templates' => [ 'menus' ],
				'condition' => [ $this, 'has_menus' ],
				'inline' => false,
				'priority' => 3
			],

			// Sidebar - only on pages with sidebar
			'sidebar' => [
				'templates' => [ 'sidebar', 'widgets' ],
				'condition' => [ $this, 'has_sidebar' ],
				'inline' => false,
				'priority' => 4
			],

			// Post content - only on singular posts/pages
			'post' => [
				'templates' => [ 'post' ],
				'condition' => 'is_singular',
				'inline' => false,
				'priority' => 3
			],

			// Comments - only on singular with comments open
			'comments' => [
				'templates' => [ 'comments' ],
				'condition' => [ $this, 'needs_comments' ],
				'inline' => false,
				'priority' => 5
			],

			// Loops - only on archive/loop pages
			'loops' => [
				'templates' => [ 'loops' ],
				'condition' => [ $this, 'needs_loops' ],
				'inline' => false,
				'priority' => 3
			],

			// Footer - always needed
			'footer' => [
				'templates' => [ 'footer' ],
				'condition' => '__return_true',
				'inline' => false,
				'priority' => 4
			],

			// Utility classes - load based on setting
			'utilities' => [
				'templates' => [ 'blocks', 'spacers', 'columns' ],
				'condition' => [ $this, 'load_utilities' ],
				'inline' => false,
				'priority' => 5
			],

			// Effects/animations - defer loading
			'effects' => [
				'templates' => [ 'effects' ],
				'condition' => '__return_true',
				'inline' => false,
				'priority' => 10,
				'defer' => true
			]
		];

		// Allow filtering
		$this->categories = apply_filters( 'md_css_categories', $this->categories );
	}

	/**
	 * Check if page needs form styles
	 */
	public function needs_forms() {
		// Always load on pages with comments, search, or known form shortcodes
		if ( is_singular() && comments_open() ) return true;
		if ( is_search() ) return true;
		if ( is_page_template( 'contact' ) ) return true;

		// Check for form plugins
		if ( class_exists( 'WPCF7' ) ) return true;
		if ( class_exists( 'GFForms' ) ) return true;

		// Default: load forms (can be filtered)
		return apply_filters( 'md_needs_forms_css', true );
	}

	/**
	 * Check if any menus are registered
	 */
	public function has_menus() {
		return has_nav_menu( 'header' ) || has_nav_menu( 'main' ) || has_nav_menu( 'footer' );
	}

	/**
	 * Check if page has sidebar
	 */
	public function has_sidebar() {
		$layout = md_setting( [ 'content', 'style' ] );

		// No sidebar layout
		if ( $layout === 'full' ) return false;

		// Check if sidebar is active
		return is_active_sidebar( 'sidebar-main' );
	}

	/**
	 * Check if page needs comments CSS
	 */
	public function needs_comments() {
		if ( ! is_singular() ) return false;
		if ( ! comments_open() && get_comments_number() == 0 ) return false;
		return true;
	}

	/**
	 * Check if utility classes should be loaded
	 */
	public function load_utilities() {
		// Can be disabled via settings
		return ! md_setting( [ 'settings', 'css', 'disable_utilities' ], false );
	}

	/**
	 * Check if page needs loop styles (archives, home, search)
	 */
	public function needs_loops() {
		// Load on archive pages
		if ( is_archive() ) return true;
		if ( is_home() ) return true;
		if ( is_search() ) return true;

		// Also load on 404 (may show related posts)
		if ( is_404() ) return true;

		return apply_filters( 'md_needs_loops_css', false );
	}

	/**
	 * Get CSS for a specific category
	 */
	public function get_category_css( $category ) {
		if ( ! isset( $this->categories[ $category ] ) ) {
			return '';
		}

		// Check cache
		$cache_key = 'md_css_' . $category;
		if ( isset( $this->cache[ $cache_key ] ) ) {
			return $this->cache[ $cache_key ];
		}

		// Generate CSS
		$css = $this->compile_category( $category );

		// Cache it
		$this->cache[ $cache_key ] = $css;

		return $css;
	}

	/**
	 * Compile CSS for a category
	 */
	private function compile_category( $category ) {
		$config = $this->categories[ $category ];
		$templates = $config['templates'];

		// Get design values
		$design = new md_design();
		$values = $design->values();

		// Set up variables (same as md_css::templates)
		$g = 1.618;
		$css_unit = md_get_unit();

		$site_width = $values['content']['width']['site'];
		$content_width = $values['content']['width']['content_width'];
		$post_width = $values['content']['width']['post'];
		$sidebar_width = $values['content']['width']['sidebar'];

		$colors = $values['colors'];
		$typography = $values['typography'];
		$header = $values['header'];
		$content = $values['content'];

		$font_size = $values['typography']['body']['font_size'];
		$font_family = $values['typography']['body']['font_family'];
		$line_height = $values['typography']['body']['line_height'];
		$font_weight = ! empty( $typography['body']['font_weight'] ) ? $typography['body']['font_weight'] : 'normal';
		$bold = ! empty( $typography['body']['bold'] ) ? $typography['body']['bold'] : 'bold';

		// Spacing calculations
		$single_px = $line_height['desktop'];
		$small_px = round( $single_px / 6 );
		$third_px = round( $single_px / 3 );
		$half_px = round( $single_px / 2 );
		$mid_px = $single_px + $half_px;
		$double_px = round( $single_px * 2 );
		$triple_px = round( $single_px * 3 );
		$quad_px = round( $single_px * 4 );

		$single = $lh = md_unit( $single_px );
		$small = $lhsm = md_unit( $small_px );
		$third = $lhth = md_unit( $third_px );
		$half = $lhh = md_unit( $half_px );
		$mid = $lhm = md_unit( $mid_px );
		$double = $lhd = md_unit( $double_px );
		$triple = $lht = md_unit( $triple_px );
		$quad = $lhq = md_unit( $quad_px );

		$submenu_width = md_unit( $double_px * 5 );
		$gutter_width = round( ( $site_width - $post_width ) / 2 );
		$breakout = ( $gutter_width / $post_width ) * 100;
		$breakout_full = ( $gutter_width / $site_width ) * 100;

		$h1 = $values['typography']['h1'];
		$h2 = $values['typography']['h2'];
		$h3 = $values['typography']['h3'];
		$h4 = $values['typography']['h4'];
		$h5 = $values['typography']['h5'];
		$h6 = $values['typography']['h6'];

		// Output buffer the templates
		ob_start();

		foreach ( $templates as $template ) {
			$path = locate_template( "css/{$template}.php" );
			if ( ! $path ) {
				$path = MD_CSS_DIR . "{$template}.php";
			}

			if ( file_exists( $path ) ) {
				include $path;
			}
		}

		$css = ob_get_clean();

		// Clean and minify
		$css = $this->minify( $css );

		return $css;
	}

	/**
	 * Minify CSS
	 */
	private function minify( $css ) {
		// Remove style tags
		$css = str_replace( [ '<style type="text/css">', '<style type=\'text/css\'>', '<style>', '</style>' ], '', $css );

		// Remove comments
		$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );

		// Remove whitespace
		$s = [ "\r", "\n", "\t", '  ', ' {', '{ ', ' }', '} ', ': ', ' :', '; ', ' ;', ', ', ' ,' ];
		$r = [ '', '', '', ' ', '{', '{', '}', '}', ':', ':', ';', ';', ',', ',' ];
		$css = str_replace( $s, $r, $css );

		// Clean up extra spaces
		$css = preg_replace( '/\s+/', ' ', $css );

		return trim( $css );
	}

	/**
	 * Enqueue optimized CSS files
	 */
	public function enqueue_optimized_css() {
		// Skip if using legacy mode
		if ( md_setting( [ 'settings', 'css', 'legacy_mode' ], false ) ) {
			return;
		}

		// Skip if inline CSS is enabled (handled elsewhere)
		if ( md_setting( [ 'settings', 'css', 'inline' ] ) ) {
			return;
		}

		// Get categories to load
		$to_load = $this->get_categories_to_load();

		foreach ( $to_load as $category => $config ) {
			// Skip inline categories (handled in head)
			if ( ! empty( $config['inline'] ) ) {
				continue;
			}

			// Enqueue the category CSS
			$handle = "md-{$category}";
			$css_content = $this->get_category_css( $category );

			if ( ! empty( $css_content ) ) {
				// Always use inline styles for immediate effect (no file compilation needed)
				wp_register_style( $handle, false );
				wp_enqueue_style( $handle );
				wp_add_inline_style( $handle, $css_content );
			}
		}
	}

	/**
	 * Output critical CSS inline in head
	 */
	public function inline_critical_css() {
		// Skip if using legacy mode
		if ( md_setting( [ 'settings', 'css', 'legacy_mode' ], false ) ) {
			return;
		}

		$critical_categories = [ 'critical' ];

		// Add typography to critical if small
		$typography_css = $this->get_category_css( 'typography' );
		if ( strlen( $typography_css ) < 5000 ) {
			$critical_categories[] = 'typography';
		}

		$critical_css = '';
		foreach ( $critical_categories as $category ) {
			$critical_css .= $this->get_category_css( $category );
		}

		if ( ! empty( $critical_css ) ) {
			echo '<style id="md-critical-css">' . $critical_css . '</style>' . "\n";
		}
	}

	/**
	 * Defer non-critical CSS loading
	 */
	public function defer_non_critical_css( $html, $handle, $href, $media ) {
		// Check if this style should be deferred
		if ( strpos( $handle, 'md-' ) !== 0 ) {
			return $html;
		}

		// Check for defer flag
		$defer = wp_styles()->get_data( $handle, 'defer' );
		if ( ! $defer ) {
			return $html;
		}

		// Convert to preload with onload
		$html = str_replace(
			"rel='stylesheet'",
			"rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"",
			$html
		);

		// Add noscript fallback
		$noscript = '<noscript><link rel="stylesheet" href="' . esc_url( $href ) . '" media="' . esc_attr( $media ) . '"></noscript>';
		$html .= $noscript;

		return $html;
	}

	/**
	 * Get categories that should be loaded on current page
	 */
	private function get_categories_to_load() {
		$to_load = [];

		foreach ( $this->categories as $category => $config ) {
			$condition = $config['condition'];

			// Evaluate condition
			$should_load = false;

			if ( is_string( $condition ) && function_exists( $condition ) ) {
				$should_load = call_user_func( $condition );
			} elseif ( is_array( $condition ) && is_callable( $condition ) ) {
				$should_load = call_user_func( $condition );
			} elseif ( is_bool( $condition ) ) {
				$should_load = $condition;
			}

			if ( $should_load ) {
				$to_load[ $category ] = $config;
			}
		}

		// Sort by priority
		uasort( $to_load, function( $a, $b ) {
			return ( $a['priority'] ?? 5 ) - ( $b['priority'] ?? 5 );
		});

		return $to_load;
	}

	/**
	 * Get file path/url for category
	 */
	private function get_category_file( $category ) {
		$dir = get_template_directory() . '/dist/css/';
		$url = get_template_directory_uri() . '/dist/css/';

		// Create directory if needed
		if ( ! is_dir( $dir ) ) {
			wp_mkdir_p( $dir );
		}

		return [
			'path' => $dir . $category . '.css',
			'url' => $url . $category . '.css'
		];
	}

	/**
	 * Compile all category files
	 */
	public function compile_all() {
		foreach ( $this->categories as $category => $config ) {
			$css = $this->compile_category( $category );
			$file = $this->get_category_file( $category );

			file_put_contents( $file['path'], $css );
		}

		// Also generate combined file for legacy mode
		$this->compile_combined();
	}

	/**
	 * Compile combined CSS file (for legacy mode)
	 */
	private function compile_combined() {
		$md_css = new md_css();
		$md_css->compile();
	}

	/**
	 * Get estimated CSS savings for current page
	 */
	public function get_savings_estimate() {
		$total_size = 0;
		$loaded_size = 0;

		foreach ( $this->categories as $category => $config ) {
			$css = $this->get_category_css( $category );
			$size = strlen( $css );
			$total_size += $size;

			$condition = $config['condition'];
			$should_load = false;

			if ( is_string( $condition ) && function_exists( $condition ) ) {
				$should_load = call_user_func( $condition );
			} elseif ( is_array( $condition ) && is_callable( $condition ) ) {
				$should_load = call_user_func( $condition );
			}

			if ( $should_load ) {
				$loaded_size += $size;
			}
		}

		return [
			'total' => $total_size,
			'loaded' => $loaded_size,
			'saved' => $total_size - $loaded_size,
			'percent' => $total_size > 0 ? round( ( ( $total_size - $loaded_size ) / $total_size ) * 100 ) : 0
		];
	}

}

/**
 * Initialize the CSS optimizer
 */
function md_css_optimizer() {
	static $instance = null;

	if ( $instance === null ) {
		$instance = new md_css_optimizer();
	}

	return $instance;
}

/**
 * Compile optimized CSS files
 */
function md_compile_optimized_css() {
	$optimizer = new md_css_optimizer();
	$optimizer->compile_all();
}

/**
 * Initialize CSS optimizer after theme is set up
 */
add_action( 'after_setup_theme', function() {
	// Initialize if optimized CSS is enabled
	if ( function_exists( 'md_setting' ) && md_setting( [ 'settings', 'css', 'optimize' ], false ) ) {
		add_action( 'init', 'md_css_optimizer' );
	}
}, 20 );

// Add compile hook - use WordPress native hook for option updates
add_action( 'update_option_marketers_delight', function() {
	if ( function_exists( 'md_setting' ) && md_setting( [ 'settings', 'css', 'optimize' ], false ) ) {
		md_compile_optimized_css();
	}
});
