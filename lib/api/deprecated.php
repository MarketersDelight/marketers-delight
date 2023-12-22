<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Deprecated 5.6
function md_headline_classes( $classes = array() ) {
	$classes = apply_filters( 'md_filter_headline_classes', $classes );
	$classes = join( ' ', $classes );
	return esc_attr( $classes );
}
function md_filter_css_values() { return apply_filters( 'md_filter_css_values', array() ); }
function md_logo_html() { return apply_filters( 'md_filter_logo_html', 'div' ); }
function md_button( $fields ) { md_link( $fields ); }
function md_hook_headline_top() { do_action( 'md_hook_headline_top' ); }
function md_hook_headline_bottom() { do_action( 'md_hook_headline_bottom' ); }
function md_hook_content_item_text_top() { do_action( 'md_hook_content_item_text_top' ); }
function md_hook_teaser_top() { do_action( 'md_hook_teaser_top' ); }
function md_hook_teaser_bottom() { do_action( 'md_hook_teaser_bottom' ); }
function md_hook_header_triggers() { do_action( 'md_hook_header_triggers' ); }
function md_author() { md_author_box(); }
function md_featured_image_style() { md_cover_style(); }
function md_featured_image_cover() { md_cover_style(); }
function md_featured_image_caption() { md_get_caption(); }
function md_page_data() { return array(); }

/**
 * Checks for content headline.
 *
 * @since 4.1
 * @deprecated 5.6
 */
function md_has_headline_cover() {
	$cover = md_cover();
	return is_singular() && ! empty( $cover['position'] ) && in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) ? true : false;
}

/**
 * A list of post types to show Share buttons on.
 * @since 5.0
 * @deprecated 5.6
 */
function md_share_post_types() {
	return array_merge( apply_filters( 'md_share_show_on', array() ), md_post_type_meta() );
}

/**
 * A list of classes to add to the sidebar.
 *
 * @since 4.5
 * @deprecated 5.6
 */
function md_byline_classes() {
	$classes[] = 'byline';
	$classes = apply_filters( 'md_filter_byline_classes', $classes );
	return join( ' ', $classes );
}

/**
 * Outputs main sidebar or custom sidebar.
 *
 * @since 4.1
 * @deprecated 5.6
 */
function md_sidebar() {
	$name = md_get_sidebar_id();
	dynamic_sidebar( $name );
}

/**
 * A list of classes to add to the sidebar.
 *
 * @since 4.5
 * @deprecated 5.6
 */
function md_sidebar_classes() {
	echo apply_filters( 'md_filter_sidebar_classes', '' );
}

/**
 * Collect sidebar data to load custom sidebars across
 * various post type screens (filter in your own CPTs
 * to add to the Sidebars Manager).
 *
 * @since 4.6.2
 * @deprecated 5.6
 */
function md_sidebars() {
	return apply_filters( 'md_filter_sidebars_post_types', array(
		'post' => array(
			'archive' => true,
			'category' => true,
			'single' => true
		),
		'page' => array(
			'single' => true
		)
	) );
}

/**
 * Filter the default image sizes of MD.
 *
 * @since 4.7.4.4
 * @deprecated 5.6
 */
function md_image_sizes() {
	return apply_filters( 'md_filter_image_sizes', array(
		'md-banner' => array(
			'width'  => 600,
			'height' => 250
		),
		'md-image' => array(
			'width'  => 325,
			'height' => 425
		)
	) );
}

/**
 * Final logo logic and rendering.
 *
 * @since 4.5.4
 * @deprecated 6.0
 */
function md_the_logo() {
	$has_custom_logo = md_has_custom_logo();
	$has_logo_html = md_setting( array( 'logo', 'logo_html_display', 'enable' ) );
	$logo_html = md_setting( array( 'logo', 'logo_html' ) );
	if ( $has_logo_html && ! empty( $logo_html ) )
		echo $logo_html;
	else {
		$logo_id = md_setting( array( 'logo', 'logo', 'id' ) );
		$secondary_logo = md_setting( array( 'logo', 'logo_alt', 'url' ) );
		$text_global = md_setting( array( 'colors', 'page_cover', 'styles', 'text_color' ) );
		$text_single = md_post_meta( array( 'colors', 'text_color', 'alternate' ) );
		$cover = md_cover();
		if ( ( ( ( is_singular() || is_category() || is_tax() ) && $cover['position'] == 'header_cover_full' ) || apply_filters( 'md_filter_logo_alt', false ) ) && ! empty( $secondary_logo ) ) {
			if ( ( ! empty( $logo_id ) && $secondary_logo ) && ( ( empty( $text_global ) && empty( $text_single ) ) || ( ! empty( $text_global ) && ! empty( $text_single ) ) ) )
				md_secondary_logo();
			else
				echo wp_get_attachment_image( $logo_id, 'full', false, array( 'class' => 'custom-logo-link' ) );
		}
		else
			echo wp_get_attachment_image( $logo_id, 'full', false, array( 'class' => 'custom-logo-link' ) );
	}
}

