<?php

$taxonomy = md_category_loop_taxonomy( $loop );
$categories = md_category_loop_terms( $loop, $taxonomy );
$list_posts = $loop['loop_type'] === 'category_posts';

if ( ! $categories ) {
	if ( empty( $args['query'] ) )
		md_404();

	return false;
}

$stickies = $list_posts ? md_category_loop_stickies( $post_type, $taxonomy ) : array();

echo '<div class="' . esc_attr( $loop['categories_classes'] ) . '">';

foreach ( $categories as $index => $category ) {

	if ( $list_posts ) {
		$posts = md_category_loop_posts( $category, $taxonomy, $loop, $stickies[$category->slug] ?? array() );

		if ( $posts->have_posts() || ! empty( $loop['category']['show_empty'] ) )
			include md_template( 'features', 'loop/category-post', true );

		wp_reset_postdata();
	}
	else include md_template( 'features', 'loop/category-post', true );

	md_hook_x_loop( $loop, $index + 1 );
}

echo '</div>';

md_pagination( $loop );
