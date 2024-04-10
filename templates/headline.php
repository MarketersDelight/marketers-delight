<?php

do_action( "md_hook_before_{$context}_header", "before_{$context}_header" );

echo '<div class="' . esc_attr( $classes ) . '"' . $style . '>'; // open .{$context}-headline

md_overlay( $cover );

do_action( "md_hook_{$context}_header_top", "{$context}_header_top" );

if ( $is_inline )
	echo md_title( $args );

echo '<div class="wrap">'; // open .wrap

do_action( "md_hook_{$context}_header_wrap_top", "{$context}_header_wrap_top" );

if ( ! $is_inline )
	echo md_title( $args );

if ( $description || ( $cta && $is_inline ) )
	echo '<div class="description">'.
	 	( $description ? wpautop( $description ) : '' ).
	 	( $cta && $is_inline ? $cta : '' ).
	 	'</div>';

if ( ! $is_inline && $cta )
	echo $cta;

if ( ! empty( $cover['photo']['id'] ) )
	echo md_get_caption( $cover['photo']['id'] );

do_action( "md_hook_{$context}_header_wrap_bottom", "{$context}_header_wrap_bottom" );

echo '</div>'; // close .wrap

do_action( "md_hook_{$context}_header_bottom", "{$context}_header_bottom" );

echo '</div>'; // close .{$context}-headline

do_action( "md_hook_after_{$context}_header", "after_{$context}_header" );
