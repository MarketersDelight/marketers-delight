<?php

/**
 * Calculate classes to apply to post box.
 * Returns a string ($classes) to be used in post_class( $classes ) in template.
 *
 * @since 6.0
 */

function md_post_class( $loop, $c ) {
	$classes = array( 'entry' );
	$loop_style = 'box-style';
	$cover = md_cover();
	$disable_box_style = md_setting( array( 'colors', 'design', 'box_style' ) );

	// Posts with Covers
	if ( is_singular() && in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) )
		$loop['featured_image'] = 'center';

	if ( isset( $loop['is_featured'] ) )
		$classes[] = 'featured';
	else
		$classes[] = 'standard';

	if ( $loop['columns'] > 1 && $loop['columns'] <= 5 )
		$classes[] = 'f' . $loop['columns'];

	if ( ! empty( $loop['is_query'] ) || ( ! is_singular() && empty( $loop['is_query'] ) ) )
		$classes[] = $c % 2 == 0 ? 'even' : 'odd';

	if ( $disable_box_style )
		$loop_style = '';

	if ( isset( $loop['style'] ) )
		if ( $loop['style'] !== 'simple' )
			$loop_style = str_replace( '_', '-', $loop['style'] );
		else
			$loop_style = '';

	if ( $loop_style )
		$classes[] = $loop_style;

	if ( isset( $loop['featured_image_id'] ) )
		$classes[] = 'image-' . str_replace( '_', '-', $loop['featured_image'] );

	if ( ! empty( $cover['position'] ) )
		$classes[] = 'has-cover';

	return join( ' ', $classes );
}

/**
 * Checks if headline is enabled.
 *
 * @since 4.1
 */

function md_has_headline() {
	if ( ! md_meta( array( 'layout', 'content', 'headline' ) ) )
		return true;
}

/**
 * Checks for headline with cover.
 *
 * @since 4.1
 */

function md_has_headline_cover() {
	$cover = md_cover();

	return is_singular() && ! empty( $cover['position'] ) && in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) ? true : false;
}

/**
 * Displays the headline of any post/page.
 *
 * @since 4.1
 */

function md_headline( $args = array() ) {
	if ( ! md_has_headline() )
		return;

	$context = isset( $args['context'] ) ? $args['context'] : 'post';
	$loop = array();

	if ( isset( $args['loop'] ) )
		$loop = $args['loop'];

	$cover = md_cover( $context );
	$has_cover = is_singular() && in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) );
	$style = isset( $cover['style'] ) ? md_style( $cover['style'] ) : '';

	$classes = isset( $args['classes'] ) ? $args['classes'] : array();
	$classes = array_merge( array( "$context-header" ), $classes, md_cover_classes( $cover ) );
	$classes = join( ' ' , $classes );

	if ( $context == 'post' && ! $has_cover ) { // Above Title Featured Image position
		$loop['show_image'] = array( 'above_headline' );
		md_featured_image( $loop );
	}

	include( md_template( 'headline', true ) );

	if ( $context == 'post' && ! $has_cover ) { // Below Title Featured Image position
		$loop['show_image'] = array( 'below_headline' );
		md_featured_image( $loop );
	}
}

/**
 * Render the Post/Page Title with title wrap classes,
 * optional permalink, byline, hooks, and $loop flexibility.
 *
 * @since 6.0
 */

function md_title( $args = array() ) {
	$context = isset( $args['context'] ) ? esc_attr( $args['context'] ) : 'post';
	$title = get_the_title();
	$permalink = null;
	$byline_args = array();
	$h = is_singular() || $context == 'page' ? 'h1' : 'h2';

	if ( isset( $args['loop'] ) )
		$loop = $byline_args['loop'] = $args['loop'];

	if ( isset( $args['title'] ) )
		$title = $args['title'];

	if ( ( ! is_singular() && $context == 'post' ) || ! empty( $args['loop']['is_query'] ) )
		$permalink = get_permalink();

	if ( $context == 'post' && md_module( array( 'loop', 'category_posts', 'enable' ) ) )
		$h = 'h3';

	if ( isset( $args['loop']['is_query'] ) )
		$h = 'h4';

	do_action( "md_hook_{$context}_header_top" );

	if ( $context == 'post' )
		md_byline( 'before_headline', $byline_args );

	if ( $title ) {
		$title_html = '';

		if ( $permalink )
			$title_html .= '<a href="' . esc_url( $permalink ) . '">';

		$title_html .= md_text_field( $title );

		if ( $permalink )
			$title_html .= '</a>';

		echo '<div class="title-wrap">';

		do_action( "md_hook_before_{$context}_title" );

		if ( isset( $loop['show_image'] ) )
			md_featured_image( $loop );

		echo "<$h class=\"title\">$title_html</$h>";

		do_action( "md_hook_after_{$context}_title" );

		echo '</div>';
	}

	if ( $context == 'post' )
		md_byline( 'after_headline', $byline_args );

	do_action( "md_hook_{$context}_header_bottom" );
}

