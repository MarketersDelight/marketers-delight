<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Filter body classes.
 *
 * @since 4.1
 */

function md_body_classes( $classes ) {
	// Add custom body classes
	$custom_classes = md_meta( array( 'scripts', 'body_class' ) );
	if ( ! empty( $custom_classes ) ) {
		$custom_classes = explode( ' ' , $custom_classes );
		foreach ( $custom_classes as $custom_class )
			$classes[] = esc_attr( $custom_class );
	}
	// Remove excess WP classes
	$classes = array_diff( $classes, array(
		'single-format-standard',
		'single-format-' . get_post_format()
	) );

	return $classes;
}

add_filter( 'body_class', 'md_body_classes' );

/**
 * Render dynamic HTML for important structural tags.
 *
 * @since 5.6
 */

function md_html( $area ) {
	$html = 'div';

	if ( $area == 'h' )
		$html = is_singular() ? 'h1' : 'h2';
	elseif ( $area == 'article' )
		$html = is_singular() ? 'div' : 'article';

	return $html;
}

/**
 * Inner HTML element and closing div.
 *
 * @since 5.6
 */

function md_inner_html() {
	echo '<div class="inner">';
}

function md_html_close() {
	echo '</div>';
}

/**
 * Checks if logo is enabled.
 *
 * @since 4.1
 */

function md_has_logo() {
	if ( ( md_has_custom_logo() || md_has_site_title() || md_has_tagline() ) && ! md_module( array( 'layout', 'header', 'logo' ) ) )
		return true;
}

/**
 * Checks for WordPress' custom logo.
 *
 * @since 4.5.4
 */

function md_has_custom_logo() {
	$logo_image = md_setting( array( 'colors', 'logo', 'url' ) );
	$has_logo_html = md_setting( array( 'colors', 'logo_html_display', 'enable' ) );
	$logo_html = md_setting( array( 'colors', 'logo_html' ) );

	if ( $logo_image || ( $has_logo_html && ! empty( $logo_html ) ) )
		return true;
}

/**
 * Check for MD Site Title.
 *
 * @since 4.5.4
 */

function md_has_site_title() {
	if ( ! md_setting( array( 'header', 'display', 'site_title' ) ) )
		return true;
}

/**
 * Checks if page has tagline.
 *
 * @since 4.4.2
 */

function md_has_tagline() {
	if ( get_bloginfo( 'description' ) && ! md_setting( array( 'header', 'display', 'site_tagline' ) ) && ! md_module( array( 'layout', 'header', 'tagline' ) ) )
		return true;
}

/**
 * Outputs the menu name assigned to the specified Menu area.
 *
 * @since 4.0
 */

function md_get_menu_name( $menu ) {
	$menus = get_nav_menu_locations();

	if ( ! empty( $menus[$menu] ) ) {
		$menu_object = wp_get_nav_menu_object( $menus[$menu] );
		$menu_name = isset( $menu_object->name ) ? $menu_object->name : '';
	}

	if ( empty( $menu_name ) )
		$menu_name = __( 'Menu', 'md' );

	return esc_html( $menu_name );
}

/**
 * Checks if menu is enabled.
 *
 * @since 4.1
 */

function md_has_menu() {
	$header_elements = unserialize( md_setting( array( 'header', 'builder_elements' ) ) );
	if (
		! md_module( array( 'layout', 'header', 'remove' ) ) &&
		! md_module( array( 'layout', 'header', 'menu' ) ) &&
		( has_nav_menu( 'header' ) || ! empty( $header_elements['menu'] ) )
	)
		return true;
}

/**
 * Checks if content box is enabled.
 *
 * @since 4.1
 */

function md_has_content_box() {
	if ( ! md_module( array( 'layout', 'content', 'remove' ) ) )
		return apply_filters( 'md_filter_has_content_box', true );
}

/**
 * Displays titles for various types of archives.
 *
 * @since 4.0
 */

function md_content_box() {
	if ( md_has_content_box() ) {
		$html = md_html( 'content' );
		include( md_template( 'content-box', true ) );
	}
}

/**
 * A list of classes to add to the content box container.
 *
 * @since 4.1
 */

