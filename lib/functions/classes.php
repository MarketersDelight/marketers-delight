<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

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
 * Add classes to the Header Wrap area.
 *
 * @since 4.6
 */

function md_header_wrap_classes() {
	$classes = array();
	$classes[] = 'header-wrap';
	$classes = apply_filters( 'md_header_wrap_classes', $classes );
	return join( ' ', $classes );
}

/**
 * A list of classes to add to the header.
 *
 * @since 4.1
 */

function md_header_classes() {
	$classes = array();
	$classes[] = 'header';
	$classes[] = ( ! md_has_menu() || ! md_has_logo() ? 'header-simple' : 'header-standard' );
	$classes = apply_filters( 'md_filter_header_classes', $classes );
	return join( ' ', $classes );
}

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
		if ( md_meta( array( 'layout', 'content_box' ), get_the_ID() ) )
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

	$classes[] = 'format';

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
	$classes = apply_filters( 'md_filter_content_classes', $classes );
	return join( ' ', $classes );
}

/**
 * Add/remove post classes.
 *
 * @since 4.1
 */

function md_post_classes( $classes ) {
	// Remove excess WP classes
	$classes = array_diff( $classes, array(
		'hentry',
		'format-standard',
		'post-' . get_the_ID(),
		'type-' . get_post_type(),
		'status-' . get_post_status(),
		'format-' . get_post_format()
	) );

	$classes[] = 'post-box';

	return $classes;
}
add_filter( 'post_class', 'md_post_classes' );

/**
 * Filter classes to teaser boxes.
 *
 * @since 4.9.2
 */

function md_teaser_classes( $classes = array() ) {
	$classes[] = 'blog-teaser';
	return join( ' ', $classes );
}

/**
 * Determines needed classes for a headline type element. Spacing,
 * padding, featured image styles, etc.
 *
 * @since 4.1
 */

function md_headline_classes( $pos = null, $image = null ) {
	$position = isset( $pos ) ? $pos : md_featured_image_position();
	$image = isset( $image ) ? $image : has_post_thumbnail();
	$classes[] = 'content-headline';

	if ( in_array( $position, array( 'headline_cover', 'header_cover' ) ) || ( ( ! is_singular() && ! is_category() && ! is_tax() ) && $position == 'header_cover_full' ) ) {
		$classes[] = 'featured-image-cover';
		if ( md_post_meta( array( 'featured_image', 'text_color', 'alternate' ) ) )
			$classes[] = 'text-alt';
	}

	if ( ( is_singular() || is_category() || is_tax() ) && $position == 'header_cover' )
		$classes[] = 'header-cover';

	if ( in_array( $position, array( 'header_cover', 'header_cover_full' ) ) )
		$classes[] = 'format';

	$classes = apply_filters( 'md_filter_headline_classes', $classes );

	return join( ' ', $classes );
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
 * Returns HTML classes for different layouts.
 *
 * @since 4.1
 */

function md_content_block() {
	if ( md_has_sidebar() )
		return 'block-double';
	else
		return 'block-full';
}

/**
 * A list of classes to add to the sidebar.
 *
 * @since 4.5
 */

function md_sidebar_classes() {
	echo apply_filters( 'md_filter_sidebar_classes', '' );
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

/**
 * Add classes to specified WordPress Widgets (saves sooo much CSS).
 *
 * @since 4.0
 */

function md_widget_classes( $params ) {
	global $wp_registered_widgets;
	$classes = apply_filters( 'md_widget_classes', array(
		'list box-style-list' => array(
			'recent-posts',
			'recent-comments',
			'archives',
			'meta',
			'categories'
		),
		'list list-large box-style-list' => array(
			'rss'
		)
	) );
	foreach ( $classes as $class => $widgets )
		foreach ( $widgets as $widget )
			if ( $params[0]['widget_id'] == "$widget-" . $wp_registered_widgets[$params[0]['widget_id']]['params'][0]['number'] )
				$params[0]['before_widget'] = preg_replace( '/class="([^"]*)"/', 'class="$1 ' . $class . '"', $params[0]['before_widget'] );
	return $params;
}
add_filter( 'dynamic_sidebar_params', 'md_widget_classes' );