/**
 * Show full content or excerpt of any given page.
 *
 * @since 5.1
 */

function md_the_content( $loop ) {
	if ( isset( $loop['content'] ) && $loop['content'] == 'hide' )
		return;

	md_hook_before_the_content();

	the_content( $loop['read_more'] );

	if ( ! isset( $loop['is_query'] ) )
		wp_link_pages();

	md_hook_after_the_content();
}

/**
 * Create our own Excerpt with native WP functions so
 * we can modify length and more without use of filters.
 *
 * @since 6.0
 */

function md_the_excerpt( $loop ) {
	if ( isset( $loop['content'] ) && $loop['content'] == 'hide' )
		return;

	$link_class = '';

	if ( ! empty( $loop['read_more_style'] ) )
		$link_class = ' button';

	$excerpt = wp_trim_words( get_the_excerpt(), $loop['excerpt_length'], $loop['excerpt_more'] );

	echo
		wpautop( $excerpt ).
		'<p class="read-more"><a href="' . get_permalink() . '" class="more-link' . $link_class . '">' . esc_html( $loop['read_more'] ) . '</a></p>';
}

/**
 * Displays post/page content text.
 *
 * @since 4.1
 * @renamed 6.0 md_content_text()
 */

function md_content( $loop ) {
	if ( get_the_content() ) {
		echo '<div class="the-content">';

		md_featured_image( $loop );

		if ( ( is_singular() && in_the_loop() ) || isset( $loop['content'] ) && $loop['content'] == 'full' )
			md_the_content( $loop );
		else
			md_the_excerpt( $loop );

		echo '</div>';
	}

	md_byline( 'after_post', array(
		'classes' => 'post-footer',
		'loop' => $loop
	) );
}

/**
 * Checks if author box.
 *
 * @since 4.5
 */

function md_has_author_box() {
	$enable = md_post_type_field( array( 'layout', 'content', 'add_author_box' ) );

	if ( is_singular() ) {
		$add = md_post_meta( array( 'layout', 'content', 'add_author_box' ) );
		$remove = md_post_meta( array( 'layout', 'content', 'author_box' ) );

		if ( ( $enable && ! $remove ) || $add )
			return true;
	}
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

	$h = is_author() ? 'h1' : 'h3';
	$twitter = get_the_author_meta( 'twitter' );
	$url = get_the_author_meta( 'url' );
	$author = get_author_posts_url( get_the_author_meta( 'ID' ) );
	$desc = get_the_author_meta( 'description' );
	$has_avatar = get_option( 'show_avatars' );

	include( md_template( 'author-box', true ) );
}

/**
 * Checks if comments are on page.
 *
 * @since 4.1
 */

function md_has_comments() {
	if ( ( comments_open() || get_comments_number() != 0 ) && ! post_password_required() )
		return true;
}

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

/**
 * Displays full comments template.
 *
 * @since 4.1
 */

function md_comments() {
	if ( ! is_404() && md_has_comments() && is_singular() )
		comments_template( '/templates/comments/comments.php' );
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
	$is_author = $comment->user_id == $post->post_author ? true : false;
	$avatar_size = esc_html( $args['avatar_size'] );

	if ( ! empty( $args['has_children'] ) )
		$classes[] = 'parent';

	if ( $is_author )
		$classes[] = 'is-author';

	$classes = array_values( $classes );

	include( md_template( 'comments/comment', true ) );
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
			'cancel_reply_link' => __( 'Cancel', 'md')
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
