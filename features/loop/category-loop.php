<?php

/**
 * The taxonomy a category Loop lists: the Loop's chosen taxonomy when the
 * post type uses it, otherwise the post type's first taxonomy.
 *
 * @since 6.0.3
 */

function md_category_loop_taxonomy( $loop ) {
	$taxonomy = $loop['category_taxonomy'] ?? '';

	if ( $taxonomy && is_object_in_taxonomy( $loop['post_type'], $taxonomy ) )
		return $taxonomy;

	$taxonomies = get_object_taxonomies( $loop['post_type'] );

	return $taxonomies[0] ?? '';
}

/**
 * The terms shown on the current page of a category Loop, in the Loop's order.
 *
 * @since 6.0.3
 */

function md_category_loop_terms( $loop, $taxonomy ) {
	$term_args = md_category_loop_term_args( $loop, $taxonomy );

	if ( ! $term_args )
		return array();

	$orderby = $loop['category_orderby'] ?? '';
	$order = $loop['category_order'] ?? '';
	$per_page = (int) ( $loop['category_per_page'] ?? 0 );
	$offset = $per_page > 0 ? $per_page * ( $loop['paged'] - 1 ) : 0;

	// Latest post order is sorted after the query, so every term is fetched and paged by hand

	if ( $orderby === 'latest' ) {
		$query = new WP_Term_Query( $term_args );
		$terms = md_category_loop_sort_by_latest( $query->terms, $taxonomy, $loop );

		if ( $order === 'DESC' )
			$terms = array_reverse( $terms );

		return $per_page > 0 ? array_slice( $terms, $offset, $per_page ) : $terms;
	}

	if ( $orderby )
		$term_args['orderby'] = $orderby;

	if ( $order )
		$term_args['order'] = $order;

	if ( $per_page > 0 ) {
		$term_args['number'] = $per_page;
		$term_args['offset'] = $offset;
	}

	$query = new WP_Term_Query( $term_args );

	return $query->terms;
}

/**
 * WP_Term_Query args for the terms a category Loop lists, before ordering and
 * pagination. On a term archive, only that term's children are listed. False
 * when there's nothing to list.
 *
 * @since 6.0.3
 */

function md_category_loop_term_args( $loop, $taxonomy ) {
	if ( ! $taxonomy )
		return false;

	$queried = get_queried_object();
	$term_args = array(
		'taxonomy' => $taxonomy,
		'parent' => $queried instanceof WP_Term && $queried->taxonomy === $taxonomy ? $queried->term_id : 0,
		'hide_empty' => empty( $loop['category']['show_empty'] )
	);

	if ( ! empty( $loop['category_terms'] ) )
		$term_args['include'] = md_parse_term_ids( $loop['category_terms'], $taxonomy );

	if ( ! empty( $loop['category_include'] ) )
		$term_args['include'] = wp_parse_id_list( $loop['category_include'] );

	if ( ! empty( $loop['category_exclude'] ) )
		$term_args['exclude'] = wp_parse_id_list( $loop['category_exclude'] );

	if ( ! isset( $term_args['include'] ) )
		return $term_args;

	// WP_Term_Query ignores exclude when include is set, so excluded terms come out of the include list

	$term_args['include'] = array_values( array_diff( $term_args['include'], $term_args['exclude'] ?? array() ) );

	return $term_args['include'] ? $term_args : false;
}

/**
 * Sort terms by their newest post, newest first. Terms without posts go last.
 *
 * @since 6.0.3
 */

