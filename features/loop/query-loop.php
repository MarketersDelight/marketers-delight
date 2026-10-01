<?php

/**
 * Public content post types with MD settings can be queried.
 * Child/helper types inherit another type's settings and are excluded.
 *
 * @since 6.0
 */

function md_query_loop_sources( $context = '' ) {
	$sources = array();

	foreach ( array_unique( md_post_type_meta() ) as $post_type ) {
		$object = get_post_type_object( $post_type );

		if ( $post_type === 'page' || ! $object || ! $object->public || ! $object->publicly_queryable || ! empty( $object->md_settings_parent ) )
			continue;

		if ( $context === 'search' && $object->exclude_from_search )
			continue;

		$sources[$post_type] = $object->labels->name;
	}

	return $sources;
}

/**
 * Public taxonomies used by available Query Loop sources.
 *
 * @since 6.0
 */

function md_query_loop_taxonomies() {
	$taxonomies = array();

	foreach ( array_keys( md_query_loop_sources() ) as $source )
		foreach ( get_object_taxonomies( $source, 'objects' ) as $taxonomy )
			if ( $taxonomy->public )
				$taxonomies[$taxonomy->name] = $taxonomy->labels->name;

	return $taxonomies;
}

/**
 * Field definitions for Query Loop builder items.
 *
 * @since 6.0
 */

function md_query_loop_fields() {
	return array(
		'source' => array( 'type' => 'text' ),
		'context' => array( 'type' => 'select', 'options' => array( 'current', 'latest' ) ),
		'loop_type' => array( 'type' => 'select', 'options' => array( 'month', 'category', 'category_posts' ) ),
		'items' => array( 'type' => 'number' ),
		'orderby' => array( 'type' => 'select', 'options' => array( 'date', 'title', 'modified', 'comment_count', 'menu_order' ) ),
		'order' => array( 'type' => 'select', 'options' => array( 'ASC', 'DESC' ) ),
		'taxonomy' => array( 'type' => 'text' ),
		'terms' => array( 'type' => 'text' ),
		'category_per_page' => array( 'type' => 'number' ),
		'posts_per_category' => array( 'type' => 'number' ),
		'category_columns' => array( 'type' => 'number' ),
		'category_orderby' => array( 'type' => 'select', 'options' => array( 'slug', 'term_id', 'count', 'parent' ) ),
		'category_order' => array( 'type' => 'select', 'options' => array( 'ASC', 'DESC' ) ),
		'category' => array( 'type' => 'checkbox', 'options' => array( 'show_empty', 'hide_description' ) ),
		'loop' => array( 'type' => 'text' ),
		'columns' => array( 'type' => 'number' ),
		'columns_mobile' => array( 'type' => 'number' ),
		'content' => array( 'type' => 'select', 'options' => array( 'excerpt', 'full', 'hide' ) ),
		'featured_image' => array( 'type' => 'select', 'options' => array_keys( ( new md_fields_data )->values['featured_image'] ) ),
		'byline' => array( 'type' => 'select', 'options' => array( 'hide' ) ),
		'pagination' => array( 'type' => 'select', 'options' => array( 'none', 'page_numbers', 'prev_next' ) )
	);
}

/**
 * Render the compact controls inside any MD Builder Query Loop item.
 *
 * @since 6.0
 */

function md_query_loop_admin_fields( $group, $type, $fields, $row = array() ) {
	$sources = md_query_loop_sources();
	$taxonomies = md_query_loop_taxonomies();

	include md_template( 'features', 'loop/admin/query-loop', true );
}

/**
 * Resolve the active archive filter for one secondary query.
 *
 * @since 6.0
 */

