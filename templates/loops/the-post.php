<?php

$classes = $style = array();

if ( $columns > 1 ) {
	if ( $columns <= 5 )
		$classes[] = "f{$columns}";
	elseif ( $columns > 5 )
		$style['flex_basis'] = ( 100 / $columns ) . '%';

	$classes[] = $c % 2 == 0 ? 'even' : 'odd';
}

$classes = join( ' ', $classes );

if ( ! empty( $loops[$loop]['dropin'] ) )
	include( md_template( 'dropins', "{$loop}/loop-{$loop}", true ) );
elseif ( ! empty( $loops[$loop] ) )
	include( md_template( 'loops/loop' . ( $loop == 'default' ? '' : "-{$loop}" ), true ) );
else
	include( md_template( 'loops/loop', true ) );

md_hook_x_loop( $c );

$c++;
