<?php

$h = 'h1';
$title = apply_filters( "md_{$context}_title", md_get_title( $context ) );

if ( $context == 'post' && ! is_singular() && ! is_404() ) {
	$h = 'h2';
	$title = '<a href="' . get_permalink() . '">' . $title . '</a>';
}

if ( isset( $args['wrap'] ) )
	echo '<div class="title-wrap">';

if ( isset( $args['byline'] ) )
	md_byline( 'before_headline' );

do_action( "md_hook_before_{$context}_title" );

echo "<{$h} class=\"title\">" . $title . "</{$h}>";

do_action( "md_hook_after_{$context}_title" );

if ( isset( $args['byline'] ) )
	md_byline( 'after_headline' );

if ( isset( $args['description'] ) )
	md_description( $context );

if ( isset( $args['cta'] ) )
	md_cta( $context );

if ( isset( $args['wrap'] ) )
	echo '</div>';