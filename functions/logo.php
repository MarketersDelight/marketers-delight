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
 * Only use to display text on page, not recommended in <title>.
 *
 * @since 5.5.8
 */

function md_site_title() {
	$title = get_bloginfo( 'name' );

	return md_setting( array( 'logo', 'site_title', 'text' ), $title );
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

	return md_setting( array( 'logo', 'site_tagline', 'text' ), $tagline );
}

/**
 * Checks if logo is enabled through custom options.
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
 * @renamed 6.0 (formerly md_has_custom_logo)
 */

function md_custom_logo() {
	$custom_logo = false;
	$logo = md_setting( 'logo' );

	if ( ! empty( $logo['logo_html_display']['enable'] ) && ! empty( $logo['logo_html'] ) )
		$custom_logo = $logo['logo_html'];
	elseif ( ! empty( $logo['logo']['id'] ) ) {
        $logo_id = $logo['logo']['id'];
		$cover = md_cover();

		if ( ! empty( $logo['logo_alt']['id'] ) && $cover['position'] == 'header_cover_full' )
			$logo_id = $logo['logo_alt']['id'];

		$custom_logo = wp_get_attachment_image( $logo_id, 'full' );
	}

	if ( $custom_logo )
		$custom_logo = '<div class="logo"><a href="' . home_url( '/' ) . '">' . $custom_logo . '</a></div>';

	return $custom_logo;
}

/**
 * Displays the logo, used in header by default.
 *
 * @since 4.1
 */

function md_logo() {
	include( md_template( 'logo', true ) );
}
