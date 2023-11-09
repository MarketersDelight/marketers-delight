<?php
/**
 * This file contains most actions and filters registered
 * throughout Marketers Delight. For use in Drop-ins and
 * Child Themes. Search FILTERS or HOOKS
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*------------------------------*\
	FILTERS
\*------------------------------*/

/**
 * A collection of all registered components and fields for build and save.
 *
 * @since 5.0
 */

function md_register( $group = null ) {
	$data = apply_filters( 'md_register', array() );

	if ( isset( $group ) )
		return ! empty( $data[$group] ) ? $data[$group] : array();

	return $data;
}

/**
 * Filter the default image sizes of MD.
 *
 * @since 4.7.4.4
 */

function md_image_sizes() {
	return apply_filters( 'md_filter_image_sizes', array(
		'md-banner' => array(
			'width'  => 600,
			'height' => 250
		),
		'md-block' => array(
			'width'  => 550,
			'height' => 550
		),
		'md-image' => array(
			'width'  => 325,
			'height' => 425
		)
	) );
}

/**
 * Default nav menus.
 *
 * @since 4.1
 */

function md_filter_register_nav_menus() {
	$menus['header'] = __( 'Header Menu', 'md' );
	$menus['header_loggedin'] = __( 'Header Menu (logged-in users only)', 'md' );

	return apply_filters( 'md_filter_register_nav_menus', $menus );
}

/**
 * Default post type screens MD metaboxes are added to.
 *
 * @since 4.3.5
 */

function md_post_type_meta() {
	return apply_filters( 'md_post_type_meta', array( 'post', 'page' ) );
}

/**
 * Default post type screens MD meta boxes are added to.
 *
 * @since 5.3.1
 */

function md_filter_css_values() {
	return apply_filters( 'md_filter_css_values', array() );
}

/**
 * Default taxonomy screens MD metaboxes are added to.
 *
 * @since 4.5.4
 */

function md_taxonomy_meta() {
	return apply_filters( 'md_taxonomy_meta', array( 'category' ) );
}

/**
 * A list of page settings modules to add across various page types.
 *
 * @since 5.6
 */

function md_admin_settings() {
	return apply_filters( 'md_admin_settings', array() );
}

/**
 * A reverse list of admin settings locations by fields.
 *
 * @since 5.6
 */

function md_admin_fields() {
	$fields = array();
	$settings = md_admin_settings();

	foreach ( $settings as $setting => $options )
		foreach ( $options as $field )
			$fields[$field][] = $setting;

	return $fields;
}

/**
 * A collection of save fields to be pre-grouped for Page Settings.
 *
 * @since 5.6
 */

function md_page_settings_fields() {
	$sanitize = new md_sanitize;

	return apply_filters( 'md_page_settings_fields', array(
		'archives_title' => array( 'type' => 'text' ),
		'archives_text' => array( 'type' => 'textarea' ),
		'featured_image' => array(
			'image' => array(
				'type' => 'upload',
				'upload_type' => 'media'
			),
			'position' => array(
				'type' => 'select',
				'options' => array_keys( $sanitize->values['featured_image'] )
			)
		),
		'page_display' => array(
			'type' => 'checkbox',
			'options' => array( 'stats', 'category', 'show_on_posts' )
		)
	) );
}

/**
 * Checks if page template is active.
 *
 * @since 4.9.4
 */

function md_filter_template() {
	return apply_filters( 'md_filter_has_template', true );
}

/**
 * Determine the logo HTML tag.
 *
 * @since 4.1
 */

function md_logo_html() {
	return apply_filters( 'md_filter_logo_html', 'div' );
}

/**
 * Compile Popups to load on any given page.
 *
 * @since 5.0
 */

function md_filter_popups() {
	return apply_filters( 'md_filter_popups', array() );
}

/**
 * A list of post types to show Share buttons on.
 *
 * @since 5.0
 */

function md_share_post_types() {
	return array_merge( apply_filters( 'md_share_show_on', array() ), md_post_type_meta() );
}

/**
 * Filter comments classes.
 *
 * @since 5.0.9
 */

function md_filter_comments_classes() {
	$classes = array();
	$classes[] = 'comments';
	$classes = apply_filters( 'md_filter_comments_classes', $classes );

	return join( ' ', $classes );
}

/**
 * Manage number of footer columns with an array of digits.
 *
 * @since 4.5
 */

function md_filter_footer_columns() {
	return apply_filters( 'md_filter_footer_columns', array( 1, 2, 3 ) );
}




/*------------------------------*\
	HOOKS
\*------------------------------*/

function md_block_editor_css() {
	do_action( 'md_block_editor_css' );
}

function md_hook_css_data() { // 5.3.1.1
	do_action( 'md_hook_css_data' );
}

function md_hook_before_html() {
	do_action( 'md_hook_before_html', 'before_html' );
}

