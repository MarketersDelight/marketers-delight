<?php

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
 * Render Site Title as default WP text or custom title.
 *
 * @since 5.5.8
 */

function md_site_title() {
	$title = get_bloginfo( 'name' );

	return md_setting( array( 'logo', 'site_title_text' ), $title );
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
 * Render Site Tagline as default WP text or custom tagline.
 *
 * @since 5.5.8
 */

function md_site_tagline() {
	$tagline = get_bloginfo( 'description' );

	return md_setting( array( 'logo', 'site_tagline_text' ), $tagline );
}

/**
 * Checks if logo is enabled.
 *
 * @since 4.1
 */

function md_has_logo() {
	if ( ( md_custom_logo() || md_has_site_title() || md_has_tagline() ) && ! md_module( array( 'layout', 'header', 'logo' ) ) )
		return true;
}

/**
 * Render custom logo image/markup.
 *
 * @since 4.5.4
 * @renamed 5.6 (formerly md_has_custom_logo)
 */

function md_custom_logo() {
	$logo = false;
	$logo_id = md_setting( array( 'logo', 'logo', 'id' ) );
	$has_logo_html = md_setting( array( 'logo', 'logo_html_display', 'enable' ) );
	$logo_html = md_setting( array( 'logo', 'logo_html' ) );

	if ( $has_logo_html && $logo_html )
		$logo = $logo_html;

	if ( $logo_id ) {
		$secondary_logo_id = md_setting( array( 'logo', 'logo_alt', 'id' ) );
		$cover = md_cover();

		if ( $secondary_logo_id && $cover['position'] == 'header_cover_full' )
			$logo_id = $secondary_logo_id;

		$logo = wp_get_attachment_image( $logo_id, 'full' );
	}

	if ( $logo )
		$logo = '<div class="logo"><a href="' . home_url( '/' ) . '">' . $logo . '</a></div>';

	return $logo;
}

/**
 * Displays the logo, used in header by default.
 *
 * @since 4.1
 */

function md_logo() {
	include( md_template( 'logo', true ) );
}
