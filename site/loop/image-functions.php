<?php

/**
 * Featured Image HTML output.
 *
 * @since 4.0
 */

function md_featured_image( $context = 'post', $loop = array() ) {
	$featured_image = md_get_featured_image( $context );

	// If no image, stop function
	if ( empty( $featured_image['id'] ) )
		return;

	// Get Image Position
	if ( isset( $loop['featured_image'] ) )
		$position = $loop['featured_image'];
	else
		$position = md_featured_image_position( $context );

	// Hide image if user specified
	if ( $position == 'remove' )
		return;

	// Only show on specified position, if set from options
	if ( isset( $loop['show_image'] ) && ! in_array( $position, $loop['show_image'] ) )
		return;

	// Hide image on specified position, if set from options
	if ( isset( $loop['hide_image'] ) && in_array( $position, $loop['hide_image'] ) )
		return;

	// Set Permalink
	$permalink = '';

	if ( $context == 'post' )
		$permalink = get_permalink();

	// Move image out of Cover and into another position
	if ( is_singular() && in_the_loop() ) {
		$cover = md_cover();
		$permalink = '';

		if ( in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) )
			$position = 'center';
	}

	// Set image size
	$size = 'full';

	if ( isset( $loop['featured_image_size'] ) )
		$size = $loop['featured_image_size'];

	// Render Image
	echo '<div class="featured-image">';

	if ( $context == 'page' && ! empty( $featured_image['width'] ) )
		echo md_inline_css( array(
			'.page-header .featured-image' => array(
				'max-width' => array(
					'query' => $featured_image['width'],
					'unit' => 'px'
				)
			)
		) );

	echo
		 ( $permalink ? '<a href="' . esc_url( $permalink ) . '">' : '' ).
		 wp_get_attachment_image( $featured_image['id'], $size ).
		 ( $permalink ? '</a>' : '' ).
		 md_get_caption().
		 '</div>'.
		 md_hook_after_featured_image();
}

/**
 * Call the Page Featured Image (does not apply to Single).
 *
 * @since 6.0
 */

function md_page_featured_image() {
	md_featured_image( 'page' );
}

/**
 * Get Featured Image data.
 *
 * @since 6.0
 */

function md_get_featured_image( $context = 'post', $field = null ) {
	$featured_image = array();
	$defaults = array(
		'id' => get_post_thumbnail_id(),
		'position' => md_featured_image_position(),
		'width' => array()
	);

	if ( $context == 'page' ) {
		$featured_image['id'] = md_module( array( 'hero', 'image', 'id' ) );
		$featured_image['position'] = md_featured_image_position( 'page' );
		$featured_image['width'] = md_module( array( 'hero', 'image_width' ), array() );
	}

	$featured_image = wp_parse_args( $featured_image, $defaults );

	if ( isset( $field ) )
		return $featured_image[$field];

	return $featured_image;
}

/**
 * Returns position meta value.
 *
 * @since 4.1
 */

function md_featured_image_position( $context = null ) {
	$default = md_post_type_field( array( 'layout', 'featured_image' ), 'right' );

	if ( $context == 'page' ) {
		if ( is_category() || is_tax() )
			$position = md_term_meta( array( 'hero', 'image_position' ), null, $default );
		else
			$position = md_post_type_field( array( 'hero', 'image_position' ), $default );
	}
	else {
		$post_type = md_post_type_field( array( 'loop', 'featured_image' ), $default );

		if ( is_singular() )
			$position = md_post_meta( array( 'layout', 'featured_image' ), null, $default );
		elseif ( is_category() || is_tax() )
			$position = md_term_meta( array( 'hero', 'image_position' ), null, $post_type );
		else
			$position = $post_type;
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

	if ( $context == 'page' ) {
		if ( is_category() || is_tax() )
			$hero = md_term_meta( 'hero' );
		else
			$hero = md_post_type_field( 'hero' );
	}
	else
		$hero = md_post_meta( 'hero' );

	if ( ! empty( $hero['cover_position'] ) ) {
		$cover['position'] = $hero['cover_position'];

		if ( ! empty( $hero['cover_photo'] ) ) {
			$cover['photo'] = $hero['cover_photo'];
			$cover['style'] = array(
				'bg_image' => esc_url( $cover['photo']['url'] ),
				'bg_size' => 'auto'
			);
		}

		if ( ! empty( $hero['cover_bg_color'] ) )
			$cover['bg_color'] = $hero['cover_bg_color'];

		if ( ! empty( $hero['cover_display'] ) )
			$cover['display'] = $hero['cover_display'];
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
		if ( empty( $cover['hide_cover'] ) ) {
			$classes[] = 'cover';
			$classes[] = str_replace( '_', '-', $cover['position'] );

			if ( ! empty( $cover['display']['alternate'] ) )
				$classes[] = 'alt';
		}

		if ( $cover['position'] == 'header_cover_full' )
			$classes[] = 'format';
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

	if ( empty( $cover['position'] ) )
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

	return $caption;
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
