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
	elseif ( $context === 'post' && ! empty( $loop['featured_image_size'] ) )
		$size = $loop['featured_image_size'];
    elseif ( is_author() )
		$size = 250;

	if ( ! empty( $media['image_width'] ) ) {
		$image_width = 'min(' . $media['image_width'] . 'px, 100%)';
		$style['vars']['md-media-width'] = absint( $media['image_width'] ) . 'px';

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

function md_get_image_position_classes( $position, $args = array() ) {
	$classes = array();
	$mobile = $args['mobile'] ?? '';
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

	if ( $mobile === 'hide' || ( $mobile === 'beside' && in_array( $position, array( 'left', 'right', 'title_left', 'title_right' ), true ) ) )
		$classes[] = "mobile-{$mobile}";

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
		$option = is_author()
			? md_post_type_field( 'featured_media', array(), 'author' )
			: md_module( 'featured_media', array(), array( 'inherit_post_type' => false ) );

		// An author page shows the author's photo, or the sitewide image without one

		if ( is_author() && ( $photo_id = md_author_photo_id( get_queried_object_id() ) ) ) {
			$option['image'] = array( 'id' => $photo_id );
			$option['author'] = get_queried_object_id();
		}
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
 * The post author's uploaded photo as a title image, when the post type
 * shows one and the post doesn't hide it. Authors without an uploaded
 * photo get no title image. 'author' marks the image as the author's.
 *
 * @since 6.0.4
 */

function md_get_title_author_media() {
	$settings = md_post_type_field( 'author_photo', array() );

	if ( empty( $settings['display']['show'] ) || md_post_meta( array( 'layout', 'content', 'hide_title_image' ) ) )
		return array();

	$author_id = (int) get_post_field( 'post_author' );
	$photo_id = md_author_photo_id( $author_id );

	if ( ! $photo_id )
		return array();

	return array(
		'media_type' => 'image',
		'image' => array( 'id' => $photo_id ),
		'author' => $author_id,
		'position' => ! empty( $settings['position'] ) ? $settings['position'] : 'title_left',
		'mobile' => $settings['mobile'] ?? '',
		'image_width' => $settings['image_width'] ?? ''
	);
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

	$media['position'] = $position;

	if ( $position == 'remove' )
		return;

	if ( $context == 'post' && ! empty( $args['loop']['content'] ) && $args['loop']['content'] === 'hide' && in_array( $position, array( 'left', 'right', 'center' ), true ) )
		return;

	if (
		( $type === 'image' && empty( $media['image']['id'] ) ) ||
		( $type !== 'image' && empty( $media[$type] ) )
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

	if ( is_author() && $context === 'page' )
		return md_post_type_field( array( 'featured_media', 'position' ), $default, 'author' );

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
		$settings_post_type = is_author() ? 'author' : null;

		$position = md_post_type_field( $single_key, $default, $settings_post_type );
		$position = md_post_type_field( $loop_key, $position, $settings_post_type );

		$is_term = is_category() || is_tax();

		if ( $is_term ) {
			$position = md_taxonomy_field( $loop_key, $position );
			$position = md_term_meta( $loop_key, null, $position );
		}

		$inherit = md_post_type_field( $inherit_key, null, $settings_post_type );

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
 * Featured Media fields and their save rules, shared by the Featured Media
 * settings and Title Sections.
 *
 * @since 6.0.4
 */

function md_featured_media_fields() {
	$data = new md_fields_data;

	return array(
		'media_type' => array(
			'type' => 'select',
			'options' => array( 'image', 'video', 'custom_html' ),
			'code_options' => array( 'custom_html' )
		),
		'position' => array(
			'type' => 'select',
			'options' => array_keys( $data->values['featured_image'] )
		),
		'image' => array(
			'type' => 'upload',
			'upload_type' => 'media'
		),
		'image_width' => array( 'type' => 'number' ),
		'mobile' => array(
			'type' => 'select',
			'options' => array( 'beside', 'hide' )
		),
		'video' => array( 'type' => 'url' ),
		'custom_html' => array( 'type' => 'code' )
	);
}

/**
 * Render the Featured Media settings: the media on the left, its layout
 * on the right. Shared by Featured Media and Title Sections. $args:
 * is_post (posts use their own featured image, so there's no upload),
 * image_only (no Media Type select), position_label (the empty Position
 * choice) and remove (offer "Don't show").
 *
 * @since 6.0.4
 */

function md_featured_media_admin( $fields, $prefix, $args = array() ) {
	$args = wp_parse_args( $args, array(
		'is_post' => false,
		'image_only' => false,
		'position_label' => __( 'Use default position', 'md' ),
		'remove' => true
	) );

	$media_type = $fields->module( 'media_type' );
	$position = $fields->module( 'position' ) ?: '';
	$can_edit_code = current_user_can( 'manage_options' ) && current_user_can( 'unfiltered_html' );
	$side_positions = array( '', 'title_left', 'title_right', 'left', 'right' );

	$positions = array(
		'title_left' => __( 'Left', 'md' ),
		'title_right' => __( 'Right', 'md' ),
		'title_center' => __( 'Center', 'md' ),
		'left' => __( 'Left', 'md' ),
		'right' => __( 'Right', 'md' ),
		'above_headline' => __( 'Above the title', 'md' ),
		'below_headline' => __( 'Below the title', 'md' ),
		'center' => __( 'At the bottom', 'md' )
	);

	$position_groups = array(
		__( 'Beside title', 'md' ) => array( 'title_left', 'title_right', 'title_center' ),
		__( 'Beside title and text', 'md' ) => array( 'left', 'right' ),
		__( 'Full width', 'md' ) => array( 'above_headline', 'below_headline', 'center' )
	);

	if ( $args['remove'] )
		$positions['remove'] = __( 'Don\'t show', 'md' );

	include md_template( 'features', 'featured-media/admin/featured-media', true );
}
