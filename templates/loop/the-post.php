<?php

$loop = $looped;
$loop = md_the_loop( $loop, $c );
$classes = md_post_class( $loop, $c );

if ( isset( $loop['loop'] ) && ! empty( $loops[$loop['loop']]['template'] ) )
	include( esc_attr( $loops[$loop['loop']]['template'] ) );
else
	include( md_template( 'loop/loop', true ) );

if ( empty( $loop['category_posts']['enable'] ) )
	md_hook_x_loop( $loop, $c );

$c++;
