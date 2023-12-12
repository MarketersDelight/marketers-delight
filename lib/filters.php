<?php
/**
 * This file contains most actions and filters registered
 * throughout Marketers Delight. For use in Drop-ins and
 * Child Themes. Search FILTERS or HOOKS
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

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
 * Default post type screens MD metaboxes are added to.
 *
 * @since 4.3.5
 */

function md_post_type_meta() {
	return apply_filters( 'md_post_type_meta', array( 'post', 'page' ) );
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
 * Checks if page template is active.
 *
 * @since 4.9.4
 */

function md_filter_template() {
	return apply_filters( 'md_filter_has_template', true );
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
