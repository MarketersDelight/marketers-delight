<?php

$query = new WP_Query( $loop['query'] );

if ( $query->have_posts() ) {
    echo '<div class="' . esc_attr( $loop_classes ) . '">';

    while ( $query->have_posts() ) {
        $query->the_post();

        include md_template( 'loop/the-post', true );
    }

    echo '</div>';
}
else md_404();

wp_reset_postdata();

md_pagination( $loop );