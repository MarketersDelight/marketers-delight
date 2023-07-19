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
	$classes = array();
	$classes[] = 'header';

	if ( ! md_has_menu() || ! md_has_logo() )
		$classes[] = 'header-simple';
	else
		$classes[] =  'header-' . md_setting( array( 'header', 'layout' ), 'standard' );

	if ( md_has_logo() )
		$classes[] = 'has-logo';

	$classes = apply_filters( 'md_filter_header_classes', $classes );

	return join( ' ', $classes );
}

/**
 * Checks if has header search.
 *
 * @since 5.6
 */

function md_has_header_search() {
	$header_elements = unserialize( md_setting( array( 'header', 'builder_elements' ) ) );
	return ( ! empty( $header_elements['search'] ) ? true : false );
}

/**
 * Checks if Main Menu is active on page.
 *
 * @since 4.1
 */

function md_has_main_menu() {
	if ( ! has_nav_menu( 'main' ) )
		return;

	$remove = md_module( array( 'layout', 'main_menu', 'remove' ) );

	if ( ! empty( $remove ) )
		return false;

	return apply_filters( 'md_filter_has_main_menu', true );
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