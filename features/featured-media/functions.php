<?php

/**
 * Render the WordPress Featured Image for Posts and MD Pages.
 * $args take same as other elements that accept $loop, like md_content( $loop ).
 *
 * @since 4.0
 * @renamed from md_featured_image 6.0
 */

function md_featured_media( $context = 'post', $args = array() ) {
	$loop = ! empty( $args['loop'] ) ? $args['loop'] : md_get_loop();
	$args['loop'] = $loop;
	$classes = array( 'featured-media' );

	// Determine visibility on page

	if ( array_key_exists( 'media', $args ) ) {
		if ( empty( $args['media'] ) )
			return;

		$media = $args['media'];

		if ( isset( $args['show_image'] ) && ! in_array( $media['position'], $args['show_image'], true ) )
			return;
	}
	else $media = md_has_media( $context, $args );

	if ( empty( $media ) )
		return;

	// Set properties

	$permalink = '';
	$attr = $style = array();
	$type = $media['media_type'];

	if ( $context == 'post' && ! is_singular() && ! is_404() )
		$permalink = get_permalink();

	$size = 'full';

	if ( isset( $args['size'] ) )
		$size = $args['size'];
    elseif ( isset( $args['featured_image_size'] ) )
        $size = $args['featured_image_size'];
	elseif ( ! empty( $loop['featured_image_size'] ) )
		$size = $loop['featured_image_size'];
    elseif ( is_author() )
		$size = 250;

	if ( ! empty( $media['image_width'] ) ) {
		$image_width = 'min(' . $media['image_width'] . 'px, 100%)';

		if ( $type !== 'image' )
			$style['max_width'] = $image_width;

		$attr['style'] = "max-width: $image_width";
	}

	// Set classes

	$classes[] = 'media-' . str_replace( '_', '-', $type );

	if ( isset( $args['classes'] ) )
		$classes[] = $args['classes'];

	$classes = join( ' ', $classes );

	// Set inline style

	if ( isset( $args['style'] ) )
		$style = array_merge( $style, $args['style'] );

	$style = md_style( $style );

	// Render template

	include md_template( 'features', 'featured-media/featured-media', true );
}

/**
 * Return class names for any given image position.
 *
 * @since 6.0
 */

function md_get_image_position_classes( $position ) {
	$classes = array();
	$inline_images = array( 'left', 'right' );
	$full_images = array( 'center', 'above_headline', 'below_headline' );
	$title_images = array( 'title_left', 'title_right', 'title_center' );

	if ( in_array( $position, $title_images, true ) ) {
		$classes[] = 'image-title';
		$classes[] = str_replace( '_', '-', $position );
	}
	elseif ( in_array( $position, $inline_images, true ) ) {
		$classes[] = 'image-inline';
		$classes[] = "image-{$position}";
	}
	elseif ( in_array( $position, $full_images, true ) ) {
		$classes[] = 'image-full';
		$classes[] = 'image-' . str_replace( '_headline', '', $position );
	}

	return $classes;
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
		$option = md_module( 'featured_media', array(), array( 'inherit_post_type' => false ) );

		if ( is_author() )
			$option['author'] = true;
	}
	else {
		$option = md_post_meta( 'featured_media', true, array() );

		if ( get_post_thumbnail_id() ) {
			if ( empty( $option['image'] ) || ! is_array( $option['image'] ) )
				$option['image'] = array();

			$option['image']['id'] = get_post_thumbnail_id();
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

	if ( $context == 'post' && isset( $args['loop']['featured_image'] ) )
		$position = md_loop_media_position( $args['loop'] );

	if ( $position == 'remove' )
		return;

	if ( ! empty( $args['loop']['content'] ) && $args['loop']['content'] === 'hide' && in_array( $position, array( 'left', 'right', 'center' ), true ) )
		return;

	if (
		( $type == 'image' && empty( $media['image']['id'] ) && empty( $media['author'] ) ) ||
		( empty( $media[$type] ) )
	)
		return;

	if ( isset( $media['show_image'] ) && ! in_array( $position, $media['show_image'], true ) )
		return;

	if ( isset( $media['hide_image'] ) && in_array( $position, $media['hide_image'], true ) )
		return;

	return $media;
}

/**
 * Featured image position for the current post in a loop. When the loop is set to
 * inherit the position, a post's own Featured Media position wins over the loop's.
 *
 * @since 6.0
 */

function md_loop_media_position( $loop ) {
	$position = $loop['featured_image'] ?? '';

	if ( ! empty( $loop['inherit']['position'] ) && ( $own = md_post_meta( array( 'featured_media', 'position' ), null, '' ) ) )
		$position = $own;

	return $position;
}

/**
 * Returns position value for Post/Page Image.
 *
 * @since 4.1
 * @renamed md_featured_image_position 6.0
 */

function md_media_position( $context = 'post' ) {
	$default = 'right';

	if ( is_author() )
		return $default;

	$position = $default;
	$key = array( 'featured_media', 'position' );
	$single_key = array( 'layout', 'featured_image' );

	if ( $context === 'page' ) {
		$position = md_post_type_field( $key, $default );

		if ( is_category() || is_tax() ) {
			$position = md_taxonomy_field( $key, $position );
			$position = md_term_meta( $key, null, $position );
		}
	}
	elseif ( is_singular() ) {
		$position = md_post_type_field( $single_key, $default );
		$position = md_post_meta( $key, null, $position );
	}
	else {
		$loop_key = array( 'loop', 'featured_image' );
		$inherit_key = array( 'loop', 'inherit', 'position' );

		$position = md_post_type_field( $single_key, $default );
		$position = md_post_type_field( $loop_key, $position );

		$is_term = is_category() || is_tax();

		if ( $is_term ) {
			$position = md_taxonomy_field( $loop_key, $position );
			$position = md_term_meta( $loop_key, null, $position );
		}

		$inherit = md_post_type_field( $inherit_key );

		if ( $is_term ) {
			$inherit = md_taxonomy_field( $inherit_key, $inherit );
			$inherit = md_term_meta( $inherit_key, null, $inherit );
		}

		if ( $inherit )
			$position = md_post_meta( $key, null, $position );
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
