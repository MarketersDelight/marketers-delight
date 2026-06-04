<?php

$t = 1;
$loop_class = 'loop-' . str_replace( '_', '-', $post_type );
$taxonomies = get_object_taxonomies( $post_type );
$taxonomy = ! empty( $taxonomies[0] ) ? $taxonomies[0] : '';

// Build Term_Query args

$term_args['taxonomy'] = $taxonomy;
$term_args['parent']   = 0;

if ( ! empty( $loop['category_orderby'] ) )
	$term_args['orderby'] = $loop['category_orderby'];

if ( ! empty( $loop['category_per_page'] ) ) {
	$category_per_page = (int) $loop['category_per_page'];
	$term_args['number'] = $category_per_page;
	$term_args['offset'] = $category_per_page * ( $loop['paged'] - 1 );
}

if ( ! empty( $loop['category_order'] ) )
	$term_args['order'] = $loop['category_order'];

foreach ( array( 'include', 'exclude' ) as $sort )
	if ( ! empty( $loop["category_$sort"] ) )
		$term_args[$sort] = array_map( 'intval', array_filter( explode( ',', $loop["category_$sort"] ) ) );

if ( ! empty( $loop['category_posts']['show_empty'] ) )
	$term_args['hide_empty'] = false;

// Compile loop classes

$categories_classes = array( 'categories', "category-$loop_class" );

if ( isset( $loop['category_columns'] ) && $loop['category_columns'] > 1 ) {
	$categories_classes[] = 'columns';
	$categories_classes[] = 'columns-' . $loop['category_columns'];

	if ( $loop['category_columns'] >= 3 )
		$categories_classes[] = 'slim';
}

$categories_classes = join( ' ', $categories_classes );

// Render category loop listing

$categories = new WP_Term_Query( $term_args );

if ( empty( $categories->terms ) ) {
	md_404();

	return false;
}

echo '<div id="loop" class="' . esc_attr( $categories_classes ) . '">';

foreach ( $categories->terms as $category ) {
	$c = 1;
	$posts = new WP_Query( array(
		'post_type' => $post_type,
		'posts_per_page' => absint( $loop['posts_per_page'] ),
		'no_found_rows' => true,
		'tax_query' => array(
			array(
				'taxonomy' => $taxonomy,
				'field' => 'slug',
				'terms' => $category->slug
			)
		)
	) );

	$category_id = $category->term_id;
	$category_description = term_description( $category_id );

	if ( $posts->have_posts() || ! empty( $loop['category_posts']['show_empty'] ) )
		include md_template( 'loop/category-post', true );

	md_hook_x_loop( $loop, $t );

	$t++;

	wp_reset_postdata();
}

echo '</div>';

if ( ! isset( $args['query'] ) && ! empty( $loop['category_per_page'] ) )
	md_pagination( $loop );