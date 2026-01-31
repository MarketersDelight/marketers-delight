<?php

/**
 * A list of available loop templates, selectable from admin.
 *
 * @since 6.0
 */

function md_filter_loops() {
	return apply_filters( 'md_filter_loops', array(
		'post' => array(
			'name' => __( 'Post loop', 'md' ),
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
		'box_style' => __( 'Box style', 'md' ),
		'border_style' => __( 'Border style', 'md' ),
		'none' => __( 'No style', 'md' )
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
 * The Main Loop Logic loaded to all posts, pages, and archives.
 * Meant to mirror native WP page hierarchy and loop accordingly.
 *
 * @since 4.1
 *
 * $args (Optional) set loop attributes
 * $data (Optional) gets passed data from md_hook_content,
 *       and is second argument when used in add_action
 */

function md_loop( $args = array() ) {
	$c = 1;
	$html = 'article';
	$categories_classes = array( 'categories' );
	$post_type = isset( $args['post_type'] ) ? $args['post_type'] : md_get_post_type();

	if ( is_singular() || is_404() )
		if ( md_has_header_cover() )
			$html = 'div';
		else
			$html = 'main';

	$loop_classes = array( 'loop' );
	$loops = md_loops();
	$loop = md_post_type_field( 'loop', array() );

	if ( is_singular() || is_404() || is_category() || is_tax() )
		$loop = md_module( 'loop', array() );

	$loop = apply_filters( 'md_filter_set_loop', $loop );
	$loop_type = isset( $loop['loop'] ) ? $loop['loop'] : 'post';

	if ( isset( $loops[$loop_type]['data'] ) )
		$data = $loop['data'] = $loops[$loop_type]['data'];

	if ( ! empty( $loops[$loop_type]['defaults'] ) )
		$loop = array_merge( $loops[$loop_type]['defaults'], $loop );

	$loop['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	$loop['by_category'] = ! is_tax() && ! is_category() && ! empty( $loop['category_posts']['enable'] ) ? true : false;
	$loop['has_sidebar'] = md_has_sidebar();

	if ( isset( $args['in_loop'] ) )
		$loop['in_loop'] = true;

	if ( isset( $args['sticky'] ) )
		$loop['sticky'] = true;

	if ( empty( $loop['posts_per_page'] ) )
		$loop['posts_per_page'] = get_option( 'posts_per_page' );

	if ( empty( $loop['columns'] ) )
		$loop['columns'] = 1;

//	$loop = wp_parse_args( $args, $loop );

	$loop_classes[] = 'loop-' . str_replace( '_', '-', $post_type );

	if ( $loop_type !== $post_type )
		$loop_classes[] = "loop-{$loop_type}";

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
	$loop_classes = join( ' ', $loop_classes );

	$looped = $loop;

	do_action( 'md_loop_before' );

	if ( isset( $loop['sticky'] ) || isset( $loop['in_loop'] ) )
		include md_template( 'loop/the-post', true );
	elseif ( $loop['by_category'] )
		include md_template( 'loop/category-posts', true );
	elseif ( isset( $args['query'] ) ) {
		echo "<div class=\"$loop_classes\">";
		include md_template( 'loop/the-query', true );
		echo '</div>';
	}
	elseif ( have_posts() ) {
		echo ! is_singular() ? "<div class=\"$loop_classes\">" : '';

		md_hook_loop_top();

		while ( have_posts() ) {
			the_post();
			include md_template( 'loop/the-post', true );
		}

		if ( ! is_singular() ) {
			echo '</div>';
			md_pagination( $loop );
		}
	}
	else md_404();

	do_action( 'md_loop_after' );
}

/**
 * Hook custom content after Loop Item X.
 *
 * @since 5.1
 */

function md_hook_x_loop( $loop, $c ) {
	if ( ! empty( $loop['cta_x_loop'] ) && $c == $loop['cta_x_loop'] && $loop['paged'] == 1 )
		do_action( 'md_hook_x_loop', $loop );
}

/**
 * Set default values of an individual item in a loop.
 *
 * @since 6.0
 */

function md_loop_post( $loop, $c ) {
	$image = md_get_image();

	if ( ! empty( $loop['featured'] ) && $c <= $loop['featured'] )
		$loop = md_loop_featured( $loop );

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

	$loop['featured_image'] = '';

	if ( ! empty( $image['id'] ) ) {
		$loop['featured_image_id'] = $image['id'];
		$loop['featured_image'] = $image['position'];
	}

	if ( ! isset( $loop['content'] ) )
		$loop['content'] = '';

	return $loop;
}

/**
 * Override portions of $loop when post is set to Featured.
 *
 * @since 6.0
 */

function md_loop_featured( $loop ) {
	$loop['content'] = '';
	$loop['is_featured'] = true;

	if ( ! empty( $loop['featured_remove_byline'] ) ) {
		$loop['remove_byline'] = $loop['featured_remove_byline'];
		unset( $loop['featured_remove_byline'] );
	}

	if ( ! empty( $loop['featured_post_footer']['remove'] ) ) {
		$loop['post_footer']['remove'] = true;
		unset( $loop['featured_post_footer'] );
	}

	if ( ! empty( $loop['featured_content'] ) ) {
		$loop['content'] = $loop['featured_content'];
		unset( $loop['featured_content'] );
	}

	if ( ! empty( $loop['featured_featured_image'] ) ) {
		$loop['is_featured'] = true;
		$loop['featured_image'] = $loop['featured_featured_image'];
		unset( $loop['featured_featured_image'] );
	}

	if ( ! empty( $loop['featured_excerpt_more'] ) ) {
		$loop['excerpt_more'] = $loop['featured_excerpt_more'];
		unset( $loop['featured_excerpt_more'] );
	}

	if ( ! empty( $loop['featured_excerpt_length'] ) ) {
		$loop['excerpt_length'] = $loop['featured_excerpt_length'];
		unset( $loop['featured_excerpt_length'] );
	}

	if ( ! empty( $loop['featured_read_more'] ) ) {
		$loop['read_more'] = $loop['featured_read_more'];
		unset( $loop['featured_read_more'] );
	}

	if ( ! empty( $loop['excerpt_settings'] ) ) {
		$loop['excerpt_settings'] = $loop['featured_excerpt_settings'];
		unset( $loop['featured_excerpt_settings'] );
	}

	return $loop;
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

function md_pagination( $loop = array() ) {
	if ( is_singular() )
		return;

	$big = 999999999;
	$type = md_module( array( 'loop', 'pagination' ) );
	$classes = $type == 'prev_next' ? 'prev-next' : 'numbers';

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