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
 * The author box after a single post's content, built from the author's
 * profile: photo, name, bio and links. Runs on md_hook_content_item.
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
