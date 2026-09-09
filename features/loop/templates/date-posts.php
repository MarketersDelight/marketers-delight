<?php $is_query = isset( $args['query'] );

if ( $is_query )
	$date_query = $loop['query'] instanceof WP_Query ? $loop['query'] : new WP_Query( $loop['query'] );

if ( $is_query ? $date_query->have_posts() : have_posts() ) {
	$date_month = '';

	if ( ! $is_query )
		md_hook_loop_top();

	while ( $is_query ? $date_query->have_posts() : have_posts() ) {

		if ( $is_query )
			$date_query->the_post();
		else
			the_post();

		$post_date = md_get_loop_date( null, array( 'post_type' => $loop['post_type'] ) );

		if ( empty( $post_date ) )
			$post_date = array(
				'month' => 'undated',
				'label' => __( 'Undated', 'md' )
			);

		if ( $date_month !== $post_date['month'] ) {

			if ( $date_month )
				echo '</div></div>';

			$date_month = $post_date['month'];

			echo '<div class="loop-dates">'.
				 '<h2 class="loop-date-title">';

			if ( $date_month === 'undated' )
				echo esc_html( $post_date['label'] );
			else {
				$date_label = '<time datetime="' . esc_attr( $date_month ) . '">' . esc_html( $post_date['label'] ) . '</time>';

				echo ! empty( $post_date['url'] )
					? '<a href="' . esc_url( $post_date['url'] ) . '">' . $date_label . '</a>'
					: $date_label;
			}

			echo '</h2>'.
				 '<div class="' . esc_attr( $loop_classes ) . '"' . $loop_columns_style . '>';

		}

		include md_template( 'features', 'loop/the-post', true );
	}

	echo '</div></div>';

}

elseif ( $is_query ) {
	$not_found = $args['not_found'] ?? null;

	if ( is_callable( $not_found ) )
		call_user_func( $not_found );
	elseif ( $not_found )
		echo $not_found;
}

else md_404();

if ( $is_query )
	wp_reset_postdata();

md_pagination( $loop_base );
