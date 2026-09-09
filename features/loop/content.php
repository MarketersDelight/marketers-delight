<?php

/**
 * Checks if author box is active on page.
 *
 * @since 4.5
 */

function md_has_author_box() {
	if ( ! is_singular() || get_the_ID() !== get_queried_object_id() )
		return false;

	$enable = md_post_type_field( array( 'layout', 'content', 'add_author_box' ) );
	$add = md_post_meta( array( 'layout', 'content', 'add_author_box' ) );
	$remove = md_post_meta( array( 'layout', 'content', 'author_box' ) );

	return ( $enable && ! $remove ) || $add;
}

/**
 * Theme author box, shown after the content on a single entry.
 *
 * It pulls information from the author's profile in the WordPress dashboard.
 * This way, all user's can show their bio after each post.
 *
 * @since 4.0
 */

function md_author_box() {
	if ( ! md_has_author_box() )
		return;

	$author_id = get_post_field( 'post_author', get_queried_object_id() );
	$author = get_userdata( $author_id );
	$twitter = get_user_meta( $author_id, 'twitter', true );
	$website_url = $author->user_url;
	$author_name = $author->display_name;
	$description = $author->description;
	$author_url = get_author_posts_url( $author_id );
	$has_avatar = get_option( 'show_avatars' );

	include md_template( 'features', 'loop/author-box', true );
}

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
	$post_type_label = $post_type && ! empty( $post_type->labels->singular_name )
		? $post_type->labels->singular_name
		: __( 'Post', 'md' );

	include md_template( 'features', 'loop/post-nav', true );
}

/**
 * Check if Post Nav is active on page.
 *
 * @since 6.0
 */

function md_has_post_nav() {
	if ( ! is_singular() || is_page() || get_the_ID() !== get_queried_object_id() )
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
