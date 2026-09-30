<?php
/**
 * Reusable post type queries rendered with MD's existing Loop templates.
 *
 * @since 6.0
 */

/**
 * Public content post types with MD settings can be queried.
 * Child/helper types inherit another type's settings and are excluded.
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
 * Defaults remain virtual until an administrator saves the builder.
 */
function md_query_loops_defaults( $areas = array( 'author', 'search' ) ) {
	$rows = array();

	foreach ( $areas as $area )
		foreach ( md_query_loop_sources( $area ) as $source => $label )
			$rows["{$area}_{$source}"] = array(
				'builder_type' => 'query_loop',
				'builder_area' => $area,
				'name' => $label,
				'source' => $source,
				'context' => 'current',
				'pagination' => 'page_numbers'
			);

	return $rows;
}

/**
 * Public taxonomies used by available Query Loop sources.
 */
function md_query_loop_taxonomies() {
	static $taxonomies = null;

	if ( $taxonomies !== null )
		return $taxonomies;

	$taxonomies = array();

	foreach ( array_keys( md_query_loop_sources() ) as $source )
		foreach ( get_object_taxonomies( $source, 'objects' ) as $key => $object ) {
			if ( is_object( $object ) ) {
				if ( empty( $object->public ) )
					continue;

				$taxonomy = $object->name ?? $key;
				$taxonomy_label = $object->labels->name ?? $taxonomy;
			}
			else {
				$taxonomy = $object;
				$taxonomy_object = get_taxonomy( $taxonomy );

				if ( $taxonomy_object && empty( $taxonomy_object->public ) )
					continue;

				$taxonomy_label = $taxonomy_object->labels->name ?? $taxonomy;
			}

			$taxonomies[$taxonomy] = $taxonomy_label;
		}

	return $taxonomies;
}

/**
 * Field definitions for Query Loop builder items.
 */
