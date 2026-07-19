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

		if ( isset( $args['show_image'] ) && ! in_array( $media['position'], $args['show_image'] ) )
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
    elseif ( is_author() )
		$size = 250;

	if ( ! empty( $media['image_width'] ) ) {
		$image_width = $media['image_width'] . 'px';

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

	include md_template( 'featured-media', true );
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

	if ( in_array( $position, $title_images ) ) {
		$classes[] = 'image-title';
		$classes[] = str_replace( '_', '-', $position );
	}
	elseif ( in_array( $position, $inline_images ) ) {
		$classes[] = 'image-inline';
		$classes[] = "image-{$position}";
	}
	elseif ( in_array( $position, $full_images ) ) {
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
		$option = md_module( 'featured_media', array() );

		if ( is_author() )
			$option['author'] = true;
	}
	else {
		$option = md_post_meta( 'featured_media', true, array() );

		if ( get_post_thumbnail_id() )
			$option['image']['id'] = get_post_thumbnail_id();
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
	$inherit = md_post_type_field( array( 'loop', 'inherit', 'position' ) );

	if ( is_category() || is_tax() ) {
		$inherit = md_taxonomy_field( array( 'loop', 'inherit', 'position' ), $inherit );
		$inherit = md_term_meta( array( 'loop', 'inherit', 'position' ), null, $inherit );
	}

	if ( $context == 'post' && isset( $args['loop']['featured_image'] ) && ! $inherit )
		$position = $args['loop']['featured_image'];

	if ( $position == 'remove' )
		return;

	if ( ! empty( $args['loop']['content'] ) && $args['loop']['content'] === 'hide' && in_array( $position, array( 'left', 'right', 'center' ) ) )
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

/**
 * Get Cover attributes for any given page.
 *
 * @since 4.1
 * @renamed 6.0 (md_featured_image_style)
 */

function md_cover( $context = 'post' ) {
	$cover = array();
	$inherit = false;
	$post_type_cover = md_post_type_field( 'page_cover' );

	if ( is_post_type_archive() || is_home() )
		$cover = $post_type_cover ?: array();
	elseif ( is_category() || is_tax() ) {
		$tax_cover = md_taxonomy_field( 'page_cover' ) ?: array();
		$term_cover = array_filter( md_term_meta( 'page_cover', null, array() ) );
		$cover = array_merge( $tax_cover, $term_cover );
	}
	elseif ( $context === 'post' ) {
		$inherit = md_post_type_field( array( 'loop', 'inherit', 'page_cover' ) );
		$single_cover = md_post_meta( 'page_cover', null, array() );

		if ( is_category() || is_tax() )
			$inherit = md_term_meta( array( 'loop', 'inherit', 'page_cover' ), null, $inherit );

		if ( ! empty( $post_type_cover['display']['single'] ) )
			$cover = array_merge( $post_type_cover, $single_cover );
		else
			$cover = $single_cover;
	}
	else $cover = md_post_meta( 'page_cover', true, array() );

	if (
		( isset( $cover['position'] ) && $cover['position'] === 'remove' ) ||
		( $context === 'post' && ! is_singular() && ! $inherit )
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

	if ( empty( $cover['display']['alternate'] ) )
		$classes[] = 'text-white';

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

	if ( empty( $cover['photo'] ) || empty( $cover['position'] ) || ! empty( $cover['display']['disable_overlay'] ) )
		return;

	if ( ! empty( $cover['bg_color'] ) )
		$style['bg_color'] = $cover['bg_color'];

	echo '<div class="overlay"' . md_style( $style ) . '></div>';
}