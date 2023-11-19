<?php

/**
 * Checks if logo is enabled.
 *
 * @since 4.1
 */

function md_has_logo() {
	if ( ( md_has_custom_logo() || md_has_site_title() || md_has_tagline() ) && ! md_module( array( 'layout', 'header', 'logo' ) ) )
		return true;
}

/**
 * Checks for WordPress' custom logo.
 *
 * @since 4.5.4
 */

function md_has_custom_logo() {
	$logo_image = md_setting( array( 'header', 'logo', 'url' ) );
	$has_logo_html = md_setting( array( 'header', 'logo_html_display', 'enable' ) );
	$logo_html = md_setting( array( 'header', 'logo_html' ) );

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