/**
 * Checks for WordPress' custom logo.
 *
 * @since 4.5.4
 * @deprecated 6.0
 */
function md_has_custom_logo() {
	$logo_image = md_setting( array( 'logo', 'logo', 'url' ) );
	$has_logo_html = md_setting( array( 'logo', 'logo_html_display', 'enable' ) );
	$logo_html = md_setting( array( 'logo', 'logo_html' ) );
	if ( $logo_image || ( $has_logo_html && ! empty( $logo_html ) ) )
		return true;
}

/**
 * Render secondary logo for dark mode.
 *
 * @since 4.5.4
 * @deprecated 6.0
 */
function md_secondary_logo() {
	$secondary_logo_id = md_setting( array( 'logo', 'logo_alt', 'id' ) );
	echo '<span class="custom-logo-link">';
	if ( ! empty( $secondary_logo_id ) )
		echo wp_get_attachment_image( $secondary_logo_id, 'full' );
	echo '</span>';
}

/**
 * Checks if Main Menu is active on page.
 *
 * @since 4.1
 * @deprecated 5.6
 */
function md_has_main_menu() {
	if ( ! has_nav_menu( 'main' ) ) return;
	$remove = md_module( array( 'layout', 'main_menu', 'remove' ) );
	if ( ! empty( $remove ) ) return;
	return apply_filters( 'md_filter_has_main_menu', true );
}
/**
 * Load main menu template file.
 *
 * @since 4.1
 * @deprecated 5.6
 */
function md_main_menu() {
	$menu = md_meta( array( 'layout', 'main_menu_menu' ) );
	$walker = new md_menu_walker( true, true );
	include( md_template( 'menus/main-menu', true ) );
}
/**
 * Counts how many fields are active in Main menu. Minimum to show = 2.
 *
 * @since 4.1
 * @deprecated 5.6
 */
function md_main_menu_items() {
	return count( array_filter( array( has_nav_menu( 'main' ), md_main_menu_has_search(), has_nav_menu( 'social' ), apply_filters( 'md_filter_main_menu_items', '' ) ) ) );
}
function md_main_menu_has_search() {
	return ! md_setting( array( 'header', 'main_menu', 'disable', 'search' ) ) ? true : false;
}

/**
 * HTML for MD featured image on taxonomy pages.
 *
 * @since 4.3.5
 * @deprecated 5.6
 */
function md_featured_image_tax( $location ) {
	$position = md_term_meta( array( 'featured_image', 'position' ) );
	$image = md_term_meta( array( 'featured_image', 'image', 'url' ) );
	$size = md_featured_image_size( $position, 'md-thumbnail' );
?>
	<?php if ( in_array( $position, array( 'header_cover', 'header_cover_full' ) ) ) : ?>
		<?php md_archives_title(); ?>
	<?php else :
		$align = md_featured_image_alignment_classes( $position );
		$class = in_array( $position, array( '', 'left', 'right', 'center' ) ) ? ' class="circle"' : '';
		$dim = in_array( $position, array( '', 'left', 'right', 'center' ) ) ? 100 : '';
	?>
		<div class="featured-image-tax<?php echo $align; ?>">
			<img src="<?php echo esc_url( $image ); ?>"<?php echo $class; ?> width="<?php echo esc_attr( $dim ); ?>" height="<?php echo esc_attr( $dim ); ?>" />
		</div>
	<?php endif; ?>
<?php }

/**
 * Featured Image position class.
 *
 * @since 4.1
 * @deprecated 5.6
 */
function md_featured_image_alignment_classes( $position ) {
	$wrap = ! isset( $atts['wrap'] ) ? ' wrap' : '';
	if ( $position == '' || $position == 'right' )
		return "alignright$wrap";
	elseif ( $position == 'left' )
		return "alignleft$wrap";
	elseif ( $position == 'center' )
		return 'aligncenter';
}

/**
 * Returns image size. Use anywhere you need to set a the_post_thumbnail size.
 *
 * @since 4.1
 * @deprecated 5.6
 */
function md_featured_image_size( $pos = null, $thumb = null ) {
	$position = md_featured_image_position( $pos );
	if ( $position == '' || $position == 'left' || $position == 'right' )
		return ( ! isset( $thumb ) ? 'md-image' : $thumb );
	if ( $position == 'center' )
		return ( ! isset( $thumb ) ? 'full' : $thumb );
	return 'full';
}

