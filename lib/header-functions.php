<?php

/**
 * Add classes to the Header Wrap area.
 *
 * @since 4.6
 */

function md_header_wrap_classes() {
	$classes = array();
	$classes[] = 'header-wrap';
	$classes = apply_filters( 'md_header_wrap_classes', $classes );

	return join( ' ', $classes );
}

/**
 * A list of classes to add to the header.
 *
 * @since 4.1
 */

function md_header_classes() {
	$layout = md_setting( array( 'header', 'layout' ), 'standard' );
	$classes = array();
	$classes[] = 'header';

	if ( md_setting( array( 'header', 'sticky', 'enable' ) ) )
		$classes[] = 'sticky';

	if ( ! md_has_menu() || ! md_has_logo() )
		$classes[] = 'header-simple';
	else
		$classes[] =  esc_attr( "header-{$layout}" );

	if ( $layout == 'flyer' ) {
		$header_aside = md_get_builder( 'header', 'data', 'header_aside' );

		if ( empty( $header_aside ) )
			$classes[] = 'solo';
	}

	$classes = apply_filters( 'md_filter_header_classes', $classes );

	return join( ' ', $classes );
}

/**
 * Checks if header is enabled.
 *
 * @since 4.1
 */

function md_has_header() {
	if ( ! md_module( array( 'layout', 'header', 'remove' ) ) && ( md_has_logo() || md_has_menu() ) )
		return apply_filters( 'md_filter_has_header', true );
}

/**
 * Displays the header menu.
 *
 * @since 4.1
 */

function md_header_menu() {
	$menu_location = is_user_logged_in() && has_nav_menu( 'header_loggedin' ) ? 'header_loggedin' : 'header';

	include( md_template( 'header-menu', true ) );
}

/**
 * Outputs the menu name assigned to the specified Menu area.
 *
 * @since 4.0
 */

function md_get_menu_name( $menu ) {
	$menus = get_nav_menu_locations();

	if ( ! empty( $menus[$menu] ) ) {
		$menu_object = wp_get_nav_menu_object( $menus[$menu] );
		$menu_name = isset( $menu_object->name ) ? $menu_object->name : '';
	}

	if ( empty( $menu_name ) )
		$menu_name = __( 'Menu', 'md' );

	return esc_html( $menu_name );
}

/**
 * Checks if menu is enabled.
 *
 * @since 4.1
 */

function md_has_menu() {
	$header_elements = md_get_builder( 'header' );

	if (
		! md_module( array( 'layout', 'header', 'remove' ) ) &&
		! md_module( array( 'layout', 'header', 'menu' ) ) &&
		( has_nav_menu( 'header' ) || ! empty( $header_elements['menu'] ) )
	)
		return true;
}

/**
 * Checks if has header search.
 *
 * @since 6.0
 */

function md_has_header_search() {
	$header_elements = md_get_builder( 'header' );

	return ( ! empty( $header_elements['search'] ) ? true : false );
}
