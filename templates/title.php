<?php

if ( $permalink )
	$title_html .= '<a href="' . esc_url( $permalink ) . '">';

$title_html .= md_text_field( $title );

if ( $permalink )
	$title_html .= '</a>';

if ( $is_inline )
	echo "<$h class=\"title\">$title_html</$h>";

echo '<div class="wrap">';

do_action( "md_hook_before_{$context}_title" );

if ( $context == 'post' )
	md_byline( 'before_headline', $byline_args );

if ( ! $is_inline )
	echo "<$h class=\"title\">$title_html</$h>";

if ( $description || ( $cta && $is_inline ) ) {
	echo '<div class="description">'.
		 ( $description ? wpautop( $description ) : '' ).
		 ( $cta && $is_inline ? md_inline_cta( $context ) : '' ).
		 '</div>';
}

if ( $context == 'post' )
	md_byline( 'after_headline', $byline_args );

do_action( "md_hook_after_{$context}_title" );

if ( ! $is_inline )
	echo md_inline_cta( $context );

echo '</div>'; // close .wrap