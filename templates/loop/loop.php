<?php

echo "<$h id=\"post_"  . get_the_ID() . '" class="' . implode( ' ', get_post_class( $classes ) ) . '">';

if ( $loop['featured_image'] == 'above_headline' )
	md_featured_image( 'post', $loop );

if ( ! md_has_headline_cover() )
	md_headline( array( 'loop' => $loop ) );

if ( $loop['featured_image'] !== 'above_headline' )
	md_featured_image( 'post', $loop );

md_content( $loop );

md_hook_content_item();

echo "</$h>";