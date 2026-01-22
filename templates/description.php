<?php

$excerpt = $context == 'post' && is_singular() && has_excerpt() ? get_the_excerpt() : '';
$description = md_module( array( 'page_settings', 'description' ), $excerpt );

if ( $context !== 'post' )
	if ( is_post_type_archive() || is_home() )
		$description = md_post_type_field( 'archives_text' );
	elseif ( is_page() || is_front_page() )
		$description = get_the_excerpt();
	elseif ( ( is_category() || is_tax() ) && get_queried_object() )
		$description = category_description();
	elseif ( is_author() )
		$description = get_the_author_meta( 'description' );

if ( empty( $description ) )
	return;

do_action( "md_hook_before_{$context}_description" );

echo '<div class="description">' . wpautop( $description );

if ( isset( $args['show_cta'] ) )
	md_cta( $context );

echo '</div>';

do_action( "md_hook_after_{$context}_description" );