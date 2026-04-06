<?php

/**
 * A list of available loop templates, selectable from admin.
 *
 * @since 6.0
 */

function md_filter_loops() {
	return apply_filters( 'md_filter_loops', array(
		'article' => array(
			'name' => __( 'Article view (default)', 'md' ),
			'description' => __( 'A traditional blog with a flexible layout and styles.', 'md' )
		)
	) );
}

/**
 * A list of available loop styles (applied to all templates),
 * selectable from admin.
 *
 * @since 6.0
 */

function md_filter_loop_styles() {
	return apply_filters( 'md_filter_loop_styles', array(
		'box' => __( 'Box style', 'md' ),
		'border' => __( 'Border style', 'md' ),
		'plain' => __( 'No style', 'md' )
	) );
}

/**
 * A list of Loops registered to MD's settings.
 *
 * @since 5.1
 */

function md_loops( $sort = null ) {
	$data = array();
	$loops = md_filter_loops();

	if ( isset( $sort ) ) {
		foreach ( $loops as $id => $fields ) {
			if ( isset( $fields['hide'] ) )
				continue;

			if ( $sort == 'ids' )
				$data[] = $id;
			elseif ( $sort == 'options' )
				$data[$id] = $fields['name'];
		}
	}
	else $data = $loops;

	return $data;
}

/**
 * Return loop data with context awareness and user-set
 * options blended with global post type level data.
 *
 * @since 6.0
 */

