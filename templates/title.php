<?php

do_action( "md_hook_{$context}_header_top", "{$context}_header_top" );

if ( $title ) {

	if ( $permalink )
		$title_html .= '<a href="' . esc_url( $permalink ) . '">';

	$title_html .= md_text_field( $title );

	if ( $permalink )
		$title_html .= '</a>';

	if ( $is_inline )
		echo "<$h class=\"title\">$title_html</$h>";

	echo $context !== 'post' ? '<div class="block-inner">' : '';

	do_action( "md_hook_before_{$context}_title" );

	if ( $context == 'post' )
		md_byline( 'before_headline', $byline_args );

	if ( ! $is_inline )
		echo "<$h class=\"title\">$title_html</$h>";

	if ( $context !== 'post' && ( $description || $cta ) ) {
		echo '<div class="description">'.
			 ( $description ? wpautop( $description ) : '' ).
			 ( $is_inline && $cta ? md_get_inline_cta() : '' ).
			 '</div>';
	}

	if ( $context == 'post' )
		md_byline( 'after_headline', $byline_args );

	do_action( "md_hook_after_{$context}_title" );

	if ( ! $is_inline )
		md_inline_cta();

	echo $context !== 'post' ? '</div>' : ''; // close .block-inner
}

do_action( "md_hook_{$context}_header_bottom", "{$context}_header_bottom" );

if ( ! empty( $cover['photo']['id'] ) )
	echo md_get_caption( $cover['photo']['id'] );
