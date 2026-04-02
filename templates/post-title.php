<?php

echo '<header class="post-title' . esc_attr( $classes ) . "\"$style>";

md_overlay();

do_action( 'md_hook_post_title_top' );

echo ( is_singular() || is_404() ) && $has_header_cover ? '<div class="inner">' : '';

if ( $media && in_array( $media['position'], $title_images ) ) { // Render when image is aligned left/right inside title

	echo '<div class="wrap">';

	md_the_title( 'post', array(
		'loop' => ! empty( $args['loop'] ) ? $args['loop'] : array(),
		'wrap' => true,
		'byline' => true,
		'description' => true,
		'cta' => true
	) );

	md_featured_media();

	echo '</div>';

}

else { // Default view (Wide)

	md_the_title( 'post', array(
		'loop' => ! empty( $args['loop'] ) ? $args['loop'] : array(),
		'byline' => true
	) );

	md_description();

	md_cta();

}

echo ( is_singular() || is_404() ) && $has_header_cover ? '</div>' : '';

do_action( 'md_hook_post_title_bottom' );

echo '</header>';