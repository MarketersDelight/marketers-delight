<?php

$c = 1;
$categories = $terms = array();
$taxonomies = get_object_taxonomies( get_post_type() );

if ( empty( $fields['term'] ) )
	$categories[] = ! empty( $taxonomies[0] ) ? esc_attr( $taxonomies[0] ) : 'category';
elseif ( $fields['term'] == 'all' )
	$categories = $taxonomies;
else
	$categories[] = esc_attr( $fields['term'] );

foreach ( $categories as $category )
	$terms = array_merge( $terms, get_the_terms( get_the_ID(), $category ) );

if ( empty( $terms ) )
	return;

foreach ( $terms as $order => $term ) {
	if ( isset( $fields['settings']['first'] ) && $c > 1 ) return;
	echo '<span class="byline-category byline-item byline-' . esc_attr( $term->slug ) . '"><a href="' . get_term_link( $term->term_id ) . '">' . esc_html( $term->name ) . '</a></span>';
	$c++;
}