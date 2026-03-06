<?php

/**
 * Render the WordPress Featured Image for Posts and MD Pages.
 * $args take same as other elements that accept $loop, like md_content( $loop ).
 *
 * @since 4.0
 * @renamed from md_featured_image 6.0
 */

function md_featured_media( $context = 'post', $args = array() ) {
	$media = md_has_media( $context, $args );

	if ( empty( $media ) )
		return;

	$permalink = '';
	$type = $media['media_type'];
	$style = ! empty( $media['image_width'] ) ? md_style( array( 'max_width' => $media['image_width'] . 'px' ) ) : '';

	if ( $context == 'post' && ! is_singular() && ! is_404() )
		$permalink = get_permalink();

	$size = 'full';

	if ( isset( $args['size'] ) )
		$size = $args['size'];
	elseif ( is_author() )
		$size = 250;

	include md_template( 'featured-media', true );
}

/**
 * Get Featured Image data based on current page.
 *
 * @since 6.0
 */

function md_get_media( $context = 'post' ) {
	$option = array();
	$defaults = array(
		'media_type' => 'image',
		'position' => md_media_position( $context )
	);

	if ( $context == 'page' ) {
		$option = md_module( 'featured_media', array() );

		if ( is_author() )
			$option['author'] = true;
	}
	else {
		$option = md_post_meta( 'featured_media', array() );

		if ( get_post_thumbnail_id() ) {
			$option['image']['id'] = get_post_thumbnail_id();
			$option['image_width'] = md_post_meta( array( 'layout', 'featured_image_width' ) );
		}
	}

	$media = wp_parse_args( $option, $defaults );

	if ( ! is_singular() && $media['media_type'] == 'custom_html' )
		$media['media_type'] = 'image';

	return $media;
}

/**
 * Check if have Post/Page Image based on criteria.
 *
 * @since 6.0
 */

function md_has_media( $context = 'post', $args = array() ) {
	$media = array_merge( md_get_media( $context ), $args );
	$type = $media['media_type'];
	$position = $media['position'];

	if ( isset( $media['featured_image'] ) )
		$position = $media['featured_image'];

	if ( $position == 'remove' )
		return;

	if (
		( $type == 'image' && empty( $media['image']['id'] ) && empty( $media['author'] ) ) ||
		( empty( $media[$type] ) )
	)
		return;

	if ( isset( $media['show_image'] ) && ! in_array( $position, $media['show_image'] ) )
		return;

	if ( isset( $media['hide_image'] ) && in_array( $position, $media['hide_image'] ) )
		return;

	return $media;
}

/**
 * Returns position value for Post/Page Image.
 *
 * @since 4.1
 * @renamed md_featured_image_position 6.0
 */

function md_media_position( $context = 'post' ) {
	$default = 'right';

	if ( is_author() ) // 6.0 - author is hardcoded until dedicated author admin page is added
		$position = $default;
	elseif ( $context == 'page' ) {
		$position = md_post_type_field( array( 'featured_media', 'position' ), $default );

		if ( is_category() || is_tax() )
			$position = md_term_meta( array( 'featured_media', 'position' ), null, $position );
	}
	else {
		$position = md_post_type_field( array( 'layout', 'featured_image' ), $default );
		$inherit = md_post_type_field( array( 'loop', 'inherit', 'position' ) );

		if ( is_singular() )
			$position = md_post_meta( array( 'layout', 'featured_image' ), null, $position );
		else {
			$position = md_post_type_field( array( 'loop', 'featured_image' ), $position );

			if ( is_category() || is_tax() )
				$position = md_term_meta( array( 'loop', 'featured_image' ), null, $position );

			if ( $inherit )
				$position = md_post_meta( array( 'layout', 'featured_image' ), null, $position );
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
	$inherit = md_post_type_field( array( 'loop', 'inherit', 'page_cover' ) );

	if ( $context == 'page' ) {
		if ( is_post_type_archive() && ! empty( $post_type['display']['archive'] ) )
			$cover = $post_type;
		elseif ( is_category() || is_tax() ) {
			$cover = array_filter( md_term_meta( 'page_cover', null, array() ) );

			if ( ! empty( $post_type['display']['term'] ) )
				$cover = array_merge( $post_type, $cover );
		}
	}
	elseif ( ! empty( $post_type['display']['single'] ) )
		$cover = array_merge( $post_type, $cover );

	if (
		( isset( $cover['position'] ) && $cover['position'] == 'remove' ) ||
		( ! is_singular() && ! $inherit )
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