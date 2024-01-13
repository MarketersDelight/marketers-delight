<?php

$query_args = array(
	'post_type' => $post_type,
	'posts_per_page' => $posts_per_page,
	'no_found_rows' => true
);

if ( isset( $loop['orderby'] ) )
	$query_args['orderby'] = esc_attr( $loop['orderby'] );

if ( isset( $loop['order'] ) )
	$query_args['order'] = esc_attr( $loop['order'] );

if ( isset( $loop['offset'] ) )
	$query_args['offset'] = esc_attr( $loop['offset'] );

if ( isset( $loop['tags'] ) )
	$query_args['tag'] = $loop['tags'];

if ( isset( $loop['author'] ) )
	$query_args['author__in'] = $loop['author'];

if ( isset( $loop['include_cats'] ) ) {
	$taxonomies = get_object_taxonomies( $post_type );
	$taxonomy = ! empty( $taxonomies[0] ) ? $taxonomies[0] : '';

	$query_args['tax_query'] = array(
		array(
			'taxonomy' => $taxonomy,
			'field' => 'term_id',
			'terms' => array_keys( $loop['include_cats'] )
		)
	);
}

$query = new WP_Query( $query_args );

if ( $query->have_posts() )

while ( $query->have_posts() ) {
	$query->the_post();

	include( md_template( 'loop/the-post', true ) );
}

else
	md_404_template();

wp_reset_postdata();
