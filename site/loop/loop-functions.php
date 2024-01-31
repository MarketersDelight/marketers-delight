<?php
/**
 * A list of Loops registered to MD's settings.
 *
 * @since 5.1
 */

function md_loops( $sort = null ) {
	$data = array();
	$loops = apply_filters( 'md_filter_loops', array(
		'fluid' => array(
			'name' => __( 'Fluid', 'md' ),
			'description' => __( 'The default blog style with a flexible layout.', 'md' ),
			'image' => MD_URL . 'lib/admin/images/loop-fluid.png'
		),
		'list' => array(
			'name' => __( 'List', 'md' ),
			'description' => __( 'A simplified list with compact images.', 'md' ),
			'image' => MD_URL . 'lib/admin/images/loop-list.png',
			'template' => md_template( 'loop/loop-list', true )
		),
/*
		'icons' => array(
			'name' => __( 'Icon Cards', 'md' ),
			'description' => __( 'Small cards with a focus on the image thumbnail.', 'md' ),
			'image' => MD_URL . 'lib/admin/images/loop-icons.png'
		),
*/
		'covers' => array(
			'name' => __( 'Post Covers', 'md' ),
			'description' => __( 'Posts list with full-width background image covers.', 'md' ),
			'image' => MD_URL . 'lib/admin/images/loop-fluid.png',
			'template' => md_template( 'loop/loop-covers', true )
		)
	) );

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
	else
		$data = $loops;

	return $data;
}

/**
 * Get the current page Loop. If post_type parameter
 * is set in $loops, all views will be set according to the
 * loop being registered. Unless set, categories will use the
 * same loop as archives.
 *
 * @since 4.6.4
 */

function md_get_loop() {
	$loop = 'default';
	$post_type = md_get_post_type();
	$settings = md_module( array( 'loop', 'archives' ) );
	$loops = md_loops();

	if ( ! empty( $settings ) )
		$loop = $settings;
	elseif ( ! empty( $loops[$post_type]['post_type'] ) ) {
		if ( ( is_category() || is_tax() ) && md_term_meta( array( 'loop', 'archives' ) ) == '' )
			$loop = md_post_type_field( array( 'loop', 'archives' ), $post_type );
		else
			$loop = $post_type;
	}

	return apply_filters( 'md_filter_loop', $loop );
}

/**
 * Render custom built queries with passed settings data.
 *
 * The $position variable is set from the hook md_query() is hooked to.
 *
 * @since 6.0
 */

function md_query( $position = null ) {
	$queries = md_post_type_field( array( 'loop', 'query' ), array() );

	foreach ( $queries as $query_id => $loop ) {
		if ( ! isset( $loop['position'] ) || $loop['position'] !== $position )
			continue;

		$loop['is_query'] = true;

		include( md_template( 'loop/query', true ) );
	}
}

/**
 * Load Queries to hook areas when auto-inserted.
 *
 * @since 6.0
 */

function md_query_template() {
	if ( ! is_post_type_archive() && ! is_home() )
		return;

	$queries = md_post_type_field( array( 'loop', 'query' ), array() );
	$hooks = array(
		'before_content_box' => 'md_hook_before_content_box',
		'before_content' => 'md_hook_before_content',
		'content' => 'md_hook_after_content',
		'before_footer' => 'md_hook_before_footer'
	);

	foreach ( $queries as $query_id => $loop ) {
		if ( ! isset( $loop['position'] ) )
			continue;

		$position = $loop['position'];

		if ( empty( $hooks[$position] ) )
			continue;

		add_action( $hooks[$position], 'md_query' );
	}
}

add_action( 'template_redirect', 'md_query_template' );

/**
 * The Main Loop used on all posts, pages, and archives.
 *
 * @since 4.1
 */

