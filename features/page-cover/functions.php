<?php

/**
 * Get Cover attributes for any given page.
 *
 * @since 4.1
 * @renamed 6.0 (md_featured_image_style)
 */

function md_cover( $context = null ) {
	if ( ! isset( $context ) )
		$context = is_singular() || is_404() ? 'post' : 'page';

	$cover = array();
	$inherit = false;
	$post_type_cover = md_post_type_field( 'page_cover', array() );

	if ( $context === 'post' ) {
		$inherit = md_post_type_field( array( 'loop', 'inherit', 'page_cover' ) );
		$single_cover = array_filter( md_post_meta( 'page_cover', null, array() ), function( $value ) {
			return $value !== '' && $value !== false && $value !== null && $value !== array();
		} );

		if ( is_category() || is_tax() ) {
			$inherit = md_taxonomy_field( array( 'loop', 'inherit', 'page_cover' ), $inherit );
			$inherit = md_term_meta( array( 'loop', 'inherit', 'page_cover' ), null, $inherit );
		}

		if ( ! empty( $post_type_cover['display']['single'] ) )
			$cover = array_replace_recursive( $post_type_cover, $single_cover );
		else
			$cover = $single_cover;
	}
	elseif ( is_post_type_archive() || is_home() )
		$cover = $post_type_cover;
	elseif ( is_category() || is_tax() ) {
		$tax_cover = md_taxonomy_field( 'page_cover', array() );
		$term_cover = array_filter( md_term_meta( 'page_cover', null, array() ), function( $value ) {
			return $value !== '' && $value !== false && $value !== null && $value !== array();
		} );
		$cover = array_replace_recursive( $post_type_cover, $tax_cover, $term_cover );
	}
	else $cover = md_post_meta( 'page_cover', true, array() );

	if (
		( isset( $cover['position'] ) && $cover['position'] === 'remove' ) ||
		( $context === 'post' && ! is_singular() && ! $inherit )
	)
		$cover = array();

	return $cover;
}

/**
 * Return an array of class names related to a Cover.
 *
 * @since 6.0
 */

function md_cover_classes( $context = 'post' ) {
	$classes = array();
	$cover = md_cover( $context );

	if ( empty( $cover['position'] ) )
		return $classes;

	$classes[] = 'cover';

	$classes[] = ! empty( $cover['display']['alternate'] ) || md_cover_is_light( $cover ) ? 'text-dark' : 'text-white';

	if ( ! empty( $cover['photo']['id'] ) )
		$classes[] = 'has-image';

	if ( ! empty( $cover['display']['bg_repeat'] ) )
		$classes[] = 'repeat';

	return join( ' ', $classes );
}

/**
 * Check whether the cover color is light enough for dark text.
 *
 * @since 6.0
 */

function md_cover_is_light( $cover ) {
	$color = md_color_hex( ! empty( $cover['bg_color'] ) ? $cover['bg_color'] : '#00000080' );

	if ( ! preg_match( '/^#?([a-f0-9]{6})/i', $color, $match ) )
		return false;

	$rgb = str_split( $match[1], 2 );
	$rgb = array_map( function( $value ) {
		$value = hexdec( $value ) / 255;
		return $value <= 0.03928 ? $value / 12.92 : pow( ( $value + 0.055 ) / 1.055, 2.4 );
	}, $rgb );

	return ( 0.2126 * $rgb[0] + 0.7152 * $rgb[1] + 0.0722 * $rgb[2] ) > 0.5;
}

/**
 * Check if has either type of Header Cover to make
 * special layout considerations. Headline cover == false.
 *
 * @return Returns value of cover position if set, FALSE if not
 * @since 6.0
 */

function md_has_header_cover( $context = null ) {
	if ( ! isset( $context ) )
		$context = is_singular() || is_404() ? 'post' : 'page';

	$cover = md_cover( $context );

	if (
		( ( $context == 'post' && ( is_singular() || is_404() ) ) || $context == 'page' ) &&
		! empty( $cover['position'] ) && in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ), true )
	)
		return esc_attr( $cover['position'] );
}

/**
 * Add Overlay HTML to covers.
 *
 * @since 4.8.6
 */

function md_overlay( $context = 'post' ) {
	$style = array();
	$cover = md_cover( $context );

	if ( empty( $cover['position'] ) || ! empty( $cover['display']['disable_overlay'] ) )
		return;

	if ( ! empty( $cover['bg_color'] ) )
		$style['bg_color'] = $cover['bg_color'];

	echo '<div class="overlay" aria-hidden="true"' . md_style( $style ) . '></div>';
}
