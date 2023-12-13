<?php

/**
 * Featured Image HTML output.
 *
 * @since 4.0
 */

function md_featured_image( $size = 'full', $args = null ) {
	$position = isset( $args['position'] ) ? $args['position'] : md_featured_image_position();
	$image_id = null;
	$wrap = 'wrap';

	if ( isset( $args['image_id'] ) )
		$image_id = esc_attr( $args['image_id'] );

	$classes = array( 'featured-image' );

	if ( md_has_sidebar() )
		$wrap = 'wrap-small';

	if ( in_array( $position, array( '', 'right' ) ) )
		$classes[] = "alignright $wrap";
	elseif ( $position == 'left' )
		$classes[] = "alignleft $wrap";
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

function md_featured_image_position( $args = null ) {
	if ( isset( $args['position'] ) )
		return $args['position'];

	if ( has_filter( 'md_filter_featured_image_position' ) )
		return apply_filters( 'md_filter_featured_image_position', '' );

	$context = isset( $args['context'] ) ? $args['context'] : 'post';
	$post_type = md_post_type_field( array( 'loop', 'featured_image' ) );

	if ( $context == 'page' )
		if ( is_category() || is_tax() )
			$position = md_term_meta( array( 'featured_image', 'position' ) );
		else
			$position = md_post_type_field( array( 'featured_image', 'position' ) );
	else {
		$position = md_post_meta( array( 'featured_image', 'position' ), null, $post_type );

		if ( ( is_category() || is_tax() ) && empty( $position ) )
			$position = md_term_meta( array( 'loop', 'featured_image' ), null, $post_type );
	}

	return $position;
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
	$caption = '';

	if ( empty( $id ) )
		$id = get_post_thumbnail_id();

	$caption = wp_get_attachment_caption( $id );

	if ( ! empty( $caption ) )
		$caption = '<p class="wp-caption-text">' . $caption . '</p>';

	return $caption;
}
