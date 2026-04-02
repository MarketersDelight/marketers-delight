<?php

$loop = $args['loop'] = md_loop_item( $loop_base, $c );
$classes = md_post_class( $args, $c );

if ( isset( $loop_type ) && ! empty( $loops[$loop_type]['template'] ) )
	include esc_attr( $loops[$loop_type]['template'] );
else
	include md_template( 'loop/loop', true );

if ( empty( $loop['category_posts']['enable'] ) )
	md_hook_x_loop( $args, $c );

$c++;