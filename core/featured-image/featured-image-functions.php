<?php

/**
 * A collection of classes used to turn an element into
 * a background cover.
 *
 * @since 5.6
 */

function md_cover_classes() {
	$classes = array();
	$cover = md_cover();

	if ( ! empty( $cover['position'] ) )
		$classes[] = 'cover';

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
 * Checks for inline Featured Image.
 *
 * @since 4.1
 */

function md_has_inline_featured_image() {
	$position = md_featured_image_position();
	if ( has_post_thumbnail() && in_array( $position, array( '', 'left', 'right', 'center' ) ) )
		return true;
}

/**
 * Outputs inline style CSS to add featured image to element if
 * image position is set to header cover or headline cover.
 *
 * @since 4.1
 */

function md_featured_image_cover() {
	$cover = md_cover();

	if ( in_array( $cover['position'], array( 'headline_cover', 'header_cover' ) ) || ( in_the_loop() && ! is_singular() && $cover['position'] == 'header_cover_full' ) )
		return md_style( array(
			'bg_image' => esc_url( $cover['image'][0] ),
			'bg_size' => $cover['image'][1] < 500 ? 'auto' : 'cover'
		) );
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

	$default_position = md_setting( array( 'colors', 'featured_image', 'cover_position' ) );
	$single_position = md_meta( array( 'featured_image', 'cover_position' ), null, $default_position );
	$archive_position = md_post_type_field( array( 'featured_image', 'cover_position' ), $default_position );

	if ( ! empty( $single_position ) )
		$position = $single_position;
	elseif ( ! empty( $archive_position ) )
		$position = $archive_position;

	$cover['position'] = $position;

	if ( empty( $cover['position'] ) )
		return $cover;

	$default_cover = md_setting( array( 'colors', 'featured_image', 'cover_image', 'id' ) );
	$single_cover = md_meta( array( 'featured_image', 'cover_image', 'id' ) );
	$featured_image = get_post_thumbnail_id( get_the_ID() );

	if ( ! empty( $single_cover ) )
		$id = $single_cover;
	elseif ( ! empty( $default_cover ) )
		$id = $default_cover;
	elseif ( ! empty( $featured_image ) )
		$id = $featured_image;

	if ( empty( $id ) )
		return $cover;

	$image = wp_get_attachment_image_src( $id, 'full' );

	if ( ! empty( $image ) ) {
		$cover['id'] = $id;
		$cover['image'] = $image;
	}

	$default_color = md_setting( array( 'colors', 'featured_image', 'cover_color' ) );
	$color = md_meta( array( 'featured_image', 'bg_color' ), null, $default_color );

	if ( ! empty( $color ) )
		$cover['color'] = $color;

	$default_text = md_setting( array( 'colors', 'featured_image', 'cover_styles', 'text_color' ) );
	$single_text = md_meta( array( 'featured_image', 'text_color', 'alternate' ) );

	if ( ( ! empty( $default_text ) && empty( $single_text ) ) || ( empty( $default_text ) && ! empty( $single_text ) ) )
		$cover['text'] = true;

	$disable_overlay = md_setting( array( 'colors', 'featured_image', 'cover_styles', 'disable_cover' ) );
	$disable_single = md_meta( array( 'featured_image', 'text_color', 'disable_cover' ) );

	if ( ( ! empty( $disable_overlay ) && empty( $disable_single ) ) || ( empty( $disable_overlay ) && ! empty( $disable_single ) ) )
		$cover['disable_overlay'] = true;

	return $cover;
}