<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Checks if page template is active.
 *
 * @since 4.9.4
 */

function md_filter_template() {
	return apply_filters( 'md_filter_has_template', true );
}

/**
 * Checks if logo is enabled.
 *
 * @since 4.1
 */

function md_has_logo() {
	if ( ( md_has_custom_logo() || md_has_site_title() || md_has_tagline() ) && ! md_meta( array( 'layout', 'header', 'logo' ) ) )
		return true;
}

/**
 * Checks for WordPress' custom logo.
 *
 * @since 4.5.4
 */

function md_has_custom_logo() {
	$logo_image = md_setting( array( 'colors', 'logo', 'url' ) );
	$has_logo_html = md_setting( array( 'colors', 'logo_html_display', 'enable' ) );
	$logo_html = md_setting( array( 'colors', 'logo_html' ) );

	if ( $logo_image || ( $has_logo_html && ! empty( $logo_html ) ) )
		return true;
}

/**
 * Check for MD Site Title.
 *
 * @since 4.5.4
 */

function md_has_site_title() {
	if ( ! md_setting( array( 'header', 'display', 'site_title' ) ) )
		return true;
}

/**
 * Checks if page has tagline.
 *
 * @since 4.4.2
 */

function md_has_tagline() {
	if ( get_bloginfo( 'description' ) && ! md_setting( array( 'header', 'display', 'site_tagline' ) ) && ! md_module( array( 'layout', 'header', 'tagline' ) ) )
		return true;
}

/**
 * Checks if menu is enabled.
 *
 * @since 4.1
 */

function md_has_menu() {
	$header_elements = unserialize( md_setting( array( 'header', 'builder_elements' ) ) );
	if (
		! md_module( array( 'layout', 'header', 'remove' ) ) &&
		! md_module( array( 'layout', 'header', 'menu' ) ) &&
		( has_nav_menu( 'header' ) || ! empty( $header_elements['menu'] ) )
	)
		return true;
}

/**
 * Checks if content box is enabled.
 *
 * @since 4.1
 */

function md_has_content_box() {
	if ( ! md_module( array( 'layout', 'content', 'remove' ) ) )
		return apply_filters( 'md_filter_has_content_box', true );
}

/**
 * Checks if breadcrumbs are enabled.
 *
 * @since 5.2.2
 */

function md_has_breadcrumbs() {
	$enable = md_setting( array( 'content', 'post', 'breadcrumbs' ) );
	if (
		( ! empty( $enable ) && ! md_module( array( 'layout', 'breadcrumbs', 'remove' ) ) ) ||
		( empty( $enable ) && md_module( array( 'layout', 'breadcrumbs', 'add' ) ) )
	)
		return true;
}

/**
 * Checks if headline is enabled.
 *
 * @since 4.1
 */

function md_has_headline() {
	if ( ! md_meta( array( 'layout', 'content', 'headline' ) ) )
		return true;
}

/**
 * Checks if byline is enabled.
 *
 * @since 4.1
 */

function md_has_byline() {
	$add_byline = md_post_meta( array( 'layout', 'content', 'add_byline' ) );
	$remove_byline = md_post_meta( array( 'layout', 'content', 'byline' ) );

	if ( ( ! is_page() && ! is_404() && ! $remove_byline ) || ( is_page() && $add_byline ) )
		return true;
}

/**
 * Checks if author box.
 *
 * @since 4.5
 */

function md_has_author_box() {
	$enable = md_setting( array( 'content', 'author_box', 'enable' ) );
	if (
		( is_singular( 'post' ) && ! empty( $enable ) && ! md_post_meta( array( 'layout', 'content', 'author_box' ) ) ) ||
		( is_singular() && md_post_meta( array( 'layout', 'content', 'add_author_box' ) ) )
	)
		return true;
}

/**
 * Checks if comments are on page.
 *
 * @since 4.1
 */

function md_has_comments() {
	if ( ( comments_open() || get_comments_number() != 0 ) && ! post_password_required() )
		return true;
}

/**
 * Checks if footer is enabled.
 *
 * @since 4.1
 */

function md_has_footer() {
	if ( ! md_module( array( 'layout', 'footer', 'remove' ) ) && ( md_has_footer_columns() || is_active_sidebar( 'footer-copy' ) ) )
		return apply_filters( 'md_filter_has_footer', true );
}

/**
 * Checks if footer columns are enabled.
 *
 * @since 4.1
 */

function md_has_footer_columns() {
	if ( md_footer_columns() && ! md_module( array( 'layout', 'footer', 'columns' ) ) )
		return true;
}