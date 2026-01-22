<?php

if ( has_action( 'md_hook_byline_' . get_post_type() ) )
	return do_action( 'md_hook_byline_' . get_post_type(), true );

if ( isset( $args['items'] ) )
	$items = $args['items'];
else {
	$loop = isset( $args['loop'] ) ? $args['loop'] : array();
	$items = md_get_byline( $location, $loop );
}

if ( empty( $items ) && ! empty( $args['default'] ) )
	$items = $args['default'];
elseif ( empty( $items ) ) return;

$c = 1;
$classes = array( 'byline' );
$classes[] = esc_attr( str_replace( '_', '-', $location ) );
$html = isset( $args['html'] ) ? $args['html'] : 'div';
$total = count( $items );
$data = md_byline_items();

if ( isset( $args['classes'] ) )
	$classes[] = $args['classes'];

if ( $total >= 3 )
	$classes[] = 'can-wrap';

if ( ! empty( $items['share'] ) )
	$classes[] = 'has-share';

$classes = join( ' ', $classes );

echo "<$html class=\"" .  esc_attr( $classes ) . '">';

foreach ( $items as $item => $fields ) {
	$fields['c'] = $c;

	if ( isset( $data[$item]['template'] ) )
		call_user_func( $data[$item]['template'], $fields );
	else
		include md_template( "byline/$item", true );

	$c++;
}

echo "</$html>";