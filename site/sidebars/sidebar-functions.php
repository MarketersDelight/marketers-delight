<?php

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
 * Checks page for active sidebar.
 *
 * @since 4.1
 */

function md_has_sidebar( $args = array() ) {
	$show = false;

	if ( has_filter( 'md_filter_has_sidebar' ) )
		return apply_filters( 'md_filter_has_sidebar', $show );

	if ( ! is_active_sidebar( md_get_sidebar_id() ) )
		return $show;

	$post_type = isset( $args['post_type'] ) ? $args['post_type'] : md_get_post_type();
	$post_id = isset( $args['post_id'] ) ? $args['post_id'] : get_queried_object_id();

	if ( isset( $args['page'] ) )
		$page = $args['page'];
	elseif ( is_home() || is_post_type_archive() )
		$page = 'archive';
	elseif ( is_category() || is_tax() )
		$page = 'term';
	else
		$page = 'single';

	$display = md_post_type_field( array( 'layout', "sidebar_{$page}_show" ), null, $post_type );
	$global = md_post_type_field( array( 'layout', 'sidebar', 'global' ), null, $post_type );
	$single = md_meta( array( 'layout', 'sidebar' ), $post_id );

	if ( $global ) {
		if ( empty( $display['disable'] ) && empty( $single['remove'] ) )
			$show = true;
	}
	elseif ( ! empty( $display['enable'] ) || ! empty( $single['add'] ) )
		$show = true;

	return $show;
}

/**
 * Check if current admin page has sidebar enabled on frontend.
 *
 * @since 5.6
 */
/**
 * Get active sidebar ID for current page.
 *
 * @since 4.6.2.1
 */

function md_get_sidebar_id() {
	$sidebar = 'sidebar-main';

	if ( is_home() || is_post_type_archive() ) {
		$sidebar = md_post_type_field( array( 'layout', 'sidebar_archive' ), $sidebar );
	}
	elseif ( is_category() || is_tax() ) {
		$sidebar = md_post_type_field( array( 'layout', 'sidebar_term' ), $sidebar );
	}
	elseif ( is_singular() ) {
		$sidebar = md_post_type_field( array( 'layout', 'sidebar_single' ), $sidebar );
	}

	return $sidebar;
}
