<?php

echo "<header class=\"$context-title entry-title " . esc_attr( $classes ) . "\"$style>";

md_overlay( $context );

do_action( "md_hook_{$context}_title_top" );

echo $has_header_cover ? '<div class="inner">' : '';

if ( $context == 'page' )
	md_featured_media( $context, array( 'show_image' => array( 'above_headline' ) ) );

// If media position is set to align directly with the title

if ( $media && in_array( $media['position'], $title_images, true ) ) {

	echo '<div class="wrap">';

	$args['wrap'] = true;

	if ( $context == 'post' )
		$args['byline'] = $args['description'] = $args['cta'] = true;

	md_the_title( $context, $args );

	md_featured_media( $context );

	echo '</div>';

	if ( $context == 'page' ) {
		md_description( $context );
		md_cta( $context );
		md_archive_meta();
	}

}

// Default markup

else {

	if ( $context == 'post' )
		$args['byline'] = true;

	if ( $has_wrap )
		echo '<div class="wrap">';

	md_the_title( $context, $args );

	if ( $context == 'page' )
		md_featured_media( $context, array( 'show_image' => array( 'below_headline' ) ) );

	md_description( $context );

	md_cta( $context );

	if ( $context == 'page' )
		md_archive_meta();

	if ( $has_wrap )
		echo '</div>';

	if ( $context == 'page' )
		md_featured_media( $context, array( 'show_image' => array( 'left', 'right', 'center' ) ) );

}

echo $has_header_cover ? '</div>' : '';

do_action( "md_hook_{$context}_title_bottom" );

echo '</header>';
