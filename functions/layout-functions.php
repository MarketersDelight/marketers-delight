<?php

/**
 * Checks if page template (Header/Content/Footer) is active.
 *
 * @since 4.9.4
 */

function md_filter_template() {
	return apply_filters( 'md_filter_has_template', true ) !== false;
}



/*------------------------------*\
	$CONTENT_BOX
\*------------------------------*/

/**
 * Checks if the current template has the content box enabled
 * or removed.
 *
 * @since 4.1
 */

function md_has_content_box() {
	$show = ! md_module( array( 'layout', 'content', 'remove' ) );

	return (bool) apply_filters( 'md_filter_has_content_box', $show );
}

/**
 * Displays titles for various types of archives.
 *
 * @since 4.0
 */

function md_content_box() {
	if ( ! md_has_content_box() )
		return;

	$has_builder = md_has_builder();
	$loop_classes = md_loop_classes();
	$loop_classes = $loop_classes['loop'];

	include md_template( 'content-box', true );
}

/**
 * A list of classes to add to the content box container.
 *
 * @since 4.1
 */

function md_content_box_classes() {
	$classes = array( 'main' );

	if ( md_has_sidebar() ) {
		$classes[] = 'compact';

		if ( md_get_layout_toggle( array( 'sidebar', 'alt' ) ) )
			$classes[] = 'sidebar-left';
	}
	else $classes[] = 'expanded';

	$classes[] = 'format';
	$classes = apply_filters( 'md_filter_content_box_classes', $classes );

	return join( ' ', $classes );
}

/**
 * Checks page for active sidebar.
 *
 * @since 4.1
 */

function md_has_sidebar( $args = array() ) {
	$show = md_has_layout( 'sidebar', $args );

	return (bool) apply_filters( 'md_filter_has_sidebar', $show, $args );
}

/**
 * Checks page for active panel.
 *
 * @since 6.0
 */

function md_has_panel( $args = array() ) {
	$show = md_has_layout( 'panel', $args );

	return (bool) apply_filters( 'md_filter_has_panel', $show, $args );
}



/*------------------------------*\
	$FOOTER
\*------------------------------*/

/**
 * Checks if footer is enabled.
 *
 * @since 4.1
 */

function md_has_footer() {
	$show = ! md_module( array( 'layout', 'footer', 'remove' ) ) && ( md_has_footer_columns() || is_active_sidebar( 'footer-copy' ) );

	return (bool) apply_filters( 'md_filter_has_footer', $show );
}

/**
 * Checks if footer columns are enabled.
 *
 * @since 4.1
 */

function md_has_footer_columns() {
	$show = ! empty( md_footer_columns() ) && ! md_module( array( 'layout', 'footer', 'columns' ) );

	return (bool) apply_filters( 'md_filter_has_footer_columns', $show );
}

/**
 * Manage number of footer columns with an array of digits.
 *
 * @since 4.5
 */

function md_filter_footer_columns() {
	return apply_filters( 'md_filter_footer_columns', array( 1, 2, 3 ) );
}

/**
 * Returns an array of active widget areas with the md-footer-col prefix.
 *
 * @since 4.0
 */

function md_footer_columns() {
	$columns = array();

	foreach ( md_filter_footer_columns() as $column )
		if ( is_active_sidebar( "md-footer-col-$column" ) )
			$columns[] = $column;

	return $columns;
}

/**
 * A list of classes to add to the header.
 *
 * @since 4.5
 */

function md_footer_classes() {
	return join( ' ', apply_filters( 'md_filter_footer_classes', array( 'footer', 'format' ) ) );
}

/**
 * Add widgetized footer columns to footer.
 *
 * @since 4.5
 */

function md_footer_columns_template() {
	$columns = md_footer_columns();
    $count = count( $columns );

	if ( md_has_footer_columns() )
		include md_template( 'footer-columns', true );
}

/**
 * Add widgetized footer copyright text to footer.
 *
 * @since 4.5
 */

function md_footer_copy() {
	if ( is_active_sidebar( 'footer-copy' ) )
		include md_template( 'footer-copy', true );
}



/*------------------------------*\
	$LAYOUT
\*------------------------------*/

/**
 * Get the result of the conditional options from the
 * Layout Toggle. This computes the active state based on
 * global, page type, down to single.
 *
 * @since 6.0
 */

function md_get_layout_toggle( $keys = array(), $args = array() ) {
	if ( empty( $keys[0] ) || empty( $keys[1] ) )
		return false;

	$post_type = $args['post_type'] ?? md_get_post_type();
	$post_id = $args['post_id'] ?? get_queried_object_id();
	$default = md_post_type_field( array( 'layout', $keys[0], $keys[1] ), null, $post_type );
	$single = md_meta( array( 'layout', $keys[0], $keys[1] ), $post_id );

	return (bool) $default !== (bool) $single;
}

