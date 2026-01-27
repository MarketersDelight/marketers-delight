<?php

// Glossary: $CONTENT | $SIDEBAR | $FOOTER

/**
 * Checks if page template is active.
 *
 * @since 4.9.4
 */

function md_filter_template() {
	return apply_filters( 'md_filter_has_template', true );
}


/*------------------------------*\
	$CONTENT
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
	if ( md_has_content_box() ) {
		$html = $inner_html = 'div';

		if ( md_has_header_cover() )
			$html = 'main';
		elseif ( ! is_singular() && md_has_sidebar() )
			$inner_html = 'main';

		include md_template( 'content-box', true );
	}
}

/**
 * A list of classes to add to the content box container.
 *
 * @since 4.1
 */

function md_content_box_classes( $classes = array() ) {
	$classes[] = 'content';
	$style = md_content_style();

	if ( $style )
		$classes[] = $style;

	if ( md_has_sidebar() ) {
		$classes[] = 'compact';
		$default_layout = md_post_type_field( array( 'layout', 'content_box' ) );
		$layout = md_meta( array( 'layout', 'content_box' ), get_queried_object_id(), $default_layout );

		if ( $layout == 'sidebar_content' )
			$classes[] = 'left';
	}
	else $classes[] = 'expanded';

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
	$classes[] = 'content-wrap';

	if ( ! md_has_sidebar() )
		$classes[] = 'inner';

	if ( is_singular() || is_404() ) {
		$classes[] = 'row';
		$classes[] = 'full';
	}

	$classes = apply_filters( 'md_filter_content_classes', $classes );

	return join( ' ', $classes );
}

/**
 * Calculate classes to apply to post box.
 * Returns a string ($classes) to be used in post_class( $classes ) in template.
 *
 * @since 6.0
 */

function md_post_class( $loop, $c = 0 ) {
	$classes = array( 'entry' );

	if ( isset( $loop['featured'] ) )
		if ( isset( $loop['is_featured'] ) )
			$classes[] = 'featured';
		else
			$classes[] = 'standard';

	if ( ! is_singular() )
		$classes[] = $c % 2 == 0 ? 'even' : 'odd';

	if ( isset( $loop['featured_image_id'] ) && ! in_array( $loop['featured_image'], array( 'remove', 'title_left', 'title_right' ) ) ) {
		$position = $loop['featured_image'];
		$classes[] = 'image-' . str_replace( '_headline', '', $position );

		if ( in_array( $position, array( 'left', 'right' ) ) )
			$classes[] = 'image-inline';
		elseif ( in_array( $position, array( 'center', 'above_headline', 'below_headline' ) ) )
			$classes[] = 'image-full';
		elseif ( in_array( $position, array( 'title_left', 'title_right' ) ) )
			$classes[] = 'image-title';
	}

	if ( md_cover() )
		$classes[] = 'has-cover';

	return join( ' ', $classes );
}

/**
 * Determine the current content style.
 * $args are passed as $loop from Loop functions.
 *
 * @since 5.1
 */

function md_content_style( $args = array() ) {
	$style = md_setting( array( 'colors', 'design' ), 'box-style' );
	$post_type = md_post_type_field( array( 'layout', 'content_style' ) );
	$single = md_module( array( 'layout', 'content_style' ), $post_type, get_queried_object_id() );

	if ( isset( $args['style'] ) )
		$style = $args['style'];
	elseif ( $single && ! isset( $args['global'] ) )
		$style = $single;

	if ( $style == 'none' )
		$style = '';

	return str_replace( '_', '-', $style );
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


/*------------------------------*\
	$SIDEBAR
\*------------------------------*/

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
	$sidebar = null;
	$default = 'sidebar-main';
	$sidebars = md_get_sidebars();

	if ( is_home() || is_post_type_archive() || is_author() )
		$sidebar = md_post_type_field( array( 'layout', 'sidebar_archive' ), $default );
	elseif ( is_category() || is_tax() ) {
		$global = md_post_type_field( array( 'layout', 'sidebar_term' ), $default );
		$sidebar = md_term_meta( array( 'layout', 'custom_sidebar' ), null, $global );
	}
	elseif ( is_singular() || is_404() ) {
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
		else $sidebar = $single;
	}

	return ( ! empty( $sidebars[$sidebar] ) ? $sidebar : $default );
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