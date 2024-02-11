<?php

$t = 1;
$taxonomies = get_object_taxonomies( $post_type );
$taxonomy = ! empty( $taxonomies[0] ) ? $taxonomies[0] : '';
$category_per_page = ! empty( $loop['category_per_page'] ) ? $loop['category_per_page'] : 5;
$categories = new WP_Term_Query( array(
	'taxonomy' => $taxonomy,
	'number' => $category_per_page,
	'offset' => ( $loop['paged'] > 0 ) ?  $category_per_page * ( $loop['paged'] - 1 ) : 1
) );

if ( empty( $loop['category_columns'] ) )
	$loop['category_columns'] = 1;

if ( $loop['category_columns'] > 1 ) {
	$categories_classes[] = 'columns';

	if ( $loop['category_columns'] <= 5 )
		$category_classes[] = 'f' . $loop['category_columns'];
}

if ( isset( $loop['size'] ) && ! empty( $loop['category_posts'] ) )
	$categories_classes[] = esc_attr( $loop['size'] );

$categories_classes = join( ' ', $categories_classes );
$category_classes = join( ' ', $category_classes );

if ( empty( $categories->terms ) )

	md_404_template();

else

echo '<div class="' . esc_attr( $categories_classes ) . '">';

foreach ( $categories->terms as $category ) {
	$c = 1;
	$posts = new WP_Query( array(
		'post_type' => $post_type,
		'posts_per_page' => esc_attr( $posts_per_page ),
		'no_found_rows' => true,
		'tax_query' => array(
			array(
				'taxonomy' => $taxonomy,
				'field' => 'slug',
				'terms' => $category->slug
			)
		)
	) );

	if ( $posts->have_posts() ) {
		$category_id = $category->term_id;
		$category_description = term_description( $category_id );

		include( md_template( 'loop/category-post', true ) );
	}

	md_hook_x_loop( $loop, $t );

	$t++;

	wp_reset_postdata();
}

echo '</div>';

if ( ! isset( $args['query'] ) )
	md_pagination( $loop );
