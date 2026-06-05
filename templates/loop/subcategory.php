<?php

if ( ! isset( $queried ) )
	$queried = get_queried_object();

$taxonomies = get_object_taxonomies( $post_type );
$children = get_terms( array(
	'taxonomy' => ( ! empty( $taxonomies[0] ) ? $taxonomies[0] : '' ),
	'parent' => ( isset( $queried->term_id ) ? $queried->term_id : 0 ),
	'hide_empty' => false
) );

if ( empty( $children ) || is_wp_error( $children ) )
	return;

//echo '<div class="subcategory ' . md_loop_classes() . '">';

echo '<div class="subcategory loop box-style full columns columns-4">';

foreach ( $children as $child ) {
	echo
		'<div class="entry"><div class="item">'.
		'<a href="' . esc_url( get_term_link( $child ) ) . '">' . esc_html( $child->name ) . '</a>'.
		'</div></div>';
}

echo '</div>';