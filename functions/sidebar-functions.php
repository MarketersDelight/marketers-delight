<?php

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