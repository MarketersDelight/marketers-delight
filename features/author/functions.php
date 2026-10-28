<?php

/**
 * Checks if author box is active on page.
 *
 * @since 4.5
 */

function md_has_author_box() {
	if ( ! md_is_queried_post() )
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

	if ( ! $author )
		return;

	$twitter = get_user_meta( $author_id, 'twitter', true );
	$website_url = $author->user_url;
	$author_name = $author->display_name;
	$description = $author->description;
	$author_url = get_author_posts_url( $author_id );
	$has_avatar = get_option( 'show_avatars' );

	include md_template( 'features', 'author/author-box', true );
}

/**
 * A user's uploaded Author photo, as an attachment ID. 0 when they have
 * none. Gravatar is never used here.
 *
 * @since 6.0.4
 */

function md_author_photo_id( $user_id ) {
	$photo_id = (int) md_user_meta( array( 'author', 'photo', 'id' ), $user_id, 0 );

	return $photo_id && wp_attachment_is_image( $photo_id ) ? $photo_id : 0;
}
