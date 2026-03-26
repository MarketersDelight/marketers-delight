<?php

$c = 1;
$categories = $terms = array();
$post_id = isset( $fields['post_id'] ) ? $fields['post_id'] : get_the_ID();
$post_type = isset( $fields['post_type'] ) ? $fields['post_type'] : md_get_post_type();
$taxonomies = get_object_taxonomies( $post_type );

if ( empty( $fields['term'] ) )
	$categories[] = ! empty( $taxonomies[0] ) ? esc_attr( $taxonomies[0] ) : 'category';
elseif ( $fields['term'] == 'all' )
	$categories = $taxonomies;
else
	$categories[] = esc_attr( $fields['term'] );

foreach ( $categories as $category )
	$terms = array_merge( $terms, get_the_terms( $post_id, $category ) );

if ( empty( $terms ) )
	return;

foreach ( $terms as $order => $term ) {
	if ( isset( $fields['settings']['first'] ) && $c > 1 )
		return;

	$tax = 'tax-' . str_replace( '_', '-', $term->taxonomy );

	echo '<span class="byline-item byline-category byline-' . esc_attr( $term->slug ) . ' byline-' . esc_attr( $tax ) . '"><a href="' . get_term_link( $term->term_id ) . '">' . esc_html( $term->name ) . '</a></span>';

	$c++;
}