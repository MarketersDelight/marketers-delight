<?php

/**
 * Featured Image HTML output.
 *
 * @since 4.0
 */

function md_featured_image( $position = null, $size = null, $args = null ) {
	$position = isset( $position ) ? $position : md_featured_image_position();

	if ( ! isset( $size ) )
		if ( in_array( $position, array( '', 'left', 'right' ) ) )
			$size = 'md-image';
		else
			$size = 'full';

	$classes = array( 'featured-image' );

	if ( in_array( $position, array( '', 'right' ) ) )
		$classes[] = 'alignright wrap';
	elseif ( $position == 'left' )
		$classes[] = 'alignleft wrap';
	elseif ( $position == 'center' )
		$classes[] = 'aligncenter';

	$classes = join( ' ', $classes );

	include( md_template( 'featured-image', true ) );
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

	$default = md_setting( array( 'colors', 'featured_image', 'position' ) );

	return md_module( array( 'featured_image', 'position' ), $default );

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
