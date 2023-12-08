<?php

/**
 * A collection of classes used to turn an element into
 * a background cover.
 *
 * @since 5.6
 */

function md_cover_classes( $custom = array() ) {
	$classes = array();
	$cover = md_cover();

	if ( isset( $custom ) )
		$classes = array_merge( $classes, $custom );

	if ( ! empty( $cover['position'] ) ) {
		$classes[] = 'cover';

		if ( is_singular() )
			$classes[] = str_replace( '_', '-', $cover['position'] );
	}

	if ( ! empty( $cover['text'] ) )
		$classes[] = 'alt';

	return join( ' ', $classes );
}

/**
 * Checks for content headline.
 *
 * @since 4.1
 */

function md_has_headline_cover() {
	$cover = md_cover();

	return in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) && is_singular() ? true : false;
}

/**
 * Outputs inline style CSS to add featured image to element if
 * image position is set to header cover or headline cover.
 *
 * @since 4.1
 * @formerly md_featured_image_cover
 * @changed 5.6
 */

function md_cover_style( $args = null ) {
	if ( isset( $args['hide_cover'] ) )
		return;

	$cover = md_cover();

	if ( ! empty( $cover['image'] ) && ( in_array( $cover['position'], array( 'headline_cover', 'header_cover' ) ) || ( in_the_loop() && ! is_singular() && $cover['position'] == 'header_cover_full' ) ) )
		return md_style( array(
			'bg_image' => esc_url( $cover['image'][0] ),
			'bg_size' => $cover['image'][1] < 500 ? 'auto' : 'cover'
		) );
}

/**
 * Show image caption from Cover image.
 *
 * @since 5.6
 */

function md_cover_caption() {
	$cover = md_cover();

	if ( is_singular() && ! empty( $cover['position'] ) )
		md_get_caption( $cover['id'] );
}

function md_has_cover() {
	$cover = md_cover();

	if ( ! empty( $cover['id'] && $cover['position'] ) )
		return true;

	return false;
}

/**
 * Get Cover attributes for any given page.
 *
 * @since 4.1
 * @renamed 5.6 (md_featured_image_style)
 */

function md_cover() {
	$id = $url = $position = $term_id = '';
	$cover = array( 'id' => '', 'position' => '', 'image' => '', 'color' => '', 'text' => '' );

	$default_position = md_setting( array( 'colors', 'page_cover', 'cover_position' ) );
	$default_cover_id = md_setting( array( 'colors', 'page_cover', 'cover_image', 'id' ) );
	$default_color = md_setting( array( 'colors', 'page_cover', 'cover_color' ) );
	$default_text = md_setting( array( 'colors', 'page_cover', 'cover_styles', 'text_color' ) );
	$disable_overlay = md_setting( array( 'colors', 'page_cover', 'cover_styles', 'disable_cover' ) );

	$show_on_posts = md_post_type_field( array( 'page_cover', 'text_color', 'posts' ) );
	$show_on_categories = md_post_type_field( array( 'page_cover', 'text_color', 'categories' ) );
	$cover_post_meta = array_filter( md_post_meta( 'page_cover', null, array() ) );

	$taxonomies = get_taxonomies( array( 'public' => true ) );
	$terms = wp_get_post_terms( get_the_ID(), $taxonomies );

	if ( ! empty( $terms[0] ) ) {
		$term = $terms[0];
		$term_id = $term->term_id;
	}

	$show_on_category_posts = md_term_meta( array( 'page_cover', 'text_color', 'category_posts' ), $term_id );

	if (
		( is_singular() && $show_on_posts && ! $cover_post_meta && ! $show_on_category_posts ) ||
		( ( is_category() || is_tax() ) && $show_on_categories && ! array_filter( md_term_meta( 'page_cover', null, array() ) ) )
	) {
		$position = md_post_type_field( array( 'page_cover', 'cover_position' ), $default_position );
		$cover_id = md_post_type_field( array( 'page_cover', 'cover_image', 'id' ), $default_cover_id );
		$color = md_post_type_field( array( 'page_cover', 'bg_color' ) );
		$single_text = md_post_type_field( array( 'page_cover', 'text_color', 'alternate' ), $default_text );
		$disable_single = md_post_type_field( array( 'page_cover', 'text_color', 'disable_cover' ), $disable_overlay );
	}
	elseif ( is_singular() && $show_on_category_posts && ! $cover_post_meta ) {
		$position = md_term_meta( array( 'page_cover', 'cover_position' ), $term_id, $default_position );
		$cover_id = md_term_meta( array( 'page_cover', 'cover_image', 'id' ), $term_id, $default_cover_id );
		$color = md_term_meta( array( 'page_cover', 'bg_color' ), $term_id );
		$single_text = md_term_meta( array( 'page_cover', 'text_color', 'alternate' ), $term_id, $default_text );
		$disable_single = md_term_meta( array( 'page_cover', 'text_color', 'disable_cover' ), $term_id, $disable_overlay );
	}
	elseif ( in_the_loop() ) {
		$position = md_post_meta( array( 'page_cover', 'cover_position' ), null, $default_position );
		$cover_id = md_post_meta( array( 'page_cover', 'cover_image', 'id' ), null, $default_cover_id );
		$color = md_post_meta( array( 'page_cover', 'bg_color' ), null );
		$single_text = md_post_meta( array( 'page_cover', 'text_color', 'alternate' ), null, $default_text );
		$disable_single = md_post_meta( array( 'page_cover', 'text_color', 'disable_cover' ), null, $disable_overlay );
	}
	else {
		$position = md_module( array( 'page_cover', 'cover_position' ), $default_position );
		$cover_id = md_module( array( 'page_cover', 'cover_image', 'id' ), $default_cover_id );
		$color = md_module( array( 'page_cover', 'bg_color' ) );
		$single_text = md_module( array( 'page_cover', 'text_color', 'alternate' ), $default_text );
		$disable_single = md_module( array( 'page_cover', 'text_color', 'disable_cover' ), $disable_overlay );
	}

	$cover['position'] = $position;

	if ( empty( $cover['position'] ) )
		return $cover;

	if ( ! empty( $cover_id ) )
		$id = $cover_id;

	$image = wp_get_attachment_image_src( $id, 'full' );

	if ( ! empty( $image ) ) {
		$cover['id'] = $id;
		$cover['image'] = $image;
	}

	if ( ! empty( $color ) )
		$cover['color'] = $color;

	if ( ( ! empty( $default_text ) && empty( $single_text ) ) || ( empty( $default_text ) && ! empty( $single_text ) ) )
		$cover['text'] = true;

	if ( ( ! empty( $disable_overlay ) && empty( $disable_single ) ) || ( empty( $disable_overlay ) && ! empty( $disable_single ) ) )
		$cover['disable_overlay'] = true;

	return $cover;
}