function md_query_loop_context( $context ) {
	if ( $context === 'latest' )
		return array();

	if ( $context !== 'current' )
		return null;

	if ( is_author() )
		return array( 'author' => get_queried_object_id() );

	if ( is_search() )
		return array( 's' => get_query_var( 's' ) );

	if ( is_date() ) {
		$query = array( 'year' => (int) get_query_var( 'year' ) );

		if ( is_month() || is_day() )
			$query['monthnum'] = (int) get_query_var( 'monthnum' );

		if ( is_day() )
			$query['day'] = (int) get_query_var( 'day' );

		return $query;
	}

	if ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();

		return $term instanceof WP_Term ? array( 'tax_query' => array( array(
			'taxonomy' => $term->taxonomy,
			'field' => 'term_id',
			'terms' => $term->term_id
		) ) ) : null;
	}

	return array();
}

/**
 * Create a bounded WP_Query using the selected source and page context.
 *
 * @since 6.0
 */

function md_query_loop_query( $fields, $id ) {
	$source = sanitize_key( $fields['source'] ?? '' );
	$sources = md_query_loop_sources();

	if ( ! isset( $sources[$source] ) )
		return null;

	$context = md_query_loop_context( $fields['context'] ?? 'current' );

	if ( $context === null )
		return null;

	$default_items = get_option( 'posts_per_page' ) ?: 10;
	$loop_type = ! empty( $fields['loop_type'] ) ? $fields['loop_type'] : 'post_listing';
	$category_loop = in_array( $loop_type, array( 'category', 'category_posts' ), true );
	$group_by_month = $loop_type === 'month';
	$items = $category_loop
		? (int) ( $loop_type === 'category_posts' ? ( $fields['posts_per_category'] ?? $default_items ) : ( $fields['category_per_page'] ?? $default_items ) )
		: ( ! empty( $fields['items'] ) ? (int) $fields['items'] : (int) $default_items );
	$items = max( 1, min( 100, $items ) );
	$orderby = $fields['orderby'] ?? '';
	$order = $fields['order'] ?? '';
	$orderby = $orderby ?: 'date';
	$order = $order ?: 'DESC';
	$orderby = in_array( $orderby, array( 'date', 'title', 'modified', 'comment_count', 'menu_order', 'rand' ), true ) ? $orderby : 'date';
	$order = $order === 'ASC' ? 'ASC' : 'DESC';
	$pagination = $fields['pagination'] ?? 'none';

	// Month headings require date order and are opt-in for each Query Loop.
	if ( $group_by_month )
		$orderby = 'date';

	// Random order has no stable page two. Paginated loops use date order.
	if ( $orderby === 'rand' && $pagination !== 'none' )
		$orderby = 'date';

	$search_relevance = isset( $context['s'] ) && empty( $fields['orderby'] ) && ! $group_by_month;
	$page_key = 'md_loop_' . sanitize_key( $id );
	$page = $pagination !== 'none' && isset( $_GET[$page_key] ) ? absint( wp_unslash( $_GET[$page_key] ) ) : 1;
	$query = array_merge( array(
		'post_type' => $source,
		'post_status' => 'publish',
		'posts_per_page' => $items,
		'paged' => max( 1, $page ),
		'ignore_sticky_posts' => 1
	), $context );

	if ( ! $search_relevance ) {
		$query['orderby'] = $orderby;
		$query['order'] = $order;
	}

	$taxonomy = sanitize_key( $fields['taxonomy'] ?? '' );
	$terms = array_filter( array_map( 'trim', explode( ',', (string) ( $fields['terms'] ?? '' ) ) ) );

	if ( $taxonomy ) {
		if ( ! is_object_in_taxonomy( $source, $taxonomy ) )
			return null;

		if ( $terms ) {
			$ids_only = count( array_filter( $terms, 'ctype_digit' ) ) === count( $terms );
			$query['tax_query'][] = array(
				'taxonomy' => $taxonomy,
				'field' => $ids_only ? 'term_id' : 'slug',
				'terms' => $ids_only ? array_map( 'absint', $terms ) : array_map( 'sanitize_title', $terms )
			);
		}
		else
			$query['tax_query'][] = array( 'taxonomy' => $taxonomy, 'operator' => 'EXISTS' );
	}

	return new WP_Query( $query );
}

