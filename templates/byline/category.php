<?php

if ( empty( $fields['term'] ) ) {
	$taxonomies = get_object_taxonomies( get_post_type() );
	$term = ! empty( $taxonomies[0] ) ? esc_attr( $taxonomies[0] ) : 'category';
}
else $term = esc_attr( $fields['term'] );

$terms = get_the_terms( get_the_ID(), $term );

if ( ! empty( $terms ) )
	foreach ( $terms as $order => $term )
		echo '<span class="byline-category byline-item"><a href="' . get_term_link( $term->term_id ) . '">' . esc_html( $term->name ) . '</a></span>';