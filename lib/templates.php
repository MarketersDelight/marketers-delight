<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * A collection of action hooks and filters that construct various
 * parts of the website layout.
 *
 * @since 5.6
 */

function md_templates() {
	if ( md_has_breadcrumbs() )
		add_action( 'md_hook_content_box_top', 'md_breadcrumbs' );

	$cover = md_cover();

	if ( empty( $cover['position'] ) )
		add_action( 'md_hook_before_content_box', 'md_page_title' );

	add_action( 'md_hook_content', 'md_loop' );
	add_action( 'md_hook_content', 'md_pagination', 30 );

	if ( md_has_headline() && ! md_has_headline_cover() )
		add_action( 'md_hook_content_item', 'md_headline', 20 );

	add_action( 'md_hook_content_item', 'md_content_text', 40 );
	add_action( 'md_hook_content_item', 'md_comments', 60 );
	add_action( 'md_hook_after_comments_list', 'md_comment_form' );

	if ( ! is_404() && md_has_byline() ) {
		$byline_position = md_module( array( 'loop', 'byline_position' ) );
		$hook_byline = 'md_hook_before_headline';
		if ( $byline_position == 'after_headline' )
			$hook_byline = 'md_hook_after_headline';
		add_action( $hook_byline, 'md_byline' );
	}

	add_action( 'md_hook_before_headline', 'md_cover_caption', 3 );

	if ( md_has_author_box() )
		add_action( 'md_hook_content_item', 'md_author', 50 );

	if ( is_singular( 'post' ) )
		add_action( 'md_hook_content', 'md_post_nav', 70 );

	add_action( 'md_hook_footer', 'md_footer_columns_template' );
	add_action( 'md_hook_footer_bottom', 'md_footer_copy' );
}

add_action( 'template_redirect', 'md_templates' );

/**
 * Displays the logo, used in header by default.
 *
 * @since 4.1
 */

function md_logo() {
	include( md_template( 'logo', true ) );
}

function md_the_logo() {
	$has_logo_html = md_setting( array( 'colors', 'logo_html_display', 'enable' ) );
	$logo_html = md_setting( array( 'colors', 'logo_html' ) );

	if ( $has_logo_html && ! empty( $logo_html ) )
		echo $logo_html;
	else {
		$logo_id = md_setting( array( 'colors', 'logo', 'id' ) );
		$secondary_logo = md_setting( array( 'colors', 'logo_alt', 'url' ) );
		$text_global = md_setting( array( 'content', 'featured_image', 'styles', 'text_color' ) );
		$text_single = md_post_meta( array( 'featured_image', 'text_color', 'alternate' ) );
		$cover = md_cover();
		if ( ( ( ( is_singular() || is_category() || is_tax() ) && $cover['position'] == 'header_cover_full' ) || apply_filters( 'md_filter_logo_alt', false ) ) && ! empty( $secondary_logo ) ) {
			if ( ( ! empty( $logo_id ) && $secondary_logo ) && ( ( empty( $text_global ) && empty( $text_single ) ) || ( ! empty( $text_global ) && ! empty( $text_single ) ) ) )
				md_secondary_logo();
			else
				echo wp_get_attachment_image( $logo_id, 'full', false, array( 'class' => 'custom-logo-link' ) );
		}
		else
			echo wp_get_attachment_image( $logo_id, 'full', false, array( 'class' => 'custom-logo-link' ) );
	}
}

function md_secondary_logo() {
	$secondary_logo_id = md_setting( array( 'header', 'logo_alt', 'id' ) );
	echo '<span class="custom-logo-link">';
	if ( ! empty( $secondary_logo_id ) )
		echo wp_get_attachment_image( $secondary_logo_id, 'full' );
	echo '</span>';
}

/**
 * Render Site Title as default WP text or custom title.
 *
 * @since 5.5.8
 */

function md_site_title() {
	$title = get_bloginfo( 'name' );
	return md_setting( array( 'header', 'site_title' ), $title );
}

/**
 * Render Site Tagline as default WP text or custom tagline.
 *
 * @since 5.5.8
 */

function md_site_tagline() {
	$tagline = get_bloginfo( 'description' );
	return md_setting( array( 'header', 'site_tagline' ), $tagline );
}

/**
 * Displays the header menu.
 *
 * @since 4.1
 */

function md_header_menu() {
	$menu_location = is_user_logged_in() && has_nav_menu( 'header_loggedin' ) ? 'header_loggedin' : 'header';
	include( md_template( 'header-menu', true ) );
}

/**
 * Displays titles for various types of archives.
 *
 * @since 4.0
 */

function md_content_box() {
	if ( md_has_content_box() )
		md_template( 'content-box' );
}

/**
 * Render breadcrumbs template.
 *
 * @since 5.2.2
 */

