<?php

$taxonomies = get_object_taxonomies( $post_type );
$taxonomy = ! empty( $taxonomies[0] ) ? $taxonomies[0] : '';
$category_args = array( 'taxonomy' => $taxonomy );
$category_page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$posts_per_page = md_module( array( 'loop', 'posts_per_page' ) );
$category_per_page = md_module( array( 'loop', 'category_per_page' ), 5 );

if ( $category_per_page )
	$category_args['number'] = $category_per_page;

$category_args['offset'] = ( $category_page > 0 ) ?  $category_per_page * ( $category_page - 1 ) : 1;
$categories = get_terms( $category_args );

if ( empty( $categories ) )
	md_404_template();

else

foreach ( $categories as $category ) {
	$posts = new WP_Query( array(
		'post_type' => $post_type,
		'posts_per_page' => $posts_per_page,
		'tax_query' => array( array(
			'taxonomy' => $taxonomy,
			'field' => 'slug',
			'terms' => $category->slug
		) )
	) );

	if ( $posts->have_posts() ) {
		$category_id = $category->term_id;

		echo '<div class="category-row">'.
			 '<div class="category-header">'.
			 '<h2 class="title">' . esc_html( $category->name ) . '</h2>'.
			 term_description( $category_id ).
			 '</div>'.
			 "<div class=\"category-posts loop$wrap_classes\">";

		while ( $posts->have_posts() ) {
			$posts->the_post();
			include( md_template( 'loops/the-post', true ) );
		}

		echo '</div>'.
			 '</div>';

	}

	wp_reset_query();
}
