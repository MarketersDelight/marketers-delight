<?php

do_action( "md_hook_before_{$context}_headline", "before_{$context}_headline" );

echo '<div class="' . esc_attr( $classes ) . '"' . $style . '>'; // open .{$context}-headline

md_overlay( $cover );

do_action( "md_hook_{$context}_headline_top", "{$context}_headline_top" );

if ( $is_inline )
	echo md_title( $args );

echo '<div class="wrap">'; // open .wrap

do_action( "md_hook_{$context}_headline_wrap_top", "{$context}_headline_wrap_top" );

if ( ! $is_inline )
	echo md_title( $args );

echo md_description( $description );

if ( ! $is_inline && $cta )
	echo $cta;

if ( ! empty( $cover['photo']['id'] ) )
	echo md_get_caption( $cover['photo']['id'] );

do_action( "md_hook_{$context}_headline_wrap_bottom", "{$context}_headline_wrap_bottom" );

echo '</div>'; // close .wrap

do_action( "md_hook_{$context}_headline_bottom", "{$context}_headline_bottom" );

echo '</div>'; // close .{$context}-headline

do_action( "md_hook_after_{$context}_headline", "after_{$context}_headline" );
