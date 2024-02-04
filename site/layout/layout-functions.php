<?php

add_action( 'md_hook_content_top', 'md_breadcrumbs', 20 );
add_action( 'md_hook_content', 'md_loop', 30 );
add_filter( 'excerpt_more', '__return_empty_string' );
add_action( 'md_hook_content_item', 'md_author', 60 );
add_action( 'md_hook_content_item', 'md_comments', 60 );
add_action( 'md_hook_after_comments_list', 'md_comment_form' );
add_action( 'md_hook_content', 'md_post_nav', 70 );
add_action( 'md_hook_footer', 'md_footer_columns_template', 20 );
add_action( 'md_hook_footer_bottom', 'md_footer_copy', 20 );

/**
 * Outputs inline JavaScript to footer.
 *
 * @since 4.0
 */

if ( ! function_exists( 'md_inline_js' ) ) :

function md_inline_js() {
	if ( md_has_menu() )
		wp_add_inline_script( 'marketers-delight', 'MD.headerMenu();' );

	if ( has_nav_menu( 'main_menu' ) )
		wp_add_inline_script( 'marketers-delight', 'MD.mainMenu();' );

	wp_add_inline_script( 'marketers-delight', "MD.toggle();" );

	if ( md_setting( array( 'header', 'display', 'sticky' ) ) )
		wp_add_inline_script( 'marketers-delight', 'MD.sticky();' );
}

endif;

/**
 * Inner HTML element and closing div.
 *
 * @since 6.0
 */

function md_inner_html() {
	echo '<div class="inner">';
}

function md_html_close() {
	echo '</div>';
}

/**
 * Show Page Title of current page.
 *
 * @since 6.0
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
	$classes = array( 'footer', 'format' );
	$classes = apply_filters( 'md_filter_footer_classes', $classes );
	$classes = join( ' ', $classes );

	return $classes;
}

/**
 * Add widgetized footer columns to footer.
 *
 * @since 4.5
 */

function md_footer_columns_template() {
	$columns = md_filter_footer_columns();

	if ( md_has_footer_columns() )
		include( md_template( 'footer-columns', true ) );
}

/**
 * Add widgetized footer copyright text to footer.
 *
 * @since 4.5
 */

function md_footer_copy() {
	if ( is_active_sidebar( 'footer-copy' ) )
		include( md_template( 'footer-copy', true ) );
}
