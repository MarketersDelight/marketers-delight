<?php

/**
 * Calculate classes to apply to post box.
 * Returns a string ($classes) to be used in post_class( $classes ) in template.
 *
 * @since 6.0
 */

function md_post_class( $loop, $c = 0 ) {
	$classes = array( 'entry' );
	$cover = md_cover();

	// Posts with Covers outside of content box add image to post content center
//	if ( is_singular() && in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) )
//		$loop['featured_image'] = 'center';

	if ( isset( $loop['featured'] ) )
		if ( isset( $loop['is_featured'] ) )
			$classes[] = 'featured';
		else
			$classes[] = 'standard';

	if ( ! empty( $loop['is_query'] ) || ( ! is_singular() && empty( $loop['is_query'] ) ) )
		$classes[] = $c % 2 == 0 ? 'even' : 'odd';

	if ( isset( $loop['featured_image_id'] ) && $loop['featured_image'] !== 'remove' ) {
		$image_class = $loop['featured_image'];

		if ( $loop['featured_image'] == 'above_headline' )
			$image_class = 'before';
		elseif ( $loop['featured_image'] == 'below_headline' )
			$image_class = 'after';

		$classes[] = 'image-' . str_replace( '_', '-', $image_class );
	}

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

	return is_singular() && in_the_loop() && ! empty( $cover['position'] ) && in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) ? true : false;
}

/**
 * Displays the headline of any post/page.
 *
 * @since 4.1
 */

function md_headline( $args = array() ) {
//	if ( ! md_has_headline() )
//		return;

	$loop = array();
	$context = isset( $args['context'] ) ? $args['context'] : 'post';

	if ( isset( $args['loop'] ) )
		$loop = $args['loop'];

	$cover = md_cover( $context );
	$has_cover = in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) ? true : false;
	$style = isset( $cover['style'] ) ? md_style( $cover['style'] ) : '';

	$classes = array( "$context-headline", 'headline', 'block' );

	if ( $context == 'page' || $has_cover )
		$classes[] = isset( $args['inline'] ) ? 'inline' : 'wide';

	$classes = array_merge( $classes, md_cover_classes( $cover ) );

	if ( isset( $args['classes'] ) )
		$classes[] = $args['classes'];

	$classes = join( ' ', $classes );

	include( md_template( 'headline', true ) );
}

/**
 * Show Page Title of current page.
 *
 * @since 6.0
 */

function md_get_title( $context = 'post' ) {
	$title = '';

	if ( $context == 'post' )
		$title = get_the_title();
	elseif ( $context == 'page' )
		if ( is_post_type_archive() ) {
			$post_type_title = post_type_archive_title( '', false );
			$title = md_post_type_field( 'archives_title', $post_type_title );
		}
		elseif ( is_home() || is_singular( 'post' ) )
			$title = md_post_type_field( 'archives_title' );
		elseif ( is_tax() && get_queried_object() ) {
			$term_title = single_term_title( '', false );
			$title = md_term_meta( array( get_post_type(), 'archives_title' ), null, $term_title );
		}
		elseif ( is_category() )
			$title = single_cat_title( '', false );
		elseif ( is_tag() )
			$title = single_tag_title( '', false );
		elseif ( is_author() )
			$title = get_the_author();
		elseif ( is_year() )
			$title = get_the_date( 'Y' );
		elseif ( is_month() )
			$title = get_the_date( 'F Y' );
		elseif ( is_day() )
			$title = get_the_date( 'F j, Y' );

	return $title;
}

/**
 * Render the Post/Page Title with title wrap classes,
 * optional permalink, byline, hooks, and $loop flexibility.
 *
 * @since 6.0
 */

