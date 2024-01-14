<?php

add_action( 'md_hook_content_top', 'md_breadcrumbs' );

add_action( 'md_hook_content', 'md_loop', 30 );
//add_action( 'md_hook_content', 'md_query', 30 );

add_filter( 'excerpt_more', '__return_empty_string' );
add_action( 'md_hook_content_item', 'md_author', 60 );
add_action( 'md_hook_content_item', 'md_comments', 60 );
add_action( 'md_hook_after_comments_list', 'md_comment_form' );
add_action( 'md_hook_content', 'md_post_nav', 70 );

// Footer

add_action( 'md_hook_footer', 'md_footer_columns_template' );
add_action( 'md_hook_footer_bottom', 'md_footer_copy' );

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
 * Call a page title with or without a URL.
 *
 * @since 5.6
 */

function md_title( $text, $url = null ) {
	$title = '';

	if ( $url )
		$title .= '<a href="' . esc_url( $url ) . '">';

	$title .= md_text_field( $text );

	if ( $url )
		$title .= '</a>';

	return $title;
}

/**
 * Show Page Title of current page/type.
 *
 * @since 5.6
 */

function md_page_title() {
	$title = '';

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
	$header_elements = md_get_builder( 'header' );

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
	if ( md_has_content_box() )
		include( md_template( 'content-box', true ) );
}

/**
 * A list of classes to add to the content box container.
 *
 * @since 4.1
 */

function md_content_box_classes( $classes = array() ) {
	$columns = 1;
	$default_style = md_post_type_field( array( 'layout', 'content_box_style' ), 'box_style' );
	$style = md_meta( array( 'layout', 'content_box_style' ), null, $default_style );

	if ( is_singular() )
		$classes[] = 'article';
	else
		$columns = md_post_type_field( array( 'loop', 'columns' ), $columns );

	if ( md_has_sidebar() ) {
		$classes[] = 'content-sidebar';

		if ( md_meta( array( 'layout', 'content_box' ), get_queried_object_id() ) )
			$layout = md_meta( array( 'layout', 'content_box' ) );
		else
			$layout = md_post_type_field( array( 'layout', 'content_box' ) );

		if ( $layout == 'sidebar_content' )
			$classes[] = 'left';
	}
	else {
		$classes[] = 'full';

		if ( is_singular() )
			$classes[] = 'expanded';
	}

	$classes[] = 'loop-' . md_get_loop();

	if ( $style !== 'minimal' )
		$classes[] = str_replace( '_', '-', $style );

	$classes[] = 'format';

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
	$classes = apply_filters( 'md_filter_content_classes', $classes );

	return join( ' ', $classes );
}

/**
 * Return a list of sidebar names by unique IDs.
 *
 * @since 4.6.2
 */

function md_get_sidebars( $keys = null ) {
	$sidebars = array();
	$areas = md_setting( array( 'settings', 'sidebars' ), array() );

	foreach ( $areas as $area => $fields )
		if ( ! isset( $keys ) )
			$sidebars[$area] = $fields['name'];
		else
			$sidebars[] = $area;

	return $sidebars;
}

/**
 * Checks page for active sidebar.
 *
 * @since 4.1
 */

function md_has_sidebar( $args = array() ) {
	$show = false;

	if ( has_filter( 'md_filter_has_sidebar' ) )
		return apply_filters( 'md_filter_has_sidebar', $show );

	$post_id = isset( $args['post_id'] ) ? $args['post_id'] : get_queried_object_id();
	$post_type = isset( $args['post_type'] ) ? $args['post_type'] : md_get_post_type();

	if ( isset( $args['page'] ) )
		$page = $args['page'];
	elseif ( is_home() || is_post_type_archive() )
		$page = 'archive';
	elseif ( is_category() || is_tax() )
		$page = 'term';
	else
		$page = 'single';

	$display = md_post_type_field( array( 'layout', "sidebar_{$page}_show" ), null, $post_type );
	$global = md_post_type_field( array( 'layout', 'sidebar', 'global' ), null, $post_type );
	$single = md_meta( array( 'layout', 'sidebar' ), $post_id );

	if ( isset( $args['exclude_single'] ) ) { // for checking admin contexts
		if ( ( $global && empty( $display['disable'] ) ) || ( ! $global && ! empty( $display['enable'] ) ) )
			$show = true;
	}
	elseif ( $global ) {
		if ( ( empty( $display['disable'] ) && empty( $single['add'] ) ) && empty( $single['remove'] ) )
			$show = true;
	}
	elseif ( ( ! empty( $display['enable'] ) && empty( $single['remove'] ) ) || ! empty( $single['add'] ) )
		$show = true;

	return $show;
}

/**
 * Get active sidebar ID for current page.
 *
 * @since 4.6.2.1
 */

function md_get_sidebar_id() {
	$default = 'sidebar-main';
	$sidebars = md_get_sidebars();

	if ( is_home() || is_post_type_archive() || is_author() )
		$sidebar = md_post_type_field( array( 'layout', 'sidebar_archive' ), $default );
	elseif ( is_category() || is_tax() ) {
		$global = md_post_type_field( array( 'layout', 'sidebar_term' ), $default );
		$sidebar = md_term_meta( array( 'layout', 'custom_sidebar' ), null, $global );
	}
	elseif ( is_singular() ) {
		$global = md_post_type_field( array( 'layout', 'sidebar_single' ), $default );
		$single = md_post_meta( array( 'layout', 'custom_sidebar' ) );

		if ( md_has_sidebar() && ! $single ) { // Must be a better way...
			$taxonomies = get_taxonomies( array( 'public' => true ) );
			$terms = wp_get_post_terms( get_queried_object_id(), $taxonomies );

			foreach ( $terms as $term_count => $fields )
				if ( md_term_meta( array( 'layout', 'entries_sidebar' ), $fields->term_id ) ) {
					$term['taxonomy'] = $fields->taxonomy;
					$term['term_id'] = $fields->term_id;
				}

			$taxonomy = ! empty( $term['taxonomy'] ) ? $term['taxonomy'] : '';
			$term_id = ! empty( $term['term_id'] ) ? $term['term_id'] : '';

			if ( has_term( $term_id, $taxonomy ) )
				$sidebar = md_term_meta( array( 'layout', 'entries_sidebar' ), $term_id );
			else
				$sidebar = $global;
		}
		else
			$sidebar = $single;
	}

	return ( ! empty( $sidebars[$sidebar] ) ? $sidebar : $default );
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

	$html = is_author() ? 'h1' : 'h3';
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
 * @since 5.6
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
 * @since 5.6
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

function md_pagination() {
	if ( is_singular() )
		return;

	$big = 999999999;

	$type = md_module( array( 'loop', 'pagination' ) );
	$prelabel = ! empty( $loop['previous_label'] ) ? $loop['previous_label'] : __( 'Previous', 'md' );
	$nxtlabel = ! empty( $loop['next_label'] ) ? $loop['next_label'] : __( 'Next', 'md' );
	$class = $type == 'prev_next' ? 'prev-next' : 'numbers';

	if ( ! empty( $loop['category_posts']['enable'] ) ) {
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
		return apply_filters( 'md_filter_has_footer_columns', true );
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