function md_category_loop_sort_by_latest( $terms, $taxonomy, $loop ) {
	$query_args = md_category_loop_query_args( $loop );

	if ( ! $query_args )
		$query_args = array(
			'post_type' => $loop['post_type'],
			'post_status' => 'publish'
		);

	$post_ids = get_posts( array_merge( $query_args, array(
		'posts_per_page' => -1,
		'fields' => 'ids',
		'orderby' => 'date',
		'order' => 'DESC',
		'no_found_rows' => true
	) ) );

	if ( ! $post_ids )
		return $terms;

	// Each term's rank is the position of its newest post in the date-ordered list

	$position = array_flip( $post_ids );
	$last = count( $post_ids );
	$newest = array();
	$post_terms = wp_get_object_terms( $post_ids, $taxonomy, array(
		'fields' => 'all_with_object_id',
		'orderby' => 'none'
	) );

	foreach ( $post_terms as $term )
		if ( ! isset( $newest[$term->term_id] ) || $position[$term->object_id] < $newest[$term->term_id] )
			$newest[$term->term_id] = $position[$term->object_id];

	$ranks = array();

	foreach ( $terms as $index => $term )
		$ranks[$index] = $newest[$term->term_id] ?? $last;

	asort( $ranks );

	$sorted = array();

	foreach ( array_keys( $ranks ) as $index )
		$sorted[] = $terms[$index];

	return $sorted;
}

/**
 * Sticky post IDs grouped by the slug of each term they belong to.
 *
 * @since 6.0.3
 */

function md_category_loop_stickies( $post_type, $taxonomy ) {
	$stickies = array();
	$sticky_ids = md_get_sticky( $post_type );

	if ( ! $sticky_ids )
		return $stickies;

	foreach ( wp_get_object_terms( $sticky_ids, $taxonomy, array( 'fields' => 'all_with_object_id' ) ) as $term )
		$stickies[$term->slug][] = (int) $term->object_id;

	return $stickies;
}

/**
 * Query one term's posts for a "posts by category" Loop, with its sticky
 * posts pinned first.
 *
 * @since 6.0.3
 */

function md_category_loop_posts( $category, $taxonomy, $loop, $stickies = array() ) {
	$query_args = array_merge( md_category_loop_query_args( $loop ), array(
		'post_type' => $loop['post_type'],
		'posts_per_page' => (int) ( ( $loop['posts_per_category'] ?? 0 ) ?: $loop['posts_per_page'] ),
		'no_found_rows' => true
	) );
	$query_args['tax_query'] = $query_args['tax_query'] ?? array();
	$query_args['tax_query'][] = array(
		'field' => 'slug',
		'taxonomy' => $taxonomy,
		'terms' => $category->slug
	);

	if ( $stickies )
		$query_args['post__not_in'] = $stickies;

	$posts = new WP_Query( $query_args );

	if ( ! $stickies )
		return $posts;

	$pinned = get_posts( array_merge( $query_args, array(
		'post__in' => $stickies,
		'posts_per_page' => count( $stickies ),
		'ignore_sticky_posts' => 1,
		'orderby' => 'post__in'
	) ) );

	$posts->posts = array_merge( $pinned, $posts->posts );
	$posts->post_count += count( $pinned );

	return $posts;
}

/**
 * The query vars of a Loop's own query, without its page, so every term in a
 * category Loop reads from the same posts.
 *
 * @since 6.0.3
 */

function md_category_loop_query_args( $loop ) {
	$query = $loop['query'] ?? null;
	$query_args = $query instanceof WP_Query ? $query->query : array();

	unset( $query_args['paged'], $query_args['offset'] );

	return $query_args;
}

/**
 * Count every term a category Loop lists across all of its pages.
 *
 * @since 6.0.3
 */

function md_category_loop_count( $loop ) {
	$term_args = md_category_loop_term_args( $loop, md_category_loop_taxonomy( $loop ) );

	if ( ! $term_args )
		return 0;

	$term_args['fields'] = 'ids';
	$query = new WP_Term_Query( $term_args );

	return count( $query->terms );
}

/**
 * Parse a comma-separated list of term IDs and slugs into the IDs of the
 * terms that exist.
 *
 * @since 6.0.3
 */

function md_parse_term_ids( $value, $taxonomy ) {
	$ids = array();

	foreach ( array_filter( array_map( 'trim', explode( ',', (string) $value ) ) ) as $term ) {
		$found = is_numeric( $term ) ? get_term( (int) $term, $taxonomy ) : get_term_by( 'slug', sanitize_title( $term ), $taxonomy );

		if ( $found && ! is_wp_error( $found ) )
			$ids[] = (int) $found->term_id;
	}

	return $ids;
}
