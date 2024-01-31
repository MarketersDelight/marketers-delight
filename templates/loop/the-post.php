<?php

$style = array();
$cover = md_cover();
$loop = $looped;

if ( ! empty( $loop['featured'] ) && $c <= $loop['featured'] )
	$loop = md_loop_featured( $loop );

if ( empty( $loop['read_more'] ) )
	$loop['read_more'] = __( 'Continue reading &rarr;', 'md' );

if ( empty( $loop['excerpt_length'] ) )
	$loop['excerpt_length'] = 55;

if ( empty( $loop['excerpt_more'] ) )
	$loop['excerpt_more'] = '[...]';

if ( get_post_thumbnail_id() ) {
	$loop['featured_image_id'] = get_post_thumbnail_id();

	if ( ! isset( $loop['featured_image'] ) )
		$loop['featured_image'] = md_featured_image_position();
}

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
