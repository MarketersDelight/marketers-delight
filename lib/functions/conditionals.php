<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Checks if logo is enabled.
 *
 * @since 4.1
 */

function md_has_logo() {
	if ( ( md_has_custom_logo() || md_has_site_title() ) && ! md_meta( array( 'layout', 'header', 'logo' ) ) )
		return true;
}

/**
 * Checks for WordPress' custom logo.
 *
 * @since 4.5.4
 */

function md_has_custom_logo() {
	if ( md_setting( array( 'header', 'logo', 'url' ) ) )
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
	if ( get_bloginfo( 'description' ) && ! md_setting( array( 'header', 'display', 'site_tagline' ) ) && ! md_meta( array( 'layout', 'header', 'tagline' ) ) )
		return true;
}

/**
 * Checks if menu is enabled.
 *
 * @since 4.1
 */

function md_has_menu( $name = null ) {
	$name = ! isset( $name ) ? 'header' : $name;
	if ( ! md_meta( array( 'layout', 'header', 'remove' ) ) && ! md_meta( array( 'layout', 'header', 'menu' ) ) && has_nav_menu( $name ) )
		return true;
}

/**
 * Checks if Main Menu is active on page.
 *
 * @since 4.1
 */

function md_has_main_menu() {
	$is_tax = is_category() || is_tax() ? true : false;
	if ( $is_tax )
		$single = md_term_meta( array( 'layout', 'main_menu' ) );
	else
		$single = md_post_meta( array( 'layout', 'main_menu' ) );
	$single_remove = isset( $single['remove'] ) ? $single['remove'] : '';
	if ( has_nav_menu( 'main' ) ) {
		if ( ( is_singular() || $is_tax ) && ! empty( $single_remove ) )
			return false;
		return apply_filters( 'md_filter_has_main_menu', true );
	}
}

/**
 * Checks if content box is enabled.
 *
 * @since 4.1
 */

function md_has_content_box() {
	if ( ! md_meta( array( 'layout', 'content', 'remove' ) ) )
		return apply_filters( 'md_filter_has_content_box', true );
}

/**
 * Checks for content headline.
 *
 * @since 4.1
 */

function md_has_headline_cover() {
	$position = md_featured_image_position();
	return in_array( $position, array( 'header_cover', 'header_cover_full' ) ) && is_singular() ? true : false;
}

/**
 * Checks if breadcrumbs are enabled.
 *
 * @since 5.2.2
 */

function md_has_breadcrumbs() {
	$enable = md_setting( array( 'content', 'post', 'breadcrumbs' ) );
	if (
		( ! empty( $enable ) && ! md_meta( array( 'layout', 'breadcrumbs', 'remove' ) ) ) ||
		( empty( $enable ) && md_meta( array( 'layout', 'breadcrumbs', 'add' ) ) )
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
 * Checks if a sidebar is active. This gets tricky.
 *
 * @since 4.1
 */

function md_has_sidebar() {
	$id = md_get_sidebar_id();

	if ( ! is_active_sidebar( $id ) )
		return false;

	$single = md_setting( array( 'content', 'sidebar', 'single' ) );
	$category = md_setting( array( 'content', 'sidebar', 'category' ) );
	$blog_remove = md_setting( array( 'content', 'sidebar', 'blog_remove' ) );

	$single_add = md_meta( array( 'layout', 'sidebar', 'add' ) );
	$single_remove = md_meta( array( 'layout', 'sidebar', 'remove' ) );

	if ( has_filter( 'md_filter_has_sidebar' ) )
		return apply_filters( 'md_filter_has_sidebar', '' );

	if ( ( is_home() || is_author() || is_tag() ) && empty( $blog_remove ) )
		return true;

	if ( ( is_category() || is_tax() ) && (
		( ! empty( $category ) && empty( $single_remove ) ) ||
		( empty( $category ) && ! empty( $single_add ) )
	) )
		return true;

	if ( is_single() && (
		( ! empty( $single ) && empty( $single_remove ) ) ||
		( empty( $single ) && ! empty( $single_add ) )
	) )
		return true;
	elseif ( is_singular() && ! empty( $single_add ) )
		return true;

	return false;
}

/**
 * Checks if footer is enabled.
 *
 * @since 4.1
 */

function md_has_footer() {
	if ( ! md_meta( array( 'layout', 'footer', 'remove' ) ) && ( md_has_footer_columns() || is_active_sidebar( 'footer-copy' ) ) )
		return apply_filters( 'md_filter_has_footer', true );
}

/**
 * Checks if footer columns are enabled.
 *
 * @since 4.1
 */

function md_has_footer_columns() {
	if ( md_footer_columns() && ! md_meta( array( 'layout', 'footer', 'columns' ) ) )
		return true;
}