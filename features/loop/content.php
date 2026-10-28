<?php

/**
 * Creates previous/next post links at the end of a
 * single entry.
 *
 * @since 4.0
 */

function md_post_nav() {
	if ( ! md_has_post_nav() )
		return;

	$previous = get_previous_post();
	$previous_media = apply_filters( 'md_post_nav_media', '', $previous );
	$next = get_next_post();
	$next_media = apply_filters( 'md_post_nav_media', '', $next );
	$post_type = get_post_type_object( get_post_type() );
	$post_type_label = $post_type && ! empty( $post_type->labels->singular_name ) ? $post_type->labels->singular_name : __( 'Post', 'md' );

	include md_template( 'features', 'loop/post-nav', true );
}

/**
 * Check if Post Nav is active on page.
 *
 * @since 6.0
 */

function md_has_post_nav() {
	if ( ! md_is_queried_post() || is_page() )
		return false;

	$disable = md_post_type_field( array( 'layout', 'content', 'post_nav' ) );
	$add = md_post_meta( array( 'layout', 'content', 'add_post_nav' ) );
	$remove = md_post_meta( array( 'layout', 'content', 'post_nav' ) );

	return ! $remove && ( ! $disable || $add ) && ( get_previous_post() || get_next_post() );
}

/**
 * Check if custom 404 page is enabled and published.
 *
 * @since 6.0
 */

function md_has_custom_404() {
	$page_404 = md_setting( array( 'settings', '404_page' ) );

	if ( is_404() && $page_404 && get_post_status( $page_404 ) )
		return $page_404;
}

/**
 * Render the 404 template based on user settings.
 *
 * @since 4.0
 */

function md_404() {
	$page_404 = md_has_custom_404();

	if ( $page_404 ) {
		$query_404 = new WP_Query( array(
			'post_type' => 'page',
			'p' => $page_404,
			'post_status' => array( 'publish' ),
			'fields' => 'ids'
		) );

		if ( $query_404->have_posts() )
			while ( $query_404->have_posts() ) {
				$query_404->the_post();

				md_loop( array( 'in_loop' => true ) );
			}

		wp_reset_postdata();
	}
	else md_loop( array( 'in_loop' => true ) );
}
