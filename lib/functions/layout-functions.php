<?php

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
	$classes[] = 'main';

	if ( md_has_sidebar() ) {
		$classes[] = 'narrow';
		$default_layout = md_post_type_field( array( 'layout', 'content_box' ) );
		$layout = md_meta( array( 'layout', 'content_box' ), get_queried_object_id(), $default_layout );

		if ( $layout == 'sidebar_content' )
			$classes[] = 'left';
	}
	else
		$classes[] = 'expanded';

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
	$classes[] = 'row';

	if ( is_singular() ) {
		$style = md_loop_style();

		if ( $style )
			$classes[] = $style;
	}

	$classes = apply_filters( 'md_filter_content_classes', $classes );

	return join( ' ', $classes );
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