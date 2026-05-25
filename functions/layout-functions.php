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
	if ( md_has_content_box() )
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

		$show_on_left = md_post_type_field( array( 'layout', 'sidebar', 'alt' ) );
		$single = md_meta( array( 'layout', 'sidebar', 'alt' ) );

		if ( ( $show_on_left && empty( $single ) ) || ( empty( $show_on_left ) && $single ) )
			$classes[] = 'left';
	}
	else $classes[] = 'expanded';

	$classes[] = 'format';
	$classes = apply_filters( 'md_filter_content_box_classes', $classes );

	return join( ' ', $classes );
}



/*------------------------------*\
	$SIDEBAR
\*------------------------------*/

/**
 * Return a list of sidebar names by unique IDs.
 *
 * @since 4.6.2
 */

function md_get_sidebars( $keys = null ) {
	return md_layout_areas( 'sidebar', $keys );
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
 * Get active sidebar ID for current page.
 *
 * @since 4.6.2.1
 */

function md_get_sidebar_id( $args = array() ) {
	return md_get_layout_id( 'sidebar', $args );
}



/*------------------------------*\
	$PANEL
\*------------------------------*/

/**
 * Return a list of panel names by unique IDs.
 *
 * @since 6.0
 */

function md_get_panels( $keys = null ) {
	return md_layout_areas( 'panel', $keys );
}

/**
 * Check if page has a panel.
 *
 * @since 6.0
 */

function md_has_panel( $args = array() ) {
	$show = md_has_layout( 'panel', $args );

	return apply_filters( 'md_filter_has_panel', $show, $args );
}

/**
 * Get widget ID of panel on current page.
 *
 * @since 6.0
 */

function md_get_panel_id( $args = array() ) {
	return md_get_layout_id( 'panel', $args );
}

/**
 * Render the acttual panel template.
 *
 * @since 6.0
 */

function md_panel() {
	include md_template( 'panel', true );
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
	$post_id = isset( $args['post_id'] ) ? $args['post_id'] : get_queried_object_id();
	$post_type = isset( $args['post_type'] ) ? $args['post_type'] : md_get_post_type();
	$page = md_layout_context( $args );

	// If not template-hierarchy detected, suchas admin, set to single page view.
	if ( empty( $page ) )
		$page = 'single';

	$display_key = sprintf( "{$layout}_%s_show", $page );
	$display = md_post_type_field( array( 'layout', $display_key ), array(), $post_type );
	$global = md_post_type_field( array( 'layout', $layout, 'global' ), null, $post_type );
	$single = md_meta( array( 'layout', $layout ), $post_id, array() );
	$display_disabled = ! empty( $display['disable'] );
	$display_enabled = ! empty( $display['enable'] );
	$single_add = ! empty( $single['add'] );
	$single_remove = ! empty( $single['remove'] );

	if ( isset( $args['exclude_single'] ) ) {
		// Used in admin screens: ignore single overrides and only use global + page-type enable/disable rules
		if ( ( $global && ! $display_disabled ) || ( ! $global && $display_enabled ) )
			$show = true;
	}
	elseif ( $global ) {
		// Enabled via global setting, so check for term-level or single override
		if ( ! $display_disabled && ! $single_add && ! $single_remove )
			$show = true;
	}
	elseif ( ( $display_enabled && ! $single_remove ) || $single_add ) {
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