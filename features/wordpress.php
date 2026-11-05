<?php

/**
 * Apply MD's selected adjustments to WordPress defaults.
 *
 * @since 6.0
 */

function md_wordpress_setup() {
	// Disable Widgets Block Editor
	if ( md_setting( array( 'settings', 'head', 'widgets' ) ) ) {
		add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
		add_filter( 'use_widgets_block_editor', '__return_false' );
	}

	// Remove WP junk, mostly from <head>
	if ( ! md_setting( array( 'settings', 'head', 'optimize' ) ) ) {
		add_filter( 'post_class', 'md_clean_post_classes' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
		add_filter( 'emoji_svg_url', '__return_false' );
		add_filter( 'the_generator', '__return_false' );
	}

	// Remove REST and oEmbed discovery
	if ( md_setting( array( 'settings', 'head', 'wpjson' ) ) ) {
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
		remove_action( 'rest_api_init', 'wp_oembed_register_route' );
	}

	// Disable oEmbed
	if ( md_setting( array( 'settings', 'head', 'oembed' ) ) ) {
		add_filter( 'embed_oembed_discover', '__return_false' );
		remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		add_filter( 'rewrite_rules_array', 'md_disable_embed_rewrites' );
	}

	// Re-add RSS link
	add_action( 'wp_head', 'md_add_rss_link' );
}

/**
 * Clean out unneeded CSS post classes.
 *
 * @since 4.1
 */

function md_clean_post_classes( $classes ) {
	return array_diff( $classes, array(
		'format-standard',
		'hentry',
		'post-' . get_the_ID(),
		'type-' . get_post_type(),
		'status-' . get_post_status(),
		'format-' . get_post_format()
	) );
}

/**
 * Remove oEmbed rewrite rules if enabled.
 *
 * @since 4.8
 */

function md_disable_embed_rewrites( $rules ) {
	foreach ( $rules as $rule => $rewrite )
		if ( false !== strpos( $rewrite, 'embed=true' ) )
			unset( $rules[$rule] );

	return $rules;
}

/**
 * Manually add a formatted version of the site's main RSS feed to the head.
 *
 * @since 4.8
 */

function md_add_rss_link() {
	echo '<link rel="alternate" type="application/rss+xml" title="' . get_bloginfo( 'sitename' ) . ' Feed" href="' . get_bloginfo( 'rss2_url' ) . '">';
}

/**
 * Add link relationships to WordPress pagination output.
 *
 * @since 6.0
 */

function md_previous_posts_link_attributes( $attributes ) {
	return trim( $attributes . ' rel="prev"' );
}

function md_next_posts_link_attributes( $attributes ) {
	return trim( $attributes . ' rel="next"' );
}

function md_paginate_links_output( $html ) {
	return str_replace( 'class="next', 'rel="next" class="next', str_replace( 'class="prev', 'rel="prev" class="prev', $html ) );
}

add_filter( 'previous_posts_link_attributes', 'md_previous_posts_link_attributes' );
add_filter( 'next_posts_link_attributes', 'md_next_posts_link_attributes' );
add_filter( 'paginate_links_output', 'md_paginate_links_output' );

/**
 * Determine whether singular posts of a registered post type are indexable.
 *
 * Post types are indexable unless they explicitly register the
 * md_singular_indexable argument as false.
 *
 * @since 6.0
 */

function md_is_singular_indexable( $post_type ) {
	$post_type = get_post_type_object( $post_type );

	return ! $post_type || ! isset( $post_type->md_singular_indexable ) || false !== $post_type->md_singular_indexable;
}

/**
 * Keep declared content fragments out of search engine indexes.
 *
 * @since 6.0
 */

function md_robots_noindex_singular( $robots ) {
	if ( ! is_singular() )
		return $robots;

	$post = get_queried_object();

	if ( is_object( $post ) && ! empty( $post->post_type ) && ! md_is_singular_indexable( $post->post_type ) ) {
		unset( $robots['index'] );
		$robots['noindex'] = true;
	}

	return $robots;
}

add_filter( 'wp_robots', 'md_robots_noindex_singular' );

/**
 * Remove non-indexable singular post types from WordPress core sitemaps.
 *
 * @since 6.0
 */

function md_sitemaps_post_types( $post_types ) {
	foreach ( $post_types as $post_type => $object )
		if ( ! md_is_singular_indexable( $post_type ) )
			unset( $post_types[$post_type] );

	return $post_types;
}

add_filter( 'wp_sitemaps_post_types', 'md_sitemaps_post_types' );

/**
 * Get the custom post types that support Sticky posts. Core
 * already handles Sticky for the post type.
 *
 * @since 6.2
 */

function md_sticky_post_types() {
	return array_values( get_post_types( array(
		'_builtin' => false,
		'show_ui' => true,
		'show_in_rest' => true
	) ) );
}

/**
 * Add a sticky field to the REST API for custom post types so
 * the Block Editor can stick and unstick their posts.
 *
 * @since 6.2
 */

function md_register_sticky_rest_field() {
	register_rest_field( md_sticky_post_types(), 'sticky', array(
		'get_callback' => 'md_get_sticky_rest_field',
		'update_callback' => 'md_update_sticky_rest_field',
		'schema' => array(
			'description' => __( 'Whether or not the post should be treated as sticky.', 'md' ),
			'type' => 'boolean',
			'context' => array( 'view', 'edit' )
		)
	) );
}

function md_get_sticky_rest_field( $post ) {
	return is_sticky( $post['id'] );
}

function md_update_sticky_rest_field( $value, $post ) {
	$sticky = (bool) $value;

	if ( $sticky === is_sticky( $post->ID ) )
		return true;

	if ( ! current_user_can( get_post_type_object( $post->post_type )->cap->edit_others_posts ) )
		return new WP_Error( 'rest_cannot_assign_sticky', __( 'Sorry, you are not allowed to make posts sticky.', 'md' ), array( 'status' => rest_authorization_required_code() ) );

	if ( $sticky )
		stick_post( $post->ID );
	else
		unstick_post( $post->ID );

	return true;
}

add_action( 'rest_api_init', 'md_register_sticky_rest_field' );
