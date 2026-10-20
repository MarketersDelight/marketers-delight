<?php
/**
 * Field definitions for Query Loop builder items.
 *
 * @since 6.0
 */

function md_query_loop_fields() {
	$data = new md_fields_data;

	return array(
		'source' => array(
			'type' => 'text'
		),
		'context' => array(
			'type' => 'select',
			'options' => array( 'current', 'latest' )
		),
		'loop_type' => array(
			'type' => 'select',
			'options' => array( 'month', 'category', 'category_posts' )
		),
		'items' => array(
			'type' => 'number',
			'min' => -1,
			'max' => 100
		),
		'orderby' => array(
			'type' => 'select',
			'options' => array( 'date', 'title', 'modified', 'comment_count', 'menu_order' )
		),
		'order' => array(
			'type' => 'select',
			'options' => array( 'ASC', 'DESC' )
		),
		'taxonomy' => array(
			'type' => 'text'
		),
		'terms' => array(
			'type' => 'text'
		),
		'exclude_terms' => array(
			'type' => 'text'
		),
		'category_per_page' => array(
			'type' => 'number',
			'min' => -1,
			'max' => 100
		),
		'posts_per_category' => array(
			'type' => 'number',
			'min' => -1,
			'max' => 100
		),
		'category_columns' => array(
			'type' => 'number'
		),
		'category_orderby' => array(
			'type' => 'select',
			'options' => array( 'latest', 'slug', 'term_id', 'count', 'parent' )
		),
		'category_order' => array(
			'type' => 'select',
			'options' => array( 'ASC', 'DESC' )
		),
		'category' => array(
			'type' => 'checkbox',
			'options' => array( 'show_empty', 'hide_description' )
		),
		'loop' => array(
			'type' => 'text'
		),
		'columns' => array(
			'type' => 'number'
		),
		'columns_mobile' => array(
			'type' => 'number'
		),
		'content' => array(
			'type' => 'select',
			'options' => array( 'excerpt', 'full', 'hide' )
		),
		'featured_image' => array(
			'type' => 'select',
			'options' => array_keys( $data->values['featured_image'] )
		),
		'byline' => array(
			'type' => 'select',
			'options' => array( 'hide' )
		),
		'pagination' => array(
			'type' => 'select',
			'options' => array( 'none', 'page_numbers', 'prev_next' )
		)
	);
}

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
 * Render one independent query through the existing MD Loop function.
 *
 * @since 6.0.3
 */

function md_query_loop( $fields, $post_type, $id = '' ) {
	$id = $id ?: 'query_loop';
	$query = md_query_loop_query( $fields, $id );
	$loop_type = ( $fields['loop_type'] ?? '' ) ?: 'post_listing';
	$category_loop = in_array( $loop_type, array( 'category', 'category_posts' ), true );

	if ( ! $query || ( ! $query->have_posts() && $loop_type !== 'category' ) )
		return false;

	$source = sanitize_key( $fields['source'] );
	$taxonomy = sanitize_key( $fields['taxonomy'] ?? '' );
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

	if ( $taxonomy )
		$args['category_taxonomy'] = $taxonomy;

	if ( $category_loop ) {
		$args['category_per_page'] = md_query_loop_count( $fields['category_per_page'] ?? '', $default_items );
		$args['posts_per_category'] = md_query_loop_count( $fields['posts_per_category'] ?? '', $default_items );
		$args['category_columns'] = max( 1, (int) ( $fields['category_columns'] ?? 1 ) );
		$args['category_orderby'] = $fields['category_orderby'] ?? 'name';
		$args['category_order'] = $fields['category_order'] ?? 'ASC';
		$args['category'] = $fields['category'] ?? array();
		$args['category_include'] = '';
		$source_taxonomies = get_object_taxonomies( $source );
		$args['category_exclude'] = implode( ',', md_parse_term_ids( $fields['exclude_terms'] ?? '', $taxonomy ?: ( $source_taxonomies[0] ?? '' ) ) );

		if ( ! empty( $fields['terms'] ) )
			$args['category_terms'] = $fields['terms'];
	}

	// Display settings left blank use the Query Loop defaults above

	foreach ( array( 'loop', 'columns', 'columns_mobile', 'content', 'featured_image' ) as $key )
		if ( ( $fields[$key] ?? '' ) !== '' )
			$args[$key] = $fields[$key];

	$loops = md_loops();

	if ( isset( $args['loop'] ) && ! isset( $loops[$args['loop']] ) )
		unset( $args['loop'] );

	if ( isset( $args['content'] ) )
		$args['featured_content'] = $args['content'];

	if ( isset( $args['featured_image'] ) )
		$args['featured_featured_image'] = $args['featured_image'];

	if ( ( $fields['byline'] ?? '' ) === 'hide' )
		$args['remove_byline'] = $args['featured_remove_byline'] = array( 'remove' => true );

	// A labeled loop is a <section> with its own h2, so its item headings drop a level.

	$label = trim( (string) ( $fields['name'] ?? '' ) );
	$tag = $label ? 'section' : 'div';
	$args['heading_offset'] = $label ? 1 : 0;

	echo "<$tag class=\"md-query-loop md-query-loop-" . esc_attr( $source ) . '">';

	if ( $label )
		echo '<h2 class="md-query-loop-title">' . esc_html( $label ) . '</h2>';

	md_loop( $args );

	echo "</$tag>";

	return true;
}

