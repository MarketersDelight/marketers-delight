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
	md_featured_media( $context, array(
		'media' => $data['media'],
		'show_image' => array( 'above_headline' )
	) );

// If media position is set to align directly with the title

if ( $media && in_array( $media_position, $title_images, true ) ) {

	$has_title_text = $context == 'post' || $args['aside'];

	if ( $has_title_text ) {
		$args['description'] = true;

		if ( $args['aside'] )
			$args['regions'] = true;
	}

	echo '<div class="wrap">';

	$args['wrap'] = true;

	md_the_title( $context, $args );

	md_featured_media( $context, array( 'media' => $data['media'] ) );

	echo '</div>';

	if ( ! $has_title_text ) {
		md_description( $context, array( 'description' => $data['description'] ) );

		md_title_position( 'after_title', $context, $args );
	}

}

// Default markup

else {

	if ( $context != 'post' )
		md_featured_media( $context, array(
			'media' => $data['media'],
			'show_image' => array( 'left', 'right' )
		) );

	if ( $has_wrap )
		echo '<div class="wrap">';

	if ( $args['aside'] )
		echo '<div class="title-main">';

	md_the_title( $context, $args );

	if ( $context != 'post' )
		md_featured_media( $context, array(
			'media' => $data['media'],
			'show_image' => array( 'below_headline' )
		) );

	md_description( $context, array( 'description' => $data['description'] ) );

	md_title_position( 'after_title', $context, $args );

	if ( $args['aside'] ) {
		echo '</div><div class="title-aside">';

		md_title_position( 'byline_aside', $context, $args );

		echo '</div>';
	}

	if ( $has_wrap )
		echo '</div>';

	if ( $context != 'post' )
		md_featured_media( $context, array(
			'media' => $data['media'],
			'show_image' => array( 'center' )
		) );

}

if ( $has_inner )
	do_action( "md_hook_{$context}_title_inner_bottom" );

echo $has_inner ? '</div>' : '';

do_action( "md_hook_{$context}_title_bottom" );

echo "</$tag>";
