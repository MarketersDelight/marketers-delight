<?php

/**
 * Featured Image HTML output.
 *
 * @since 4.0
 */

function md_featured_image( $loop = array() ) {
	if ( ! has_post_thumbnail() )
		return;

	if ( isset( $loop['featured_image'] ) )
		$position = $loop['featured_image'];
	else
		$position = md_featured_image_position();

	if ( $position == 'remove' )
		return;

	if ( is_singular() ) { // Posts with Covers
		$cover = md_cover();

		if ( in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) )
			$position = 'center';
	}

	if ( isset( $loop['show_image'] ) && ! in_array( $position, $loop['show_image'] ) )
		return;

	if ( isset( $loop['image_inline'] ) && ! in_array( $position, array( '', 'left', 'right', 'center' ) ) )
		return;

	$permalink = get_permalink();
	$wrap = 'wrap';
	$size = 'full';

	if ( is_singular() && in_the_loop() )
		$permalink = '';

	if ( isset( $loop['featured_image_size'] ) )
		$size = $loop['featured_image_size'];

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
	$single = md_post_type_field( array( 'layout', 'featured_image' ), $post_type );
	$context = isset( $args['context'] ) ? $args['context'] : 'post';

	if ( $context == 'page' ) {
		if ( is_category() || is_tax() )
			$position = md_term_meta( array( 'featured_image', 'position' ), null, $default );
		else
			$position = md_post_type_field( array( 'featured_image', 'position' ), $default );
	}
	else {
		if ( is_singular() )
			$position = md_post_meta( array( 'featured_image', 'position' ), null, $single );
		else
			$position = $post_type;

		if ( ( is_category() || is_tax() ) && empty( $position ) )
			$position = md_term_meta( array( 'loop', 'featured_image' ), null, $post_type );
	}

	return $position;
}

/**
 * Get Cover attributes for any given page.
 *
 * @since 4.1
 * @renamed 6.0 (md_featured_image_style)
 */

function md_cover( $context = 'post' ) {
	$cover = array();

	if ( $context == 'page' )
		if ( is_category() || is_tax() )
			$cover = md_term_meta( 'page_cover' );
		else
			$cover = md_post_type_field( 'page_cover' );
	else
		$cover = md_post_meta( 'page_cover' );

	if ( ! empty( $cover['position'] ) ) {
		if ( ! empty( $cover['image'] ) )
			$cover['style'] = array(
				'bg_image' => esc_url( $cover['image']['url'] ),
	//			'bg_size' => $cover['image'][1] < 500 ? 'auto' : 'cover'
				'bg_size' => 'auto'
			);

		if ( $cover['position'] == 'header_cover_full' && ( $context !== 'post' || $context == 'post' && is_singular() ) ) {
			unset( $cover['style'] );
			$cover['display']['disable_cover'] = true;
		}
	}
	else
		$cover['position'] = '';

	return $cover;
}

/**
 * Return an array of class names related to a Cover.
 *
 * @since 6.0
 */

function md_cover_classes( $cover, $string = false ) {
	$classes = array();

	if ( ! empty( $cover['position'] ) ) {
		if ( $cover['position'] == 'header_cover_full' )
			$classes[] = 'format';

		if ( empty( $cover['hide_cover'] ) ) {
			$classes[] = 'cover';
			$classes[] = str_replace( '_', '-', $cover['position'] );

			if ( ! empty( $cover['display']['alternate'] ) )
				$classes[] = 'alt';
		}
	}

	if ( ! empty( $string ) )
		$classes = join( ' ', $classes );

	return $classes;
}

/**
 * A simple and thorough check to detect Page Cover.
 *
 * @since 6.0
 */

function md_has_cover() {
	$cover = md_cover();

	if ( ! empty( $cover['id'] ) && ! empty( $cover['position'] ) )
		return true;

	return false;
}

/**
 * Get caption from image attachment or default to featured image.
 *
 * @since 4.0
 */

function md_get_caption( $id = null ) {
	if ( ! is_singular() || ! in_the_loop() )
		return;

	if ( empty( $id ) )
		$id = get_post_thumbnail_id();

	$caption = wp_get_attachment_caption( $id );

	if ( ! empty( $caption ) )
		$caption = '<p class="wp-caption-text">' . $caption . '</p>';

	echo $caption;
}

/**
 * Add Overlay HTML to covers.
 *
 * @since 4.8.6
 */

function md_overlay( $cover ) {
	if ( empty( $cover['position'] ) || ! empty( $cover['display']['disable_cover'] ) )
		return;

	$style = array();

	if ( ! empty( $cover['bg_color'] ) )
		$style['bg_color'] = $cover['bg_color'];

	echo '<div class="overlay"' . md_style( $style ) . '></div>';
}
