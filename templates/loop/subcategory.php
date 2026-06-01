<?php

$queried = get_queried_object();
$child_terms = get_terms( array(
	'taxonomy' => $queried->taxonomy,
	'parent' => $queried->term_id,
	'hide_empty' => false
) );

if ( empty( $child_terms ) || is_wp_error( $child_terms ) )
	return;

echo '<nav class="subcategory-nav">';

foreach ( $child_terms as $child )
	echo '<a href="' . esc_url( get_term_link( $child ) ) . '">' . esc_html( $child->name ) . '</a>';

echo '</nav>';
