<?php

do_action( "md_hook_before_{$context}_featured_media" );

echo '<div class="' . esc_attr( $classes ) . "\"$style>";

do_action( "md_hook_{$context}_featured_media_top" );

if ( has_action( "md_hook_{$context}_featured_media" ) )
    do_action( "md_hook_{$context}_featured_media", $media );

elseif ( $type == 'image' ) {

	echo ( $permalink ? '<a href="' . esc_url( $permalink ) . '">' : '' );

	if ( isset( $media['author'] ) )
		echo get_avatar( get_the_author_meta( 'ID' ), $size );
	else
		echo wp_get_attachment_image( $media['image']['id'], $size, false, $attr );

	echo ( $permalink ? '</a>' : '' ) . md_get_caption();

}

elseif ( $type == 'video' )
	echo wp_video_shortcode( array( 'src' => esc_url( $media[$type] ) ) );

elseif ( $type == 'custom_html' )
	echo do_shortcode( $media[$type] );

do_action( "md_hook_{$context}_featured_media_bottom" );

echo '</div>';

do_action( "md_hook_after_{$context}_featured_media" );
