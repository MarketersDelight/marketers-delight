<?php

/**
 * Collect sidebar data to load custom sidebars across
 * various post type screens (filter in your own CPTs
 * to add to the Sidebars Manager).
 *
 * @since 4.6.2
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
 * Return a list of sidebar names by unique IDs.
 *
 * @since 4.6.2
 */

function md_get_sidebars() {
	$sidebars = array();
	$areas = md_setting( array( 'sidebars', 'areas' ), array() );

	foreach ( $areas as $area => $fields )
		$sidebars[$area] = $fields['name'];

	return array_filter( $sidebars );
}

/**
 * A list of classes to add to the sidebar.
 *
 * @since 4.5
 */

function md_sidebar_classes() {
	echo apply_filters( 'md_filter_sidebar_classes', '' );
}

/**
 * Checks if a sidebar is active. This gets tricky.
 *
 * @since 4.1
 */

function md_has_sidebar() {
	$id = md_get_sidebar_id();

	if ( ! is_active_sidebar( $id ) )
		return false;

	if ( has_filter( 'md_filter_has_sidebar' ) )
		return apply_filters( 'md_filter_has_sidebar', '' );

	$post_type = get_post_type();
	$sidebars = md_setting( 'sidebars' );
	$sitewide = md_setting( array( 'sidebars', 'display', 'sitewide' ) );
	$single_add = md_module( array( 'layout', 'sidebar', 'add' ) );
	$single_remove = md_module( array( 'layout', 'sidebar', 'remove' ) );

	if ( is_post_type_archive() || is_home() || is_author() || is_tag() )
		if ( ! empty( $sidebars["{$post_type}_archive_show"]['enable'] ) )
			return true;
		elseif ( $sitewide )
			return true;

	if ( is_category() || is_tax() ) {
		$term = get_queried_object();
		$taxonomy = esc_attr( $term->taxonomy );
		$site_enable = md_setting( array( 'sidebars', "{$post_type}_{$taxonomy}_show", 'enable' ) );
		$site_disable = md_setting( array( 'sidebars', "{$post_type}_{$taxonomy}_show", 'disable' ) );

		if ( $single_remove )
			return false;
		elseif ( ( ! $site_disable && ( $sitewide || $site_enable ) ) || $single_add )
			return true;
	}

	if ( is_singular() ) {
		$site_enable = md_setting( array( 'sidebars', "{$post_type}_single_show", 'enable' ) );
		$site_disable = md_setting( array( 'sidebars', "{$post_type}_single_show", 'disable' ) );

		if ( $single_remove )
			return false;
		elseif ( ( ! $site_disable && ( $sitewide || $site_enable ) ) || $single_add )
			return true;
	}
}

/**
 * Check if current admin page has sidebar enabled on frontend.
 *
 * @since 5.6
 */

function md_admin_has_sidebar() {
	$screen = get_current_screen();
	$post_type = esc_attr( $screen->post_type );
	$screen_base = esc_attr( $screen->base );
	$sitewide = md_setting( array( 'sidebars', 'display', 'sitewide' ) );

	if ( in_array( $screen_base, array( 'post', 'post-new' ) ) )
		$key = "{$post_type}_single_show";
	elseif ( $screen_base == 'term' )
		$key = "{$post_type}_{$screen->taxonomy}_show";
	elseif ( ! empty( $_GET['page'] ) ) {
		$page = md_clean_id( esc_attr( $_GET['page'] ) );
		$key = "{$page}_archive_show";
	}

	$site_enable = md_setting( array( 'sidebars', $key, 'enable' ) );
	$site_disable = md_setting( array( 'sidebars', $key, 'disable' ) );

	if ( ! $site_disable && ( $sitewide || $site_enable ) )
		return true;
}

/**
 * Get active sidebar ID for current page.
 *
 * @since 4.6.2.1
 */

function md_get_sidebar_id() {
	$name = 'sidebar-main';
	$post_type = get_post_type();
	$global = md_get_global_sidebar_id();
	$sidebar = md_module( array( 'layout', 'custom_sidebar' ) );

	if ( ( is_home() || is_post_type_archive() ) && $sidebar )
		$name = esc_attr( $sidebar );
	elseif ( md_meta( array( 'layout', 'custom_sidebar' ) ) )
		$name = md_meta( array( 'layout', 'custom_sidebar' ) );
	elseif ( ! empty( $global ) )
		$name = $global;

	return $name;
}

/**
 * Get active global sidebar ID for current page.
 *
 * @since 4.6.2.1
 */

function md_get_global_sidebar_id() {
	$name = '';
	$post_types = $term = array();
	$id = get_queried_object_id();
	$post_type = get_post_type();
	$sidebars = md_sidebars();
	$option = md_setting( 'sidebars' );

	// post types
	foreach ( $sidebars as $type => $pages )
		$post_types[] = $type;

	// taxonomies
	$taxonomies = get_taxonomies( array( 'public' => true ) );
	$terms = wp_get_post_terms( $id, $taxonomies );
	$tax_var = get_query_var( 'taxonomy' );

	foreach ( $terms as $term_count => $fields )
		if ( md_term_meta( array( 'layout', 'entries_sidebar' ), $fields->term_id ) ) {
			$term['taxonomy'] = $fields->taxonomy;
			$term['term_id'] = $fields->term_id;
		}

	$taxonomy = ! empty( $term['taxonomy'] ) ? $term['taxonomy'] : '';
	$term_id = ! empty( $term['term_id'] ) ? $term['term_id'] : '';

	// single posts in category sidebar
	if ( is_singular() && has_term( $term_id, $taxonomy ) && md_term_meta( array( 'layout', 'entries_sidebar' ), $term_id ) != '' )
		$name = md_term_meta( array( 'layout', 'entries_sidebar' ), $term_id );
	// global post types archive sidebar
	elseif ( ( is_home() || is_author() || is_post_type_archive( $post_type ) ) && ! empty( $sidebars[$post_type]['archive'] ) && ! empty( $option["{$post_type}_archive"] ) )
		$name = $option["{$post_type}_archive"];
	// global post types single sidebar
	elseif ( is_singular( $post_type ) && ! empty( $sidebars[$post_type]['single'] ) && ! empty( $option["{$post_type}_single"] ) )
		$name = $option["{$post_type}_single"];
	elseif ( ( is_category() || is_tax() ) && ! empty( $sidebars[$post_type][$tax_var] ) && ! empty( $option["{$post_type}_{$tax_var}"] ) )
		$name = $option["{$post_type}_{$tax_var}"];

	return $name;
}
