<?php
/**
 * This file contains most actions and filters registered
 * throughout Marketers Delight. For use in Drop-ins and
 * Child Themes.
 */

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
 * Get list of items that can be used in a Byline.
 *
 * @since 5.6
 */

function md_byline_items() {
	return apply_filters( 'md_byline', array() );
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
 * A list of post types and taxonomies to enable MD Optins features to.
 *
 * @since 5.0
 */

function md_optins_locations( $sort = null ) {
	$locations = array();
	$data = apply_filters( 'md_optins_locations', array(
		'sitewide' => __( 'Sitewide', 'md' ),
		'front' => __( 'Front Page', 'md' ),
		'home' => __( 'Blog Page', 'md' ),
		'post' => __( 'All Posts', 'md' ),
		'category' => __( 'All Categories', 'md' ),
		'page' => __( 'All Pages', 'md' ),
		'author' => __( 'All Author Pages', 'md' ),
		'search' => __( 'Search Results', 'md' )
	) );

	if ( isset( $sort ) ) {
		foreach ( $data as $id => $label ) {
			if ( $sort == 'ids' )
				$locations[] = $id;
			elseif ( $sort == 'options' )
				$locations[$id] = $label;
		}
	}
	else
		$locations = $data;

	return $locations;
}

/**
 * Manage number of footer columns with an array of digits.
 *
 * @since 4.5
 */

function md_filter_footer_columns() {
	return apply_filters( 'md_filter_footer_columns', array( 1, 2, 3 ) );
}
