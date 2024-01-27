<?php

$classes = $style = array();
$cover = md_cover();
$featured_image_id = get_post_thumbnail_id();
$featured_image_position = md_featured_image_position();

if ( ! empty( $loop['featured'] ) && $c <= $loop['featured'] ) {
	$classes[] = 'featured';
	$loop = md_loop_featured( $loop );
}
else {
	$classes[] = 'standard';
	$loop = $looped;
}

if ( empty( $loop['read_more'] ) )
	$loop['read_more'] = __( 'Continue reading &rarr;', 'md' );

if ( empty( $loop['excerpt_length'] ) )
	$loop['excerpt_length'] = 55;

if ( empty( $loop['excerpt_more'] ) )
	$loop['excerpt_more'] = '[...]';

if ( $featured_image_id ) {
	if ( isset( $loop['featured_image'] ) )
		$featured_image_position = $loop['featured_image'];

	$classes[] = 'image-' . str_replace( '_', '-', $featured_image_position );
}

if ( $columns > 1 )
	if ( $columns <= 5 )
		$classes[] = "f{$columns}";
	else
		$style['flex_basis'] = ( 100 / $columns ) . '%';

$classes[] = $c % 2 == 0 ? 'even' : 'odd';
$classes = join( ' ', $classes );

if ( isset( $loop['loop'] ) && ! empty( $loops[$loop['loop']]['template'] ) )
	include( esc_attr( $loops[$loop['loop']]['template'] ) );
else
	include( md_template( 'loop/loop', true ) );

if ( empty( $loop['category_posts']['enable'] ) )
	md_hook_x_loop( $loop, $c );

$c++;
