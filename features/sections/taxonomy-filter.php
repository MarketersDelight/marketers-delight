<?php

/**
 * Define the fields stored by a Taxonomy Filter section.
 *
 * @since 6.0.3
 */

function md_taxonomy_filter_fields() {
	return array(
		'taxonomy' => array(
			'type' => 'select',
			'options' => array_values( get_taxonomies() )
		),
		'taxonomy_settings' => array(
			'type' => 'checkbox',
			'options' => array( 'hide_empty' )
		),
		'all_label' => array(
			'type' => 'text'
		)
	);
}

/**
 * Render Taxonomy Filter controls inside a builder row.
 *
 * @since 6.0.3
 */

function md_taxonomy_filter_admin_fields( $group, $type, $fields ) {
	$options = array();
	$post_type = $fields->screen_post_type();
	$taxonomies = get_object_taxonomies( $post_type, 'objects' );

	foreach ( $taxonomies as $taxonomy => $object )
		if ( $object->public )
			$options[$taxonomy] = $object->labels->name;

	include md_template( 'features', 'sections/admin/taxonomy-filter', true );
}

/**
 * Render taxonomy navigation from a section builder row: an All link,
 * then the terms of the branch being viewed, each with its post count.
 *
 * @since 6.0.3
 */

function md_taxonomy_filter( $fields, $post_type, $id, $main_query ) {
	$taxonomy = md_taxonomy_filter_taxonomy( $fields['taxonomy'] ?? '', $post_type );

	if ( ! $taxonomy )
		return;

	$excluded = md_taxonomy_filter_excluded_term_ids( $taxonomy, $main_query );
	$all = get_terms( array(
		'taxonomy' => $taxonomy,
		'hide_empty' => ! empty( $fields['taxonomy_settings']['hide_empty'] ),
		'pad_counts' => true,
		'exclude' => $excluded
	) );

	if ( is_wp_error( $all ) || ! $all )
		return;

	$navigation_term = md_taxonomy_filter_navigation_term( $all, $taxonomy );
	$terms = md_taxonomy_filter_terms( $all, $navigation_term );

	if ( ! $terms )
		return;

	$is_single = is_singular();
	$current_ids = md_taxonomy_filter_current_ids( $taxonomy );
	$archive_url = $navigation_term ? get_term_link( $navigation_term ) : get_post_type_archive_link( $post_type );
	$active = ! $is_single && ( $navigation_term ? in_array( $navigation_term->term_id, $current_ids, true ) : ( is_post_type_archive( $post_type ) || ( is_home() && $post_type === 'post' ) ) );
	$total = md_taxonomy_filter_count( $post_type, $taxonomy, $navigation_term, $excluded );

	include md_template( 'features', 'sections/taxonomy-filter', true );
}

/**
 * The taxonomy a filter lists: the one chosen in its settings, else the
 * one being viewed, else the post type's first public taxonomy.
 *
 * @since 6.0.4
 */

function md_taxonomy_filter_taxonomy( $taxonomy, $post_type ) {
	$taxonomies = get_object_taxonomies( $post_type, 'objects' );
	$queried = get_queried_object();

	if ( isset( $taxonomies[$taxonomy] ) )
		return $taxonomy;

	if ( $queried instanceof WP_Term && isset( $taxonomies[$queried->taxonomy] ) )
		return $queried->taxonomy;

	foreach ( $taxonomies as $name => $object )
		if ( $object->public )
			return $name;

	return '';
}

/**
 * Term IDs a filter marks active: a single's own terms, or the term
 * archive being viewed.
 *
 * @since 6.0.4
 */

function md_taxonomy_filter_current_ids( $taxonomy ) {
	if ( ! is_singular() )
		return array( get_queried_object_id() );

	$ids = wp_get_post_terms( get_queried_object_id(), $taxonomy, array( 'fields' => 'ids' ) );

	return is_wp_error( $ids ) ? array() : array_map( 'intval', $ids );
}

/**
 * The terms a filter lists: the navigation term's children, stepping down
 * past any level with a single term so the filter always offers a choice.
 *
 * @since 6.0.4
 */

function md_taxonomy_filter_terms( $all, $navigation_term ) {
	$terms = md_taxonomy_filter_child_terms( $all, $navigation_term ? $navigation_term->term_id : 0 );

	while ( count( $terms ) === 1 && md_taxonomy_filter_child_terms( $all, $terms[0]->term_id ) )
		$terms = md_taxonomy_filter_child_terms( $all, $terms[0]->term_id );

	return $terms;
}

/**
 * Term IDs the main archive query leaves out of a taxonomy, so the filter
 * doesn't link to terms the Loop hides.
 *
 * @since 6.0.3
 */

function md_taxonomy_filter_excluded_term_ids( $taxonomy, $main_query ) {
	$excluded = array();

	foreach ( (array) $main_query->get( 'tax_query' ) as $clause )
		if ( is_array( $clause ) && ( $clause['operator'] ?? '' ) === 'NOT IN' && ( $clause['taxonomy'] ?? '' ) === $taxonomy )
			$excluded = array_merge( $excluded, (array) $clause['terms'] );

	return $excluded;
}

/**
 * The navigation term whose children a Taxonomy Filter lists: the viewed term when
 * it has children, otherwise its parent. Null at the top level.
 *
 * @since 6.0.3
 */

function md_taxonomy_filter_navigation_term( $terms, $taxonomy ) {
	$queried = get_queried_object();

	if ( ! $queried instanceof WP_Term || $queried->taxonomy !== $taxonomy )
		return null;

	if ( md_taxonomy_filter_child_terms( $terms, $queried->term_id ) )
		return $queried;

	return $queried->parent ? get_term( $queried->parent, $taxonomy ) : null;
}

/**
 * Terms from a list whose parent is the given term ID.
 *
 * @since 6.0.3
 */

function md_taxonomy_filter_child_terms( $terms, $parent_id ) {
	$children = array();

	foreach ( $terms as $term )
		if ( $term->parent === $parent_id )
			$children[] = $term;

	return $children;
}

/**
 * Count published posts in a filter's category branch, without excluded terms.
 *
 * @since 6.0.3
 */

function md_taxonomy_filter_count( $post_type, $taxonomy, $navigation_term, $excluded ) {
	$tax_query = array();

	if ( $navigation_term )
		$tax_query[] = array( 'taxonomy' => $taxonomy, 'terms' => $navigation_term->term_id, 'include_children' => true );

	if ( $excluded )
		$tax_query[] = array( 'taxonomy' => $taxonomy, 'terms' => $excluded, 'operator' => 'NOT IN' );

	$query = new WP_Query( array(
		'post_type' => $post_type,
		'post_status' => 'publish',
		'posts_per_page' => 1,
		'fields' => 'ids',
		'tax_query' => $tax_query
	) );

	return (int) $query->found_posts;
}
