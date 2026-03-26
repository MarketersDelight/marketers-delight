<?php

/**
 * Render the inner content of a title.
 *
 * @since 6.0
 */

function md_the_title( $context = 'post', $args = array() ) {
	$h = 'h1';
	$title = apply_filters( "md_{$context}_title", md_get_title( $context ) );
	$category_posts = md_module( array( 'loop', 'category_posts', 'enable' ) );

	if ( $context == 'post' && ! is_singular() && ! is_404() ) {
		$h = $category_posts ? 'h3' : 'h2';
		$title = '<a href="' . get_permalink() . '">' . $title . '</a>';
	}

	include md_template( 'title', true );
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
		$title = md_post_type_field( 'archives_title', $post_type_title );
	}
	elseif ( is_tax() && get_queried_object() ) {
		$term_title = single_term_title( '', false );
		$title = md_term_meta( array( 'hero', 'archives_title' ), null, $term_title );
	}
	elseif ( is_category() ) {
		$cat_title = single_cat_title( '', false );
		$title = md_term_meta( array( 'hero', 'archives_title' ), null, $cat_title );
	}
	elseif ( is_tag() )
		$title = single_tag_title( '', false );
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

	return $title;
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

function md_title( $context = 'post' ) {
	if ( ! md_get_title( $context ) )
		return;

	$has_header_cover = md_has_header_cover( $context );

	if ( $has_header_cover && in_the_loop() )
		return;

	$is_inline = false;
	$style = array();
	$classes = array( '' );
	$inline_images = array( 'left', 'right' );
	$title_images = array( 'title_left', 'title_right', 'title_center' );
	$full_width = array( 'center', 'above_headline', 'below_headline' );

	$post_type_loop = md_post_type_field( 'loop', array() );
	$loop = array_merge( $post_type_loop, md_module( 'loop', array() ) );

	$media = md_has_media( $context );
	$cover = md_cover( $context );
	$has_sidebar = md_has_sidebar();
	$has_wrap = $media && ! in_array( $media['position'], $full_width ) ? true : false;

	if ( ( $has_sidebar && ! $has_header_cover ) || ( $context == 'post' && ! is_singular() && isset( $loop['columns'] ) && $loop['columns'] > 2 ) ) {
		$is_inline = true;
		$classes[] = 'inline';
	}
	else $classes[] = 'wide';

	if ( $media && ( $context == 'page' || ( $context == 'post' && in_array( $media['position'], $title_images ) ) ) ) {
		$class_name = 'image-' . $media['position'];

		if ( in_array( $media['position'], $inline_images ) )
			$classes[] = 'image-inline';
		elseif ( in_array( $media['position'], $full_width ) ) {
			$classes[] = 'image-full';
			$class_name = str_replace( '_headline', '', $media['position'] );
		}
		elseif ( in_array( $media['position'], $title_images ) ) {
			$classes[] = 'image-title';
			$class_name = str_replace( '_', '-', $media['position'] );
		}

		$classes[] = $class_name;
	}

	if ( ! empty( $cover['position'] ) ) {
		$classes[] = md_cover_classes( $context );

		if ( ! empty( $cover['photo']['url'] ) )
			$style['bg_image'] = esc_url( $cover['photo']['url'] );
	}

	$classes = join( ' ', $classes );
	$style = md_style( $style );

	do_action( "md_hook_{$context}_title_before" );
	include md_template( "{$context}-title", true );
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

	if ( $context == 'post' && is_singular() ) {
		$show_excerpt = md_post_type_field( array( 'page_cover', 'display', 'show_excerpt' ) );

		if ( has_excerpt() && $show_excerpt )
			$description = get_the_excerpt();

		$description = md_post_meta( array( 'page_cover', 'title_content' ), null, $description );
	}
	elseif ( $context == 'page' )
		if ( is_post_type_archive() || is_home() )
			$description = md_post_type_field( 'archives_text' );
		elseif ( ( is_category() || is_tax() ) && get_queried_object() ) {
			$category_description = category_description();
			$description = md_term_meta( array( 'hero', 'archives_text' ), null, $category_description );
		}
		elseif ( is_author() )
			$description = get_the_author_meta( 'description' );

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

    if ( empty( $cta ) )
        if ( $context == 'page' )
            $cta = md_module( 'page_cta' );
        else {
            if ( ! is_singular() )
                return;

            $cta = md_post_meta( 'page_cta' );
        }

    $type = ! empty( $cta['page_cta'] ) ? $cta['page_cta'] : '';

    if ( $type == 'links' && ! empty( $cta['links'] ) ) {
		foreach ( $cta['links'] as $group => $fields )
			if ( ! empty( $cta['links'][$group] ) ) {
				$cta['links'][$group]['classes'] = 'cta-link';
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
		'display' => '',
		'user' => '',
		'popup' => '',
		'classes' => '',
		'button_style' => array(),
		'toggle' => array(
			'hide_label' => '',
			'hide_label_mobile' => ''
		)
	) );

	if ( ( $fields['user'] == 'logged_out' && is_user_logged_in() ) || ( $fields['user'] == 'logged_in' && ! is_user_logged_in() ) )
		return;

	if ( empty( $fields['name'] ) && empty( $fields['icon'] ) )
		return;

	include md_template( 'link', true );

	return $html;
}

function md_link( $fields, $p = '' ) {
	echo md_get_link( $fields, $p );
}

/**
 * Get list of items that can be used in a Byline.
 *
 * @since 6.0
 */

function md_byline_items() {
	return apply_filters( 'md_byline', array() );
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

	$c = 1;
	$classes = array( 'byline' );
	$classes[] = str_replace( '_', '-', $location );
	$html = isset( $args['html'] ) ? $args['html'] : 'div';
	$total = count( $items );
	$data = md_byline_items();

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
	$loop = md_loop_options();

	// Skip build if no byline on page

	if ( ! empty( $loop['remove_byline'][$position] ) || ! empty( $loop['remove_byline']['remove'] ) )
		return $byline;

	// Build data from user options based on page type in WP

	$builder = md_post_type_field( array( 'byline', 'builder' ), array() );

	if ( is_category() || is_tax() )
		$builder = md_term_meta( array( 'byline', 'builder' ), null, $builder );

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
		$context = is_home() || is_archive() ? 'archives' : 'single';

		foreach ( $builder as $id => $fields )
			if ( $context == $fields['builder_area'] && $position == $fields['position'] ) {
				$type = $fields['builder_type'];
				$byline[$type][$id] = $fields;
				$byline[$type][$id]['id'] = $id;
			}
	}

	return $byline;
}