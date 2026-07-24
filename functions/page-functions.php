<?php

/**
 * Check if page has Builder enabled.
 *
 * @since 6.0
 */

function md_has_builder() {
	return md_meta( array( 'layout', 'content', 'builder' ) );
}

/**
 * Callback function for get_searchform().
 *
 * @since 6.0
 */

function md_search( $args = array() ) {
	include locate_template( 'searchform.php' );
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

	if ( ( is_page() && ! wp_get_post_parent_id( get_the_ID() ) ) )
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

	$term = null;
	$post_id = get_the_ID();
	$post_type = md_get_post_type();
	$post_type_obj = get_post_type_object( $post_type );
	$post_type_title = $post_type_obj ? wp_kses_data( $post_type_obj->labels->name ) : '';

	if ( $post_type === 'post' ) {
		$blog_id = get_option( 'page_for_posts' );
		$post_type_title = $blog_id ? get_the_title( $blog_id ) : __( 'Blog', 'md' );
	}

	if ( is_category() || is_tag() || is_tax() )
		$term = get_queried_object();
	elseif ( is_singular() ) {
		$taxonomies = get_object_taxonomies( $post_type );

		if ( ! empty( $taxonomies ) ) {
			$post_terms = wp_get_post_terms( $post_id, $taxonomies[0], array( 'number' => 1 ) );

			if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) )
				$term = $post_terms[0];
		}
	}

	include md_template( 'breadcrumbs', true );
}

/**
 * Checks if the post content is enabled onpage.
 *
 * @since 6.0
 */

function md_has_post_content() {
	if ( ! md_module( array( 'layout', 'content', 'the_content' ) ) )
		return apply_filters( 'md_filter_has_the_content', true );
}

/**
 * Outputs the_content with option enhancements.
 *
 * @since 6.0
 */

function md_the_content( $loop ) {
	if ( $loop['content'] === 'hide' || ( ! get_the_content() && ! get_the_excerpt() && ! is_404() ) )
		return;

	$has_wrap = ! isset( $loop['is_slim'] );
	$has_builder = isset( $loop['has_builder'] );
	$id = ( is_singular() ? ' id="the_content"' : '' );
	$has_excerpt = ( empty( $loop['content'] ) || $loop['content'] == 'excerpt' ) && get_the_excerpt();
	$show_full_content = $loop['content'] == 'full' || ( empty( $loop['query'] ) && ( is_singular() || is_404() ) && ( in_the_loop() || isset( $loop['in_loop'] ) ) );

	include md_template( 'loop/the-content', true );
}

/**
 * Outputs the WordPress excerpt with read more and
 * length enhancements.
 *
 * @since 6.0
 */

function md_excerpt( $loop ) {
	return wpautop( wp_trim_words( get_the_excerpt(), $loop['excerpt_length'], $loop['excerpt_more'] ) ).
		( empty( $loop['excerpt_settings']['remove_text'] ) ?
			'<p class="read-more"><a href="' . get_permalink() . '" class="more-link">' . esc_html( $loop['read_more'] ) . '</a></p>'
		: '' );
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

	if (
		! is_page() && is_singular() && ( get_previous_post() || get_next_post() ) &&
		! $single_remove &&
		( ! $disable || $single_add )
	)
		return true;
}

/**
 * Create pagination for use on home and archives pages.
 *
 * @since 4.0
 */

function md_pagination( $loop = array() ) {
	if ( is_singular() )
		return;

	$big = 999999999;
	$type = md_module( array( 'loop', 'pagination' ) );
	$classes = $type == 'prev_next' ? 'prev-next' : 'numbers';
	$loop = ! empty( $loop ) ? $loop : md_get_loop();

	if ( isset( $loop['by_category'] ) ) {
		$taxonomies = get_object_taxonomies( md_get_post_type() );
		$taxonomy = ! empty( $taxonomies[0] ) ? $taxonomies[0] : '';
		$category_per_page = ! empty( $loop['category_per_page'] ) ? $loop['category_per_page'] : 5;
		$total_terms = wp_count_terms( $taxonomy, array( 'hide_empty' => true ) );
		$total = ceil( $total_terms / $category_per_page );
	}
	else {
		global $wp_query;
		$total = $wp_query->max_num_pages;
	}

	if ( $total <= 1 )
		return;

	include md_template( 'pagination', true );
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
