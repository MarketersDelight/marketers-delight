<?php

$loop = $args['loop'] = md_loop_item( $loop_base, $c );
$classes = md_post_class( $loop, $c );

if ( isset( $loop_template ) && ! empty( $loops[$loop_template]['template'] ) )
	include esc_attr( $loops[$loop_template]['template'] );
else
	include md_template( 'loop/loop', true );

if ( $loop['loop_type'] !== 'category_posts' )
	md_hook_x_loop( $loop, $c );

$c++;