<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

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

// Old Byline hooks, deprecated 5.1
function md_hook_byline_top() {
	do_action( 'md_hook_byline_top' );
}
function md_hook_byline_bottom() {
	do_action( 'md_hook_byline_bottom' );
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