/**
 * Create a bounded WP_Query using the selected source and page context.
 *
 * @since 6.0
 */

function md_query_loop_query( $fields, $id ) {
	// Check the post type and page context, and stop if either can't be used

	$sources = md_query_loop_sources();
	$source = sanitize_key( $fields['source'] ?? '' );
	$context = md_query_loop_context( $fields['context'] ?? 'current' );

	if ( ! isset( $sources[$source] ) || $context === null )
		return null;

	// Read the display mode, pagination, and sort settings

	$loop_type = ( $fields['loop_type'] ?? '' ) ?: 'post_listing';
	$pagination = $fields['pagination'] ?? 'none';
	$page_key = 'md_loop_' . sanitize_key( $id );
	$orderby = in_array( $fields['orderby'] ?? '', array( 'title', 'modified', 'comment_count', 'menu_order', 'rand' ), true ) ? $fields['orderby'] : 'date';

	// Each display mode keeps its number of items in a different setting

	$count = 'items';

	if ( $loop_type === 'category' )
		$count = 'category_per_page';
	elseif ( $loop_type === 'category_posts' )
		$count = 'posts_per_category';

	// Month groups need date order, and random order can't be paginated

	if ( $loop_type === 'month' || ( $orderby === 'rand' && $pagination !== 'none' ) )
		$orderby = 'date';

	// Build the query from the settings and the current page's context

	$query = array_merge( array(
		'post_type' => $source,
		'post_status' => 'publish',
		'posts_per_page' => md_query_loop_count( $fields[$count] ?? '', get_option( 'posts_per_page' ) ?: 10 ),
		'paged' => $pagination !== 'none' && isset( $_GET[$page_key] ) ? max( 1, absint( wp_unslash( $_GET[$page_key] ) ) ) : 1,
		'ignore_sticky_posts' => 1
	), $context );

	// Search results keep relevance order unless an order is chosen

	if ( ! isset( $context['s'] ) || ! empty( $fields['orderby'] ) || $loop_type === 'month' ) {
		$query['orderby'] = $orderby;
		$query['order'] = ( $fields['order'] ?? '' ) === 'ASC' ? 'ASC' : 'DESC';
	}

	// Limit to chosen terms (or any term in the taxonomy), then leave out excluded terms

	$taxonomy = sanitize_key( $fields['taxonomy'] ?? '' );
	$terms = array_filter( array_map( 'trim', explode( ',', (string) ( $fields['terms'] ?? '' ) ) ) );

	if ( $taxonomy ) {
		if ( ! is_object_in_taxonomy( $source, $taxonomy ) )
			return null;

		if ( $terms ) {
			$ids_only = count( array_filter( $terms, 'is_numeric' ) ) === count( $terms );
			$query['tax_query'][] = array(
				'taxonomy' => $taxonomy,
				'field' => $ids_only ? 'term_id' : 'slug',
				'terms' => $ids_only ? array_map( 'absint', $terms ) : array_map( 'sanitize_title', $terms )
			);
		}
		else $query['tax_query'][] = array( 'taxonomy' => $taxonomy, 'operator' => 'EXISTS' );

		if ( $exclude = md_parse_term_ids( $fields['exclude_terms'] ?? '', $taxonomy ) )
			$query['tax_query'][] = array( 'taxonomy' => $taxonomy, 'field' => 'term_id', 'terms' => $exclude, 'operator' => 'NOT IN' );
	}

	// Make the query

	return new WP_Query( $query );
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

	if ( is_category() || is_tag() || is_tax() )
		return array( 'tax_query' => array( array(
			'taxonomy' => get_queried_object()->taxonomy,
			'field' => 'term_id',
			'terms' => get_queried_object_id()
		) ) );

	return array();
}

/**
 * Resolve a Query Loop count: -1 shows all, blank or 0 uses the default, and
 * anything else is capped at 100.
 *
 * @since 6.0.3
 */

function md_query_loop_count( $value, $default ) {
	$value = (int) $value;

	if ( $value < 0 )
		return -1;

	return $value ? min( 100, $value ) : (int) $default;
}
