<?php

foreach ( $categories as $category ) {
	$posts = new WP_Query( array(
		'post_type' => $post_type,
		'posts_per_page' => 2,
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
