<?php

/**
 * Checks if the post content is enabled on page.
 *
 * @since 6.0
 */

function md_has_post_content() {
	if ( md_module( array( 'layout', 'content', 'the_content' ) ) )
		return false;

	return (bool) apply_filters( 'md_filter_has_the_content', true );
}

/**
 * Outputs the_content with option enhancements.
 *
 * @since 6.0
 */

function md_the_content( $loop ) {
	if ( $loop['content'] === 'hide' || ( ! get_the_content() && ! get_the_excerpt() && ! is_404() ) )
		return;

	$has_wrap = ! isset( $loop['is_slim'] );
	$has_builder = isset( $loop['has_builder'] );
	$id = ( is_singular() ? ' id="the_content"' : '' );
	$has_excerpt = ( empty( $loop['content'] ) || $loop['content'] == 'excerpt' ) && get_the_excerpt();
	$show_full_content = $loop['content'] == 'full' || ( empty( $loop['query'] ) && ( is_singular() || is_404() ) && ( in_the_loop() || isset( $loop['in_loop'] ) ) );

	include md_template( 'features', 'loop/the-content', true );
}

/**
 * Outputs the WordPress excerpt with read more and
 * length enhancements.
 *
 * @since 6.0
 */

function md_excerpt( $loop ) {
	return wpautop( wp_trim_words( get_the_excerpt(), $loop['excerpt_length'], $loop['excerpt_more'] ) ).
		( empty( $loop['excerpt_settings']['remove_text'] ) ?
			'<p class="read-more"><a href="' . get_permalink() . '" class="more-link">' . wp_kses_post( $loop['read_more'] ) . '</a></p>'
		: '' );
}

/**
 * Create pagination for use on home and archives pages.
 *
 * @since 4.0
 */