function md_breadcrumbs() {
	$post_type_title = $category_url = $category_title = '';
	$post_id = get_the_ID();
	$post_type = get_post_type();
	$post_type_obj = get_post_type_object( $post_type );
	if ( ! empty( $post_type_obj ) )
		$post_type_title = md_text_field( $post_type_obj->labels->name );
	$front_page = get_option( 'show_on_front' );
	if ( $post_type == 'post' ) {
		$blog_id = get_option( 'page_for_posts' );
		if ( ! empty( $blog_id ) )
			$post_type_title = get_the_title( $blog_id );
		else
			$post_type_title = __( 'Blog', 'md' );
	}
	if ( is_category() )
		$terms = get_the_category();
	elseif ( is_tag() )
		$terms = get_tag( get_queried_object_id() );
	else {
		$taxonomies = get_taxonomies( array( 'public' => true ) );
		$terms = wp_get_post_terms( $post_id, $taxonomies );
	}
	if ( ! empty( $terms ) )
		if ( is_tag() ) {
			$category_url = get_term_link( $terms->term_id );
			$category_title = $terms->name;
		}
		else {
			$category_url = get_term_link( $terms[0]->term_id );
			$category_title = $terms[0]->name;
		}
	include( md_template( 'breadcrumbs', true ) );
}

/**
 * Render Page/Archives Title text and description.
 *
 * @since 5.6
 */

function md_page_title() {
	$title = $description = '';



	if ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );
		$description = md_post_type_field( 'archives_text' );
	}
	elseif ( is_home() || is_singular( 'post' ) ) {
		$title = md_post_type_field( 'archives_title' );
		$description = md_post_type_field( 'archives_text' );
	}
	elseif ( is_tax() && get_queried_object() ) {
		$title = single_term_title( '', false );
		$description = md_term_meta( 'archives_text' );
	}
	elseif ( is_category() ) {
		$title = single_cat_title( '', false );
		$description = category_description();
	}
	elseif ( is_tag() )
		$title = single_tag_title( '', false );
	elseif ( is_author() )
		$title = get_the_author();
	elseif ( is_year() )
		$title  = get_the_date( _x( 'Y', 'yearly archives date format' ) );
	elseif ( is_month() )
		$title  = get_the_date( _x( 'F Y', 'monthly archives date format' ) );
	elseif ( is_day() )
		$title  = get_the_date( _x( 'F j, Y', 'daily archives date format' ) );

	if ( has_filter( 'md_page_title' ) )
		$title = do_action( 'md_page_title' );

	if ( has_filter( 'md_page_description' ) )
		$description = do_action( 'md_page_description' );

	if ( $title || $description )
		include( md_template( 'page-title', true ) );
}

/**
 * Displays the headline of any post/page.
 *
 * @since 4.1
 */

function md_headline() {
	$h = md_html( 'h' );
	include( md_template( 'headline', true ) );
}

/**
 * Output post byline template.
 *
 * @since 4.0
 */

function md_byline() {
	$classes = md_byline_classes();
	$byline_items = md_byline_items();
	include( md_template( 'byline/byline', true ) );
}

/**
 * Display template of individual byline items with
 * optional arguments.
 *
 * @since 5.1
 */

function md_byline_item( $item, $args = array() ) {
	$template = locate_template( "templates/byline/$item.php" );
	$byline = md_get_byline();
	if ( $template )
		include( md_template( "byline/$item", true ) );
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
 * @since 5.6
*/

function md_comment_form_reorder( $fields ) {
	$comment = $fields['comment'];
	$cookies = $fields['cookies'];

	unset( $fields['comment'] );
	unset( $fields['cookies'] );

	$fields['comment'] = $comment;
	$fields['cookies'] = $cookies;

	return $fields;
}

add_filter( 'comment_form_fields', 'md_comment_form_reorder' );

/**
 * Creates previous/next post links at the end of a
 * single entry.
 *
 * @since 4.0
 */

function md_post_nav() {
	if ( get_previous_post() || get_next_post() )
		md_template( 'post-nav' );
}

/**
 * Create pagination for use on home and archives pages.
 *
 * @since 4.0
 */

function md_pagination() {
	if ( is_singular() ) return;
	$type = md_module( array( 'loop', 'pagination' ) );
	$prelabel = md_module( array( 'loop', 'previous_label' ), __( 'Previous', 'md' ) );
	$nxtlabel = md_module( array( 'loop', 'next_label' ), __( 'Next', 'md' ) );
	include( md_template( 'pagination', true ) );
}

/**
 * Outputs main sidebar or custom sidebar.
 *
 * @since 4.1
 */

function md_sidebar() {
	$name = md_get_sidebar_id();
	dynamic_sidebar( $name );
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
	$html = is_author() ? 'h1' : 'p';
	$twitter = get_the_author_meta( 'twitter' );
	$desc = get_the_author_meta( 'description' );
	$url = get_the_author_meta( 'url' );
	$author = get_author_posts_url( get_the_author_meta( 'ID' ) );
	$archive = md_setting( array( 'content', 'author_box', 'all_posts' ) );
	$has_avatar = get_option( 'show_avatars' );
	include( md_template( 'author-box', true ) );
}

/**
 * Returns an array of active widget areas with the md-footer-col prefix.
 *
 * @since 4.0
 */

function md_footer_columns() {
	$columns = array();
	foreach ( array_filter( wp_get_sidebars_widgets() ) as $area => $widgets )
		if ( substr( $area, 0, 13 ) == 'md-footer-col' )
			$columns[] = $area;
	return count( $columns );
}

/**
 * Add widgetized footer columns to footer.
 *
 * @since 4.5
 */

function md_footer_columns_template() {
	md_template( 'footer-columns' );
}

/**
 * Add widgetized footer copyright text to footer.
 *
 * @since 4.5
 */

function md_footer_copy() {
	md_template( 'footer-copy' );
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
