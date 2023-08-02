<?php

/**
 * A collection of classes used to turn an element into
 * a background cover.
 *
 * @since 5.6
 */

function md_cover_classes( $custom = array() ) {
	$classes = array();
	$cover = md_cover();

	if ( isset( $custom ) )
		$classes = array_merge( $classes, $custom );

	if ( ! empty( $cover['position'] ) )
		$classes[] = 'cover';

	if ( in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) )
		$classes[] = 'format';

	if ( ! empty( $cover['text'] ) )
		$classes[] = 'text-alt';

	return join( ' ', $classes );
}

/**
 * Checks for content headline.
 *
 * @since 4.1
 */

function md_has_headline_cover() {
	$cover = md_cover();
	return in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) && is_singular() ? true : false;
}

/**
 * Outputs inline style CSS to add featured image to element if
 * image position is set to header cover or headline cover.
 *
 * @since 4.1
 * @formerly md_featured_image_cover
 * @changed 5.6
 */

function md_cover_style() {
	$cover = md_cover();

	if ( ! empty( $cover['image'] ) && ( in_array( $cover['position'], array( 'headline_cover', 'header_cover' ) ) || ( in_the_loop() && ! is_singular() && $cover['position'] == 'header_cover_full' ) ) )
		return md_style( array(
			'bg_image' => esc_url( $cover['image'][0] ),
			'bg_size' => $cover['image'][1] < 500 ? 'auto' : 'cover'
		) );
}

/**
 * Show image caption from Cover image.
 *
 * @since 5.6
 */

function md_cover_caption() {
	$cover = md_cover();
	if ( is_singular() && ! empty( $cover['position'] ) )
		md_get_caption( $cover['id'] );
}

/**
 * Outputs inline style CSS of featured image.
 *
 * @since 4.1
 * @renamed 5.6 (md_featured_image_style)
 */

function md_cover() {
	$id = $url = $position = '';
	$cover = array( 'id' => '', 'position' => '', 'image' => '', 'color' => '', 'text' => '' );

	$default_position = md_setting( array( 'colors', 'page_cover', 'cover_position' ) );
	$default_cover_id = md_setting( array( 'colors', 'page_cover', 'cover_image', 'id' ) );
	$default_color = md_setting( array( 'colors', 'page_cover', 'cover_color' ) );
	$default_text = md_setting( array( 'colors', 'page_cover', 'cover_styles', 'text_color' ) );
	$disable_overlay = md_setting( array( 'colors', 'page_cover', 'cover_styles', 'disable_cover' ) );

	if ( in_the_loop() ) {
		$position = md_post_meta( array( 'page_cover', 'cover_position' ), null, $default_position );
		$cover_id = md_post_meta( array( 'page_cover', 'cover_image', 'id' ), null, $default_cover_id );
		$color = md_post_meta( array( 'page_cover', 'bg_color' ), null, $default_color );
		$single_text = md_post_meta( array( 'page_cover', 'text_color', 'alternate' ), null, $default_text );
		$disable_single = md_post_meta( array( 'page_cover', 'text_color', 'disable_cover' ), null, $disable_overlay );
	}
	else {
		$position = md_module( array( 'page_cover', 'cover_position' ), $default_position );
		$cover_id = md_module( array( 'page_cover', 'cover_image', 'id' ), $default_cover_id );
		$color = md_module( array( 'page_cover', 'bg_color' ), $default_color );
		$single_text = md_module( array( 'page_cover', 'text_color', 'alternate' ), $default_text );
		$disable_single = md_module( array( 'page_cover', 'text_color', 'disable_cover' ), $disable_overlay );
	}

	$cover['position'] = $position;

	if ( empty( $cover['position'] ) )
		return $cover;

	if ( ! empty( $cover_id ) )
		$id = $cover_id;

	if ( empty( $id ) )
		return $cover;

	$image = wp_get_attachment_image_src( $id, 'full' );

	if ( ! empty( $image ) ) {
		$cover['id'] = $id;
		$cover['image'] = $image;
	}

	if ( ! empty( $color ) )
		$cover['color'] = $color;

	if ( ( ! empty( $default_text ) && empty( $single_text ) ) || ( empty( $default_text ) && ! empty( $single_text ) ) )
		$cover['text'] = true;

	if ( ( ! empty( $disable_overlay ) && empty( $disable_single ) ) || ( empty( $disable_overlay ) && ! empty( $disable_single ) ) )
		$cover['disable_overlay'] = true;

	return $cover;
}
