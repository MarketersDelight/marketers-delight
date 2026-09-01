<?php

$term = $term ?? get_queried_object();
$taxonomies = get_object_taxonomies( $post_type );
$children = get_terms( array(
	'taxonomy' => ( ! empty( $taxonomies[0] ) ? $taxonomies[0] : '' ),
	'parent' => $term->term_id ?? 0,
	'hide_empty' => false
) );

if ( empty( $children ) || is_wp_error( $children ) )
	return;

if ( ! empty( $loop['loop_type'] ) && $loop['loop_type'] === 'category' ) {

	echo '<ul class="subcategory list">';

	foreach ( $children as $child )
		echo '<li><a href="' . esc_url( get_term_link( $child ) ) . '">' . esc_html( $child->name ) . '</a></li>';

	echo '</ul>';

}

else {

	$scroller_args = array();

	foreach ( $children as $child )
		$scroller_args['items'][] = '<a href="' . esc_url( get_term_link( $child ) ) . '" class="tag">' . esc_html( $child->name ) . '</a>';

	md_scroller_nav( $scroller_args );

}
