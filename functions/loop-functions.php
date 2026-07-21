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
		),
		'list' => array(
			'name' => __( 'List view', 'md' ),
			'description'  => __( 'A simple list with a condensed post listing.', 'md' ),
			'style_target' => 'group',
			'classes' => 'slim'
		)
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
 * Determine the current loop/content box style.
 *
 * @since 5.1
 */

function md_loop_style( $args = array() ) {
	$body = md_setting( array( 'colors', 'design' ), 'box' );

	if ( isset( $args['body'] ) )
		return $body;

	$post_type = md_post_type_field( array( 'layout', 'content_style' ) );
	$style = md_module( array( 'layout', 'content_style' ), $post_type, get_queried_object_id() ) ?: $body;

	return $style;
}

/**
 * Build loop wrapper classes.
 *
 * @since 6.0
 */

function md_loop_classes( $loop = array() ) {
	if ( empty( $loop ) )
		$loop = md_get_loop();

	$loops = md_loops();
	$post_type = ! empty( $loop['post_type'] ) ? $loop['post_type'] : md_get_post_type();
	$post_type = str_replace( '_', '-', $post_type );
	$style = $loop['style'] ?? md_loop_style();
	$target = $loop['style_target'];

	// Loop classes

	$classes = array( 'loop', "loop-$post_type", 'loop-' . $loop['loop'], "{$style}-style", "{$style}-{$target}" );

	if ( $loop['columns'] > 1 ) {
		$classes[] = 'columns';
		$classes[] = 'columns-' . $loop['columns'];
		$classes[] = ! empty( $loop['is_slim'] ) ? 'slim' : 'full';
	}
	else {
		$classes[] = 'row';
		$classes[] = 'full';
	}

	$classes = array_merge( $classes,
		(array) ( $loops[$loop['loop']]['classes'] ?? array() ),
		(array) ( $loop['classes'] ?? array() )
	);

	$classes = array_unique( apply_filters( 'md_filter_loop_classes', $classes ) );

	// Category wrapper classes

	$category_type = 'category-' . ( $loop['loop_type'] == 'category_posts' ? 'posts' : 'view' );
	$category_columns = ! empty( $loop['category_columns'] ) ? (int) $loop['category_columns'] : 1;

	$categories_classes = array( 'categories', $category_type, 'category-' . $loop['loop'], "{$style}-style" );

	if ( $category_columns > 1 ) {
		$categories_classes[] = 'columns';
		$categories_classes[] = 'columns-' . $category_columns;
		$categories_classes[] = ( $category_columns >= 3 || ! empty( $loop['has_sidebar'] ) ) ? 'slim' : 'full';
	}
	else {
		$categories_classes[] = 'row';
		$categories_classes[] = 'full';
	}

	$categories_classes = array_unique( apply_filters( 'md_filter_category_loop_classes', $categories_classes ) );

	$category_classes = array( 'entry' );

	if ( $loop['loop_type'] == 'category' )
		$category_classes[] = "{$style}-{$target}";

	// Return class sets

	return array(
		'loop' => join( ' ', $classes ),
		'categories' => join( ' ', $categories_classes ),
		'category' => join( ' ', $category_classes )
	);
}

/**
 * Calculate classes to apply to post box.
 * Returns a string ($classes) to be used in post_class( $classes ) in template.
 *
 * @since 6.0
 */

