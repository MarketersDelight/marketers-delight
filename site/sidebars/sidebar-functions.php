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

function md_has_sidebar() {
	$show = false;

	if ( has_filter( 'md_filter_has_sidebar' ) )
		return apply_filters( 'md_filter_has_sidebar', $show );

	if ( ! is_active_sidebar( md_get_sidebar_id() ) )
		return $show;

	$page = 'single';

	if ( is_home() || is_post_type_archive() )
		$page = 'archive';
	elseif ( is_category() || is_tax() )
		$page = 'term';

	$post_type = md_get_post_type();
	$global = md_post_type_field( array( 'layout', 'sidebar', 'global' ) );
	$single = md_module( array( 'layout', 'sidebar' ) );

	$display = md_post_type_field( array( 'layout', "sidebar_{$page}_show" ) );

	if ( $global ) {
		if ( empty( $display['disable'] ) && empty( $single['remove'] ) )
			$show = true;
	}
	elseif ( ! empty( $display['enable'] ) || ! empty( $single['add'] ) )
		$show = true;

	return $show;
}

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
