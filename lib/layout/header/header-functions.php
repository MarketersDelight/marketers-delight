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

	if ( md_has_logo() )
		$classes[] = 'has-logo';

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
 * Displays the logo, used in header by default.
 *
 * @since 4.1
 */

function md_logo() {
	include( md_template( 'logo', true ) );
}

function md_the_logo() {
	$has_logo_html = md_setting( array( 'colors', 'logo_html_display', 'enable' ) );
	$logo_html = md_setting( array( 'colors', 'logo_html' ) );

	if ( $has_logo_html && ! empty( $logo_html ) )
		echo $logo_html;
	else {
		$logo_id = md_setting( array( 'colors', 'logo', 'id' ) );
		$secondary_logo = md_setting( array( 'colors', 'logo_alt', 'url' ) );
		$text_global = md_setting( array( 'colors', 'page_cover', 'styles', 'text_color' ) );
		$text_single = md_post_meta( array( 'page_cover', 'text_color', 'alternate' ) );
		$cover = md_cover();
		if ( ( ( ( is_singular() || is_category() || is_tax() ) && $cover['position'] == 'header_cover_full' ) || apply_filters( 'md_filter_logo_alt', false ) ) && ! empty( $secondary_logo ) ) {
			if ( ( ! empty( $logo_id ) && $secondary_logo ) && ( ( empty( $text_global ) && empty( $text_single ) ) || ( ! empty( $text_global ) && ! empty( $text_single ) ) ) )
				md_secondary_logo();
			else
				echo wp_get_attachment_image( $logo_id, 'full', false, array( 'class' => 'custom-logo-link' ) );
		}
		else
			echo wp_get_attachment_image( $logo_id, 'full', false, array( 'class' => 'custom-logo-link' ) );
	}
}

function md_secondary_logo() {
	$secondary_logo_id = md_setting( array( 'header', 'logo_alt', 'id' ) );
	echo '<span class="custom-logo-link">';
	if ( ! empty( $secondary_logo_id ) )
		echo wp_get_attachment_image( $secondary_logo_id, 'full' );
	echo '</span>';
}

/**
 * Render Site Title as default WP text or custom title.
 *
 * @since 5.5.8
 */

function md_site_title() {
	$title = get_bloginfo( 'name' );
	return md_setting( array( 'header', 'site_title' ), $title );
}

/**
 * Render Site Tagline as default WP text or custom tagline.
 *
 * @since 5.5.8
 */

function md_site_tagline() {
	$tagline = get_bloginfo( 'description' );
	return md_setting( array( 'header', 'site_tagline' ), $tagline );
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
 * Checks if has header search.
 *
 * @since 5.6
 */

function md_has_header_search() {
	$header_elements = md_get_builder( 'header' );
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
