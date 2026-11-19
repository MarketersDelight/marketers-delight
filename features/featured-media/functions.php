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

	if ( $context == 'post' && ! md_is_queried_post() && ! is_404() )
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

	if ( $permalink && $type === 'image' && ! get_post_meta( $media['image']['id'], '_wp_attachment_image_alt', true ) )
		$attr['alt'] = get_the_title();

	if ( $type === 'image' && ! in_the_loop() && wp_increase_content_media_count( 0 ) >= wp_omit_loading_attr_threshold() )
		$attr['loading'] = 'lazy';

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
			? md_value( array(
				'key' => 'featured_media',
				'default' => array(),
				'post_type' => 'author'
			) )
			: md_value( array(
				'key' => 'featured_media',
				'default' => array(),
				'page' => true,
				'inherit_post_type' => false
			) );
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

	if ( ! md_is_queried_post() && $media['media_type'] == 'custom_html' )
		$media['media_type'] = 'image';

	return apply_filters( 'md_media', $media, $context );
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

	if ( $type === 'latest' )
		$media['latest'] = md_latest_media_id();

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
	$key = array( 'featured_media', 'position' );
	$single_key = array( 'layout', 'featured_image' );
	$args = array(
		'key' => $key,
		'default' => 'right',
		'post_type' => is_author() ? 'author' : md_get_post_type()
	);

	// On term pages the taxonomy and term override the post type

	if ( is_category() || is_tax() ) {
		$args['term'] = get_queried_object_id();
		$args['taxonomy'] = get_queried_object()->taxonomy;
	}

	// The page's own title media

	if ( $context === 'page' ) {
		$position = md_value( $args );
	}

	// The single being viewed: the post type's single setting, then the post's own

	elseif ( md_is_queried_post() ) {
		$position = md_value( array(
			'key' => $single_key,
			'default' => 'right',
			'post_type' => $args['post_type']
		) );
		$position = md_post_meta( $key, null, $position );
	}

	// Loop items: single setting, then loop setting, then the post's own if inherited

	else {
		$position = md_value( array(
			'key' => $single_key,
			'default' => 'right',
			'post_type' => $args['post_type']
		) );

		$args['key'] = array( 'loop', 'featured_image' );
		$args['default'] = $position;
		$position = md_value( $args );

		$args['key'] = array( 'loop', 'inherit', 'position' );
		$args['default'] = null;

		if ( md_value( $args ) )
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
	if ( ! md_is_queried_post() || ! in_the_loop() )
		return;

	if ( empty( $id ) )
		$id = get_post_thumbnail_id();

	$caption = wp_get_attachment_caption( $id );

	if ( ! empty( $caption ) )
		$caption = '<p class="wp-caption-text">' . $caption . '</p>';

	return $caption;
}

/**
 * The newest item for a Latest item Featured Media: the newest post in the
 * current term, author or post type archive. Pages show the newest post.
 *
 * @since 6.2.4
 */

function md_latest_media_id() {
	$object = get_queried_object();
	$post_type = md_get_post_type();
	$id = 0;

	if ( ! $post_type || $post_type === 'page' )
		$post_type = 'post';

	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => 1,
		'ignore_sticky_posts' => true,
		'no_found_rows' => true,
		'fields' => 'ids'
	);

	if ( $object instanceof WP_Term ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => $object->taxonomy,
				'terms' => $object->term_id
			)
		);

		if ( md_is_shared_taxonomy( $object->taxonomy ) )
			$args['post_type'] = get_query_var( 'post_type' ) ?: md_taxonomy_post_types( $object->taxonomy );
	}
	elseif ( is_author() )
		$args['author'] = get_queried_object_id();

	$query = new WP_Query( apply_filters( 'md_latest_media_query', $args ) );

	if ( $query->posts )
		$id = $query->posts[0];

	return $id;
}

/**
 * Featured Media fields and their save rules, shared by the Featured Media
 * settings and Section Titles.
 *
 * @since 6.1
 */

function md_featured_media_fields() {
	$data = new md_fields_data;

	return array(
		'media_type' => array(
			'type' => 'select',
			'label' => __( 'Media Type', 'md' ),
			'options' => array( 'image', 'video', 'custom_html', 'latest' ),
			'code_options' => array( 'custom_html' )
		),
		'position' => array(
			'type' => 'select',
			'label' => __( 'Position', 'md' ),
			'options' => array_keys( $data->values['featured_image'] )
		),
		'image' => array(
			'type' => 'upload',
			'upload_type' => 'media',
			'label' => __( 'Image', 'md' )
		),
		'image_width' => array(
			'type' => 'number',
			'max' => 550,
			'label' => __( 'Max width', 'md' )
		),
		'mobile' => array(
			'type' => 'select',
			'label' => __( 'Position on mobile', 'md' ),
			'options' => array(
				'beside' => __( 'Keep beside', 'md' ),
				'hide' => __( 'Hide', 'md' )
			)
		),
		'video' => array(
			'type' => 'url',
			'label' => __( 'Video URL', 'md' )
		),
		'custom_html' => array(
			'type' => 'code',
			'label' => __( 'Custom HTML', 'md' )
		)
	);
}

/**
 * Render Featured Media settings, shared by Featured Media and Section Titles.
 * $args: is_post, image_only, position_label and remove.
 *
 * @since 6.1
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
	$type_options = array( 'video' => __( 'Video Embed', 'md' ) );

	if ( $can_edit_code )
		$type_options['custom_html'] = __( 'Custom HTML', 'md' );

	if ( ! $args['is_post'] )
		$type_options['latest'] = __( 'Latest item', 'md' );

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

	$width = array(
		'type' => 'range',
		'unit' => 'px'
	);

	include md_template( 'features', 'featured-media/admin/featured-media', true );
}
