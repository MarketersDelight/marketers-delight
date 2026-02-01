<?php

/**
 * Render the WordPress Featured Image for Posts and MD Pages.
 * $args take same as other elements that accept $loop, like md_content( $loop ).
 *
 * @since 4.0
 */

function md_featured_image( $context = 'post', $args = array() ) {
	$image = md_has_image( $context, $args );

	if ( empty( $image ) )
		return;

	$size = 'full';

	if ( isset( $args['size'] ) )
		$size = $args['size'];
	elseif ( is_author() )
		$size = 250;

	$permalink = $context == 'post' && ! is_singular() && ! is_404() ? get_permalink() : '';
	$style = ! empty( $image['width'] ) ? md_style( array( 'max_width' => $image['width'] ) ) : '';

	include md_template( 'featured-image', true );
}

/**
 * Get Featured Image data based on current page.
 *
 * @since 6.0
 */

function md_get_image( $context = 'post' ) {
	$image = array( 'position' => md_image_position( $context ) );

	if ( $context == 'page' ) {
		$image_id = md_module( array( 'page_image', 'image', 'id' ) );
		$image_width = md_module( array( 'page_image', 'image_width' ) );

		if ( $image_id )
			$image['id'] = $image_id;

		if ( is_author() )
			$image['author'] = true;

		if ( $image_width )
			$image['width'] = $image_width;
	}
	elseif ( get_post_thumbnail_id() )
		$image['id'] = get_post_thumbnail_id();

	return $image;
}

/**
 * Check if have Post/Page Image based on criteria.
 *
 * @since 6.0
 */

function md_has_image( $context = 'post', $args = array() ) {
	$image = md_get_image( $context );
	$image = array_merge( $image, $args );
	$position = $image['position'];

	if ( isset( $image['featured_image'] ) )
		$position = $image['featured_image'];

	if ( $position == 'remove' )
		return;

	if ( empty( $image['id'] ) && empty( $image['author'] ) )
		return;

	if ( isset( $image['show_image'] ) && ! in_array( $position, $image['show_image'] ) )
		return;

	if ( isset( $image['hide_image'] ) && in_array( $position, $image['hide_image'] ) )
		return;

	return $image;
}

/**
 * Returns position value for Post/Page Image.
 *
 * @since 4.1
 * @renamed md_featured_image_position 6.0
 */

function md_image_position( $context = 'post' ) {
	$default = 'right';

	if ( is_author() ) // 6.0 - author is hardcoded until dedicated option page is added
		$position = $default;
	elseif ( $context == 'page' ) {
		$position = md_post_type_field( array( 'page_image', 'position' ), $default );

		if ( is_category() || is_tax() )
			$position = md_term_meta( array( 'page_image', 'position' ), null, $position );
	}
	else {
		$position = md_post_meta( array( 'layout', 'featured_image' ) );

		if ( empty( $position ) )
			if ( is_singular() )
				$position = md_post_type_field( array( 'layout', 'featured_image' ), $default );
			else {
				$loop = md_post_type_field( array( 'loop', 'featured_image' ), $default );
				$position = md_term_meta( array( 'loop', 'featured_image' ), null, $loop );
			}
	}

	return $position;
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
 * Get Cover attributes for any given page.
 *
 * @since 4.1
 * @renamed 6.0 (md_featured_image_style)
 */

function md_cover( $context = 'post' ) {
	$post_type = md_post_type_field( 'page_cover' );
	$cover = array_filter( md_post_meta( 'page_cover', null, array() ) );

	if ( $context == 'page' ) {
		$cover = md_post_type_field( 'page_cover' );

		if ( is_category() || is_tax() ) {
			$cover = array_filter( md_term_meta( 'page_cover', null, array() ) );

			if ( ! empty( $post_type['display']['term'] ) )
				$cover = array_merge( $post_type, $cover );
		}
	}
	elseif ( ! empty( $post_type['display']['single'] ) )
		$cover = array_merge( $post_type, $cover );

	if ( isset( $cover['position'] ) && $cover['position'] == 'remove' )
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

	if ( ! empty( $cover['display']['alternate'] ) )
		$classes[] = 'alt';

	if ( ! empty( $cover['display']['bg_repeat'] ) )
		$classes[] = 'repeat';

	return join( ' ', $classes );
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
		! empty( $cover['position'] ) && in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) )
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

	echo '<div class="overlay"' . md_style( $style ) . '></div>';
}