function md_get_loop( $args = array() ) {
	$args = is_array( $args ) ? $args : array();
	$loop = array();
	$loops = md_loops();

	// Set contextual data

	$post_type = md_post_type_field( 'loop', array() );
	$loop_type = md_post_type_field( array( 'loop', 'loop' ), 'article' );
	$single = md_module( 'loop', array() );

	if ( is_singular() || is_404() ) {
		$loop = $single;
		$loop['loop'] = $loop_type;
	}
	else
		$loop = array_merge( $post_type, $single );

	// Set defaults

	if ( ! empty( $loops[$loop_type]['defaults'] ) )
		$loop = array_merge( $loops[$loop_type]['defaults'], $loop );

	$loop['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	$loop['by_category'] = ! is_tax() && ! is_category() && ! empty( $loop['category_posts']['enable'] ) ? true : false;
	$loop['has_sidebar'] = md_has_sidebar();

	if ( empty( $loop['posts_per_page'] ) )
		$loop['posts_per_page'] = get_option( 'posts_per_page' );

	if ( empty( $loop['columns'] ) )
		$loop['columns'] = 1;

	if ( md_has_media() ) {
		$media = md_get_media();
		$loop['featured_image'] = $media['position'];

		if ( ! empty( $media['image']['id'] ) )
			$loop['featured_image_id'] = $media['image']['id'];
	}

	if ( ! empty( $args['sticky'] ) )
		$loop['sticky'] = true;

	if ( ! empty( $args['in_loop'] ) )
		$loop['in_loop'] = true;

	$loop['loop'] = isset( $loop['loop'] ) ? $loop['loop'] : 'article';
	$loop['loop_classes'] = md_loop_classes( $loop );

	return apply_filters( 'md_filter_set_loop', $loop );
}

/**
 * Hook custom content after Loop Item X.
 *
 * @since 5.1
 */

function md_hook_x_loop( $args, $c ) {
	$loop = $args['loop'];

	if ( ! empty( $loop['cta_x_loop'] ) && $c == $loop['cta_x_loop'] && $loop['paged'] == 1 )
		do_action( 'md_hook_x_loop', $loop );
}

/**
 * Build per-item loop settings from page-level defaults.
 *
 * @since 6.0
 */

function md_loop_item( $loop = array(), $c = 1 ) {
	if ( ! empty( $loop['excerpt_settings']['remove_text'] ) )
		$loop['read_more'] = '';
	elseif ( empty( $loop['read_more'] ) )
		$loop['read_more'] = __( 'Continue reading &rarr;', 'md' );

	if ( empty( $loop['excerpt_length'] ) )
		$loop['excerpt_length'] = 55;

	if ( ! empty( $loop['excerpt_settings']['remove_more'] ) )
		$loop['excerpt_more'] = '';
	elseif ( empty( $loop['excerpt_more'] ) )
		$loop['excerpt_more'] = '[...]';

	if ( md_has_media() ) {
		$media = md_get_media();
		$loop['featured_image'] = $media['position'];

		if ( ! empty( $media['image']['id'] ) )
			$loop['featured_image_id'] = $media['image']['id'];
	}
	else {
		unset( $loop['featured_image'] );
		unset( $loop['featured_image_id'] );
	}

	if ( ! isset( $loop['content'] ) )
		$loop['content'] = '';

	if ( ! empty( $loop['featured'] ) && $c <= $loop['featured'] )
		$loop = md_loop_featured( $loop );

	return $loop;
}

/**
 * Override portions of $loop when post is set to Featured.
 *
 * @since 6.0
 */

function md_loop_featured( $loop ) {
	$loop['is_featured'] = true;
	$featured_map = array(
		'featured_remove_byline' => 'remove_byline',
		'featured_content' => 'content',
		'featured_featured_image' => 'featured_image',
		'featured_excerpt_more' => 'excerpt_more',
		'featured_excerpt_length' => 'excerpt_length',
		'featured_read_more' => 'read_more',
		'featured_excerpt_settings' => 'excerpt_settings'
	);

	foreach ( $featured_map as $featured_key => $loop_key ) {
		if ( empty( $loop[$featured_key] ) )
			continue;

		$loop[$loop_key] = $loop[$featured_key];
		unset( $loop[$featured_key] );
	}

	if ( ! empty( $loop['featured_post_footer']['remove'] ) ) {
		$loop['post_footer']['remove'] = true;
		unset( $loop['featured_post_footer'] );
	}

	return $loop;
}

/**
 * Build loop wrapper classes.
 *
 * @since 6.0
 */

function md_loop_classes( $loop = array() ) {
	if ( empty( $loop ) )
		$loop = md_get_loop();

	$loop_type = isset( $loop['loop'] ) ? $loop['loop'] : 'article';
	$loop_class = 'loop-' . str_replace( '_', '-', md_get_post_type() );
	$loop_classes = array( 'loop', $loop_class, "loop-{$loop_type}" );

	if ( $loop['columns'] > 1 ) {
		$loop_classes[] = 'columns';
		$loop_classes[] = 'columns-' . $loop['columns'];

		if (
			$loop['columns'] >= 3 ||
			( $loop['columns'] == 2 && ( ! empty( $loop['has_sidebar'] ) || ! empty( $loop['by_category'] ) ) )
		)
			 $loop_classes[] = 'slim';
		else
			$loop_classes[] = 'full';
	}
	else {
		$loop_classes[] = 'row';
		$loop_classes[] = 'full';
	}
//	elseif ( ! $loop['by_category'] )
//		$loop_classes[] = 'full';

//	if ( ! empty( $loop['by_category'] ) && isset( $loop['category_columns'] ) && $loop['category_columns'] >= 2 )
//		$loop_classes[] = 'slim';

	$loop_classes = apply_filters( 'md_filter_loop_classes', $loop_classes );

	return join( ' ', $loop_classes );
}

/**
 * The Main Loop Logic loaded to all posts, pages, and archives.
 * Meant to mirror native WP page hierarchy and loop accordingly.
 *
 * @since 4.1
 * $args (Optional) set loop attributes
 */

function md_loop( $args = array() ) {
	$args = is_array( $args ) ? $args : array();
	$c = 1;
	$html = is_singular() ? 'div' : 'article';
	$loop = $loop_base = md_get_loop( $args );
	$loops = md_loops();
	$args = array_merge( $args, array( 'loop' => $loop ) );
	$loop_classes = $loop['loop_classes'];

	// Render Loop templates

	do_action( 'md_loop_before' );

	if ( ! empty( $loop['sticky'] ) || ! empty( $loop['in_loop'] ) )
		include md_template( 'loop/the-post', true );
	elseif ( $loop['by_category'] )
		include md_template( 'loop/category-posts', true );
	elseif ( isset( $args['query'] ) )
		include md_template( 'loop/the-query', true );
	elseif ( have_posts() ) {
		echo ! is_singular() ? '<section class="' . esc_attr( $loop_classes ) . '">' : '';

		md_hook_loop_top();

		while ( have_posts() ) {
			the_post();
			include md_template( 'loop/the-post', true );
		}

		if ( ! is_singular() ) {
			echo '</section>';
			md_pagination( $args );
		}
	}
	else md_404();

	do_action( 'md_loop_after' );
}

/**
 * Render the 404 template based on user settings.
 *
 * @since 4.0
 */

function md_404() {
	$page_404 = md_has_custom_404();

	if ( $page_404 ) {
		$query_404 = new WP_Query( array(
			'post_type' => 'page',
			'p' => $page_404,
			'post_status' => array( 'publish' ),
			'fields' => 'ids'
		) );

		if ( $query_404->have_posts() )
			while ( $query_404->have_posts() ) {
				$query_404->the_post();

				md_loop( array( 'in_loop' => true ) );
			}

		wp_reset_query();
	}
	else md_loop( array( 'in_loop' => true ) );
}

/**
 * Check if custom 404 page is enabled and published.
 *
 * @since 6.0
 */

function md_has_custom_404() {
	$page_404 = md_setting( array( 'settings', '404_page' ) );

	if ( is_404() && $page_404 && get_post_status( $page_404 ) )
		return $page_404;
}

/**
 * Create pagination for use on home and archives pages.
 *
 * @since 4.0
 */

function md_pagination( $args = array() ) {
	if ( is_singular() )
		return;

	$big = 999999999;
	$type = md_module( array( 'loop', 'pagination' ) );
	$classes = $type == 'prev_next' ? 'prev-next' : 'numbers';
	$loop = ! empty( $args['loop'] ) ? $args['loop'] : md_get_loop();

	if ( $loop['by_category'] ) {
		$taxonomies = get_object_taxonomies( md_get_post_type() );
		$taxonomy = ! empty( $taxonomies[0] ) ? $taxonomies[0] : '';
		$category_per_page = ! empty( $loop['category_per_page'] ) ? $loop['category_per_page'] : 5;
		$total_terms = wp_count_terms( $taxonomy, array( 'hide_empty' => true ) );
		$total = ceil( $total_terms / $category_per_page );
	}
	else {
		global $wp_query;
		$total = $wp_query->max_num_pages;
	}

	if ( $total <= 1 )
		return;

	include md_template( 'loop/pagination', true );
}