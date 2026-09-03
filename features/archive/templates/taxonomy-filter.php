<?php

$label = ! empty( $fields['all_label'] ) ? $fields['all_label'] : __( 'All', 'md' );
$count = '<span class="small has-muted-color">' . esc_html( number_format_i18n( $total ) ) . '</span>';
$items = array( '<a href="' . esc_url( $archive_url ) . '" class="tag' . ( $active ? ' active' : '' ) . '"' . ( $active ? ' aria-current="page"' : '' ) . '>' . esc_html( $label ) . ' ' . $count . '</a>' );

foreach ( $terms as $term ) {
	$aria = '';
	$classes = 'tag';
	$count = '<span class="small has-muted-color">' . esc_html( number_format_i18n( $term->count ) ) . '</span>';

	if ( $current_id === $term->term_id ) {
		$classes .= ' active';
		$aria = ' aria-current="page"';
	}

	$items[] = '<a href="' . esc_url( get_term_link( $term ) ) . "\" class=\"$classes\"$aria>" . esc_html( $term->name ) . ' ' . $count . '</a>';
}

md_scroller_nav( array(
	'html' => 'nav',
	'label' => __( 'Filter by category', 'md' ),
	'classes' => 'taxonomy-filter',
	'items' => $items
) );