function md_post_class( $loop = array(), $c = 1 ) {
	$classes = array( 'entry' );
	$cover = md_cover();
	$loop = ! empty( $loop ) ? $loop : md_get_loop();

	if ( ! empty( $loop['featured'] ) && isset( $loop['is_featured'] ) )
		$classes[] = 'featured';
	else
		$classes[] = 'standard';

	if ( ! is_singular() )
		$classes[] = $c % 2 == 0 ? 'even' : 'odd';

	if ( ! is_singular() && is_sticky() )
		$classes[] = 'sticky';

	if ( isset( $loop['featured_image'] ) && ! in_array( $loop['featured_image'], array( 'remove', 'title_left', 'title_right', 'title_center' ) ) ) {
		$position = $loop['featured_image'];
		$classes[] = 'image-' . str_replace( '_headline', '', $position );

		if ( in_array( $position, array( 'left', 'right', 'center' ) ) )
			$classes[] = 'image-inline';
		elseif ( in_array( $position, array( 'above_headline', 'below_headline' ) ) )
			$classes[] = 'image-full';
		elseif ( in_array( $position, array( 'title_left', 'title_right', 'title_center' ) ) )
			$classes[] = 'image-title';
	}

	if ( isset( $cover['position'] ) )
		$classes[] = 'has-cover';

	return join( ' ', $classes );
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

	if ( md_has_media() && ! isset( $loop['featured_image'] ) ) {
		$media = md_get_media();
		$loop['featured_image'] = $media['position'];

		if ( ! empty( $media['image']['id'] ) )
			$loop['featured_image_id'] = $media['image']['id'];
	}
	else unset( $loop['featured_image_id'] );

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
	$featured = array(
		'featured_remove_byline' => 'remove_byline',
		'featured_content' => 'content',
		'featured_featured_image' => 'featured_image',
		'featured_excerpt_more' => 'excerpt_more',
		'featured_excerpt_length' => 'excerpt_length',
		'featured_read_more' => 'read_more',
		'featured_excerpt_settings' => 'excerpt_settings'
	);

	foreach ( $featured as $key => $loop_key ) {
		if ( empty( $loop[$key] ) )
			continue;

		$loop[$loop_key] = $loop[$key];
		unset( $loop[$key] );
	}

	if ( ! empty( $loop['featured_post_footer']['remove'] ) ) {
		$loop['post_footer']['remove'] = true;
		unset( $loop['featured_post_footer'] );
	}

	return $loop;
}

/**
 * Return loop data with context awareness and user-set
 * options blended with global post type level data.
 *
 * @since 6.0
 */