/**
 * Render one independent query through the existing MD Loop function.
 *
 * @since 6.0
 */

function md_query_loop_render( $fields, $id = '' ) {
	$id = $id ?: 'query_loop';
	$query = md_query_loop_query( $fields, $id );
	$loop_type = ! empty( $fields['loop_type'] ) ? $fields['loop_type'] : 'post_listing';
	$category_loop = in_array( $loop_type, array( 'category', 'category_posts' ), true );

	if ( ! $query || ( ! $query->have_posts() && $loop_type !== 'category' ) )
		return false;

	$source = sanitize_key( $fields['source'] );
	$pagination = $fields['pagination'] ?? 'none';
	$default_items = get_option( 'posts_per_page' ) ?: 10;
	$args = array(
		'post_type' => $source,
		'query' => $query,
		'loop_defaults' => array(
			'loop' => 'article',
			'columns' => 1,
			'columns_mobile' => 1,
			'posts_per_page' => $default_items,
			'orderby' => 'date',
			'order' => 'DESC',
			'featured' => 0,
			'content' => 'excerpt',
			'excerpt_length' => 55,
			'excerpt_more' => '[...]',
			'read_more' => __( 'Continue reading &rarr;', 'md' ),
			'featured_image_size' => 'full',
			'remove_byline' => array(),
			'cta_x_loop' => 0
		),
		'no_pagination' => $pagination === 'none',
		'pagination_type' => $pagination,
		'pagination_arg' => 'md_loop_' . sanitize_key( $id ),
		'skip_subcategory' => true,
		'by_category' => $category_loop ? true : null,
		'by_date' => false,
		'loop_type' => $loop_type,
		'paged' => max( 1, (int) $query->get( 'paged' ) )
	);
	if ( $category_loop ) {
		$args['category_per_page'] = max( 1, (int) ( $fields['category_per_page'] ?? $default_items ) );
		$args['posts_per_category'] = max( 1, (int) ( $fields['posts_per_category'] ?? $default_items ) );
		$args['category_columns'] = max( 1, (int) ( $fields['category_columns'] ?? 1 ) );
		$args['category_orderby'] = $fields['category_orderby'] ?? 'name';
		$args['category_order'] = $fields['category_order'] ?? 'ASC';
		$args['category'] = $fields['category'] ?? array();
		$args['category_include'] = '';
		$args['category_exclude'] = '';
	}
	if ( ! empty( $fields['taxonomy'] ) )
		$args['category_taxonomy'] = sanitize_key( $fields['taxonomy'] );
	if ( $category_loop && ! empty( $fields['terms'] ) )
		$args['category_terms'] = $fields['terms'];
	foreach ( array( 'loop', 'columns', 'columns_mobile', 'content', 'featured_image' ) as $key )
		if ( isset( $fields[$key] ) && $fields[$key] !== '' )
			$args[$key] = $fields[$key];

	if ( ! empty( $args['loop'] ) && ! isset( md_loops()[$args['loop']] ) )
		unset( $args['loop'] );

	if ( isset( $args['content'] ) )
		$args['featured_content'] = $args['content'];

	if ( isset( $args['featured_image'] ) )
		$args['featured_featured_image'] = $args['featured_image'];

	if ( ! empty( $fields['byline'] ) && $fields['byline'] === 'hide' ) {
		$args['remove_byline'] = array( 'remove' => true );
		$args['featured_remove_byline'] = array( 'remove' => true );
	}

	$label = trim( (string) ( $fields['name'] ?? '' ) );
	echo '<section class="md-query-loop md-query-loop-' . esc_attr( $source ) . '">';

	if ( $label )
		echo '<h2 class="md-query-loop-title">' . esc_html( $label ) . '</h2>';

	md_loop( $args );

	echo '</section>';

	return true;
}
