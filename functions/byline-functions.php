<?php

/**
 * Get list of items that can be used in a Byline, optionally
 * limited to items available for a post type.
 *
 * @since 6.0
 */

function md_byline_items( $post_type = null ) {
	$items = apply_filters( 'md_byline', array() );

	if ( $post_type === null )
		return $items;

	foreach ( $items as $id => $fields )
		if ( ! empty( $fields['post_types'] ) && ( ! $post_type || ! in_array( $post_type, (array) $fields['post_types'], true ) ) )
			unset( $items[$id] );

	return $items;
}

/**
 * Get the resolved Byline builder, replacing inherited areas that the
 * current post type explicitly configures.
 *
 * @since 6.0
 */

function md_get_byline_builder( $post_type = null ) {
	$post_type = $post_type ?: md_get_post_type();
	$builder = md_post_type_field( array( 'byline', 'builder' ), array(), $post_type );
	$defaults = md_setting_defaults();
	$current = $defaults[$post_type]['byline']['builder'] ?? array();
	$saved = md_setting_part( $post_type );
	$saved = $saved[$post_type]['byline']['builder'] ?? null;

	// A saved builder replaces its defaults instead of merging repeatable row IDs.

	if ( is_array( $saved ) )
		$current = $saved;

	if ( ! $builder || ! $current )
		return $builder;

	$areas = array();

	foreach ( $current as $fields )
		if ( ! empty( $fields['builder_area'] ) )
			$areas[$fields['builder_area']] = true;

	foreach ( $builder as $id => $fields )
		if ( ! array_key_exists( $id, $current ) && ! empty( $fields['builder_area'] ) && isset( $areas[$fields['builder_area']] ) )
			unset( $builder[$id] );

	return $builder;
}

/**
 * Call the template of a single byline item with
 * passable settings.
 *
 * @since 6.0
 */

function md_byline_item( $type, $fields = array() ) {
	include md_template( "byline/$type", true );
}

/**
 * Render the byline template with designated items.
 *
 * @since 4.0
 */

function md_byline( $location = 'before_title', $args = array() ) {
	if ( has_action( 'md_hook_byline_' . get_post_type() ) )
		return do_action( 'md_hook_byline_' . get_post_type(), true );

	$items = md_get_byline( $location, $args );

	if ( empty( $items ) )
		return;

	$post_type = $args['loop']['post_type'] ?? get_post_type();
	$data = md_byline_items();

	foreach ( $items as $item => $groups )
		if ( ! empty( $data[$item]['post_types'] ) && ( ! $post_type || ! in_array( $post_type, (array) $data[$item]['post_types'], true ) ) )
			unset( $items[$item] );

	if ( empty( $items ) )
		return;

	$c = 1;
	$classes = array( 'byline' );
	$classes[] = str_replace( '_', '-', $location );
	$html = isset( $args['html'] ) ? $args['html'] : 'div';
	$total = count( $items );
	if ( isset( $args['classes'] ) )
		$classes[] = $args['classes'];

	if ( $total >= 3 )
		$classes[] = 'can-wrap';

	if ( ! empty( $items['share'] ) )
		$classes[] = 'has-share';

	$classes = join( ' ', $classes );

	include md_template( 'byline/byline', true );
}

/**
 * Get byline items based on location and source.
 *
 * Accepts: before_title | after_title | before_post | after_post
 *
 * @since 6.0
 */

function md_get_byline( $position, $args = array() ) {
	$byline = $items = array();
	$loop = ! empty( $args['loop'] ) ? $args['loop'] : md_get_loop();
	$post_type = $loop['post_type'] ?? md_get_post_type();

	// Skip build if no byline on page

	if ( ! empty( $loop['remove_byline'][$position] ) || ! empty( $loop['remove_byline']['remove'] ) )
		return $byline;

	// Build data from user options based on page type in WP

	$builder = md_get_byline_builder( $post_type );

	if ( is_category() || is_tax() )
		$builder = md_module( array( 'byline', 'builder' ), $builder );

	// Check if items set manually in $args, or show default items while options empty

	if ( isset( $args['items'] ) )
		$items = $args['items'];
	elseif ( isset( $args['default'] ) && empty( $builder ) )
		$items = $args['default'];

	// Finally, format data for easy usage

	if ( $items )
		foreach ( $items as $item => $item_fields )
			$byline[$item][] = $item_fields;
	else {
		$context = ( is_home() || is_archive() ? 'archives' : 'single' );
		$context = ! empty( $args['context'] ) ? $args['context'] : $context;

		// Single inherits Archives builder entirely if empty

		if ( $context === 'single' ) {
			$has_single = false;

			foreach ( $builder as $fields )
				if ( $fields['builder_area'] === 'single' ) {
					$has_single = true;
					break;
				}

			if ( ! $has_single )
				$context = 'archives';
		}

		// Build bylines by area

		foreach ( $builder as $id => $fields )
			if ( $context == $fields['builder_area'] && $position == $fields['position'] ) {
				$type = $fields['builder_type'];
				$byline[$type][$id] = $fields;
				$byline[$type][$id]['id'] = $id;
			}
	}

	return apply_filters( 'md_filter_byline_items', $byline, $position, $args );
}
