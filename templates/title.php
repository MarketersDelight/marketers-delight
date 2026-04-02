<?php

if ( isset( $args['wrap'] ) )
	echo '<div class="title-wrap">';

if ( isset( $args['byline'] ) )
	md_byline( 'before_title', $args );

do_action( "md_hook_before_{$context}_title" );

echo "<{$h} class=\"title\">" . $title . "</{$h}>";

do_action( "md_hook_after_{$context}_title" );

if ( isset( $args['byline'] ) )
	md_byline( 'after_title', $args );

if ( isset( $args['description'] ) )
	md_description( $context );

if ( isset( $args['cta'] ) )
	md_cta( $context );

if ( isset( $args['wrap'] ) )
	echo '</div>';