<?php

if ( ! isset( $loop['has_builder'] ) )
	echo "<$html class=\"" . implode( ' ', get_post_class( $classes ) ) . '">';

md_hook_content_top();

md_byline( 'before_post', array(
	'loop' => $loop,
	'classes' => 'post-meta'
) );

md_featured_media( 'post', array(
	'loop' => $loop,
	'show_image' => array( 'above_headline' )
) );

md_title( 'post', $args );

md_featured_media( 'post', array(
	'loop' => $loop,
	'show_image' => array( 'below_headline' )
) );

md_hook_before_the_content();

md_the_content( $loop );

md_hook_after_the_content();

if ( ! isset( $loop['post_footer']['remove'] ) )
	md_byline( 'after_post', array(
		'loop' => $loop,
		'classes' => 'post-footer item', 'html' => 'footer'
	) );

md_hook_content_item();

md_hook_content_bottom();

if ( ! isset( $loop['has_builder'] ) )
	echo "</$html>";