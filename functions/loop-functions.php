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

	$post_type = ! empty( $loop['post_type'] ) ? $loop['post_type'] : md_get_post_type();
	$class = 'loop-' . str_replace( '_', '-', $post_type );
	$style = isset( $loop['style'] ) ? $loop['style'] : md_loop_style();
	$classes = array( 'loop', $class, 'loop-' . $loop['loop'] );

	if ( $style )
		$classes[] = "{$style}-style";

	if ( $loop['columns'] > 1 ) {
		$classes[] = 'columns';
		$classes[] = 'columns-' . $loop['columns'];

		if ( ! empty( $loop['is_slim'] ) )
			$classes[] = 'slim';
		else
			$classes[] = 'full';
	}
	else {
		$classes[] = 'row';
		$classes[] = 'full';
	}

	if ( isset( $loop['classes'] ) )
		$classes = array_merge( $classes, (array) $loop['classes'] );

	$classes = apply_filters( 'md_filter_loop_classes', $classes );

	return join( ' ', $classes );
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

	if ( ! empty( $args['query'] ) ) {
		$key = '';

		if ( ! empty( $args['post_type'] ) )
			$key = $args['post_type'];
		elseif ( ! empty( $args['query']['post_type'] ) )
			$key = $args['query']['post_type'];

		if ( $key )
			$loop = md_post_type_field( 'loop', array(), $key );
	}
	else {
		$post_type = md_post_type_field( 'loop', array() );
		$loop_type = md_post_type_field( array( 'loop', 'loop' ), 'article' );
		$single = md_module( 'loop', array() );

		if ( is_singular() || is_404() )
			$loop = array_merge( array( 'loop' => $loop_type ), $single );
		else {
			$tax_defaults = ( is_category() || is_tax() ) ? md_taxonomy_field( 'loop', array() ) : array();
			$loop         = array_merge( $post_type, $tax_defaults, $single );
		}

		$loop['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

		if ( empty( $loop['posts_per_page'] ) )
			$loop['posts_per_page'] = get_option( 'posts_per_page' );

		if ( ! is_tax() && ! is_category() && ! empty( $loop['category_posts']['enable'] ) )
			$loop['by_category'] = true;

		if ( ( is_category() || is_tax() ) && ! empty( $loop['subcategory']['enable'] ) ) {
			$queried = get_queried_object();
			if ( $queried && ! empty( get_term_children( $queried->term_id, $queried->taxonomy ) ) )
				$loop['subcategory'] = true;
		}

		if ( md_has_builder() )
			$loop['has_builder'] = true;

		if ( md_has_sidebar() )
			$loop['has_sidebar'] = true;
	}

	if ( empty( $loop['columns'] ) )
		$loop['columns'] = 1;

	$loop = array_merge( $loop, $args );
	$loop['loop'] = ! empty( $loop['loop'] ) ? $loop['loop'] : 'article';

	if ( ! empty( $loop['query'] ) )
		foreach ( array( 'posts_per_page', 'orderby', 'order' ) as $key )
			if ( empty( $loop['query'][$key] ) && ! empty( $loop[$key] ) )
				$loop['query'][$key] = $loop[$key];

	if ( ! isset( $loop['style'] ) )
		$loop['style'] = ! empty( $args['query'] ) ? md_loop_style( array( 'body' => true ) ) : md_loop_style();

	if ( ! empty( $loop['has_sidebar'] ) || $loop['columns'] >= 3 || ( $loop['columns'] == 2 && ! empty( $loop['has_sidebar'] ) ) )
		$loop['is_slim'] = true;

	$loop['loop_classes'] = md_loop_classes( $loop );

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
	$post_type = get_post_type();
	$html = ! md_has_header_cover( 'post' ) ? 'article' : 'div';
	$loop = $loop_base = md_get_loop( $args );
	$loops = md_loops();
	$args = array_merge( $args, array( 'loop' => $loop ) );
	$loop_type = $loop['loop'];
	$loop_classes = $loop['loop_classes'];

	md_hook_loop_before();

	if ( ( is_category() || is_tax() ) && ! empty( $loop['subcategory'] ) )
		include md_template( 'loop/subcategory', true );

	if ( ! empty( $loop['sticky'] ) || ! empty( $loop['in_loop'] ) )
		include md_template( 'loop/the-post', true );
	elseif ( isset( $loop['by_category'] ) )
		include md_template( 'loop/category-posts', true );
	elseif ( isset( $args['query'] ) ) {
		$query = new WP_Query( $loop['query'] );

		if ( $query->have_posts() ) {
			echo '<div class="' . esc_attr( $loop_classes ) . '">';

			while ( $query->have_posts() ) {
				$query->the_post();
				include md_template( 'loop/the-post', true );
			}

			echo '</div>';
		}
		else md_404();

		wp_reset_postdata();

		md_pagination( $loop );
	}
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
	else md_404();

	md_hook_loop_after();
}