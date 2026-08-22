<?php

/**
 * Render the inner content of a title.
 *
 * @since 6.0
 */

function md_the_title( $context = 'post', $args = array() ) {
	$h = 'h1';
	$title = apply_filters( "md_{$context}_title", md_get_title( $context ) );

	if ( $context == 'post' && ! is_singular() && ! is_404() ) {
		$h = ! empty( $args['loop']['by_category'] ) ? 'h3' : 'h2';

		if ( ! empty( $title ) )
			$title = '<a href="' . get_permalink() . '">' . $title . '</a>';
	}

	include md_template( 'the-title', true );
}

/**
 * Get the plaintext title of the current page based on content type.
 *
 * @since 6.0
 */

function md_get_title( $context = 'post' ) {
	$title = get_the_title();

	if ( $context == 'post' ) {
		if ( is_404() && ! md_has_custom_404() )
			$title = __( 'Page not found', 'md' );

		return $title;
	}

	if ( is_home() || is_post_type_archive() || is_singular( 'post' ) ) {
		$post_type_title = post_type_archive_title( '', false );
		$title = md_parse_text( md_post_type_field( 'archives_title', $post_type_title ), 'archive' );
	}
	elseif ( is_tax() || is_category() || is_tag() ) {
		$title = md_term_meta( array( 'hero', 'archives_title' ) );

		if ( ! $title )
			$title = md_module( 'archives_title', single_term_title( '', false ), array( 'inherit_post_type' => false ) );

		$title = md_parse_text( $title, 'term' );
	}
	elseif ( is_search() )
		$title = sprintf( esc_html__( 'Search Results For: %s', 'md' ), '<span class="search-query">' . get_search_query() . '</span>' );
	elseif ( is_author() )
		$title = get_the_author();
	elseif ( is_year() )
		$title = get_the_date( 'Y' );
	elseif ( is_month() )
		$title = get_the_date( 'F Y' );
	elseif ( is_day() )
		$title = get_the_date( 'F j, Y' );

	return wp_kses_post( trim( (string) $title ) );
}

/**
 * Render the layout markup of the Post or Page title with supporting
 * elements like image, byline, etc. A title used within a loop
 * has key differences than when rendered as the main Page Title.
 *
 * $context accepts 'post' by default or 'page'
 *
 * @since 6.0
 */

function md_title( $context = 'post', $args = array() ) {
	if ( ! apply_filters( "md_has_{$context}_title", true, $args ) )
		return;

	if ( ! md_get_title( $context ) && $context !== 'post' )
		return;

	$has_header_cover = md_has_header_cover( $context );

	if ( $has_header_cover && in_the_loop() )
		return;

	$classes = $style = array();
	$inline_images = array( 'left', 'right' );
	$title_images = array( 'title_left', 'title_right', 'title_center' );
	$full_width = array( 'center', 'above_headline', 'below_headline' );
	$loop = md_get_loop();
	$media = md_has_media( $context, array( 'loop' => $loop ) );
	$cover = md_cover( $context );
	$has_sidebar = md_has_sidebar();
	$has_wrap = $context == 'page' && $media && ! in_array( $media['position'], $full_width, true );
	$args['loop'] = ! empty( $args['loop'] ) ? $args['loop'] : array();

	// Layout type classes

	if ( ! $has_header_cover && ( ( $context === 'page' && $has_sidebar ) || ( $context !== 'page' && ! empty( $loop['is_slim'] ) ) ) )
		$classes[] = 'inline';
	else
		$classes[] = 'wide';

	// Featured image related classes

	if ( $media && ( $context == 'page' || ( $context == 'post' && in_array( $media['position'], $title_images, true ) ) ) )
		$classes = array_merge( $classes, md_get_image_position_classes( $media['position'] ) );

	// Page cover classes

	if ( $context == 'post' )
		$classes[] = 'item';

	if ( ! empty( $cover['position'] ) ) {
		$classes[] = md_cover_classes( $context );

		if ( ! empty( $cover['photo']['id'] ) )
			$style['bg_image'] = wp_get_attachment_image_url( $cover['photo']['id'], 'full' );
		elseif ( ! empty( $cover['bg_color'] ) )
			$style['bg_color'] = $cover['bg_color'];
	}

	$classes = join( ' ', $classes );
	$style = md_style( $style );

	// Render title

	do_action( "md_hook_{$context}_title_before" );

	include md_template( 'title', true );

	do_action( "md_hook_{$context}_title_after" );
}

