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
 * The Google Fonts stylesheet URL for the fonts and weights in use.
 * Kept so sites that picked Google Fonts before 6.2 keep loading them;
 * 6.2 fonts come from font stacks, the Font Library or the theme.
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
	$typography = md_setting( 'typography' );
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
