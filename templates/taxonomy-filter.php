<?php

$label = ( ! empty( $settings['all_label'] ) ? $settings['all_label'] : __( 'All', 'md' ) ) . ' ' . number_format_i18n( $total );
$items = array( '<a href="' . esc_url( $archive_url ) . '" class="tag' . ( $active ? ' active' : '' ) . '"' . ( $active ? ' aria-current="page"' : '' ) . '>' . esc_html( $label ) . '</a>' );

foreach ( $terms as $term ) {
	$aria = '';
	$classes = 'tag';
	$label = "$term->name " . number_format_i18n( $term->count );

	if ( $current_id === $term->term_id ) {
		$classes .= ' active';
		$aria = ' aria-current="page"';
	}

	$items[] = '<a href="' . esc_url( get_term_link( $term ) ) . "\" class=\"$classes\"$aria>" . esc_html( $label ) . '</a>';
}

md_scroller_nav( array(
	'html' => 'nav',
	'label' => __( 'Filter by category', 'md' ),
	'classes' => 'taxonomy-filter',
	'items' => $items
) );
