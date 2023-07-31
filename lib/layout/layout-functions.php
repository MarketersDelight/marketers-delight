<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Render dynamic HTML for important structural tags.
 *
 * @since 5.6
 */

function md_html( $area ) {
	$html = 'div';

	if ( $area == 'h' )
		$html = is_singular() ? 'h1' : 'h2';
	elseif ( $area == 'article' )
		$html = is_singular() ? 'div' : 'article';

	return $html;
}

/**
 * Call a page title with or without a URL.
 *
 * @since 5.6
 */

function md_title( $text, $url, $args = null ) {
	$title = '';

	if ( ! is_singular() )
		$title .= '<a href="' . esc_url( $url ) . '">';

	$title .= esc_html( $text );

	if ( ! is_singular() )
		$title .= '</a>';

	return $title;
}

/**
 * Inner HTML element and closing div.
 *
 * @since 5.6
 */

function md_inner_html() {
	echo '<div class="inner">';
}

function md_html_close() {
	echo '</div>';
}

/**
 * Filter body classes.
 *
 * @since 4.1
 */

function md_body_classes( $classes ) {
	// Add custom body classes
	$custom_classes = md_meta( array( 'scripts', 'body_class' ) );
	if ( ! empty( $custom_classes ) ) {
		$custom_classes = explode( ' ' , $custom_classes );
		foreach ( $custom_classes as $custom_class )
			$classes[] = esc_attr( $custom_class );
	}
	// Remove excess WP classes
	$classes = array_diff( $classes, array(
		'single-format-standard',
		'single-format-' . get_post_format()
	) );

	return $classes;
}

add_filter( 'body_class', 'md_body_classes' );

/**
 * A list of classes to add to the content box container.
 *
 * @since 4.1
 */

function md_content_box_classes( $classes = array() ) {
	$position = md_featured_image_position();
	$classes[] = 'content-box';

	if ( md_has_sidebar() ) {
		$classes[] = 'content-sidebar';
		if ( md_meta( array( 'layout', 'content_box' ), get_queried_object_id() ) )
			$layout = md_meta( array( 'layout', 'content_box' ) );
		else
			$layout = md_setting( array( 'content', 'layout' ) );

		if ( $layout == 'sidebar_content' )
			$classes[] = 'sidebar-left';
	}
	else
		$classes[] = 'content-full';

	if ( md_setting( array( 'content', 'style' ) ) )
		$classes[] = 'style-' . md_setting( array( 'content', 'style' ) );
	else
		$classes[] = 'style-default';

	$classes[] = 'loop-' . md_get_loop();

	$classes = apply_filters( 'md_filter_content_box_classes', $classes );

	return join( ' ', $classes );
}

/**
 * A list of classes to add to content box.
 *
 * @since 4.5
 */

function md_content_classes( $classes = array() ) {
	$classes[] = 'content';
	$classes[] = 'format';
	$classes = apply_filters( 'md_filter_content_classes', $classes );

	return join( ' ', $classes );
}

/**
 * Add/remove post classes.
 *
 * @since 4.1
 */

function md_post_classes( $classes ) {
	$classes[] = 'post-box';

	// Remove excess WP classes
	$classes = array_diff( $classes, array(
		'hentry',
		'format-standard',
		'post-' . get_the_ID(),
		'type-' . get_post_type(),
		'status-' . get_post_status(),
		'format-' . get_post_format()
	) );

	// Apply classes to certain featured image positions
	$position = md_featured_image_position();
	$cover = md_cover();

	if ( ! empty( $cover['position'] ) )
		$classes[] = 'has-cover';

	if ( ! empty( $position ) )
		$classes[] = 'image-' . esc_attr( $position );

	return $classes;
}

add_filter( 'post_class', 'md_post_classes' );

/**
 * Determines needed classes for a headline type element. Spacing,
 * padding, featured image styles, etc.
 *
 * @since 4.1
 */

function md_headline_classes( $custom = null ) {
	if ( isset( $custom ) )
		$classes = $custom;

	$classes[] = 'headline-wrap';

	$cover_classes = md_cover_classes();

	if ( ! empty( $cover_classes ) )
		$classes[] = $cover_classes;

	$classes = join( ' ', $classes );

	return apply_filters( 'md_filter_headline_classes', esc_attr( $classes ) );
}

/**
 * A list of classes to add to the sidebar.
 *
 * @since 4.5
 */

function md_byline_classes() {
	$classes[] = 'byline';
	$classes = apply_filters( 'md_filter_byline_classes', $classes );

	return join( ' ', $classes );
}

/**
 * A list of classes to add to the header.
 *
 * @since 4.5
 */

function md_footer_classes() {
	$classes = apply_filters( 'md_filter_footer_classes', array() );
	return join( ' ', $classes );
}
