<?php

/**
 * Checks if comments are on page.
 *
 * @since 4.1
 */

function md_has_comments() {
	return ( comments_open() || get_comments_number() != 0 ) && ! post_password_required();
}

/**
 * Displays full comments template.
 *
 * @since 4.1
 */

function md_comments() {
	if ( md_has_comments() )
		comments_template();
}

/**
 * This function accounts for the output of an individual comment, and is referenced
 * in wp_list_comments(). see comments.php.
 *
 * @since 4.1
 */

function md_comment( $comment, $args, $depth ) {
	global $post;

	$classes = array();
	$GLOBALS['comment'] = $comment;
	$comment_id = get_comment_ID();
	$comment_link = get_comment_link( $comment->comment_ID );
	$is_author = $comment->user_id == $post->post_author;
	$avatar_size = intval( $args['avatar_size'] );

	if ( ! empty( $args['has_children'] ) )
		$classes[] = 'parent';

	if ( $is_author )
		$classes[] = 'is-author';

	$classes = array_values( $classes );

	include md_template( 'features', 'loop/comment', true );
}

/**
 * Insert comment form to Comments template.
 *
 * @since 5.5.7
 */

function md_comment_form( $args = array() ) {
	if ( empty( $args ) )
		$args = array(
			'title_reply' => __( 'Leave a Comment', 'md' ),
			'comment_notes_before' => false,
			'comment_notes_after' => false,
			'logged_in_as' => false,
			'cancel_reply_link' => __( 'Cancel', 'md'),
			'title_reply_before' => '<p id="reply-title" class="comment-reply-title">',
			'title_reply_after' => '</p>'
		);

	comment_form( $args );
}

/**
 * Move Name, Email, and Website fields back to top of Comment Form.
 *
 * @since 6.0
 */

function md_comment_form_reorder( $fields ) {
	$comment = $fields['comment'];
	$cookies = isset( $fields['cookies'] ) ? $fields['cookies'] : '';

	unset( $fields['comment'] );
	unset( $fields['cookies'] );

	$fields['comment'] = $comment;
	$fields['cookies'] = $cookies;

	return $fields;
}

add_filter( 'comment_form_fields', 'md_comment_form_reorder' );

/**
 * Strips pingbacks count from comment count.
 *
 * @since 4.0
 */

function md_real_comment_count( $count ) {
	if ( ! is_admin() ) {
		global $id;

		$status = get_comments( "status=approve&post_id=$id" );
		$comments_by_type = separate_comments( $status );

		return count( $comments_by_type['comment'] );
	}
	else
		return $count;
}
