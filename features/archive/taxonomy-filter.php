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

	include md_template( 'features', 'archive/admin/taxonomy-filter', true );
}

/**
 * Render taxonomy navigation from a section builder row.
 *
 * @since 6.0.3
 */

function md_taxonomy_filter( $fields, $post_type, $id, $main_query ) {
	$current_id = get_queried_object_id();
	$taxonomies = get_object_taxonomies( $post_type, 'objects' );
	$taxonomy = $fields['taxonomy'] ?? '';
	$options = $fields['taxonomy_settings'] ?? array();

	if ( empty( $taxonomies[$taxonomy] ) ) {
		$taxonomy = is_tax() || is_category() ? get_queried_object()->taxonomy : '';

		if ( empty( $taxonomies[$taxonomy] ) )
			foreach ( $taxonomies as $name => $object )
				if ( $object->public ) {
					$taxonomy = $name;
					break;
				}
	}

	if ( ! $taxonomy )
		return;

	$excluded = md_taxonomy_filter_excluded_term_ids( $taxonomy, $main_query );
	$all = get_terms( array(
		'taxonomy' => $taxonomy,
		'hide_empty' => ! empty( $options['hide_empty'] ),
		'pad_counts' => true,
		'exclude' => $excluded
	) );

	if ( is_wp_error( $all ) || ! $all )
		return;

	$navigation_term = md_taxonomy_filter_navigation_term( $all, $taxonomy );
	$terms = md_taxonomy_filter_child_terms( $all, $navigation_term ? $navigation_term->term_id : 0 );

	while ( count( $terms ) === 1 && md_taxonomy_filter_child_terms( $all, $terms[0]->term_id ) )
		$terms = md_taxonomy_filter_child_terms( $all, $terms[0]->term_id );

	if ( ! $terms )
		return;

	if ( $navigation_term ) {
		$active = $current_id === $navigation_term->term_id;
		$archive_url = get_term_link( $navigation_term );
	}
	else {
		$active = is_post_type_archive( $post_type ) || ( is_home() && $post_type === 'post' );

		if ( $post_type === 'post' ) {
			$page_for_posts = get_option( 'page_for_posts' );
			$archive_url = $page_for_posts ? get_permalink( $page_for_posts ) : home_url( '/' );
		}
		else $archive_url = get_post_type_archive_link( $post_type );
	}

	$total = $active ? (int) $main_query->found_posts : md_taxonomy_filter_count( $post_type, $taxonomy, $navigation_term, $excluded );

	include md_template( 'features', 'archive/taxonomy-filter', true );
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