function md_pagination( $loop = array() ) {
	if ( is_singular() || ! empty( $loop['no_pagination'] ) )
		return;

	$big = 999999999;
	$type = md_module( array( 'loop', 'pagination' ) );
	$classes = $type == 'prev_next' ? 'prev-next' : 'numbers';
	$loop = ! empty( $loop ) ? $loop : md_get_loop();

	if ( isset( $loop['by_category'] ) ) {
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

	include md_template( 'features', 'loop/pagination', true );
}

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
			'is_slim' => true
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
 * Pass a post ID to resolve the same inheritance from an admin editor.
 * Exclude the single value to get the post type/global fallback.
 *
 * @since 5.1
 */

function md_loop_style( $args = array() ) {
	$args = wp_parse_args( $args, array(
		'post_id' => null,
		'post_type' => null,
		'exclude_single' => false
	) );
	$body = md_setting( array( 'colors', 'design' ), 'box' );

	if ( isset( $args['body'] ) )
		return $body;

	if ( ! empty( $args['post_id'] ) ) {
		$post_type = $args['post_type'] ?: md_get_post_type( $args['post_id'] );
		$inherited = md_post_type_field( array( 'layout', 'content_style' ), $body, $post_type ) ?: $body;

		if ( ! empty( $args['exclude_single'] ) )
			return $inherited;

		return md_post_meta( array( 'layout', 'content_style' ), $args['post_id'] ) ?: $inherited;
	}

	$post_type = md_post_type_field( array( 'layout', 'content_style' ) );
	$style = md_module( array( 'layout', 'content_style' ), $post_type, array( 'id' => get_queried_object_id() ) ) ?: $body;

	return $style;
}

/**
 * Get a list of Sticky posts by post type.
 *
 * @since 6.0
 */

function md_get_sticky( $post_type = null ) {
	if ( empty( $post_type ) )
		$post_type = get_post_type();

	$sticky = array();

	foreach ( get_option( 'sticky_posts', array() ) as $id )
		if ( $post_type === get_post_type( $id ) )
			$sticky[] = $id;

	return $sticky;
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

		if ( $loop['columns'] > 2 )
			$classes[] = 'has-mobile-columns';

		$classes[] = ! empty( $loop['is_slim'] ) ? 'slim' : 'full';
	}
	else {
		$classes[] = 'row';
		$classes[] = ! empty( $loops[$loop['loop']]['is_slim'] ) ? 'slim' : 'full';
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

	if ( in_array( $loop['loop_type'], array( 'category', 'category_posts' ), true ) )
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
	$cover = md_cover( 'post' );
	$loop = ! empty( $loop ) ? $loop : md_get_loop();

	if ( ! empty( $loop['featured'] ) && isset( $loop['is_featured'] ) )
		$classes[] = 'featured';
	else
		$classes[] = 'standard';

	if ( ! is_singular() )
		$classes[] = $c % 2 == 0 ? 'even' : 'odd';

	if ( ! is_singular() && is_sticky() )
		$classes[] = 'sticky';

	$position = isset( $loop['featured_image'] ) ? md_loop_media_position( $loop ) : '';

	if ( $position && ! in_array( $position, array( 'remove', 'title_left', 'title_right', 'title_center' ), true ) )
		$classes = array_merge( $classes, md_get_image_position_classes( $position ) );

	if ( isset( $cover['position'] ) )
		$classes[] = 'has-cover';

	return join( ' ', $classes );
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
		'featured_featured_image_size' => 'featured_image_size',
		'featured_inherit' => 'inherit',
		'featured_excerpt_more' => 'excerpt_more',
		'featured_excerpt_length' => 'excerpt_length',
		'featured_read_more' => 'read_more',
		'featured_excerpt_settings' => 'excerpt_settings'
	);

	if ( empty( $loop['featured_featured_image_size'] ) )
		$loop['featured_image_size'] = 'full';

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
 * Build a date archive URL that retains the active content context.
 *
 * @since 6.0
 */

function md_get_date_archive_link( $year, $month = 0, $day = 0, $args = array() ) {
	$args = wp_parse_args( $args, array(
		'post_type' => '',
		'taxonomy' => '',
		'term' => ''
	) );
	$url = $day ? get_day_link( $year, $month, $day ) : ( $month ? get_month_link( $year, $month ) : get_year_link( $year ) );
	$query_args = array();
	$post_type = $args['post_type'] ?: get_query_var( 'post_type' );

	if ( empty( $post_type ) )
		$post_type = md_get_post_type();

	if ( $post_type && $post_type !== 'post' )
		$query_args['post_type'] = $post_type;

	if ( ! $args['taxonomy'] && ( is_category() || is_tag() || is_tax() ) ) {
		$term = get_queried_object();
		$args['taxonomy'] = ! empty( $term->taxonomy ) ? $term->taxonomy : '';
		$args['term'] = ! empty( $term->slug ) ? $term->slug : '';
	}

	if ( $args['taxonomy'] && $args['term'] ) {
		$taxonomy = get_taxonomy( $args['taxonomy'] );

		if ( $taxonomy && ! empty( $taxonomy->query_var ) )
			$query_args[$taxonomy->query_var] = $args['term'];
	}

	return $query_args ? add_query_arg( $query_args, $url ) : $url;
}

/**
 * Build the month key and label used to section a date-based Loop.
 *
 * @since 6.0
 */

function md_get_loop_date( $post = null, $args = array() ) {
	$timestamp = get_post_time( 'U', true, $post );

	if ( ! $timestamp )
		return array();

	$year = absint( wp_date( 'Y', $timestamp ) );
	$month = absint( wp_date( 'n', $timestamp ) );
	$url = md_get_date_archive_link( $year, $month, 0, $args );

	if ( is_month() && absint( get_query_var( 'year' ) ) === $year && absint( get_query_var( 'monthnum' ) ) === $month )
		$url = '';

	return apply_filters( 'md_filter_loop_date', array(
		'month' => wp_date( 'Y-m', $timestamp ),
		'label' => wp_date( 'F Y', $timestamp ),
		'url' => $url
	), $post, $args );
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
 * Return loop data with context awareness and user-set
 * options blended with global post type level data.
 *
 * @since 6.0
 */

function md_get_loop( $args = array() ) {
	$loop = array();
	$loops = md_loops();
	$post_type = ! empty( $args['post_type'] ) ? $args['post_type'] : md_get_post_type();

	// Build parameters if a manual loop query

	if ( ! empty( $args['query'] ) ) {
		$key = '';

		if ( ! empty( $args['post_type'] ) )
			$key = $args['post_type'];
		elseif ( $args['query'] instanceof WP_Query && ! empty( $args['query']->query['post_type'] ) )
			$key = $args['query']->query['post_type'];
		elseif ( is_array( $args['query'] ) && ! empty( $args['query']['post_type'] ) )
			$key = $args['query']['post_type'];

		if ( $key ) {
			$post_type = $key;
			$loop = md_post_type_field( 'loop', array(), $key );
		}
	}

	// Build parameters for auto page detection and admin settings

	else {
		$post_type_loop = md_post_type_field( 'loop', array() );
		$loop_template = md_post_type_field( array( 'loop', 'loop' ), 'article' );
		$single = md_module( 'loop', array(), array( 'inherit_post_type' => false ) );

		// Determine main loop keys from contextual admin settings

		if ( is_singular() || is_404() ) {
			$single_loop = ! empty( $loops[$loop_template]['single'] ) ? $loop_template : 'article';
			$loop = array_merge( array( 'loop' => $single_loop ), $single );
		}
		else {
			$tax = ( is_category() || is_tax() ) ? md_taxonomy_field( 'loop', array() ) : array();
			$single = array_filter( $single, function( $value ) {
				return $value !== '' && $value !== false && $value !== null && $value !== array();
			} );
			$loop = array_replace_recursive( $post_type_loop, $tax, $single );
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
				if ( in_array( $loop['loop_type'], array( 'category_posts', 'category' ), true ) )
					$loop['by_category'] = true;

				if ( ! empty( $loop['category']['hide_subcategory'] ) )
					$loop['subcategory'] = true;
			}
		}
		elseif ( in_array( $loop['loop_type'], array( 'category_posts', 'category' ), true ) )
			$loop['by_category'] = true;

		if ( md_has_builder() )
			$loop['has_builder'] = true;

		if ( md_has_sidebar() )
			$loop['has_sidebar'] = true;

		if ( is_category() || is_tax() )
			foreach ( array( 'category_include', 'category_exclude', 'include_cats', 'exclude_cats' ) as $key )
				$loop[$key] = md_module( array( 'loop', $key ), null, array( 'inherit_post_type' => false ) );
	}

	$loop = array_merge( $loop, $args );
	$loop['loop'] = ! empty( $loop['loop'] ) ? $loop['loop'] : 'article';
	$loop = array_merge( $loops[$loop['loop']]['defaults'] ?? array(), $loop );
	$loop['post_type'] = $post_type;

	if ( ! isset( $loop['loop_type'] ) )
		$loop['loop_type'] = '';

	if (
		! empty( $loop['date']['group'] ) && empty( $loop['by_category'] ) &&
		( ! is_singular() || ! empty( $args['query'] ) )
	)
		$loop['by_date'] = true;

	if ( empty( $loop['columns'] ) )
		$loop['columns'] = 1;

	if ( ! isset( $loop['style'] ) ) {
		$archive_style = $loops[$loop['loop']]['archive_style'] ?? null;

		if ( $archive_style && ( is_archive() || is_home() ) )
			$loop['style'] = $archive_style;
		else {
			$loop_args = ! empty( $args['query'] ) ? array( 'body' => true ) : array();
			$loop['style'] = md_loop_style( $loop_args );
		}
	}

	if (
		! empty( $loops[$loop['loop']]['is_slim'] ) ||
		! empty( $loop['has_sidebar'] ) || $loop['columns'] >= 3 ||
		( ! empty( $loop['by_category'] ) && ! empty( $loop['category_columns'] ) && $loop['category_columns'] > 1 ) )
		$loop['is_slim'] = true;

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

	if ( ! empty( $loop['query'] ) && is_array( $loop['query'] ) ) {
		foreach ( array( 'posts_per_page', 'orderby', 'order' ) as $key )
			if ( empty( $loop['query'][$key] ) && ! empty( $loop[$key] ) )
				$loop['query'][$key] = $loop[$key];

		if ( ! empty( $loop['by_date'] ) ) {
			$loop['query']['orderby'] = 'date';
			$loop['query']['ignore_sticky_posts'] = 1;
		}
	}

	// Return loop data for any given page

	return apply_filters( 'md_filter_set_loop', $loop );
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
	$default_html = ! md_has_header_cover( 'post' ) ? 'article' : 'div';
	$html = ! empty( $args['html'] ) && in_array( $args['html'], array( 'article', 'div' ), true ) ? $args['html'] : $default_html;
	$loop = $loop_base = md_get_loop( $args );
	$post_type = $loop['post_type'];
	$loops = md_loops();
	$args = array_merge( $args, array( 'loop' => $loop ) );
	$loop_template = $loop['loop'];
	$loop_classes = $loop['loop_classes'];
	$loop_columns_style = $loop['columns'] > 1 ? ' style="--md-loop-columns: ' . absint( $loop['columns'] ) .
		( ! empty( $loop['columns_mobile'] ) ? '; --md-loop-columns-mobile: ' . absint( $loop['columns_mobile'] ) : '' ) . '"' : '';

	md_hook_loop_before( $args );

	// Show subcategory listing on post types / categoriess

	$show_subcategory = is_category() || is_tax()
		? empty( $loop['category']['hide_subcategory'] )
		: ! empty( $loop['category']['show_subcategory'] );

	if ( ! is_singular() && ! isset( $loop['by_category'] ) && $show_subcategory )
		include md_template( 'features', 'loop/subcategory', true );

	// A loop called within a loop (see 404)

	if ( ! empty( $loop['in_loop'] ) )
		include md_template( 'features', 'loop/the-post', true );

	// If listing by category

	elseif ( isset( $loop['by_category'] ) )
		include md_template( 'features', 'loop/category-posts', true );

	// If grouping a post listing into month sections

	elseif ( ! empty( $loop['by_date'] ) )
		include md_template( 'features', 'loop/date-posts', true );

	// Calling a manual query loop

	elseif ( isset( $args['query'] ) )
		include md_template( 'features', 'loop/the-query', true );

	// Every default loop on a page

	elseif ( have_posts() ) {
		echo ! is_singular() ? '<div class="' . esc_attr( $loop_classes ) . '"' . $loop_columns_style . '>' : '';

		md_hook_loop_top();

		while ( have_posts() ) {
			the_post();

			include md_template( 'features', 'loop/the-post', true );
		}

		if ( ! is_singular() ) {
			echo '</div>';
			md_pagination( $loop );
		}
	}

	// Things that don't exist have to go somewhere too

	else md_404();

	md_hook_loop_after( $args );
}