function md_get_loop( $args = array() ) {
	$loop = array();

	// Build parameters if a manual loop query

	if ( ! empty( $args['query'] ) ) {
		$key = '';

		if ( ! empty( $args['post_type'] ) )
			$key = $args['post_type'];
		elseif ( $args['query'] instanceof WP_Query && ! empty( $args['query']->query['post_type'] ) )
			$key = $args['query']->query['post_type'];
		elseif ( is_array( $args['query'] ) && ! empty( $args['query']['post_type'] ) )
			$key = $args['query']['post_type'];

		if ( $key )
			$loop = md_post_type_field( 'loop', array(), $key );
	}

	// Build parameters for auto page detection and admin settings

	else {
		$post_type = md_post_type_field( 'loop', array() );
		$loop_template = md_post_type_field( array( 'loop', 'loop' ), 'article' );
		$single = md_module( 'loop', array(), null, array( 'inherit_post_type' => false ) );

		// Determine main loop keys from contextual admin settings

		if ( is_singular() || is_404() ) {
			$loops = md_loops();
			$single_loop = ! empty( $loops[$loop_template]['single'] ) ? $loop_template : 'article';
			$loop = array_merge( array( 'loop' => $single_loop ), $single );
		}
		else {
			$tax = ( is_category() || is_tax() ) ? md_taxonomy_field( 'loop', array() ) : array();
			$loop = array_merge( $post_type, array_filter( $tax ), array_filter( $single ) );
		}

		// Set additional parameters

		$loop['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

		if ( empty( $loop['posts_per_page'] ) )
			$loop['posts_per_page'] = get_option( 'posts_per_page' );

		if ( empty( $loop['loop_type'] ) )
			$loop['loop_type'] = '';

		if ( is_category() || is_tax() ) {
			$queried = get_queried_object();
			$has_children = $queried && ! empty( get_term_children( $queried->term_id, $queried->taxonomy ) );

			if ( $has_children ) {
				if ( in_array( $loop['loop_type'], array( 'category_posts', 'category' ) ) )
					$loop['by_category'] = true;

				if ( ! empty( $loop['category']['hide_subcategory'] ) )
					$loop['subcategory'] = true;
			}
		}
		elseif ( in_array( $loop['loop_type'], array( 'category_posts', 'category' ) ) )
			$loop['by_category'] = true;

		if ( md_has_builder() )
			$loop['has_builder'] = true;

		if ( md_has_sidebar() )
			$loop['has_sidebar'] = true;

		if ( is_category() || is_tax() )
			foreach ( array( 'category_include', 'category_exclude', 'include_cats', 'exclude_cats' ) as $key )
				$loop[$key] = md_module( array( 'loop', $key ), null, null, array( 'inherit_post_type' => false ) );
	}

	if ( ! isset( $loop['loop_type'] ) )
		$loop['loop_type'] = '';

	if ( empty( $loop['columns'] ) )
		$loop['columns'] = 1;

	$loop = array_merge( $loop, $args );
	$loop['loop'] = ! empty( $loop['loop'] ) ? $loop['loop'] : 'article';

	if ( ! isset( $loop['style'] ) ) {
		$loop_args = ! empty( $args['query'] ) ? array( 'body' => true ) : array();
		$loop['style'] = md_loop_style( $loop_args );
	}

	if (
		! empty( $loop['has_sidebar'] ) || $loop['columns'] >= 3 ||
		( ! empty( $loop['by_category'] ) && ! empty( $loop['category_columns'] ) && $loop['category_columns'] > 1 ) )
		$loop['is_slim'] = true;

	$loops = md_loops();

	if ( ! empty( $loops[$loop['loop']]['style_target'] ) )
		$loop['style_target'] = $loops[$loop['loop']]['style_target'];
	elseif ( isset( $loop['by_category'] ) )
		$loop['style_target'] = 'group';
	else
		$loop['style_target'] = 'entry';

	if ( ! empty( $loops[$loop['loop']]['args'] ) )
		$loop['template'] = $loops[$loop['loop']]['args'];

	$classes = md_loop_classes( $loop );
	$loop['loop_classes'] = $classes['loop'];
	$loop['categories_classes'] = $classes['categories'];
	$loop['category_classes'] = $classes['category'];

	// A manual query might want to inherit some settings

	if ( ! empty( $loop['query'] ) && is_array( $loop['query'] ) )
		foreach ( array( 'posts_per_page', 'orderby', 'order' ) as $key )
			if ( empty( $loop['query'][$key] ) && ! empty( $loop[$key] ) )
				$loop['query'][$key] = $loop[$key];

	// Return loop data for any given page

	return apply_filters( 'md_filter_set_loop', $loop );
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
 * The Main Loop Logic loaded to all posts, pages, and archives.
 * Meant to mirror native WP page hierarchy and loop accordingly.
 *
 * @since 4.1
 * $args (Optional) set loop attributes
 */

function md_loop( $args = array() ) {
	$c = 1;
	$args = is_array( $args ) ? $args : array();
	$post_type = md_get_post_type();
	$html = ! md_has_header_cover( 'post' ) ? 'article' : 'div';
	$loop = $loop_base = md_get_loop( $args );
	$loops = md_loops();
	$args = array_merge( $args, array( 'loop' => $loop ) );
	$loop_template = $loop['loop'];
	$loop_classes = $loop['loop_classes'];

	md_hook_loop_before();

	// Show subcategory listing on post types / categoriess

	$show_subcategory = is_category() || is_tax()
		? empty( $loop['category']['hide_subcategory'] )
		: ! empty( $loop['category']['show_subcategory'] );

	if ( ! is_singular() && ! isset( $loop['by_category'] ) && $show_subcategory )
		include md_template( 'loop/subcategory', true );

	// A loop called within a loop (see 404)

	if ( ! empty( $loop['in_loop'] ) )
		include md_template( 'loop/the-post', true );

	// If listing by category

	elseif ( isset( $loop['by_category'] ) )
		include md_template( 'loop/category-posts', true );

	// Calling a manual query loop

	elseif ( isset( $args['query'] ) )
		include md_template( 'loop/the-query', true );

	// Every default loop on a page

	elseif ( have_posts() ) {
		echo ! is_singular() ? '<div class="' . esc_attr( $loop_classes ) . '">' : '';

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

	// Things that don't exist have to go somewhere too

	else md_404();

	md_hook_loop_after();
}