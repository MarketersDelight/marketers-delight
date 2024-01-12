<?php

$args = array( 'query' => $fields );
$query_classes = array( 'query' );
$has_sidebar = ! empty( $fields['sidebar']['enable'] ) ? true : false;

if ( $has_sidebar ) {
	$query_classes[] = 'content-sidebar';

	if ( isset( $fields['content_layout'] ) && $fields['content_layout'] == 'sidebar_content' )
		$query_classes[] = 'left';
}

$query_classes = join( ' ', $query_classes );

echo '<div class="' . $query_classes . '">';

if ( $has_sidebar ) {
	$args['has_sidebar'] = true;

	echo '<div class="content">';
}

md_loop( $args );

if ( $has_sidebar ) {
	$index = 'sidebar-main';

	echo
		'</div>'.
		'<div class="sidebar' . ( isset( $fields['sidebar']['sticky'] ) ? ' sticky' : '' ) . '">';

	if ( ! empty( $fields['custom_sidebar'] ) )
		$index = $fields['custom_sidebar'];

	dynamic_sidebar( $index );

	echo '</div>';
}

echo '</div>';
