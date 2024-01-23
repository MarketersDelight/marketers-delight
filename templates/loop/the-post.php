<?php

$classes = $style = array();
$cover = md_cover();
$featured_image_id = get_post_thumbnail_id();
$featured_image_position = md_featured_image_position();

if ( ! is_singular() )
	if ( ! empty( $loop['featured'] ) && $c <= $loop['featured'] ) {
		$classes[] = 'featured';
		$loop['is_featured'] = true;

		if ( ! empty( $loop['featured_featured_image'] ) )
			$loop['is_featured'] = $loop['featured_featured_image'];
	}
	else {
		$classes[] = 'standard';

		if ( isset( $loop['featured'] ) )
			unset( $loop['is_featured'] );
	}

if ( $featured_image_id && isset( $loop['featured_image'] ) ) {
	$featured_image_position = $loop['featured_image'];
	$classes[] = 'image-' . str_replace( '_', '-', $featured_image_position );
}

if ( $columns > 1 )
	if ( $columns <= 5 )
		$classes[] = "f{$columns}";
	else
		$style['flex_basis'] = ( 100 / $columns ) . '%';

$title_args['loop'] = $loop;

$classes[] = $c % 2 == 0 ? 'even' : 'odd';
$classes = join( ' ', $classes );

if ( isset( $loop['loop'] ) && ! empty( $loops[$loop['loop']]['template'] ) )
	include( esc_attr( $loops[$loop['loop']]['template'] ) );
else
	include( md_template( 'loop/loop', true ) );

if ( empty( $loop['category_posts']['enable'] ) )
	md_hook_x_loop( $loop, $c );

$c++;
