<?php

if ( ! $has_builder )
    echo "<div{$id} class=\"the-content item\">".
    ( $has_wrap ? '<div class="wrap">' : '' );

md_featured_media( 'post', array(
    'loop' => $loop,
    'show_image' => array( 'left', 'right', 'center' )
) );

md_hook_the_content_top();

if ( $show_full_content ) {

    if ( is_404() && ! md_has_custom_404() )
        include md_template( '404', true );
    elseif ( md_post_meta( array( 'layout', 'content', 'wpautop' ) ) )
        echo do_shortcode( get_the_content() );
    else
        the_content( esc_html( $loop['read_more'] ) );

    wp_link_pages();

}

elseif ( $has_excerpt )
    echo md_excerpt( $loop );

md_hook_the_content_bottom();

if ( ! $has_builder )
    echo ( $has_wrap ? '</div>' : '' ).
    '</div>';