function md_title( $args = array() ) {
	$context = isset( $args['context'] ) ? $args['context'] : 'post';
	$title = md_get_title( $context );

	if ( ! $title )
		return;

	$title_html = '';
	$permalink = null;
	$description = md_get_description( $context );
	$byline_args = array();
	$h = is_singular() || $context == 'page' ? 'h1' : 'h2';
	$cover = md_cover( $context );
	$cta = md_get_inline_cta();
	$is_inline = isset( $args['inline'] ) ? true : false;

	if ( isset( $args['loop'] ) )
		$byline_args['loop'] = $args['loop'];

	if ( ( ! is_singular() && $context == 'post' ) || ! empty( $args['loop']['is_query'] ) )
		$permalink = get_permalink();

	if ( $context == 'post' && md_module( array( 'loop', 'category_posts', 'enable' ) ) )
		$h = 'h3';

	if ( isset( $args['loop']['is_query'] ) )
		$h = 'h4';

	include( md_template( 'title', true ) );
}

/**
 * Show Description of current page.
 *
 * @since 6.0
 */

function md_get_description( $context = 'post' ) {
	$description = '';

	if ( $context == 'post' && has_excerpt() )
		$description = get_the_excerpt();
	elseif ( $context == 'page' )
		if ( is_post_type_archive() || is_home() )
			$description = md_post_type_field( 'archives_text' );
		elseif ( is_page() )
			$description = get_the_excerpt();
		elseif ( ( is_category() || is_tax() ) && get_queried_object() )
			$description = category_description();
		elseif ( is_author() )
			$description = get_the_author_meta( 'description' );

	return $description;
}

/**
 * Get Hero/inline CTA of any given page. A CTA can be a
 * link group, email form, custom HTML, or more.
 *
 * @since 6.0
 */

function md_inline_cta() {
	echo md_get_inline_cta();
}

function md_get_inline_cta() {
	if ( in_the_loop() )
		$hero = md_post_meta( 'hero' );
	else
		$hero = md_module( 'hero' );

	if ( empty( $hero['page_cta'] ) )
		return;

	$html = '';

	if ( $hero['page_cta'] == 'links' ) {
		$html .= '<div class="cta">';

		if ( ! empty( $hero['link_secondary'] ) ) {
			$hero['link_secondary']['link_classes'] = 'cta-link';
			$html .= md_get_link( $hero['link_secondary'] );
		}

		if ( ! empty( $hero['link_primary'] ) ) {
			$hero['link_primary']['link_classes'] = 'cta-link';
			$html .= md_get_link( $hero['link_primary'] );
		}

		$html .= '</div>';
	}
	elseif ( $hero['page_cta'] == 'custom' )
		$html .= md_module( 'custom_html' );

	return $html;
}

/**
 * Show full content or excerpt of any given page.
 *
 * @since 5.1
 */

function md_the_content( $loop ) {
	if ( isset( $loop['content'] ) && $loop['content'] == 'hide' )
		return;

	if ( md_post_meta( array( 'layout', 'content', 'wpautop' ) ) )
		echo get_the_content( $loop['read_more'] );
	else
		the_content( $loop['read_more'] );

	if ( ! isset( $loop['is_query'] ) )
		wp_link_pages();
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
	md_hook_before_the_content();

	$loop['content'] = isset( $loop['content'] ) ? $loop['content'] : '';

	if ( get_the_content() && $loop['content'] !== 'hide' ) {
		echo '<div class="the-content">';

		md_hook_the_content_top();

		if ( ( is_singular() && in_the_loop() ) || $loop['content'] == 'full' )
			md_the_content( $loop );
		else
			md_the_excerpt( $loop );

		md_hook_the_content_bottom();

		echo '</div>';
	}

	md_hook_after_the_content();

	if ( ! isset( $loop['post_footer']['remove'] ) )
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
	if ( in_the_loop() && is_singular() && ! is_404() && ( comments_open() || get_comments_number() != 0 ) && ! post_password_required() )
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
	$is_author = $comment->user_id == $post->post_author ? true : false;
	$avatar_size = esc_html( $args['avatar_size'] );

	if ( ! empty( $args['has_children'] ) )
		$classes[] = 'parent';

	if ( $is_author )
		$classes[] = 'is-author';

	$classes = array_values( $classes );

	include( md_template( 'comment', true ) );
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
