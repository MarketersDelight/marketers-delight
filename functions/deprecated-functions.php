<?php

/**
 * Kept for the Glossary Drop-in until it moves to md_email().
 *
 * @since 4.9
 * @deprecated 6.0
 */

function md_email_data( $atts = null ) {
	return array();
}

/**
 * The Google Fonts URL for the fonts in use, kept for sites that picked them
 * before 6.2.
 *
 * @since 4.8
 * @deprecated 6.2
 */

function md_google_fonts() {
	$parts = array();
	$fonts = md_web_fonts( 'google' );

	foreach ( $fonts as $name => $weights ) {
		$normal = $italic = array();

		foreach ( $weights as $w ) {
			if ( substr( $w, -1 ) === 'i' )
				$italic[] = substr( $w, 0, -1 );
			else
				$normal[] = $w;
		}

		if ( ! empty( $italic ) ) {
			$combos = array();

			foreach ( $normal as $w )
				$combos[] = "0,$w";

			foreach ( $italic as $w )
				$combos[] = "1,$w";

			sort( $combos );

			$parts[] = urlencode( $name ) . ':ital,wght@' . implode( ';', $combos );
		}
		elseif ( ! empty( $normal ) )
			$parts[] = urlencode( $name ) . ':wght@' . implode( ';', $normal );
		else
			$parts[] = urlencode( $name );
	}

	return 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', $parts ) . '&display=swap';
}

/**
 * Fonts and weights in use, by font type (google, typekit). Kept so
 * sites that picked Google or Adobe Fonts before 6.2 keep loading them.
 *
 * @since 4.8
 * @deprecated 6.2
 */

function md_web_fonts( $show_type = null ) {
	$fonts = array();
	$typography = md_value( 'typography' );
	$headings = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'header', 'sidebar_title', 'footer_title' );
	$areas = array_merge( array( 'body', 'site_title', 'site_tagline', 'sidebar', 'footer' ), $headings );
	$body = $typography['body'] ?? array();
	$h1 = $typography['h1'] ?? array();
	$body_t = $body['font_type'] ?? '';
	$body_f = $body['font_family'] ?? '';
	$bold = $body['bold'] ?? '';
	$h1_t = $h1['font_type'] ?? '';
	$h1_f = $h1['font_family'] ?? '';

	foreach ( $areas as $area ) {
		$a = $typography[$area] ?? array();
		$type = $a['font_type'] ?? '';
		$family = $a['font_family'] ?? '';
		$weight = $a['font_weight'] ?? '';
		$style = $a['font_style'] ?? '';

		if ( ! empty( $weight ) ) {
			$weights = array( $weight );

			if ( $type == 'google' && ! empty( $style ) )
				$weights[] = "{$weight}i";

			if ( empty( $family ) ) {
				if ( in_array( $area, $headings ) && ! empty( $h1_f ) )
					$fonts[$h1_t][$h1_f] = array_merge( $fonts[$h1_t][$h1_f] ?? array(), $weights );
				elseif ( ! empty( $body_f ) )
					$fonts[$body_t][$body_f] = array_merge( $fonts[$body_t][$body_f] ?? array(), $weights );
			}
			else $fonts[$type][$family] = array_merge( $fonts[$type][$family] ?? array(), $weights );
		}
		elseif ( ! empty( $family ) )
			$fonts[$type][$family] = array();

		if ( $area == 'body' && $bold )
			$fonts[$type][$family][] = $bold;
	}

	foreach ( $fonts as $type => $families )
		foreach ( $families as $family => $weights )
			$fonts[$type][$family] = array_unique( $weights );

	return isset( $show_type ) ? ( $fonts[$show_type] ?? '' ) : $fonts;
}

/**
 * A post type's setting, its settings parents' included. Use md_value().
 *
 * @since 6.0
 * @deprecated 6.2.2
 */

function md_post_type_field( $keys = null, $default = null, $post_type = null ) {
	if ( ! isset( $post_type ) )
		$post_type = md_get_post_type();

	return md_value( array(
		'key' => $keys,
		'default' => $default,
		'post_type' => $post_type
	) );
}

/**
 * A taxonomy's setting for a post type. Use md_value().
 *
 * @since 6.0
 * @deprecated 6.2.2
 */

function md_taxonomy_field( $keys = null, $default = null, $post_type = null, $taxonomy = null ) {
	if ( ! $post_type )
		$post_type = md_get_post_type();

	if ( ! $taxonomy ) {
		$queried = get_queried_object();
		$taxonomy = isset( $queried->taxonomy ) ? $queried->taxonomy : null;
	}

	if ( ! $taxonomy )
		return $default;

	return md_value( array(
		'key' => $keys,
		'default' => $default,
		'taxonomy' => $taxonomy,
		'post_type' => $post_type,
		'inherit_post_type' => false
	) );
}

/**
 * The setting for the page being viewed. Use md_value().
 *
 * @since 4.7
 * @deprecated 6.2.2
 */

function md_module( $keys = null, $default = null, $args = array() ) {
	$args = wp_parse_args( $args, array(
		'id' => null,
		'inherit_post_type' => true
	) );

	return md_value( array(
		'key' => $keys,
		'default' => $default,
		'page' => true,
		'id' => $args['id'],
		'inherit_post_type' => $args['inherit_post_type']
	) );
}

