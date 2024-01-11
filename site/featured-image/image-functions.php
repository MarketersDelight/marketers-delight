<?php

/**
 * Featured Image HTML output.
 *
 * @since 4.0
 */

function md_featured_image( $args = array() ) {
	if ( ! has_post_thumbnail() )
		return;

	$pos_args = array();

	if ( isset( $args['position'] ) )
		$position = $args['position'];
	else
		$position = md_featured_image_position();

	if ( isset( $args['inline'] ) && ! in_array( $position, array( '', 'left', 'right', 'center' ) ) )
		return;

	if ( isset( $args['show'] ) && $position !== $args['show'] )
		return;

	$wrap = 'wrap';
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
 * Returns position meta value.
 *
 * @since 4.1
 */

function md_featured_image_position( $args = array() ) {
	$default = isset( $args['position'] ) ? $args['position'] : 'right';
	$post_type = md_post_type_field( array( 'loop', 'featured_image' ), $default );
	$context = isset( $args['context'] ) ? $args['context'] : 'post';

	if ( $context == 'page' ) {
		if ( is_category() || is_tax() )
			$position = md_term_meta( array( 'featured_image', 'position' ), null, $default );
		else
			$position = md_post_type_field( array( 'featured_image', 'position' ), $default );
	}
	else {
		$position = md_post_meta( array( 'featured_image', 'position' ), null, $post_type );

		if ( ( is_category() || is_tax() ) && empty( $position ) )
			$position = md_term_meta( array( 'loop', 'featured_image' ), null, $post_type );
	}

	return $position;
}

/**
 * Get caption from image attachment or default to featured image.
 *
 * @since 4.0
 */

function md_get_caption( $id = null ) {
	if ( ! is_singular() )
		return;

	if ( empty( $id ) )
		$id = get_post_thumbnail_id();

	$caption = wp_get_attachment_caption( $id );

	if ( ! empty( $caption ) )
		$caption = '<p class="wp-caption-text">' . $caption . '</p>';

	echo $caption;
}