/**
 * If image is set above or below headline, add Featured Image.
 *
 * @since 4.1
 * @deprecated 5.6
 */
function md_featured_image_above_headline() {
	if ( has_post_thumbnail() && md_featured_image_position() == 'above_headline' )
		md_featured_image();
}
function md_featured_image_below_headline() {
	if ( has_post_thumbnail() && md_featured_image_position() == 'below_headline' )
		md_featured_image();
}

/**
 * Filter classes to teaser boxes.
 *
 * @since 4.9.2
 * @deprecated 5.6
 */
function md_teaser_classes( $classes = array() ) {
	$classes[] = 'blog-teaser';
	return join( ' ', $classes );
}

/**
 * Returns content item HTML container.
 *
 * @since 4.1
 * @deprecated 5.6
 */
function md_content_item_headline_html() {
	return md_has_headline_cover() ? 'div' : 'header';
}

/**
 * Displays header aside content.
 *
 * @since 4.8
 * @deprecated 5.6
 */
function md_header_aside() { }
function md_hook_header_aside() { do_action( 'md_hook_header_aside' ); }

/**
 * Add classes to specified WordPress Widgets (saves sooo much CSS).
 *
 * @since 4.0
 * @deprecated 5.6
 */
function md_widget_classes( $params ) {
	global $wp_registered_widgets;
	$classes = apply_filters( 'md_widget_classes', array(
		'list box-style-list' => array(
			'recent-posts',
			'recent-comments',
			'archives',
			'meta',
			'categories'
		),
		'list list-large box-style-list' => array(
			'rss'
		)
	) );
	foreach ( $classes as $class => $widgets )
		foreach ( $widgets as $widget )
			if ( $params[0]['widget_id'] == "$widget-" . $wp_registered_widgets[$params[0]['widget_id']]['params'][0]['number'] )
				$params[0]['before_widget'] = preg_replace( '/class="([^"]*)"/', 'class="$1 ' . $class . '"', $params[0]['before_widget'] );
	return $params;
}
//add_filter( 'dynamic_sidebar_params', 'md_widget_classes' );

/**
 * Returns HTML classes for different layouts.
 *
 * @since 4.1
 * @deprecated 5.6
 */
function md_content_block() {
	if ( md_has_sidebar() ) return 'block-double';
	else return 'block-full';
}

/**
 * Create Site Design admin page.
 * This page was removed in 5.4.2 and this Class
 * solely serves as a URL redirect.
 *
 * @since 5.0
 * @deprecated 5.5
 */
class md_site_design extends md_api {
	public function actions() {
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'md_site_design' ) {
			wp_redirect( admin_url( 'admin.php?page=md_settings' ) );
			exit;
		}
	}
	public function register() {
		return array( 'admin_page' => array() );
	}
}
new md_site_design;

/**
 * A list of post types and taxonomies to enable MD Optins features to.
 *
 * @since 5.0
 * @moved to /md-dropins/optins/
 */
function md_optins_locations( $sort = null ) {
	$defaults = array(
		'sitewide' => __( 'Sitewide', 'md' ),
		'front' => __( 'Front Page', 'md' ),
		'home' => __( 'Blog Page', 'md' ),
		'post' => __( 'All Posts', 'md' ),
		'category' => __( 'All Categories', 'md' ),
		'page' => __( 'All Pages', 'md' ),
		'author' => __( 'All Author Pages', 'md' ),
		'search' => __( 'Search Results', 'md' ),
	);
	$filter = apply_filters( 'md_optins_locations', array() );
	if ( isset( $sort ) ) {
		if ( $sort == 'ids' ) {
			foreach ( $defaults as $id => $label )
				$locations[] = $id;
			foreach ( $filter as $group => $fields )
				foreach ( $fields as $key => $label )
					if ( ! in_array( $key, array( 'archive', 'single' ) ) )
						$locations[] = $key;
					else
						$locations[] = "{$group}_{$key}";
		}
		elseif ( $sort == 'options' ) {
			$locations = $defaults;
			foreach ( $filter as $group => $fields )
				foreach ( $fields as $key => $label )
					if ( ! in_array( $key, array( 'archive', 'single' ) ) )
						$locations[$key] = $label;
					else
						$locations["{$group}_{$key}"] = $label;

		}
	}
	else
		$locations = array_merge( $defaults, $filter );
	return $locations;
}

/**
 * Returns custom page nav menu.
 *
 * @since 4.1
 * @deprecated 5.5
 */

