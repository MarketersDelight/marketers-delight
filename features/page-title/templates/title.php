<?php

$data = $args['data'];
$tag = ! empty( $args['tag'] ) ? $args['tag'] : 'header';

echo "<$tag$id class=\"$context-title entry-title " . esc_attr( $classes ) . "\"$style>";

if ( ! empty( $args['top'] ) )
	echo $args['top'];

md_overlay( $context, $data['cover'] );

do_action( "md_hook_{$context}_title_top" );

if ( $has_inner ) {
	echo '<div class="inner">';
	do_action( "md_hook_{$context}_title_inner_top" );
}

if ( $context != 'post' )
	md_featured_media( $context, array( 'media' => $data['media'], 'show_image' => array( 'above_headline' ) ) );

// If media position is set to align directly with the title

if ( $media && in_array( $media_position, $title_images, true ) ) {

	echo '<div class="wrap">';

	$args['wrap'] = true;

	if ( $context == 'post' )
		$args['byline'] = $args['description'] = $args['cta'] = true;

	md_the_title( $context, $args );

	md_featured_media( $context, array( 'media' => $data['media'] ) );

	echo '</div>';

	if ( $context != 'post' ) {
		md_description( $context, array( 'description' => $data['description'] ) );
		md_cta( $context, $data['cta'] );

		if ( ! empty( $data['foot'] ) )
			echo '<p class="foot">' . $data['foot'] . '</p>';

		if ( ! empty( $data['archive_meta'] ) )
			md_archive_meta();
	}

}

// Default markup

else {

	if ( $context == 'post' )
		$args['byline'] = true;

	if ( $context != 'post' )
		md_featured_media( $context, array( 'media' => $data['media'], 'show_image' => array( 'left', 'right' ) ) );

	if ( $has_wrap )
		echo '<div class="wrap">';

	md_the_title( $context, $args );

	if ( $context != 'post' )
		md_featured_media( $context, array( 'media' => $data['media'], 'show_image' => array( 'below_headline' ) ) );

	md_description( $context, array( 'description' => $data['description'] ) );

	if ( isset( $args['byline'] ) )
		md_byline( 'after_title', $args );

	md_cta( $context, $data['cta'] );

	if ( ! empty( $data['foot'] ) )
		echo '<p class="foot">' . $data['foot'] . '</p>';

	if ( ! empty( $data['archive_meta'] ) )
		md_archive_meta();

	if ( $has_wrap )
		echo '</div>';

	if ( $context != 'post' )
		md_featured_media( $context, array( 'media' => $data['media'], 'show_image' => array( 'center' ) ) );

}

if ( $has_inner )
	do_action( "md_hook_{$context}_title_inner_bottom" );

echo $has_inner ? '</div>' : '';

do_action( "md_hook_{$context}_title_bottom" );

echo "</$tag>";
