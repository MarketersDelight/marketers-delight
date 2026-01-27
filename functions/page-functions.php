<?php

/**
 * Get the plaintext title of the current page based on content type.
 *
 * @since 6.0
 */

function md_get_title( $context = 'post' ) {
	$title = get_the_title();

	if ( $context == 'post' ) {
		if ( is_404() && ! md_has_custom_404() )
			$title = __( 'Page not found', 'md' );

		return $title;
	}

	if ( is_home() || is_post_type_archive() || is_singular( 'post' ) ) {
		$post_type_title = post_type_archive_title( '', false );
		$title = md_post_type_field( 'archives_title', $post_type_title );
	}
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
 * Render the layout markup of the Post or Page title with supporting
 * elements like image, byline, etc. A title used within a loop
 * has key differences than when rendered as the main Page Title.
 *
 * $context accepts 'post' by default or 'page'
 *
 * @since 6.0
 */

function md_title( $context = 'post' ) {
	if ( ! md_get_title( $context ) )
		return;

	$has_header_cover = md_has_header_cover( $context );

	if ( $has_header_cover && in_the_loop() )
		return;

	$is_inline = false;
	$style = array();
	$classes = array( '' );
	$inline_images = array( 'left', 'right' );
	$title_images = array( 'title_left', 'title_right' );
	$full_width = array( 'center', 'above_headline', 'below_headline' );
	$image = md_has_image( $context );
	$cover = md_cover( $context );
	$has_sidebar = md_has_sidebar();
	$has_wrap = $image && ! in_array( $image['position'], $full_width ) ? true : false;
	$has_inner = $has_header_cover ? true : false;

	if ( $has_sidebar && ! $has_header_cover ) {
		$is_inline = true;
		$classes[] = 'inline';
	}
	else $classes[] = 'wide';

	if ( $image && ( $context == 'page' || ( $context == 'post' && in_array( $image['position'], $title_images ) ) ) ) {
		$class_name = $image['position'];

		if ( in_array( $image['position'], $inline_images ) )
			$classes[] = 'image-inline';
		elseif ( in_array( $image['position'], $full_width ) ) {
			$classes[] = 'image-full';
			$class_name = str_replace( '_headline', '', $image['position'] );
		}
		elseif ( in_array( $image['position'], $title_images ) ) {
			$classes[] = 'image-title';
			$class_name = str_replace( 'title_', '', $image['position'] );
		}

		$classes[] = $class_name;
	}

	if ( ! empty( $cover['position'] ) ) {
		$classes[] = md_cover_classes( $context );

		if ( ! empty( $cover['photo']['url'] ) )
			$style['bg_image'] = esc_url( $cover['photo']['url'] );
	}

	$classes = join( ' ', $classes );
	$style = md_style( $style );

	do_action( "md_hook_{$context}_title_before" );
	include md_template( "{$context}-title", true );
	do_action( "md_hook_{$context}_title_after" );
}

/**
 * Render the inner content of a title.
 *
 * @since 6.0
 */

function md_the_title( $context = 'post', $args = array() ) {
	include md_template( 'title', true );
}

/**
 * Show Description of current page.
 *
 * @since 6.0
 */

function md_description( $context = 'post', $args = array() ) {
	include md_template( 'description', true );
}

/**
 * Easily output a link/button with different kind of action.
 *
 * @since 4.3.5
 */

function md_link( $fields, $p = '' ) {
	echo md_get_link( $fields, $p );
}

function md_get_link( $fields, $p = '' ) {
	$html = $attrs = '';
	$styles = array();
	$defaults = array(
		'type' => 'url',
		'style' => 'link',
		'area' => '',
		'name' => '',
		'subtitle' => '',
		'icon' => '',
		'url' => '',
		'phone' => '',
		'size' => '',
		'color' => '',
		'display' => '',
		'user' => '',
		'popup' => '',
		'classes' => '',
		'button_style' => array(),
		'toggle' => array(
			'hide_label' => '',
			'hide_label_mobile' => ''
		)
	);

	$fields = wp_parse_args( $fields, $defaults );

	if ( ( $fields['user'] == 'logged_out' && is_user_logged_in() ) || ( $fields['user'] == 'logged_in' && ! is_user_logged_in() ) )
		return;

	if ( empty( $fields['name'] ) && empty( $fields['icon'] ) )
		return;

	include md_template( 'link', true );

	return $html;
}

/**
 * Get Hero/inline CTA of any given page. A CTA can be a
 * link group, email form, custom HTML, or more.
 *
 * @since 6.0
 */

function md_cta( $context = 'post' ) {
	if ( $context == 'page' ) {
		$cta = md_post_type_field( 'page_cta' );
		$type = md_post_type_field( array( 'page_cta', 'page_cta' ) );
		$links = md_post_type_field( array( 'page_cta', 'links' ) );
	}
	else {
		$cta = md_post_meta( 'page_cta' );
		$type = md_post_meta( array( 'page_cta', 'page_cta' ) );
		$links = md_post_meta( array( 'page_cta', 'links' ) );
	}
	include md_template( 'cta', true );
}

/**
 * Get list of items that can be used in a Byline.
 *
 * @since 6.0
 */

function md_byline_items() {
	return apply_filters( 'md_byline', array() );
}

/**
 * Render the byline template with designated items.
 *
 * @since 4.0
 */

function md_byline( $location = 'before_headline', $args = array() ) {
	include md_template( 'byline/byline', true );
}

/**
 * Get byline items based on a specified position.
 *
 * Accepts: before_headline | after_headline | before_post | after_post
 *
 * @since 6.0
 */

function md_get_byline( $position, $loop = array() ) {
	$remove = '';
	$byline = array();
	$context = 'single';
	$builder = md_post_type_field( array( 'byline', 'builder' ), array() );
	if ( is_category() || is_tax() )
		$builder = md_term_meta( array( 'byline', 'builder' ), null, $builder );

	if ( isset( $loop['remove_byline'] ) )
		$remove = $loop['remove_byline'];

	if ( $position == $remove || $remove == 'remove' || ( $position == 'after_post' && isset( $loop['post_footer']['remove'] ) ) )
		return;

	if ( is_home() || is_archive() || isset( $loop['is_query'] ) )
		$context = 'archives';

	foreach ( $builder as $id => $fields )
		if ( $context == $fields['builder_area'] && $position == $fields['position'] ) {
			$type = $fields['builder_type'];
			$byline[$type] = $fields;
			$byline[$type]['id'] = $id;
		}

	return $byline;
}

/**
 * Checks if breadcrumbs are enabled.
 *
 * @since 5.2.2
 */

function md_has_breadcrumbs() {
	$global_add = md_post_type_field( array( 'layout', 'breadcrumbs', 'add' ) );
	$single_add = md_meta( array( 'layout', 'breadcrumbs', 'add' ) );
	$single_remove = md_meta( array( 'layout', 'breadcrumbs', 'remove' ) );

	if ( $single_remove )
		return;

	if ( ! $global_add && ! $single_add )
		return;

	if ( is_front_page() || ( is_page() && ! wp_get_post_parent_id( get_the_ID() ) ) )
		return;

	return true;
}

/**
 * Render breadcrumbs template.
 *
 * @since 5.2.2
 */

function md_breadcrumbs() {
	if ( ! md_has_breadcrumbs() )
		return;

	$post_type_title = $category_url = $category_title = '';
	$post_id = get_the_ID();
	$post_type = md_get_post_type();
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

	include md_template( 'breadcrumbs', true );
}

/**
 * Checks if author box is active on page.
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

	include md_template( 'author-box', true );
}

/**
 * Render the searchform with formatting options.
 *
 * @since 6.0
 */

function md_search( $fields ) {
	$id = isset( $fields['id'] ) ? $fields['id'] : 's';
	$location = isset( $fields['location'] ) ? $fields['location'] : '';
	$parent = isset( $fields['parent'] ) ? $fields['parent'] : 'header';
	$title = isset( $fields['title'] ) ? $fields['title'] : __( 'Search', 'md' );
	$placeholder = isset( $fields['placeholder'] ) ? $fields['placeholder'] : __( 'Type to search...', 'md' );
	$submit_text = isset( $fields['submit_text'] ) ? $fields['submit_text'] : __( 'Search', 'md' );

	$fields['classes'][] = 'search-form';
	$fields['classes'][] = 'form-icons';

	if ( ! empty( $fields['toggle']['search'] ) )
		$fields['classes'][] = 'form-toggle';
	else
		$fields['classes'][] = 'inline';

	if ( ! empty( $fields['toggle']['hide_label'] ) )
		$fields['classes'][] = 'hide-label';

	if ( ! empty( $fields['toggle']['hide_label_mobile'] ) )
		$fields['classes'][] = 'hide-label-mobile';

	$classes = join( ' ', $fields['classes'] );

	include locate_template( 'searchform.php' );
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