function md_main_menu_custom_menu() {
	if ( is_category() || is_tax() )
		return md_term_meta( array( 'layout', 'main_menu_menu' ) );
	else
		return md_post_meta( array( 'layout', 'main_menu_menu' ) );
}

/**
 * A quick recap of what's new in MD.
 *
 * @since 4.8.4
 * @deprecated 5.3
 */

function md_whats_new() {
	$new['version'] = '';
	$new['link'] = '#';
	$new['items'][] = '';
	return $new;
}

/**
 * Checks if post listings has excerpts enabled.
 *
 * @since 4.5
 * @dprecated 5.1
 */
function md_has_teasers() {
	return ! is_singular() && (
		( md_setting( array( 'loop' ) ) == 'teasers' && ( is_home() || is_category() || is_tax() ) ) ||
		( is_archive() && md_term_meta( array( 'layout', 'content', 'teasers' ) ) )
	) ? true : false;
}

function md_hook_content_schema() {
	do_action( 'md_hook_content_schema' );
}

/**
 * Old menu Walker menu class name. Preserved for backwards compatibility.
 *
 * @since 4.5
 */

class md_main_menu_walker extends Walker_Nav_Menu {
	function __construct( $title = true, $desc = false ) {
		new md_menu_walker( $title, $desc );
	}
}

/**
 * Filterable schema type for content type.
 *
 * @since 4.7.3.1
 * @deprecated 5.0.9
 */

function md_article_schema() {
	return apply_filters( 'md_filter_article_schema', 'http://schema.org/Article' );
}

/**
 * Displays content meta tags for Schema compatiblity.
 *
 * @since 4.4.2
 * @deprecared 5.0.9
 */
function md_content_schema() {
	md_template( 'content-schema' );
}

/**
 * A collection of all registered fields used on save.
 *
 * @since 4.7
 * @DEPRECATED 5.0 use md_register()
 */

function md_get_register_fields() {
	return apply_filters( 'md_filter_register_fields', array() );
}

/**
 * Filter prefixes of Customizer options to save to main
 * options array.
 *
 * @since 4.7
 * @DEPRECATED 5.0
 */

function md_get_merge_fields() {
	return apply_filters( 'md_filter_merge_fields', array() );
}

/**
 * Pull data from the Marketers Delight options array. For
 * best performance, always pull MD settings from here.
 *
 * @since 4.7
 * @deprecated 5.0, use md_setting()
 */

function get_md( $id = null, $field = null, $atts = null, $customize = null ) {
	$data = array();
	$option = get_option( 'marketers_delight' );
	if ( empty( $option ) )
		$option = array();
	if ( is_customize_preview() && isset( $id ) && isset( $field ) && isset( $customize ) && isset( $customize ) ) {
		$option = get_option( "md_{$id}" );
		$data = ! empty( $option[$field][$atts] ) ? $option[$field][$atts] : '';
	}
	elseif ( isset( $id ) && isset( $field ) && isset( $atts ) )
		$data = ! empty( $option[$id][$field][$atts] ) ? $option[$id][$field][$atts] : '';
	elseif ( isset( $id ) && isset( $field ) )
		$data = ! empty( $option[$id][$field] ) ? $option[$id][$field] : '';
	elseif ( isset( $id ) )
		$data = ! empty( $option[$id] ) ? $option[$id] : array();
	else
		$data = $option;
	return $data;
}

/**
 * A simple way to get various levels of post meta.
 *
 * @since 4.7
 * @deprecated 5.0, use md_post_meta()
 */

function md_get_meta( $group = null, $field = null, $atts = null, $id = null ) {
	$post_id = get_the_ID();

	if ( isset( $id ) ) {
		$is_string = is_string( $id ) ? true : false;
		$post_id = $is_string ? $id : get_queried_object_id();
	}

	$meta = get_post_meta( $post_id, 'marketers_delight', true );

	if ( ! empty( $meta ) )
		if ( isset( $group ) && isset( $field ) && isset( $atts ) )
			return isset( $meta[$group][$field][$atts] ) ? $meta[$group][$field][$atts] : '';
		elseif ( isset( $group ) && isset( $field ) )
			return isset( $meta[$group][$field] ) ? $meta[$group][$field] : '';
		elseif ( isset( $group ) )
			return isset( $meta[$group] ) ? $meta[$group] : '';
		else
			return $meta;
}

/**
 * Quickly access taxonomy field data (deprecates md_tax_data()).
 *
 * @since 4.7
 * @deprecated 5.0, use md_term_meta()
 */

