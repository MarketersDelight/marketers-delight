<?php

$style = array();
$cover = md_cover();
$loop = $looped;
$loop = md_the_loop( $loop, $c );
$classes = md_loop_classes( $loop, $c );

if ( $loop['columns'] > 5 )
	$style['flex_basis'] = ( 100 / $loop['columns'] ) . '%';

if ( isset( $loop['loop'] ) && ! empty( $loops[$loop['loop']]['template'] ) )
	include( esc_attr( $loops[$loop['loop']]['template'] ) );
else
	include( md_template( 'loop/loop', true ) );

if ( empty( $loop['category_posts']['enable'] ) )
	md_hook_x_loop( $loop, $c );

$c++;