function md_hook_header() {
	do_action( 'md_hook_header' );
}

function md_hook_header_top() {
	do_action( 'md_hook_header_top' );
}

function md_hook_header_bottom() {
	do_action( 'md_hook_header_bottom' );
}

function md_hook_before_header() {
	do_action( 'md_hook_before_header', 'before_header' );
}

function md_hook_after_header() {
	do_action( 'md_hook_after_header', 'after_header' );
}

function md_hook_before_header_menu() {
	do_action( 'md_hook_before_header_menu' );
}

function md_hook_after_header_menu() {
	do_action( 'md_hook_after_header_menu' );
}

function md_hook_header_details() {
	do_action( 'md_hook_header_details' );
}

function md_hook_header_logo_bottom() {
	do_action( 'md_hook_header_logo_bottom' );
}

function md_hook_after_site_title() {
	do_action( 'md_hook_after_site_title' );
}

function md_hook_before_content_box() {
	do_action( 'md_hook_before_content_box', 'before_content_box' );
}

function md_hook_content_box_top() {
	do_action( 'md_hook_content_box_top' );
}

function md_hook_content_box_bottom() {
	do_action( 'md_hook_content_box_bottom' );
}

function md_hook_content() {
	do_action( 'md_hook_content', 'content' );
}

function md_hook_content_top() {
	do_action( 'md_hook_content_top' );
}

function md_hook_content_bottom() {
	do_action( 'md_hook_content_bottom' );
}

function md_hook_before_content() {
	do_action( 'md_hook_before_content', 'before_content' );
}

function md_hook_after_content() {
	do_action( 'md_hook_after_content', 'after_content' );
}

function md_hook_content_item() {
	do_action( 'md_hook_content_item', 'content' );
}

function md_hook_before_the_content() {
	do_action( 'md_hook_before_the_content', '' );
}

function md_hook_before_headline() {
	do_action( 'md_hook_before_headline', 'before_headline' );
}

function md_hook_after_headline() {
	do_action( 'md_hook_after_headline', 'after_headline' );
}

function md_hook_before_title() {
	do_action( 'md_hook_before_title', 'before_headline' );
}

function md_hook_after_title() {
	do_action( 'md_hook_after_title', 'after_headline' );
}

function md_hook_before_headline_area() {
	do_action( 'md_hook_before_headline_area', 'before_headline_area' );
}

function md_hook_after_headline_area() {
	do_action( 'md_hook_after_headline_area', 'after_headline_area' );
}

function md_hook_post_controls() {
	do_action( 'md_hook_post_controls' );
}

function md_hook_page_title() {
	do_action( 'md_hook_page_title' );
}

function md_hook_before_page_title() {
	do_action( 'md_hook_before_page_title', 'before_page_title' );
}

function md_hook_after_page_title() {
	do_action( 'md_hook_after_page_title', 'after_page_title' );
}

function md_hook_byline_top() {
	do_action( 'md_hook_byline_top' );
}

function md_hook_byline_bottom() {
	do_action( 'md_hook_byline_bottom' );
}

function md_hook_after_featured_image() {
	do_action( 'md_hook_after_featured_image' );
}

function md_hook_featured_image_bottom() {
	do_action( 'md_hook_featured_image_bottom' );
}

function md_hook_featured_post_bottom() {
	do_action( 'md_hook_featured_post_bottom' );
}

function md_hook_x_loop( $c ) {
	$x_loop = md_module( array( 'loop', 'cta_x_loop' ) );
	$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

	if ( $c == $x_loop && $paged == 1 )
		do_action( 'md_hook_x_loop' );
}

function md_hook_comments() {
	do_action( 'md_hook_comments' );
}

function md_hook_before_comments_list() {
	do_action( 'md_hook_before_comments_list' );
}

function md_hook_after_comments_list() {
	do_action( 'md_hook_after_comments_list' );
}

function md_hook_before_sidebar() {
	do_action( 'md_hook_before_sidebar', 'before_sidebar' );
}

function md_hook_after_sidebar() {
	do_action( 'md_hook_after_sidebar', 'after_sidebar' );
}

function md_hook_before_footer() {
	do_action( 'md_hook_before_footer', 'before_footer' );
}

function md_hook_footer() {
	do_action( 'md_hook_footer' );
}

function md_hook_footer_top() {
	do_action( 'md_hook_footer_top' );
}

function md_hook_footer_bottom() {
	do_action( 'md_hook_footer_bottom' );
}

function md_hook_after_footer() {
	do_action( 'md_hook_after_footer' );
}

function md_hook_before_footer_copy() {
	do_action( 'md_hook_before_footer_copy' );
}

function md_hook_after_footer_copy() {
	do_action( 'md_hook_after_footer_copy' );
}

function md_hook_js() {
	do_action( 'md_hook_js' );
}
