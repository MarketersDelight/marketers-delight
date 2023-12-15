<?php

/**
 * Return a list of sidebar names by unique IDs.
 *
 * @since 4.6.2
 */

function md_get_sidebars() {
	$sidebars = array();
	$areas = md_setting( array( 'sidebars', 'areas' ), array() );

	foreach ( $areas as $area => $fields )
		$sidebars[$area] = $fields['name'];

	return array_filter( $sidebars );
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

	$post_type = isset( $args['post_type'] ) ? $args['post_type'] : md_get_post_type();
	$post_id = isset( $args['post_id'] ) ? $args['post_id'] : get_queried_object_id();

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

	if ( $global ) {
		if ( ( empty( $display['disable'] ) && ! empty( $single['add'] ) ) || empty( $single['remove'] ) )
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
	$sidebar = 'sidebar-main';

	if ( is_home() || is_post_type_archive() )
		$sidebar = md_post_type_field( array( 'layout', 'sidebar_archive' ), $sidebar );
	elseif ( is_category() || is_tax() ) {
		$global = md_post_type_field( array( 'layout', 'sidebar_term' ), $sidebar );
		$sidebar = md_term_meta( array( 'layout', 'custom_sidebar' ), null, $global );
	}
	elseif ( is_singular() ) {
		$global = md_post_type_field( array( 'layout', 'sidebar_single' ), $sidebar );
		$sidebar = md_post_meta( array( 'layout', 'custom_sidebar' ), null, $global );

		if ( md_has_sidebar() && ! $sidebar ) {
			$term_sidebar = '';
			$taxonomies = get_taxonomies( array( 'public' => true ) );

			foreach ( $taxonomies as $taxonomy ) {
				$terms = get_terms( array(
					'taxonomy' => $taxonomy,
					'hide_empty' => true
				) );

				foreach ( $terms as $term ) {
					$posts_sidebar = md_term_meta( array( 'layout', 'entries_sidebar' ), $term->term_id );

					if ( $posts_sidebar )
						$sidebar = $posts_sidebar;
				}
			}
		}

	}

	return $sidebar;
}
