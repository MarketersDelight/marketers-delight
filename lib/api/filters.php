<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

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
		'md-full' => array(
			'width'  => 1118,
			'height' => 350
		),
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
		),
		'md-book' => array(
			'width' => 180,
			'height' => 270
		),
		'md-thumbnail' => array(
			'width'  => 80,
			'height' => 80
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
	$menus['main'] = __( 'Main Menu', 'md' );
	$menus['social'] = __( 'Social Media Menu', 'md' );

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
 * Default post type screens MD metaboxes are added to.
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
 * A list of Loops registered to MD's settings.
 *
 * @since 5.1
 */

function md_loops( $sort = null ) {
	$data = array();
	$default = array(
		'default' => __( 'Default', 'md' ),
		'teasers' => __( 'Teasers', 'md' ),
		'blocks' => __( 'Blocks', 'md' ),
		'docs' => __( 'Docs', 'md' )
	);
	$filter = apply_filters( 'md_filter_loops', array() );
	$loops = array_merge( $default, $filter );
	if ( isset( $sort ) ) {
		foreach ( $loops as $id => $label )
			if ( $sort == 'ids' )
				$data[] = $id;
	}
	else
		$data = $loops;
	return $data;
}

/**
 * Active list of byline items. Compares preset byline items (can
 * also be filtered in/out) with user settings).
 *
 * @since 4.5
 */

function md_byline_items( $sort = null ) {
	$default = array(
		'badge' => __( 'Add <b>New!</b> Badge', 'md' ),
		'avatar' => __( 'Add <b>Avatar</b>', 'md' ),
		'author' => __( 'Remove <b>Author</b>', 'md' ),
		'date' => __( 'Remove <b>Date</b>', 'md' ),
		'last-updated' => __( 'Add <b>Last Updated</b>', 'md' ),
		'category' => __( 'Add <b>Category</b>', 'md' ),
		'comments' => __( 'Remove <b>Comments</b>', 'md' ),
		'edit' => __( 'Remove <b>Edit</b>', 'md' )
	);
	$filter = apply_filters( 'md_filter_byline_items', array() );
	$settings = md_get_byline();
	$byline_items = array_merge( $default, $filter );
	$byline = array_diff( $byline_items, array_keys( $settings ) );

	if ( isset( $sort ) ) {
		foreach ( $byline as $id => $label )
			if ( $sort == 'ids' )
				$data[] = $id;
		return $data;
	}

	return $byline;
}

/**
 * Determine the logo HTML tag.
 *
 * @since 4.1
 */

function md_logo_html() {
	echo apply_filters( 'md_filter_logo_html', 'p' );
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
 * Collect sidebar data to load custom sidebars across
 * various post type screens (filter in your own CPTs
 * to add to the Sidebars Manager).
 *
 * @since 4.6.2
 */

function md_sidebars() {
	return apply_filters( 'md_filter_sidebars_post_types', array(
		'post' => array( 'archive' => true, 'single' => true ),
		'page' => array( 'single' => true )
	) );
}

/**
 * Manage number of footer columns with an array of digits.
 *
 * @since 4.5
 */

function md_filter_footer_columns() {
	return apply_filters( 'md_filter_footer_columns', array( 1, 2, 3 ) );
}

/*-- Left-behind functions from when drop-ins were removed from Core. --*/

/**
 * Compile Popups to load on any given page.
 *
 * @since 5.0
 * @moved to MD Optins 1.0.4
 */

function md_filter_popups() {
	return apply_filters( 'md_filter_popups', array() );
}

/**
 * A list of post types to show Share buttons on.
 *
 * @since 5.0
 * @moved to MD Share 1.0.2
 */

function md_share_post_types() {
	return array_merge( apply_filters( 'md_share_show_on', array() ), md_post_type_meta() );
}