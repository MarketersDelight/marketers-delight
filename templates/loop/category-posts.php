<?php

$t = 1;
$taxonomies = get_object_taxonomies( $post_type );
$taxonomy = ! empty( $taxonomies[0] ) ? $taxonomies[0] : '';
$term_args = array(
	'parent' => ( is_tax() || is_category() ) ? get_queried_object_id() : 0,
	'taxonomy' => $taxonomy
);

// Set query ordering by category_{order} fields

foreach ( array( 'orderby' => 'category_orderby', 'order' => 'category_order' ) as $arg => $key )
	if ( ! empty( $loop[$key] ) )
		$term_args[$arg] = $loop[$key];

// Filter to specific term IDs

foreach ( array( 'include', 'exclude' ) as $sort )
	if ( ! empty( $loop["category_$sort"] ) )
		$term_args[$sort] = array_map( 'intval', array_filter( explode( ',', $loop["category_$sort"] ) ) );

if ( ! empty( $loop['category']['show_empty'] ) )
	$term_args['hide_empty'] = false;

// Pagination

if ( ! empty( $loop['category_per_page'] ) ) {
	$term_args['number'] = (int) $loop['category_per_page'];
	$term_args['offset'] = $term_args['number'] * ( $loop['paged'] - 1 );
}

// Render category loop listing

$categories = new WP_Term_Query( $term_args );

if ( empty( $categories->terms ) ) {
	md_404();

	return false;
}

echo '<div id="loop" class="' . esc_attr( $loop['category_classes'] ) . '">';

foreach ( $categories->terms as $category ) {
	$category_description = term_description( $category->term_id );

	if ( $loop['loop_type'] === 'category_posts' ) {
		$posts = new WP_Query( array(
			'post_type' => $post_type,
			'posts_per_page' => absint( ! empty( $loop['posts_per_category'] ) ? $loop['posts_per_category'] : $loop['posts_per_page'] ),
			'no_found_rows' => true,
			'tax_query' => array(
				array(
					'field' => 'slug',
					'taxonomy' => $taxonomy,
					'terms' => $category->slug
				)
			)
		) );

		if ( $posts->have_posts() || ! empty( $loop['category']['show_empty'] ) )
			include md_template( 'loop/category-post', true );

		wp_reset_postdata();

	}
	else include md_template( 'loop/category-post', true );

	md_hook_x_loop( $loop, $t );

	$t++;
}

echo '</div>';

// Category pagination

if ( ! isset( $args['query'] ) && ! empty( $loop['category_per_page'] ) )
	md_pagination( $loop );