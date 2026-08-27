<?php

$query = $loop['query'] instanceof WP_Query ? $loop['query'] : new WP_Query( $loop['query'] );

if ( $query->have_posts() ) {
    echo '<div class="' . esc_attr( $loop_classes ) . '"' . $loop_columns_style . '>';

    while ( $query->have_posts() ) {
        $query->the_post();

        include md_template( 'loop/the-post', true );
    }

    echo '</div>';
}

else {
    $not_found = $args['not_found'] ?? null;

    if ( is_callable( $not_found ) )
        call_user_func( $not_found );
    elseif ( $not_found )
        echo $not_found;
}

wp_reset_postdata();

md_pagination( $loop );
