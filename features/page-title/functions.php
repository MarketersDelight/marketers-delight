<?php

/**
 * Render the inner content of a title.
 *
 * @since 6.0
 */

function md_the_title( $context = 'post', $args = array() ) {
	$h = 'h1';
	$title = apply_filters( "md_{$context}_title", md_get_title( $context ) );

	if ( $context == 'post' && ! is_singular() && ! is_404() ) {
		$h = ! empty( $args['loop']['by_category'] ) ? 'h3' : 'h2';

		if ( ! empty( $title ) )
			$title = '<a href="' . get_permalink() . '">' . $title . '</a>';
	}

	include md_template( 'features', 'page-title/the-title', true );
}

/**
 * Get the plaintext title of the current page based on content type.
 *
 * @since 6.0
 */

function md_get_title( $context = 'post' ) {
	$title = get_the_title();

	if ( $context == 'post' ) {
		if ( is_404() && ! md_has_custom_404() )
			$title = __( 'Page not found', 'md' );

		return $title;
	}

	if ( is_home() || is_post_type_archive() || is_singular( 'post' ) ) {
		$post_type_title = post_type_archive_title( '', false );
		$title = md_parse_text( md_post_type_field( 'archives_title', $post_type_title ), 'archive' );
	}
	elseif ( is_tax() || is_category() || is_tag() ) {
		$title = md_term_meta( array( 'hero', 'archives_title' ) );

		if ( ! $title )
			$title = md_module( 'archives_title', single_term_title( '', false ), array( 'inherit_post_type' => false ) );

		$title = md_parse_text( $title, 'term' );
	}
	elseif ( is_search() )
		$title = sprintf( esc_html__( 'Search Results For: %s', 'md' ), '<span class="search-query">' . get_search_query() . '</span>' );
	elseif ( is_author() )
		$title = get_the_author();
	elseif ( is_year() )
		$title = get_the_date( 'Y' );
	elseif ( is_month() )
		$title = get_the_date( 'F Y' );
	elseif ( is_day() )
		$title = get_the_date( 'F j, Y' );

	return wp_kses_post( trim( (string) $title ) );
}

/**
 * Render the layout markup of the Post or Page title with supporting
 * elements like image, byline, etc. A title used within a loop
 * has key differences than when rendered as the main Page Title.
 *
 * $context accepts 'post' by default or 'page'
 *
 * @since 6.0
 */

function md_title( $context = 'post', $args = array() ) {
	if ( ! apply_filters( "md_has_{$context}_title", true, $args ) )
		return;

	if ( ! md_get_title( $context ) && $context !== 'post' )
		return;

	$has_header_cover = md_has_header_cover( $context );

	if ( $has_header_cover && in_the_loop() )
		return;

	$classes = $style = array();
	$inline_images = array( 'left', 'right' );
	$title_images = array( 'title_left', 'title_right', 'title_center' );
	$full_width = array( 'center', 'above_headline', 'below_headline' );
	$loop = md_get_loop();
	$media = md_has_media( $context, array( 'loop' => $loop ) );
	$cover = md_cover( $context );
	$has_sidebar = md_has_sidebar();
	$has_wrap = $context == 'page' && $media && ! in_array( $media['position'], $full_width, true );
	$args['loop'] = ! empty( $args['loop'] ) ? $args['loop'] : array();
	$media_position = $context == 'post' && ! empty( $loop['featured_image'] ) ? $loop['featured_image'] : ( $media['position'] ?? '' );

	// Layout type classes

	if ( ! $has_header_cover && ( ( $context === 'page' && $has_sidebar ) || ( $context !== 'page' && ! empty( $loop['is_slim'] ) ) ) )
		$classes[] = 'inline';
	else
		$classes[] = 'wide';

	// Featured image related classes

	if ( $media && ( $context == 'page' || ( $context == 'post' && in_array( $media_position, $title_images, true ) ) ) )
		$classes = array_merge( $classes, md_get_image_position_classes( $media_position ) );
	elseif ( ! $media && $context == 'post' && empty( $cover['position'] ) )
		$classes[] = 'no-media';

	// Page cover classes

	if ( $context == 'post' )
		$classes[] = 'item';

	if ( ! empty( $cover['position'] ) ) {
		$classes[] = md_cover_classes( $context );

		if ( ! empty( $cover['photo']['id'] ) )
			$style['bg_image'] = wp_get_attachment_image_url( $cover['photo']['id'], 'full' );
		elseif ( ! empty( $cover['bg_color'] ) )
			$style['bg_color'] = $cover['bg_color'];
	}

	$classes = join( ' ', $classes );
	$style = md_style( $style );

	// Render title

	do_action( "md_hook_{$context}_title_before" );

	include md_template( 'features', 'page-title/title', true );

	do_action( "md_hook_{$context}_title_after" );
}

/**
 * Show Description of current page.
 *
 * @since 6.0
 */

function md_description( $context = 'post', $args = array() ) {
	if ( ! apply_filters( "md_has_{$context}_title_description", true ) )
		return;

	$description = '';

	if ( ! empty( $args['description'] ) )
		$description = $args['description'];
	elseif ( $context == 'post' && is_singular() ) {
		$excerpt = md_post_type_field( array( 'page_cover', 'display', 'show_excerpt' ) ) && has_excerpt() ? get_the_excerpt() : '';
		$description = md_post_meta( array( 'page_cover', 'title_content' ), null, $excerpt );
	}
	elseif ( $context == 'page' ) {
		if ( is_post_type_archive() || is_home() )
			$description = md_parse_text( md_post_type_field( 'archives_text' ), 'archive' );
		elseif ( ( is_category() || is_tax() ) && get_queried_object() ) {
			$description = md_term_meta( array( 'hero', 'archives_text' ) );

			if ( ! $description )
				$description = category_description();

			if ( ! $description )
				$description = md_taxonomy_field( 'archives_text' );

			$description = md_parse_text( $description, 'term' );
		}
		elseif ( is_author() )
			$description = get_the_author_meta( 'description' );
	}

	if ( empty( $description ) )
		return;

	include md_template( 'features', 'page-title/description', true );
}
