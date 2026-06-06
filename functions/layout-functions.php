<?php

/**
 * Checks if page template (Header/Content/Footer) is active.
 *
 * @since 4.9.4
 */

function md_filter_template() {
	return apply_filters( 'md_filter_has_template', true );
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
	if ( ! md_module( array( 'layout', 'content', 'remove' ) ) )
		return apply_filters( 'md_filter_has_content_box', true );
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

	return apply_filters( 'md_filter_has_sidebar', $show, $args );
}

/**
 * Checks page for active panel.
 *
 * @since 6.0
 */

function md_has_panel( $args = array() ) {
	$show = md_has_layout( 'panel', $args );

	return apply_filters( 'md_filter_has_panel', $show, $args );
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
	return join( ' ', apply_filters( 'md_filter_footer_classes', array( 'footer', 'format' ) ) );
}

/**
 * Add widgetized footer columns to footer.
 *
 * @since 4.5
 */

function md_footer_columns_template() {
	$columns = md_filter_footer_columns();
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

	if ( ( $default && empty( $single ) ) || ( empty( $default ) && $single ) )
		return true;

	return false;
}

/**
 * Determine which page type is open based on template hierarchy.
 *
 * @since 6.0
 */

function md_layout_context( $args = array() ) {
	if ( isset( $args['page'] ) )
		return $args['page'];

	if ( is_home() || is_post_type_archive() || ( ! empty( $args['author_archive'] ) && is_author() ) )
		return 'archive';

	if ( is_category() || is_tax() )
		return 'term';

	if ( is_singular() || ( ! empty( $args['single_404'] ) && is_404() ) )
		return 'single';

	return '';
}

/**
 * Return layout areas by key and optional keys-only format.
 *
 * @since 6.0
 */

function md_layout_areas( $layout = 'sidebar', $keys = null ) {
	$items = array();
	$areas = md_setting( array( 'settings', "{$layout}s" ), array() );

	foreach ( $areas as $area => $fields ) {
		if ( ! isset( $keys ) )
			$items[$area] = ! empty( $fields['name'] ) ? $fields['name'] : $area;
		else
			$items[] = $area;
	}

	return $items;
}

/**
 * Users can override layout elements on Single views based on
 * Term-level settings (example: show Sidebar X on all Posts in
 * Category Y), and this function returns data when true for a page.
 *
 * @since 6.0
 */

function md_get_layout_term( $layout = 'sidebar', $post_id = null ) {
	$key = array( 'layout', "entries_{$layout}" );

	if ( empty( $key ) )
		return '';

	$post_id = isset( $post_id ) ? $post_id : get_queried_object_id();
	$taxonomies = get_taxonomies( array( 'public' => true ) );
	$terms = wp_get_post_terms( $post_id, $taxonomies );

	if ( is_wp_error( $terms ) || empty( $terms ) )
		return '';

	foreach ( $terms as $fields ) {
		$value = md_term_meta( $key, $fields->term_id );

		if ( ! empty( $value ) )
			return $value;
	}

	return '';
}

/**
 * Based on versatile criteria, determine if the page has
 * the selected layout element enabled.
 *
 * @since 6.0
 */

function md_has_layout( $layout = 'sidebar', $args = array() ) {
	$show = false;
	$post_id = $args['post_id'] ?? get_queried_object_id();
	$post_type = $args['post_type'] ?? md_get_post_type();
	$page = md_layout_context( $args ) ?: 'single';

	$global = md_post_type_field( array( 'layout', $layout, 'global' ), null, $post_type );
	$page_type = md_post_type_field( array( 'layout', sprintf( "{$layout}_%s_show", $page ) ), array(), $post_type );
	$single = md_meta( array( 'layout', $layout ), $post_id, array() );

	if ( isset( $args['exclude_single'] ) ) {
		// Used in admin screens: ignore single overrides and only use global + page-type enable/disable rules
		if ( ( $global && empty( $page_type['disable'] ) ) || ( ! $global && ! empty( $page_type['enable'] ) ) )
			$show = true;
	}
	elseif ( $global ) {
		// Enabled via global setting, so check for page type-level or single override
		if ( empty( $page_type['disable'] ) && empty( $single['add'] ) && empty( $single['remove'] ) )
			$show = true;
	}
	elseif ( ( ! empty( $page_type['enable'] ) && empty( $single['remove'] ) ) || ! empty( $single['add'] ) ) {
		// No global layout, so single page rules can enable
		$show = true;
	}

	return $show;
}

/**
 * Get the unique identifier of the layout element, suchas
 * a widget area or a key from a repeatable field.
 *
 * @since 6.0
 */

function md_get_layout_id( $layout = 'sidebar', $args = array() ) {
	$layout_id = null;
	$default = "{$layout}-main";
	$post_type = isset( $args['post_type'] ) ? $args['post_type'] : md_get_post_type();
	$post_id = isset( $args['post_id'] ) ? $args['post_id'] : get_queried_object_id();
	$context_args = $args;
	$context_args['author_archive'] = true;
	$context_args['single_404'] = true;
	$page = md_layout_context( $context_args );

	if ( $page === 'archive' )
		$layout_id = md_post_type_field( array( 'layout', "{$layout}_archive" ), $default, $post_type );
	elseif ( $page === 'term' ) {
		$global = md_post_type_field( array( 'layout', "{$layout}_term" ), $default, $post_type );
		$layout_id = md_term_meta( array( 'layout', "custom_{$layout}" ), null, $global );
	}
	elseif ( $page === 'single' ) {
		$global = md_post_type_field( array( 'layout', "{$layout}_single" ), $default, $post_type );
		$single = md_post_meta( array( 'layout', "custom_{$layout}" ), $post_id );

		if ( ! empty( $single ) )
			$layout_id = $single;
		else {
			$visible = md_has_layout( $layout, array(
				'post_id' => $post_id,
				'post_type' => $post_type,
				'page' => 'single'
			) );

			if ( $visible ) {
				$term_layout = md_get_layout_term( $layout, $post_id );
				$layout_id = ! empty( $term_layout ) ? $term_layout : $global;
			}
			else $layout_id = $global;
		}
	}

	$areas = md_layout_areas( $layout );

	if ( ! empty( $areas[$layout_id] ) )
		return $layout_id;

	return $default;
}