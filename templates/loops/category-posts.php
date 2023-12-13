<?php

foreach ( $categories as $category ) {
	$posts = new WP_Query( array(
		'post_type' => $post_type,
		'posts_per_page' => 5,
		'tax_query' => array( array(
			'taxonomy' => $taxonomy,
			'field' => 'slug',
			'terms' => $category->slug
		) )
	) );

	if ( $posts->have_posts() ) {
		$category_id = $category->term_id;
		$category_name = $category->name;

		echo '<div class="category-row">'.
			 '<h2 class="title">' . $category_name . '</h2>'.
			 "<div class=\"category-posts$wrap_classes\">";

		while ( $posts->have_posts() ) {
			$posts->the_post();
			include( md_template( 'loops/the-post', true ) );
		}

		echo '</div>'.
			 '</div>';

	}

	wp_reset_query();

}
