<?php

$featured_image = md_get_featured_image( $context );

// If no image, stop function
if ( empty( $featured_image['id'] ) || $featured_image['position'] == 'remove' )
	return;

// Get Image Position
if ( isset( $loop['featured_image'] ) )
	$position = $loop['featured_image'];
else
	$position = md_featured_image_position( $context );

// Hide image if user specified
if ( $position == 'remove' )
	return;

// Only show on specified position, if set from options
if ( isset( $loop['show_image'] ) && ! in_array( $position, $loop['show_image'] ) )
	return;

// Hide image on specified position, if set from options
if ( isset( $loop['hide_image'] ) && in_array( $position, $loop['hide_image'] ) )
	return;

// Set Permalink
$permalink = '';

if ( $context == 'post' )
	$permalink = get_permalink();

// Move image out of Cover and into another position
if ( is_singular() && in_the_loop() ) {
	$cover = md_cover();
	$permalink = '';

	if ( in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) )
		$position = 'center';
}

// Set image size
$size = 'full';

if ( isset( $loop['featured_image_size'] ) )
	$size = $loop['featured_image_size'];

// Render Image
echo '<div class="featured-image">';

if ( $context == 'page' && ! empty( $featured_image['width'] ) )
	echo md_inline_css( array(
		'.page-headline .featured-image' => array(
			'max-width' => array(
				'query' => $featured_image['width'],
				'unit' => 'px'
			)
		)
	) );

echo
	( $permalink ? '<a href="' . esc_url( $permalink ) . '">' : '' ).
	wp_get_attachment_image( $featured_image['id'], $size ).
	( $permalink ? '</a>' : '' ).
	md_get_caption().
	'</div>'.
	md_hook_after_featured_image();