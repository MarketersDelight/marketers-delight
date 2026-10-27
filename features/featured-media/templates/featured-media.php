<?php

do_action( "md_hook_before_{$context}_featured_media" );

echo '<div class="' . esc_attr( $classes ) . "\"$style>";

do_action( "md_hook_{$context}_featured_media_top", $media );

if ( has_action( "md_hook_{$context}_featured_media" ) )
    do_action( "md_hook_{$context}_featured_media", $media );

elseif ( $type == 'image' ) {

	echo ( $permalink ? '<a href="' . esc_url( $permalink ) . '">' : '' );

	echo wp_get_attachment_image( $media['image']['id'], $size, false, $attr );

	echo ( $permalink ? '</a>' : '' ) . ( empty( $media['author'] ) ? md_get_caption() : '' );

}

elseif ( $type == 'video' )
	echo wp_video_shortcode( array( 'src' => esc_url( $media[$type] ) ) );

elseif ( $type == 'custom_html' )
	echo do_shortcode( $media[$type] );

do_action( "md_hook_{$context}_featured_media_bottom", $media );

echo '</div>';

do_action( "md_hook_after_{$context}_featured_media" );
