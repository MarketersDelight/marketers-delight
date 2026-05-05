<?php

if ( $loop['content'] === 'hide' || ( ! get_the_content() && ! get_the_excerpt() && ! is_404() ) )
    return;

if ( empty( $loop['has_builder'] ) ) echo
    "<div class=\"the-content item\">".
    ( empty( $loop['has_sidebar'] ) ? '<div class="wrap">' : '' );

md_hook_the_content_top();

md_featured_media( 'post', array_merge( $args, array( 'show_image' => array( 'left', 'right', 'center' ) ) ) );

if ( $loop['content'] == 'full' || ( ( is_singular() || is_404() ) && ( in_the_loop() || isset( $loop['in_loop'] ) ) ) ) {
    if ( is_404() && ! md_has_custom_404() )
        include_once md_template( 'loop/404', true );
    else
        if ( md_post_meta( array( 'layout', 'content', 'wpautop' ) ) )
            echo do_shortcode( get_the_content() );
        else
            the_content( esc_html( $loop['read_more'] ) );

    wp_link_pages();
}
elseif ( empty( $loop['content'] ) && get_the_excerpt() )
    echo md_excerpt( $loop );

md_hook_the_content_bottom();

if ( empty( $loop['has_builder'] ) ) echo
    ( empty( $loop['has_sidebar'] ) ? '</div>' : '' ).
    '</div>';