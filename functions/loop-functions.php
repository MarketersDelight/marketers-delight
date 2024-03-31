<?php
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
	else
		$data = $loops;

	return $data;
}
/**
 * The Main Loop used on all posts, pages, and archives.
 *
 * @since 4.1
 */

function md_loop( $args = array() ) {
	$c = 1;
	$h = is_singular() ? 'div' : 'article';
	$wrap_classes = array();
	$categories_classes = array( 'categories' );
	$post_type = md_get_post_type();
	$loops = md_loops();

	if ( isset( $args['query'] ) ) {
		$loop = $args['query'];
		$loop['is_query'] = true;

		if ( isset( $loop['post_type'] ) )
			$post_type = $loop['post_type'];
	}
	else {
		if ( is_singular() )
			$loop = md_module( 'loop', array() );
		else
			$loop = md_post_type_field( 'loop', array() );

		unset( $loop['query'] );
	}

	$loop = apply_filters( 'md_filter_set_loop', $loop );
	$loop_type = isset( $loop['loop'] ) ? $loop['loop'] : 'post';

	if ( ! empty( $loops[$loop_type]['defaults'] ) )
		$loop = array_merge( $loops[$loop_type]['defaults'], $loop );

	$loop['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	$loop['by_category'] = ! is_tax() && ! is_category() && ! empty( $loop['category_posts']['enable'] ) ? true : false;

	if ( empty( $loop['posts_per_page'] ) )
		$loop['posts_per_page'] = get_option( 'posts_per_page' );

	if ( empty( $loop['columns'] ) )
		$loop['columns'] = 1;

	$wrap_classes[] = "loop-{$post_type}";

	if ( $loop_type !== $post_type )
		$wrap_classes[] = "loop-{$loop_type}";

	if ( isset( $loop['list'] ) )
		$wrap_classes[] = esc_attr( $loop['list'] );

	if ( ! isset( $loop['featured_image'] ) )
		$loop['featured_image'] = '';

	if ( isset( $loop['position'] ) && in_array( $loop['position'], array( 'before_content', 'content' ) ) )
		$loop['is_inline'] = true;

	if ( $loop['columns'] > 1 ) {
		$wrap_classes[] = 'columns';
		$wrap_classes[] = 'columns-' . $loop['columns'];

		if ( $loop['columns'] >= 3 || ( $loop['columns'] >= 2 && ! empty( $loop['by_category'] ) ) )
			$wrap_classes[] = 'slim';
	}

	if ( ! empty( $loop['by_category'] ) && isset( $loop['category_columns'] ) && $loop['category_columns'] >= 2 )
		$wrap_classes[] = 'slim';

	$loop_style = md_loop_style( $loop );

	if ( $loop_style )
		if ( $loop['by_category'] )
			$categories_classes[] = $loop_style;
		else
			$wrap_classes[] = $loop_style;

	$wrap_classes = apply_filters( 'md_filter_loop_classes', $wrap_classes );
	$wrap_classes = ' ' . join( ' ', $wrap_classes );

	$looped = $loop;

	do_action( 'md_loop_before' );

	if ( isset( $args['sticky'] ) )
		include( md_template( 'loop/the-post', true ) );
	elseif ( $loop['by_category'] )
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

/**
 * Set default loop values on current instance.
 *
 * @since 6.0
 */

function md_the_loop( $loop, $c ) {
	if ( ! empty( $loop['featured'] ) && $c <= $loop['featured'] )
		$loop = md_loop_featured( $loop );

	if ( empty( $loop['read_more'] ) )
		$loop['read_more'] = __( 'Continue reading &rarr;', 'md' );

	if ( empty( $loop['excerpt_length'] ) )
		$loop['excerpt_length'] = 55;

	if ( empty( $loop['excerpt_more'] ) )
		$loop['excerpt_more'] = '[...]';

	$featured_image = md_get_featured_image();

	if ( ! empty( $featured_image['id'] ) ) {
		$loop['featured_image_id'] = $featured_image['id'];

		if ( empty( $loop['featured_image'] ) )
			$loop['featured_image'] = $featured_image['position'];
	}

	return $loop;
}

/**
 * Determine the style class applied to any given Loop.
 *
 * @since 5.1
 */

function md_loop_style( $loop = array() ) {
	$style = 'box-style';
	$disable_box_style = md_setting( array( 'colors', 'design', 'box_style' ) );
	$disable_single = md_meta( array( 'layout', 'content', 'box_style' ) );

	if ( $disable_box_style || $disable_single )
		$style = '';

	if ( isset( $loop['style'] ) )
		if ( $loop['style'] !== 'simple' )
			$style = str_replace( '_', '-', $loop['style'] );
		else
			$style = '';

	return $style;
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
 * Create pagination for use on home and archives pages.
 *
 * @since 4.0
 */

function md_pagination( $loop = array() ) {
	if ( is_singular() )
		return;

	$big = 999999999;
	$type = md_module( array( 'loop', 'pagination' ) );
	$prelabel = ! empty( $loop['previous_label'] ) ? $loop['previous_label'] : __( 'Previous', 'md' );
	$nxtlabel = ! empty( $loop['next_label'] ) ? $loop['next_label'] : __( 'Next', 'md' );
	$class = $type == 'prev_next' ? 'prev-next' : 'numbers';

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

	include( md_template( 'pagination', true ) );
}

/**
 * Check if Post Nav is active on page.
 *
 * @since 6.0
 */

function md_has_post_nav() {
	$disable = md_post_type_field( array( 'layout', 'content', 'post_nav' ) );
	$single_remove = md_post_meta( array( 'layout', 'content', 'post_nav' ) );
	$single_add = md_post_meta( array( 'layout', 'content', 'add_post_nav' ) );

	if (
		! is_page() && is_singular() && ( get_previous_post() || get_next_post() ) &&
		! $single_remove &&
		( ! $disable || $single_add )
	)
		return true;
}

/**
 * Creates previous/next post links at the end of a
 * single entry.
 *
 * @since 4.0
 */

function md_post_nav() {
	if ( md_has_post_nav() )
		md_template( 'post-nav' );
}