function md_loop( $args = array() ) {
	$c = 1;
	$wrap_classes = array();
	$post_type = md_get_post_type();
	$loops = md_loops();

	if ( isset( $args['query'] ) ) {
		$loop = $args['query'];
		$loop['is_query'] = true;

		if ( isset( $loop['post_type'] ) )
			$post_type = $loop['post_type'];
	}
	else {
		$loop = md_module( 'loop' );
		unset( $loop['query'] );
	}

	$loop['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	$posts_per_page = ! empty( $loop['posts_per_page'] ) ? $loop['posts_per_page'] : get_option( 'posts_per_page' );

	if ( empty( $loop['columns'] ) )
		$loop['columns'] = 1;

	$wrap_classes[] = "loop-{$post_type}";

	if ( isset( $loop['position'] ) && in_array( $loop['position'], array( 'before_content', 'content' ) ) )
		$loop['is_inline'] = true;

	if ( $loop['columns'] > 1 ) {
		$wrap_classes[] = 'columns';

		if ( ( md_has_sidebar() && ! isset( $args['has_sidebar'] ) || isset( $args['has_sidebar'] ) ) || $loop['columns'] >= 3 )
			$wrap_classes[] = 'slim';
		elseif ( $loop['columns'] == 2 )
			$wrap_classes[] = 'wide';
	}

	$wrap_classes = apply_filters( 'md_filter_loop_classes', $wrap_classes );
	$wrap_classes = ' ' . join( ' ', $wrap_classes );
	$looped = $loop;

	if ( isset( $args['sticky'] ) )
		include( md_template( 'loop/the-post', true ) );
	elseif ( ! empty( $loop['category_posts']['enable'] ) )
		include( md_template( 'loop/category-posts', true ) );
	elseif ( isset( $args['query'] ) ) {
		echo "<div class=\"loop$wrap_classes\">";
		include( md_template( 'loop/the-query', true ) );
		echo '</div>';
	}
	elseif ( have_posts() ) {
		echo ! is_singular() ? "<div class=\"loop$wrap_classes\">" : '';

		md_hook_loop_top();

		while ( have_posts() ) {
			the_post();

			include( md_template( 'loop/the-post', true ) );
		}

		if ( ! is_singular() ) {
			echo '</div>';

			md_pagination( $loop );
		}
	}
	else
		md_404_template();
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
 * Call custom 404 content box template.
 *
 * @since 5.1
 */

function md_404_template() {
	$page_id = md_setting( array( 'settings', '404_page' ) );

	if ( empty( $page_id ) )
		md_template( 'content-item-404' );
	else {
		$page404 = new WP_Query( array(
			'post_type' => 'page',
			'p' => $page_id,
			'post_status' => array( 'publish' ),
			'fields' => 'ids'
		) );

		if ( $page404->have_posts() )
			while ( $page404->have_posts() ) {
				$page404->the_post();
				md_template( 'content-item' );
			}
		else
			md_template( 'content-item-404' );

		wp_reset_query();
	}
}

/**
 * Add/remove post classes.
 *
 * @since 4.1
 */

function md_post_classes( $classes ) {
	// Remove excess WP classes
	$classes = array_diff( $classes, array(
		'format-standard',
		'hentry',
		'post-' . get_the_ID(),
		'type-' . get_post_type(),
		'status-' . get_post_status(),
		'format-' . get_post_format()
	) );

	// Apply classes to certain featured image positions
	$cover = md_cover();

	if ( ! empty( $cover['position'] ) )
		$classes[] = 'has-cover';

	return $classes;
}

add_filter( 'post_class', 'md_post_classes' );

/**
 * Checks if headline is enabled.
 *
 * @since 4.1
 */

function md_has_headline() {
	if ( ! md_meta( array( 'layout', 'content', 'headline' ) ) )
		return true;
}

/**
 * Checks for headline with cover.
 *
 * @since 4.1
 */

function md_has_headline_cover() {
	$cover = md_cover();

	return is_singular() && ! empty( $cover['position'] ) && in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) ? true : false;
}

/**
 * Displays the headline of any post/page.
 *
 * @since 4.1
 */

function md_headline( $args = array() ) {
	if ( ! md_has_headline() )
		return;

	$context = isset( $args['context'] ) ? $args['context'] : 'post';
	$loop = isset( $args['loop'] ) ? $args['loop'] : array();

	$image_args = array();
	$cover = md_cover( $context );
	$style = isset( $cover['style'] ) ? md_style( $cover['style'] ) : '';

	$classes = isset( $args['classes'] ) ? $args['classes'] : array();
	$classes[] = "$context-header";
	$classes = array_merge( md_cover_classes( $cover ), $classes );
	$classes = join( ' ' , $classes );

	if ( $context == 'post' ) {
		$image_args['show'] = array( 'above_headline' );

		if ( isset( $loop['featured_image_size'] ) )
			$image_args['size'] = $loop['featured_image_size'];

			md_featured_image( $image_args );
	}

	include( md_template( 'headline', true ) );

	if ( $context == 'post' ) {
		$image_args['show'] = array( 'below_headline' );

		md_featured_image( $image_args );
	}
}

/**
 * Show full content or excerpt of any given page.
 *
 * @since 5.1
 */

function md_the_content( $loop ) {
//	if ( isset( $loop['content'] ) && $loop['content'] == 'hide' )
//		return;

	md_hook_before_the_content();

	the_content( $loop['read_more'] );

	if ( ! isset( $loop['is_query'] ) )
		wp_link_pages();

	md_hook_after_the_content();
}

/**
 * Create our own Excerpt with native WP functions so
 * we can modify length and more without use of filters.
 *
 * @since 6.0
 */

function md_the_excerpt( $loop ) {
	if ( isset( $loop['content'] ) && $loop['content'] == 'hide' )
		return;

	$link_class = '';

	if ( ! empty( $loop['read_more_style'] ) )
		$link_class = ' button';

	$excerpt = wp_trim_words( get_the_excerpt(), $loop['excerpt_length'], $loop['excerpt_more'] );

	echo
		wpautop( $excerpt ).
		'<p class="read-more"><a href="' . get_permalink() . '" class="more-link' . $link_class . '">' . esc_html( $loop['read_more'] ) . '</a></p>';
}

/**
 * Displays post/page content text.
 *
 * @since 4.1
 * @renamed 6.0 md_content_text()
 */

function md_content( $loop ) {
	if ( get_the_content() ) {
		$image_args = array(
			'inline' => true,
			'loop' => $loop
		);

		echo '<div class="the-content">';

		if ( in_the_loop() )
			md_featured_image( $image_args );

		if ( ( is_singular() && in_the_loop() ) || isset( $loop['content'] ) && $loop['content'] == 'full' )
			md_the_content( $loop );
		else
			md_the_excerpt( $loop );

		echo '</div>';
	}

	md_byline( 'after_post', array(
		'classes' => 'post-footer',
		'loop' => $loop
	) );
}

function md_loop_classes( $loop, $c ) {
	$classes = array( 'entry' );
	$loop_style = 'box-style';
	$disable_box_style = md_setting( array( 'colors', 'design', 'box_style' ) );

	if ( isset( $loop['is_featured'] ) )
		$classes[] = 'featured';
	else
		$classes[] = 'standard';

	if ( $loop['columns'] > 1 )
		if ( $loop['columns'] <= 5 )
			$classes[] = 'f' . $loop['columns'];

	if ( $disable_box_style )
		$loop_style = '';

	if ( isset( $loop['style'] ) )
		if ( $loop['style'] !== 'minimal' )
			$loop_style = str_replace( '_', '-', $loop['style'] );
		else
			$loop_style = '';

	if ( $loop_style )
		$classes[] = $loop_style;

	if ( isset( $loop['featured_image_id'] ) )
		$classes[] = 'image-' . str_replace( '_', '-', $loop['featured_image'] );

	if ( ! empty( $loop['is_query'] ) || ( ! is_singular() && empty( $loop['is_query'] ) ) )
		$classes[] = $c % 2 == 0 ? 'even' : 'odd';

	return join( ' ', $classes );
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

	if ( ! empty( $loop['featured_read_more_style'] ) ) {
		$loop['read_more_style'] = $loop['featured_read_more_style'];
		unset( $loop['featured_read_more_style'] );
	}

	return $loop;
}
