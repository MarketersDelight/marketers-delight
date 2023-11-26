<?php

/**
 * Displays the logo, used in header by default.
 *
 * @since 4.1
 */

function md_logo() {
	include( md_template( 'logo', true ) );
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
	if ( ( md_has_custom_logo() || md_has_site_title() || md_has_tagline() ) && ! md_module( array( 'layout', 'header', 'logo' ) ) )
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
 * Checks for WordPress' custom logo.
 *
 * @since 4.5.4
 */

function md_has_custom_logo() {
	$logo_image = md_setting( array( 'logo', 'logo', 'url' ) );
	$has_logo_html = md_setting( array( 'logo', 'logo_html_display', 'enable' ) );
	$logo_html = md_setting( array( 'logo', 'logo_html' ) );

	if ( $logo_image || ( $has_logo_html && ! empty( $logo_html ) ) )
		return true;
}

/**
 * Render secondary logo for dark mode.
 *
 * @since 4.5.4
 */

function md_secondary_logo() {
	$secondary_logo_id = md_setting( array( 'logo', 'logo_alt', 'id' ) );

	echo '<span class="custom-logo-link">';

	if ( ! empty( $secondary_logo_id ) )
		echo wp_get_attachment_image( $secondary_logo_id, 'full' );

	echo '</span>';
}

/**
 * Final logo logic and rendering.
 *
 * @since 4.5.4
 */

function md_the_logo() {
	$has_logo_html = md_setting( array( 'logo', 'logo_html_display', 'enable' ) );
	$logo_html = md_setting( array( 'logo', 'logo_html' ) );

	if ( $has_logo_html && ! empty( $logo_html ) )
		echo $logo_html;
	else {
		$logo_id = md_setting( array( 'logo', 'logo', 'id' ) );
		$secondary_logo = md_setting( array( 'logo', 'logo_alt', 'url' ) );
		$text_global = md_setting( array( 'colors', 'page_cover', 'styles', 'text_color' ) );
		$text_single = md_post_meta( array( 'colors', 'text_color', 'alternate' ) );
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
