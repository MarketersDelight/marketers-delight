<?php

$classes = $style = array();

if ( ! is_singular() )
	if ( ! empty( $loop['featured'] ) && $c <= $loop['featured'] )
		$classes[] = 'featured';
	else
		$classes[] = 'standard';

if ( $columns > 1 )
	if ( $columns <= 5 )
		$classes[] = "f{$columns}";
	else
		$style['flex_basis'] = ( 100 / $columns ) . '%';

$classes[] = $c % 2 == 0 ? 'even' : 'odd';
$classes = join( ' ', $classes );

if ( ! empty( $loops[$loop_id]['dropin'] ) )
	include( md_template( 'dropins', "{$loop_id}/loop-{$loop_id}", true ) );
elseif ( ! empty( $loops[$loop_id] ) )
	include( md_template( 'loop/loop' . ( $loop_id == 'default' ? '' : "-{$loop_id}" ), true ) );
else
	include( md_template( 'loop/loop', true ) );

md_hook_x_loop( $loop, $c );

$c++;
