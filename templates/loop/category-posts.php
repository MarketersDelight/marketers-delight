<?php

$taxonomies = get_object_taxonomies( $post_type );
$taxonomy = ! empty( $taxonomies[0] ) ? $taxonomies[0] : '';
$category_args = array( 'taxonomy' => $taxonomy );
$category_page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$category_per_page = md_module( array( 'loop', 'category_per_page' ), 5 );
$posts_per_page = md_module( array( 'loop', 'posts_per_page' ) );

if ( $category_per_page )
	$category_args['number'] = $category_per_page;

$category_args['offset'] = ( $category_page > 0 ) ?  $category_per_page * ( $category_page - 1 ) : 1;

$categories = new WP_Term_Query( $category_args );

if ( empty( $categories->terms ) )
	md_404_template();
else

foreach ( $categories->terms as $category ) {
	$c = 1;
	$posts = new WP_Query( array(
		'post_type' => $post_type,
		'posts_per_page' => $posts_per_page,
		'no_found_rows' => true,
		'tax_query' => array( array(
			'taxonomy' => $taxonomy,
			'field' => 'slug',
			'terms' => $category->slug
		) )
	) );

	if ( $posts->have_posts() ) {
		$category_id = $category->term_id;

		include( md_template( 'loop/category-post', true ) );
	}

	wp_reset_query();
}