function md_content_box_classes( $classes = array() ) {
	$position = md_featured_image_position();
	$classes[] = 'content-box';

	if ( md_has_sidebar() ) {
		$classes[] = 'content-sidebar';
		if ( md_meta( array( 'layout', 'content_box' ), get_queried_object_id() ) )
			$layout = md_meta( array( 'layout', 'content_box' ) );
		else
			$layout = md_setting( array( 'colors', 'layout' ) );

		if ( $layout == 'sidebar_content' )
			$classes[] = 'sidebar-left';
	}
	else
		$classes[] = 'content-full';

	if ( md_setting( array( 'colors', 'style' ) ) )
		$classes[] = 'style-' . md_setting( array( 'colors', 'style' ) );
	else
		$classes[] = 'style-default';

	$classes[] = 'loop-' . md_get_loop();

	$classes = apply_filters( 'md_filter_content_box_classes', $classes );

	return join( ' ', $classes );
}

/**
 * A list of classes to add to content box.
 *
 * @since 4.5
 */

function md_content_classes( $classes = array() ) {
	$classes[] = 'content';
	$classes[] = 'format';
	$classes = apply_filters( 'md_filter_content_classes', $classes );

	return join( ' ', $classes );
}

/**
 * Checks if breadcrumbs are enabled.
 *
 * @since 5.2.2
 * @changed 5.6, now returns breadcrumbs $position
 */

function md_has_breadcrumbs() {
	$position = md_setting( array( 'colors', 'breadcrumbs', 'position' ) );
	if ( ! is_front_page() ) {
		if ( ! empty( $position ) && ! md_module( array( 'layout', 'breadcrumbs', 'remove' ) ) )
			return $position;
		elseif ( empty( $position ) && md_module( array( 'layout', 'breadcrumbs', 'add' ) ) )
			return 'before_content_box';
	}
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
	$blog_id = get_option( 'page_for_posts' );

	if ( ! empty( $post_type_obj ) )
		$post_type_title = md_text_field( $post_type_obj->labels->name );

	if ( $post_type == 'post' ) {
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

/**
 * Checks if author box.
 *
 * @since 4.5
 */

function md_has_author_box() {
	$enable = md_setting( array( 'post', 'single', 'author_box', 'enable' ) );
	if (
		( is_singular( 'post' ) && ! empty( $enable ) && ! md_post_meta( array( 'layout', 'content', 'author_box' ) ) ) ||
		( is_singular() && md_post_meta( array( 'layout', 'content', 'add_author_box' ) ) )
	)
		return true;
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
 * Checks if footer is enabled.
 *
 * @since 4.1
 */

function md_has_footer() {
	if ( ! md_module( array( 'layout', 'footer', 'remove' ) ) && ( md_has_footer_columns() || is_active_sidebar( 'footer-copy' ) ) )
		return apply_filters( 'md_filter_has_footer', true );
}

/**
 * Checks if footer columns are enabled.
 *
 * @since 4.1
 */

function md_has_footer_columns() {
	if ( md_footer_columns() && ! md_module( array( 'layout', 'footer', 'columns' ) ) )
		return true;
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
 * A list of classes to add to the header.
 *
 * @since 4.5
 */

function md_footer_classes() {
	$classes = apply_filters( 'md_filter_footer_classes', array() );
	return join( ' ', $classes );
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
 * Get page data from different page types.
 *
 * @since 4.6
 */

function md_page_data() {
	if ( is_category() || is_tax() ) {
		$term = get_queried_object();
		$id = $term->term_id;
		return array(
			'title' => get_cat_name( $id ),
			'link' => get_category_link( $id ),
			'image' => md_term_meta( array( 'featured_image', 'image', 'url' ) ),
			'excerpt' => strip_tags( category_description( $id ) )
		);
	}
	else {
		$id = get_queried_object_id();
		return array(
			'title' => get_the_title( $id ),
			'link' => get_permalink( $id ),
			'image' => get_the_post_thumbnail_url( $id ),
			'excerpt' => get_post_field( 'post_excerpt', $id )
		);
	}
}
