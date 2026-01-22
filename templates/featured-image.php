<?php

do_action( "md_hook_before_{$context}_featured_image" );

echo '<div class="featured-image"' . $style . '>';

do_action( "md_hook_{$context}_featured_image_top" );

echo ( $permalink ? '<a href="' . esc_url( $permalink ) . '">' : '' ).
	wp_get_attachment_image( $image['id'], $size ).
	( $permalink ? '</a>' : '' ).
	md_get_caption();

do_action( "md_hook_{$context}_featured_image_bottom" );

echo '</div>';

do_action( "md_hook_after_{$context}_featured_image" );