function md_query_loop_fields() {
	return array(
		'source' => array( 'type' => 'text' ),
		'context' => array( 'type' => 'select', 'options' => array( 'current', 'latest' ) ),
		'items' => array( 'type' => 'number' ),
		'orderby' => array( 'type' => 'select', 'options' => array( 'date', 'title', 'modified', 'comment_count', 'menu_order' ) ),
		'order' => array( 'type' => 'select', 'options' => array( 'ASC', 'DESC' ) ),
		'taxonomy' => array( 'type' => 'text' ),
		'terms' => array( 'type' => 'text' ),
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
 */
function md_query_loop_admin_fields( $group, $type, $fields, $row = array() ) {
	$sources = md_query_loop_sources();
	$taxonomies = md_query_loop_taxonomies();

	include md_template( 'features', 'loop/admin/query-loop', true );
}

/**
 * Resolve the active archive filter for one secondary query.
 */
function md_query_loop_context( $context ) {
	if ( $context === 'latest' )
		return array();

	if ( $context !== 'current' )
		return null;

	if ( is_author() )
		$context = 'author';
	elseif ( is_search() )
		$context = 'search';
	elseif ( is_date() )
		$context = 'date';
	elseif ( is_category() || is_tag() || is_tax() )
		$context = 'term';
	else
		$context = 'latest';

	if ( $context === 'author' )
		return is_author() ? array( 'author' => get_queried_object_id() ) : null;

	if ( $context === 'search' )
		return is_search() ? array( 's' => get_query_var( 's' ) ) : null;

	if ( $context === 'date' ) {
		if ( ! is_date() )
			return null;

		$query = array( 'year' => (int) get_query_var( 'year' ) );

		if ( is_month() || is_day() )
			$query['monthnum'] = (int) get_query_var( 'monthnum' );

		if ( is_day() )
			$query['day'] = (int) get_query_var( 'day' );

		return $query;
	}

	if ( $context === 'term' ) {
		$term = get_queried_object();

		return $term instanceof WP_Term ? array( 'tax_query' => array( array(
			'taxonomy' => $term->taxonomy,
			'field' => 'term_id',
			'terms' => $term->term_id
		) ) ) : null;
	}

	return $context === 'latest' ? array() : null;
}

/**
 * Create a bounded WP_Query using the selected source and page context.
 */
function md_query_loop_query( $fields, $id ) {
	$source = sanitize_key( $fields['source'] ?? '' );

	if ( ! isset( md_query_loop_sources()[$source] ) )
		return null;

	$context = md_query_loop_context( $fields['context'] ?? 'current' );

	if ( $context === null )
		return null;

	$settings = md_post_type_field( 'loop', array(), $source );
	$settings = is_array( $settings ) ? $settings : array();
	$items = ! empty( $fields['items'] ) ? (int) $fields['items'] : (int) ( $settings['posts_per_page'] ?? get_option( 'posts_per_page' ) );
	$items = max( 1, min( 100, $items ) );
	$orderby = $fields['orderby'] ?? '';
	$order = $fields['order'] ?? '';
	$orderby = $orderby ?: ( $settings['orderby'] ?? 'date' );
	$order = $order ?: ( $settings['order'] ?? 'DESC' );
	$orderby = in_array( $orderby, array( 'date', 'title', 'modified', 'comment_count', 'menu_order', 'rand' ), true ) ? $orderby : 'date';
	$order = $order === 'ASC' ? 'ASC' : 'DESC';
	$pagination = $fields['pagination'] ?? 'none';


	// Random order has no stable page two. Paginated loops use date order.
	if ( $orderby === 'rand' && $pagination !== 'none' )
		$orderby = 'date';

	$search_relevance = isset( $context['s'] ) && empty( $fields['orderby'] );
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
 */
function md_query_loop_render( $fields, $id = '' ) {
	$id = $id ?: 'query_loop';
	$query = md_query_loop_query( $fields, $id );

	if ( ! $query || ! $query->have_posts() )
		return false;

	$source = sanitize_key( $fields['source'] );
	$args = array(
		'post_type' => $source,
		'query' => $query,
		'no_pagination' => true,
		'skip_subcategory' => true,
	);

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
	$pagination = $fields['pagination'] ?? 'none';

	echo '<section class="md-query-loop md-query-loop-' . esc_attr( $source ) . '">';

	if ( $label )
		echo '<h2 class="md-query-loop-title">' . esc_html( $label ) . '</h2>';

	md_loop( $args );

	if ( $pagination !== 'none' )
		md_query_loop_pagination( $query, $id, $pagination );

	echo '</section>';

	return true;
}

/**
 * Each loop has its own page number so multiple rows paginate separately.
 */
function md_query_loop_pagination( $query, $id, $type ) {
	if ( $query->max_num_pages <= 1 )
		return;

	$page_key = 'md_loop_' . sanitize_key( $id );
	$page = max( 1, (int) $query->get( 'paged' ) );

	if ( $type === 'prev_next' ) {
		$base = get_pagenum_link( 1 );
		echo '<nav class="pagination prev-next" aria-label="' . esc_attr__( 'Query Loop pages', 'md' ) . '">';

		if ( $page > 1 )
			echo '<a class="prev page-numbers" href="' . esc_url( add_query_arg( array( $page_key => $page - 1 ), $base ) ) . '">' . esc_html__( 'Previous', 'md' ) . '</a>';

		if ( $page < $query->max_num_pages )
			echo '<a class="next page-numbers" href="' . esc_url( add_query_arg( array( $page_key => $page + 1 ), $base ) ) . '">' . esc_html__( 'Next', 'md' ) . '</a>';

		echo '</nav>';
		return;
	}

	$big = 999999999;
	$base = str_replace( $big, '%#%', add_query_arg( array( $page_key => $big ), get_pagenum_link( 1 ) ) );
	$links = paginate_links( array(
		'base' => $base,
		'format' => '',
		'current' => $page,
		'total' => $query->max_num_pages,
		'type' => 'list',
		'mid_size' => 2,
		'end_size' => 1,
		'prev_text' => __( 'Previous', 'md' ),
		'next_text' => __( 'Next', 'md' )
	) );

	if ( $links )
		echo '<nav class="pagination numbers" aria-label="' . esc_attr__( 'Query Loop pages', 'md' ) . '">' . $links . '</nav>';
}
