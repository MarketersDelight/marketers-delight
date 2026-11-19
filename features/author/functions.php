<?php

/**
 * Checks if author box is active on page.
 *
 * @since 4.5
 */

function md_has_author_box() {
	if ( ! md_is_queried_post() )
		return false;

	$enable = md_value( array( 'key' => array( 'layout', 'content', 'add_author_box' ), 'post_type' => md_get_post_type() ) );
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

	$author_name = $author->display_name;
	$description = $author->description;
	$author_url = get_author_posts_url( $author_id );
	$has_avatar = get_option( 'show_avatars' );
	$label = apply_filters( 'md_author_box_label', __( 'Written by', 'md' ) );
	$subtitle = (string) apply_filters( 'md_author_box_subtitle', '', $author_id );
	$website = $author->user_url;
	$website_name = preg_replace( '/^www\./', '', (string) wp_parse_url( $website, PHP_URL_HOST ) );
	$sites = $profiles = array();

	foreach ( md_author_box_links( $author_id ) as $key => $link )
		if ( empty( $link['custom'] ) )
			$profiles[$key] = $link;
		else
			$sites[$key] = $link;

	include md_template( 'features', 'author/author-box', true );
}

/**
 * The author box's links, keyed: X here, and any a Drop-in adds through
 * md_author_box_links. Links marked custom sit beside the website.
 *
 * @since 6.2.4
 */

function md_author_box_links( $author_id ) {
	$links = array();
	$twitter = get_user_meta( $author_id, 'twitter', true );

	if ( $twitter )
		$links['twitter'] = array(
			'label' => 'X',
			'url' => strpos( $twitter, '://' ) !== false ? $twitter : 'https://x.com/' . ltrim( $twitter, '@' ),
			'icon' => 'x'
		);

	return (array) apply_filters( 'md_author_box_links', $links, $author_id );
}
