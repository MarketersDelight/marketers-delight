<?php

/**
 * Callback function for get_searchform().
 *
 * @since 6.0
 */

function md_search( $args = array() ) {
	include locate_template( 'searchform.php' );
}

/**
 * Checks if author box is active on page.
 *
 * @since 4.5
 */

function md_has_author_box() {
	$enable = md_post_type_field( array( 'layout', 'content', 'add_author_box' ) );

	if ( ! is_singular() )
		return false;

	$add = md_post_meta( array( 'layout', 'content', 'add_author_box' ) );
	$remove = md_post_meta( array( 'layout', 'content', 'author_box' ) );

	return ( $enable && ! $remove ) || $add;
}

/**
 * Theme author box. This box is used on top of author archive pages.
 *
 * It pulls information from the author's profile in the WordPress dashboard.
 * This way, all user's can show their bio after each post.
 *
 * @since 4.0
 */

function md_author_box() {
	if ( ! md_has_author_box() )
		return;

	$author_id = get_queried_object_id();

	if ( is_author() )
		$h = 'h1';
	else {
		$h = 'h3';
		$author_id = get_post_field( 'post_author', $author_id );
	}

	$author = get_userdata( $author_id );
	$twitter = get_user_meta( $author_id, 'twitter', true );
	$website_url = $author->user_url;
	$author_name = $author->display_name;
	$description = $author->description;
	$author_url = get_author_posts_url( $author_id );
	$has_avatar = get_option( 'show_avatars' );

	include md_template( 'author-box', true );
}

/**
 * Creates previous/next post links at the end of a
 * single entry.
 *
 * @since 4.0
 */

function md_post_nav() {
	if ( md_has_post_nav() )
		md_template( 'post-nav' );
}

/**
 * Check if Post Nav is active on page.
 *
 * @since 6.0
 */

function md_has_post_nav() {
	$disable = md_post_type_field( array( 'layout', 'content', 'post_nav' ) );
	$single_remove = md_post_meta( array( 'layout', 'content', 'post_nav' ) );
	$single_add = md_post_meta( array( 'layout', 'content', 'add_post_nav' ) );

	return
		! is_page() && is_singular() && ( get_previous_post() || get_next_post() ) &&
		! $single_remove &&
		( ! $disable || $single_add );
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

/**
 * Outputs the standard WordPress password form with the
 * .form-attached class added to it.
 *
 * @since 4.0
 * @revised 4.3.5
 */

if ( ! function_exists( 'md_password_form' ) ) :

function md_password_form() {
    global $post;
    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
    $o =
		'<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post" class="form-attached">'.
			'<p>' . __( 'To view this protected post, enter the password below:', 'md' ) . '</p>'.
			'<input name="post_password" id="' . $label . '" class="form-input" type="password" placeholder="' . __( 'Enter the password&hellip;', 'md' ) . '" size="20" maxlength="20" />'.
			'<input type="submit" name="submit" class="form-submit" value="' . esc_attr__( 'Get Access', 'md' ) . '" />'.
		'</form>';

	return $o;
}

endif;

add_filter( 'the_password_form', 'md_password_form' );