function md_get_tax( $group = null, $field = null, $atts = null, $id = null, $tax_id = null ) {
	if ( is_admin() ) {
		$screen = get_current_screen();
		$tag_id = ! empty( $_GET['tag_ID'] ) ? $_GET['tag_ID'] : '';
		$term_id = isset( $id ) ? $id : $tag_id;

		$tax_screen = ! empty( $screen->taxonomy ) ? $screen->taxonomy : '';
		$taxonomy = isset( $tax_id ) ? $tax_id : $tax_screen;
		$tax = get_option( "md_taxonomy_{$taxonomy}_{$term_id}" );
	}
	else {
		$term = get_queried_object();
		$tag_id = ! empty( $term->term_id ) ? $term->term_id : '';
		$term_id = isset( $id ) ? $id : $tag_id;
		$tax_screen = ! empty( $term->taxonomy ) ? $term->taxonomy : '';
		$taxonomy = isset( $tax_id ) ? $tax_id : $tax_screen;
		$tax = get_option( "md_taxonomy_{$taxonomy}_{$term_id}" );
	}

	if ( isset( $group ) && isset( $field ) && isset( $atts ) )
		return isset( $tax[$group][$field][$atts] ) ? $tax[$group][$field][$atts] : '';
	elseif ( isset( $group ) && isset( $field ) )
		return isset( $tax[$group][$field] ) ? $tax[$group][$field] : '';
	elseif ( isset( $group ) )
		return isset( $tax[$group] ) ? $tax[$group] : '';
	else
		return $tax;

	return false;
}

/**
 * Get meta field of either single or term pages.
 *
 * @since 4.7
 * @deprecated 5.0, use md_meta()
 */

function md_get_module( $group = null, $field = null, $atts = null, $id = true ) {
	if ( is_category() || is_tax() )
		return md_get_tax( $group, $field, $atts );
	else
		return md_get_meta( $group, $field, $atts, $id );
}

// DEPRECATED 5.0
function md_main_menu_search() { md_template( 'searchform-main-menu' ); }

/**
 * A simple way to get various levels of Customizer settings.
 *
 * @since 4.8.6
 * @DEPRECATED 5.0
 */
function md_theme_mod( $group = null, $field = null, $atts = null, $id = null ) {
	$design = get_theme_mod( 'marketers_delight' );
	if ( isset( $group ) && isset( $field ) && isset( $atts ) )
		return ! empty( $design[$group][$field][$atts] ) ? $design[$group][$field][$atts] : array();
	elseif ( isset( $group ) && isset( $field ) )
		return ! empty( $design[$group][$field] ) ? $design[$group][$field] : array();
	elseif ( isset( $group ) )
		return ! empty( $design[$group] ) ? $design[$group] : array();
	else
		return $design;
}

/**
 * Determines needed classes for inside a headline type element.
 * Spacing, padding, featured image styles, etc.
 *
 * @since 4.1
 * @deprecated 5.0
 */
function md_headline_inner_classes() {
	$position = md_featured_image_position();
	$classes = array();
	if ( in_array( $position, array( 'headline_cover', 'header_cover', 'header_cover_full' ) ) )
		$classes[] = in_array( $position, array( 'header_cover', 'header_cover_full' ) ) && is_singular() ? 'block-triple-tb block-double-lr inner' : ( md_has_sidebar() ? 'block-double' : 'block-triple' );
	elseif ( empty( $blocks ) )
		$classes[] = md_has_sidebar() ? 'block-double' : 'block-triple';
	$classes = apply_filters( 'md_filter_headline_inner_classes', $classes );
	return join( ' ', $classes );
}

/**
 * Outputs classes for content text used in layouts where a sidebar
 * could exist.
 *
 * @since 4.1
 * @deprecated 5.0
 */
function md_content_text_classes() {
	$position = md_featured_image_position();
	if ( in_array( $position, array( 'headline_cover', 'header_cover', 'header_cover_full' ) ) )
		$classes[] = md_content_block();
	else
		$classes[] = md_has_sidebar() ? 'block-double-content' : 'block-full-content';
	$classes = apply_filters( 'md_filter_content_text_classes', $classes );
	return join( ' ', $classes );
}

/**
 * Headline area spacing.
 *
 * @since 4.1
 * @deprecated 4.9.2
 */
function md_headline_block() {
	if ( md_has_sidebar() )
		return 'block-double';
	else
		return 'block-triple';
}

// DEPRECATED 4.9.1
function md_main_menu_classes(){}

// DEPRECATED 4.9
function md_layout(){}

// Deprecated 4.8.3
function md_featured_image_tax_data() {return array();}

// Deprecated 4.8
function md_featured_image_cover_classes(){return'';}
