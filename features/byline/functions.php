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
 * A valet way to render byline item classes in template files.
 *
 * @since 6.0
 */

function md_byline_classes( $fields, $classes = '' ) {
	$classes = array_merge( array( 'byline-item' ), (array) $classes );

	if ( ! empty( $fields['classes'] ) )
		$classes = array_merge( $classes, (array) $fields['classes'] );

	$classes = explode( ' ', join( ' ', $classes ) );

	return esc_attr( join( ' ', array_unique( array_filter( $classes ) ) ) );
}

/**
 * Call the template of a single byline item with
 * passable settings.
 *
 * @since 6.0
 */

function md_byline_item( $type, $fields = array() ) {
	$items = md_byline_items();

	if ( isset( $items[$type]['template'] ) )
		call_user_func( $items[$type]['template'], $fields );
	else
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

	$builder = md_get_post_type_builder( 'byline', $post_type );

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