/**
 * Show Description of current page.
 *
 * @since 6.0
 */

function md_description( $context = 'post', $args = array() ) {
	if ( ! apply_filters( "md_has_{$context}_title_description", true ) )
		return;

	$description = '';

	if ( ! empty( $args['description'] ) )
		$description = $args['description'];
	elseif ( $context == 'post' && is_singular() ) {
		$excerpt = md_post_type_field( array( 'page_cover', 'display', 'show_excerpt' ) ) && has_excerpt() ? get_the_excerpt() : '';
		$description = md_post_meta( array( 'page_cover', 'title_content' ), null, $excerpt );
	}
	elseif ( $context == 'page' ) {
		if ( is_post_type_archive() || is_home() )
			$description = md_parse_text( md_post_type_field( 'archives_text' ), 'archive' );
		elseif ( ( is_category() || is_tax() ) && get_queried_object() ) {
			$description = md_term_meta( array( 'hero', 'archives_text' ) );

			if ( ! $description )
				$description = category_description();

			if ( ! $description )
				$description = md_taxonomy_field( 'archives_text' );

			$description = md_parse_text( $description, 'term' );
		}
		elseif ( is_author() )
			$description = get_the_author_meta( 'description' );
	}

	if ( empty( $description ) )
		return;

	include md_template( 'description', true );
}

/**
 * Get Hero/inline CTA of any given page. A CTA can be a
 * link group, email form, custom HTML, or more.
 *
 * @since 6.0
 */

function md_cta( $context = 'post', $cta = array() ) {
	if ( ! apply_filters( "md_has_{$context}_cta", true ) )
		return;

	$html = '';

	if ( empty( $cta ) ) {
		if ( $context == 'page' )
			$cta = md_module( 'page_cta' );
		elseif ( $context == 'post' && is_singular() )
			$cta = md_post_meta( 'page_cta' );
		else
			return;
	}

	$type = ! empty( $cta['page_cta'] ) ? $cta['page_cta'] : '';

	if ( $type == 'links' && ! empty( $cta['links'] ) ) {
		foreach ( $cta['links'] as $group => $fields )
			if ( ! empty( $cta['links'][$group] ) ) {
				$cta['links'][$group]['classes'][] = 'cta-link';
				$html .= md_get_link( $cta['links'][$group] );
			}
	}
	elseif ( $type == 'custom' && ! empty( $cta['custom_html'] ) )
		$html = $cta['custom_html'];
	elseif ( ! empty( $type ) )
		$html = apply_filters( "md_cta_{$type}", $context, $cta );

	if ( empty( $html ) )
		return;

	include md_template( 'cta', true );
}

/**
 * Easily output a link/button with different kind of action.
 *
 * @since 4.3.5
 */

function md_get_link( $fields, $p = '' ) {
	$html = $attrs = '';
	$styles = array();
	$fields = wp_parse_args( $fields, array(
		'type' => 'url',
		'style' => 'link',
		'area' => '',
		'name' => '',
		'subtitle' => '',
		'icon' => '',
		'url' => '',
		'phone' => '',
		'size' => '',
		'color' => '',
		'popup' => '',
		'popup_args' => array(),
		'html' => '',
		'content' => '',
		'attributes' => array(),
		'classes' => '',
		'visibility' => array(),
		'button_style' => array(),
		'toggle' => array(
			'hide_label' => '',
			'hide_label_mobile' => ''
		)
	) );

	if ( empty( $fields['type'] ) )
		$fields['type'] = 'url';

	if ( ! md_check_condition( $fields['visibility'] ) )
		return;

	if ( empty( $fields['name'] ) && empty( $fields['icon'] ) && empty( $fields['content'] ) )
		return;

	include md_template( 'link', true );

	return $html;
}

