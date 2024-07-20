<?php

/**
 * Render the Featured Image template. All logic and build
 * are editable from the /templates/featured-image.php file.
 *
 * @since 4.0
 */

function md_featured_image( $context = 'post', $loop = array() ) {
	include md_template( 'featured-image', true );
}

/**
 * Call the Page Featured Image (does not apply to Single).
 * This function would be great to deprecate, but is used to
 * only call a featured image in the Page Title context.
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
		$featured_image['id'] = md_module( array( 'page_title', 'image', 'id' ) );
		$featured_image['position'] = md_featured_image_position( 'page' );
		$featured_image['width'] = md_module( array( 'page_title', 'image_width' ), array() );
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
			$position = md_term_meta( array( 'page_title', 'image_position' ), null, $default );
		else
			$position = md_post_type_field( array( 'page_title', 'image_position' ), $default );
	}
	else {
		$post_type = md_post_type_field( array( 'loop', 'featured_image' ), $default );

		if ( is_singular() )
			$position = md_post_meta( array( 'layout', 'featured_image' ), null, $default );
		elseif ( is_category() || is_tax() )
			$position = md_term_meta( array( 'page_title', 'image_position' ), null, $post_type );
		else
			$position = $post_type;
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
	$cover = array( 'position' => '' );

	if ( $context == 'page' ) {
		if ( is_category() || is_tax() )
			$page_title = md_term_meta( 'page_title' );
		else
			$page_title = md_post_type_field( 'page_title' );
	}
	else
		$page_title = md_post_meta( 'page_title' );

	if ( ! empty( $page_title['cover_position'] ) ) {
		$cover['position'] = $page_title['cover_position'];

		if ( ! empty( $page_title['cover_photo'] ) ) {
			$cover['photo'] = $page_title['cover_photo'];
			$cover['style'] = array(
				'bg_image' => esc_url( $cover['photo']['url'] ),
				'bg_size' => 'auto'
			);
		}

		if ( ! empty( $page_title['cover_bg_color'] ) )
			$cover['bg_color'] = $page_title['cover_bg_color'];

		if ( ! empty( $page_title['cover_display'] ) )
			$cover['display'] = $page_title['cover_display'];
	}

	if ( is_singular() && $cover['position'] == 'header_cover_full' )
		$cover['display']['hide_cover'] = true;

	return $cover;
}

/**
 * Return an array of class names related to a Cover.
 *
 * @since 6.0
 */

function md_cover_classes( $cover, $string = false ) {
	$classes = array();

	if ( empty( $cover['position'] ) )
		return $classes;

	$classes[] = 'cover';

//	if ( ! empty( $cover['display']['hide_cover'] ) )
//		return $classes;

	if ( ! empty( $cover['display']['alternate'] ) )
		$classes[] = 'alt';

	if ( $cover['position'] == 'header_cover_full' )
		$classes[] = 'format';

	if ( ! empty( $string ) )
		$classes = join( ' ', $classes );

	return $classes;
}

/**
 * Add Overlay HTML to covers.
 *
 * @since 4.8.6
 */

function md_overlay( $cover ) {
	if ( empty( $cover['position'] ) || ! empty( $cover['display']['hide_cover'] ) )
		return;

	$style = array();

	if ( ! empty( $cover['bg_color'] ) )
		$style['bg_color'] = $cover['bg_color'];

	echo '<div class="overlay"' . md_style( $style ) . '></div>';
}