/**
 * Determine which page type is open based on template hierarchy.
 * Pass an explicit `page` argument to resolve an admin preview or another
 * context that does not use the current frontend query.
 *
 * @since 6.0
 */

function md_layout_context( $args = array() ) {
	if ( isset( $args['page'] ) )
		return $args['page'];

	if ( is_category() || is_tag() || is_tax() )
		return 'term';

	if ( is_home() || is_archive() || is_search() )
		return 'archive';

	if ( is_singular() || is_404() )
		return 'single';

	return '';
}

/**
 * Return layout areas by key and optional keys-only format.
 *
 * @since 6.0
 */

function md_layout_areas( $layout = 'sidebar', $keys = null ) {
	$areas = md_setting( array( 'settings', "{$layout}s" ), array() );

	if ( isset( $keys ) )
		return array_keys( $areas );

	$items = array();

	foreach ( $areas as $area => $fields ) {
		$items[$area] = ! empty( $fields['name'] ) ? $fields['name'] : $area;
	}

	return $items;
}

/**
 * Users can override layout elements on Single views based on
 * Term-level settings (example: show Sidebar X on all Posts in
 * Category Y).
 *
 * Taxonomies are checked in their registered order. Within each taxonomy,
 * the deepest configured term wins, with term ID as a stable tie-breaker.
 * Filter `md_layout_taxonomies` to change taxonomy priority.
 *
 * @since 6.0
 */

function md_get_layout_term( $layout = 'sidebar', $post_id = null ) {
	$key = array( 'layout', "entries_{$layout}" );
	$post_id = isset( $post_id ) ? $post_id : get_queried_object_id();
	$taxonomies = array();

	foreach ( get_object_taxonomies( get_post_type( $post_id ) ) as $taxonomy ) {
		$object = get_taxonomy( $taxonomy );

		if ( $object && $object->public )
			$taxonomies[] = $taxonomy;
	}

	$taxonomies = apply_filters( 'md_layout_taxonomies', $taxonomies, $layout, $post_id );
	$terms = $taxonomies ? wp_get_post_terms( $post_id, $taxonomies ) : array();

	if ( is_wp_error( $terms ) || empty( $terms ) )
		return '';

	foreach ( $taxonomies as $taxonomy ) {
		$ranked = array();

		foreach ( $terms as $term ) {
			if ( $term->taxonomy !== $taxonomy )
				continue;

			$depth = count( get_ancestors( $term->term_id, $taxonomy, 'taxonomy' ) );
			$ranked[$depth][$term->term_id] = $term;
		}

		krsort( $ranked );

		foreach ( $ranked as $depth => $items ) {
			ksort( $items );

			foreach ( $items as $term ) {
				$value = md_term_meta( $key, $term->term_id );

				if ( ! empty( $value ) )
					return $value;
			}
		}
	}

	return '';
}

/**
 * Determine whether a layout element is visible. Post-type global state is
 * overridden by page context, then by an individual post or term.
 *
 * @since 6.0
 */

function md_has_layout( $layout = 'sidebar', $args = array() ) {
	$post_id = $args['post_id'] ?? get_queried_object_id();
	$post_type = $args['post_type'] ?? md_get_post_type();
	$page = md_layout_context( $args ) ?: 'single';

	$global = md_post_type_field( array( 'layout', $layout, 'global' ), null, $post_type );
	$page_type = md_post_type_field( array( 'layout', sprintf( "{$layout}_%s_show", $page ) ), array(), $post_type );
	$single = md_meta( array( 'layout', $layout ), $post_id, array() );

	$show = ( $global && empty( $page_type['disable'] ) ) || ( ! $global && ! empty( $page_type['enable'] ) );

	if ( isset( $args['exclude_single'] ) )
		return $show;

	if ( ! empty( $single['remove'] ) )
		return false;

	if ( ! empty( $single['add'] ) )
		return true;

	return $show;
}

/**
 * Get the unique layout area identifier using individual, associated term,
 * post-type context, then default precedence.
 *
 * @since 6.0
 */

function md_get_layout_id( $layout = 'sidebar', $args = array() ) {
	$default = "{$layout}-main";
	$post_type = $args['post_type'] ?? md_get_post_type();
	$post_id = $args['post_id'] ?? get_queried_object_id();
	$page = md_layout_context( $args );
	$layout_id = $page ? md_post_type_field( array( 'layout', "{$layout}_{$page}" ), $default, $post_type ) : $default;

	if ( $page === 'term' )
		$layout_id = md_term_meta( array( 'layout', "custom_{$layout}" ), null, $layout_id );
	elseif ( $page === 'single' ) {
		$single = md_post_meta( array( 'layout', "custom_{$layout}" ), $post_id );
		$term = md_get_layout_term( $layout, $post_id );

		if ( $single )
			$layout_id = $single;
		elseif ( $term )
			$layout_id = $term;
	}

	$areas = md_layout_areas( $layout );

	return ! empty( $areas[$layout_id] ) ? $layout_id : $default;
}