function md_link( $fields, $p = '' ) {
	echo md_get_link( $fields, $p );
}

/**
 * Get list of items that can be used in a Byline, optionally
 * limited to items available for a post type.
 *
 * @since 6.0
 */

function md_byline_items( $post_type = null ) {
	$items = apply_filters( 'md_byline', array() );

	if ( $post_type === null )
		return $items;

	foreach ( $items as $id => $fields )
		if ( ! empty( $fields['post_types'] ) && ( ! $post_type || ! in_array( $post_type, (array) $fields['post_types'], true ) ) )
			unset( $items[$id] );

	return $items;
}

/**
 * Call the template of a single byline item with
 * passable settings.
 *
 * @since 6.0
 */

function md_byline_item( $type, $fields = array() ) {
	include md_template( "byline/$type", true );
}

/**
 * Render the byline template with designated items.
 *
 * @since 4.0
 */

function md_byline( $location = 'before_title', $args = array() ) {
	if ( has_action( 'md_hook_byline_' . get_post_type() ) )
		return do_action( 'md_hook_byline_' . get_post_type(), true );

	$items = md_get_byline( $location, $args );

	if ( empty( $items ) )
		return;

	$post_type = $args['loop']['post_type'] ?? get_post_type();
	$data = md_byline_items();

	foreach ( $items as $item => $groups )
		if ( ! empty( $data[$item]['post_types'] ) && ( ! $post_type || ! in_array( $post_type, (array) $data[$item]['post_types'], true ) ) )
			unset( $items[$item] );

	if ( empty( $items ) )
		return;

	$c = 1;
	$classes = array( 'byline' );
	$classes[] = str_replace( '_', '-', $location );
	$html = isset( $args['html'] ) ? $args['html'] : 'div';
	$total = count( $items );
	if ( isset( $args['classes'] ) )
		$classes[] = $args['classes'];

	if ( $total >= 3 )
		$classes[] = 'can-wrap';

	if ( ! empty( $items['share'] ) )
		$classes[] = 'has-share';

	$classes = join( ' ', $classes );

	include md_template( 'byline/byline', true );
}

/**
 * Get byline items based on location and source.
 *
 * Accepts: before_title | after_title | before_post | after_post
 *
 * @since 6.0
 */

function md_get_byline( $position, $args = array() ) {
	$byline = $items = array();
	$loop = ! empty( $args['loop'] ) ? $args['loop'] : md_get_loop();

	// Skip build if no byline on page

	if ( ! empty( $loop['remove_byline'][$position] ) || ! empty( $loop['remove_byline']['remove'] ) )
		return $byline;

	// Build data from user options based on page type in WP

	$builder = md_post_type_field( array( 'byline', 'builder' ), array() );

	if ( is_category() || is_tax() )
		$builder = md_module( array( 'byline', 'builder' ), $builder );

	// Check if items set manually in $args, or show default items while options empty

	if ( isset( $args['items'] ) )
		$items = $args['items'];
	elseif ( isset( $args['default'] ) && empty( $builder ) )
		$items = $args['default'];

	// Finally, format data for easy usage

	if ( $items )
		foreach ( $items as $item => $item_fields )
			$byline[$item][] = $item_fields;
	else {
		$context = ( is_home() || is_archive() ? 'archives' : 'single' );
		$context = ! empty( $args['context'] ) ? $args['context'] : $context;

		// Single inherits Archives builder entirely if empty

		if ( $context === 'single' ) {
			$has_single = false;

			foreach ( $builder as $fields )
				if ( $fields['builder_area'] === 'single' ) {
					$has_single = true;
					break;
				}

			if ( ! $has_single )
				$context = 'archives';
		}

		// Build bylines by area

		foreach ( $builder as $id => $fields )
			if ( $context == $fields['builder_area'] && $position == $fields['position'] ) {
				$type = $fields['builder_type'];
				$byline[$type][$id] = $fields;
				$byline[$type][$id]['id'] = $id;
			}
	}

	return apply_filters( 'md_filter_byline_items', $byline, $position, $args );
}
