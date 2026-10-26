<?php

/**
 * Get list of items that can be used in Archive Meta, optionally
 * limited to items available for a post type.
 *
 * @since 6.0
 */

function md_archive_meta_items( $post_type = null ) {
	$items = apply_filters( 'md_archive_meta_items', array(), $post_type );

	return $post_type === null ? $items : md_builder_items_for( $items, $post_type );
}

/**
 * Count the approved comments across everything the current archive, or
 * a given term, lists. The result is remembered for the request, so the
 * Comment Count meta and anything else showing it share one query.
 *
 * @since 6.0
 */

function md_archive_comment_total( $post_type, $term = null ) {
	static $totals = array();

	$queried = $term ?: get_queried_object();
	$term_id = $queried instanceof WP_Term ? $queried->term_id : 0;
	$key = implode( ',', (array) $post_type ) . ":$term_id";

	if ( isset( $totals[$key] ) )
		return $totals[$key];

	$args = array(
		'post_type' => $post_type,
		'post_status' => 'publish',
		'status' => 'approve',
		'type' => 'comment',
		'count' => true
	);

	if ( $term_id ) {
		$post_ids = get_posts( array(
			'post_type' => $post_type,
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'fields' => 'ids',
			'tax_query' => array( array(
				'taxonomy' => $queried->taxonomy,
				'terms' => $term_id
			) )
		) );

		if ( ! $post_ids )
			return $totals[$key] = 0;

		$args['post__in'] = $post_ids;
	}

	return $totals[$key] = (int) get_comments( $args );
}

/**
 * Emphasize the numbers in an Archive Meta value, leaving any
 * markup an item returns untouched.
 *
 * @since 6.0
 */

function md_archive_meta_value( $value ) {
	$parts = preg_split( '/(<[^>]*>)/', wp_kses_post( $value ), -1, PREG_SPLIT_DELIM_CAPTURE );
	$depth = 0;

	foreach ( $parts as $i => $part ) {
		if ( $part === '' )
			continue;

		if ( $part[0] === '<' ) {
			if ( strpos( $part, '</' ) === 0 )
				$depth--;
			elseif ( substr( $part, -2 ) !== '/>' )
				$depth++;

			continue;
		}

		if ( ! $depth )
			$parts[$i] = preg_replace( '/\d[\d,.]*/', '<strong>$0</strong>', $part );
	}

	return join( '', $parts );
}

/**
 * Link an Archive Meta value to a page, unless it's the page being
 * viewed. Numbers inside the link are emphasized here, since values
 * only get that outside markup.
 *
 * @since 6.0.4
 */

function md_archive_meta_link( $text, $url ) {
	if ( ! $url || is_wp_error( $url ) )
		return $text;

	$current = untrailingslashit( home_url( '/' . ( $GLOBALS['wp']->request ?? '' ) ) );

	if ( untrailingslashit( strtok( $url, '?#' ) ) === $current )
		return $text;

	return '<a href="' . esc_url( $url ) . '">' . md_archive_meta_value( $text ) . '</a>';
}

/**
 * Count a term's published posts, including its child terms' posts, like
 * its archive page lists them. Posts in several child terms count once.
 *
 * @since 6.0.4
 */

function md_term_post_count( $term, $post_type = '' ) {
	$taxonomy = get_taxonomy( $term->taxonomy );

	$query = new WP_Query( array(
		'post_type' => $post_type ?: ( $taxonomy ? $taxonomy->object_type : 'any' ),
		'post_status' => 'publish',
		'posts_per_page' => 1,
		'fields' => 'ids',
		'tax_query' => array( array(
			'taxonomy' => $term->taxonomy,
			'terms' => $term->term_id,
			'include_children' => true
		) )
	) );

	return (int) $query->found_posts;
}

/**
 * Get Archive Meta markup for a builder area, keyed by the title position
 * each item is placed in. Items without a position are After Title. The
 * archives area measures the current archive; category_entry measures
 * the given term, with the settings of the post type its loop lists.
 *
 * @since 6.0.4
 */

function md_get_archive_meta( $area = 'archives', $term = null, $post_type = '' ) {
	if ( $area === 'archives' && ! is_home() && ! is_archive() )
		return array();

	$post_type = $post_type ?: md_get_post_type();
	$builder = md_get_post_type_builder( 'archive_meta', $post_type );
	$items = md_archive_meta_items( $post_type );
	$positions = $meta = array();

	foreach ( $builder as $fields ) {
		if ( md_builder_row_hidden( $fields ) )
			continue;

		if ( ( $fields['builder_area'] ?? '' ) !== $area )
			continue;

		$type = $fields['builder_type'] ?? '';
		$callback = $items[$type]['callback'] ?? null;

		if ( ! is_callable( $callback ) )
			continue;

		$value = call_user_func( $callback, $fields, $post_type, $term );

		if ( ! $value )
			continue;

		$position = ! empty( $fields['position'] ) ? $fields['position'] : 'after_title';

		if ( ! isset( $positions[$position] ) )
			$positions[$position] = '';

		$icon = ! empty( $items[$type]['meta_icon'] ) ? md_icon( $items[$type]['meta_icon'] ) : '';

		$positions[$position] .= '<span class="byline-item">' . $icon . '<span class="byline-value">' . md_archive_meta_value( $value ) . '</span></span>';
	}

	foreach ( $positions as $position => $output )
		$meta[$position] = '<div class="byline ' . esc_attr( str_replace( '_', '-', $position ) ) . ' archive-meta">' . $output . '</div>';

	return $meta;
}
