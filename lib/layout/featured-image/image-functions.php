<?php

/**
 * Featured Image HTML output.
 *
 * @since 4.0
 */

function md_featured_image( $size = null, $args = null ) {
	$position = isset( $args['position'] ) ? $args['position'] : md_featured_image_position();
	$image_id = null;

	if ( isset( $args['image_id'] ) )
		$image_id = esc_attr( $args['image_id'] );

	if ( ! isset( $size ) )
		if ( in_array( $position, array( '', 'left', 'right' ) ) )
			$size = 'md-block';
		else
			$size = 'full';

	$classes = array( 'featured-image' );

	if ( in_array( $position, array( '', 'right' ) ) )
		$classes[] = 'alignright wrap-small';
	elseif ( $position == 'left' )
		$classes[] = 'alignleft wrap-small';
	elseif ( $position == 'center' )
		$classes[] = 'aligncenter';

	$classes = join( ' ', $classes );

	include( md_template( 'featured-image', true ) );
}

/**
 * Insert featured image above/below headline with in-post check.
 *
 * @since 4.1
 * @moved 5.6
 */

function md_featured_image_before_headline() {
	$position = md_featured_image_position();

	if ( has_post_thumbnail() && $position == 'above_headline' )
		md_featured_image();
}

function md_featured_image_after_headline() {
	$position = md_featured_image_position();

	if ( has_post_thumbnail() && $position == 'below_headline' )
		md_featured_image();
}

/**
 * Returns position meta value.
 *
 * @since 4.1
 */

function md_featured_image_position( $position = null ) {
	if ( isset( $position ) )
		return $position;

	if ( has_filter( 'md_filter_featured_image_position' ) )
		return apply_filters( 'md_filter_featured_image_position', '' );

	$default = md_setting( array( 'colors', 'featured_image', 'position' ), 'right' );

	if ( in_the_loop() && ! is_singular() )
		$position = md_post_meta( array( 'featured_image', 'position' ), null, $default );
	else
		$position = md_module( array( 'featured_image', 'position' ), $default );

	return esc_attr( $position );
}

/**
 * Checks for inline Featured Image within #the_content.
 *
 * @since 4.1
 */

function md_has_inline_featured_image() {
	$position = md_featured_image_position();

	if ( has_post_thumbnail() && in_array( $position, array( '', 'left', 'right', 'center' ) ) )
		return true;
}

/**
 * Get caption from image attachment or default to featured image.
 *
 * @since 4.0
 */

function md_get_caption( $id = null ) {
	if ( empty( $id ) )
		$id = get_post_thumbnail_id();

	$caption = wp_get_attachment_caption( $id );

	if ( ! empty( $caption ) )
		echo '<p class="image-caption">' . $caption . '</p>';
}
