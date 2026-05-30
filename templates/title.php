<?php

echo "<header class=\"$context-title entry-title " . esc_attr( $classes ) . "\"$style>";

md_overlay( $context );

echo $has_header_cover ? '<div class="inner">' : '';

if ( $context == 'page' )
	md_featured_media( $context, array( 'show_image' => array( 'above_headline' ) ) );

do_action( "md_hook_{$context}_title_top" );

if ( $media && in_array( $media['position'], $title_images ) ) {

	echo '<div class="wrap">';

	$title_args['wrap'] = true;

	if ( $context == 'post' )
		$title_args['byline'] = $title_args['description'] = $title_args['cta'] = true;

	md_the_title( $context, $title_args );

	md_featured_media( $context );

	echo '</div>';

	if ( $context == 'page' ) {
		md_description( $context );
		md_cta( $context );
	}

}
else {

	if ( $context == 'post' )
		$title_args['byline'] = true;

	if ( $has_wrap )
		echo '<div class="wrap">';

	md_the_title( $context, $title_args );

	if ( $context == 'page' )
		md_featured_media( $context, array( 'show_image' => array( 'below_headline' ) ) );

	md_description( $context );

	md_cta( $context );

	if ( $has_wrap ) {
		echo '</div>';

		if ( $context == 'page' )
			md_featured_media( $context, array( 'show_image' => array( 'left', 'right', 'center' ) ) );
	}

}

do_action( "md_hook_{$context}_title_bottom" );

echo $has_header_cover ? '</div>' : '';

echo '</header>';