<?php echo $args['before_widget'];

if ( ! empty( $val['title'] ) )
	echo $args['before_title'] . $val['title'] . $args['after_title'];

echo '<div class="accordion' . ( ! empty( $val['classes'] ) ? ' ' . esc_attr( $val['classes'] ) : '' ) . '">';

foreach ( $terms as $term ) :

    $posts_query = array(
        'post_type' => $post_type,
        'posts_per_page' => ! empty( $val['posts_per_category'] ) ? $val['posts_per_category'] : 5,
        'tax_query' => array( array(
            'taxonomy' => $tax,
            'field' => 'slug',
            'terms' => ! empty( $term->slug ) ? $term->slug : ''
        ) )
    );

    if ( ! empty( $filter_post_ids ) )
        $posts_query['post__in'] = $filter_post_ids;

    $posts = new WP_Query( $posts_query );
    $count  = ! empty( $filter_post_ids ) ? $posts->found_posts : $term->count;
    $string = sprintf( __( 'See more in %s &rarr;', 'md' ), $term->name );

    if ( ! empty( $val['see_more'] ) )
        $string = strtr( $val['see_more'], array( '{count}' => $count, '{category}' => $term->name ) );

    if ( $posts->have_posts() ) :

?>

<details name="<?php echo esc_attr( $args['widget_id'] ); ?>" class="accordion-item"<?php echo $c == 1 && ! empty( $val['settings']['open'] ) ? ' open' : ''; ?>>

    <summary class="accordion-title">
        <span class="accordion-label">
            <?php echo sanitize_text_field( $term->name ); ?>
            <?php echo ! empty( $val['settings']['show_count'] ) ? ' <span class="accordion-count small text-sec">(' . $count . ')</span>' : ''; ?></span>
    </summary>

    <ul class="accordion-content">
        <?php while ( $posts->have_posts() ) : $posts->the_post();
            $current = $page_id == get_the_ID() ? ' current-menu-item' : '';
        ?>
        <li class="menu-item<?php echo $current; ?>"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></li>
        <?php endwhile; ?>
        <li class="menu-item small"><a href="<?php echo get_term_link( $term->term_id ); ?>"><?php echo sanitize_text_field( $string ); ?></a></li>
    </ul>

</details>

<?php endif; $c++; wp_reset_postdata(); endforeach;

echo '</div>' . $args['after_widget'];