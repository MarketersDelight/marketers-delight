<?php
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
	$html = md_has_header_cover( 'post' ) ? 'div' : 'article';
	$loop = $loop_base = md_get_loop( $args );

	unset( $args['loop_defaults'] );

	$post_type = $loop['post_type'];
	$loops = md_loops();
	$args = array_merge( $args, array( 'loop' => $loop ) );
	$loop_template = $loop['loop'];
	$loop_classes = $loop['loop_classes'];
	$loop_columns_style = '';

	// Choose wrapper tag, if valid

	if ( in_array( $args['html'] ?? '', array( 'article', 'div' ), true ) )
		$html = $args['html'];

	// Pass column counts to CSS

	if ( $loop['columns'] > 1 || ( $loop['columns_mobile'] ?? 1 ) > 1 ) {
		$columns_mobile = ! empty( $loop['columns_mobile'] ) ? '; --md-loop-columns-mobile: ' . absint( $loop['columns_mobile'] ) : '';
		$loop_columns_style = ' style="--md-loop-columns: ' . absint( $loop['columns'] ) . $columns_mobile . '"';
	}

	md_hook_loop_before( $args );

	// Show subcategory listing on post types / categoriess

	$show_subcategory = is_category() || is_tax() ? empty( $loop['category']['hide_subcategory'] ) : ! empty( $loop['category']['show_subcategory'] );

	if ( ! is_singular() && ! isset( $loop['by_category'] ) && $show_subcategory && empty( $args['skip_subcategory'] ) )
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

	// Things that don't exist have to go somewhere too. An empty archive
	// says so with its post type's own "not found" label.

	elseif ( is_404() || is_search() )
		md_404();
	else {
		$post_type_object = get_post_type_object( md_get_post_type() );

		if ( $post_type_object )
			echo '<p class="loop-empty has-muted-color">' . esc_html( $post_type_object->labels->not_found ) . '</p>';
	}

	md_hook_loop_after( $args );
}

/**
 * Return loop data with context awareness and user-set options blended with
 * post type settings. Manual queries may provide their own loop_defaults.
 *
 * @since 6.0
 */

function md_get_loop( $args = array() ) {
	$loop = array();
	$loops = md_loops();
	$post_type = ! empty( $args['post_type'] ) ? $args['post_type'] : md_get_post_type();
	$settings_type = is_author() && empty( $args['query'] ) ? 'author' : $post_type;

	// Build parameters if a manual loop query

	if ( ! empty( $args['query'] ) ) {
		$query_vars = $args['query'] instanceof WP_Query ? $args['query']->query : $args['query'];
		$key = ( $args['post_type'] ?? '' ) ?: ( $query_vars['post_type'] ?? '' );

		if ( $key ) {
			$post_type = $key;
			$loop = $args['loop_defaults'] ?? md_post_type_field( 'loop', array(), $key );
		}
	}

	// Build parameters for auto page detection and admin settings

	else {
		$post_type_loop = md_post_type_field( 'loop', array(), $settings_type );
		$loop_template = md_post_type_field( array( 'loop', 'loop' ), 'article', $settings_type );
		$single = is_author() ? array() : md_module( 'loop', array(), array( 'inherit_post_type' => false ) );

		// Determine main loop keys from contextual admin settings

		if ( is_singular() || is_404() ) {
			$single_loop = ! empty( $loops[$loop_template]['single'] ) ? $loop_template : 'article';
			$loop = array_merge( array( 'loop' => $single_loop ), $single );
		}
		else {
			$tax = ( is_category() || is_tax() ) ? md_taxonomy_field( 'loop', array() ) : array();

			// Blank single settings fall back to the taxonomy and post type settings

			foreach ( $single as $key => $value )
				if ( $value === '' || $value === false || $value === null || $value === array() )
					unset( $single[$key] );

			$loop = array_replace_recursive( $post_type_loop, $tax, $single );
		}

		// Set additional parameters

		$loop['paged'] = get_query_var( 'paged' ) ?: 1;

		if ( empty( $loop['posts_per_page'] ) )
			$loop['posts_per_page'] = get_option( 'posts_per_page' );

		$category_loop = in_array( $loop['loop_type'] ?? '', array( 'category_posts', 'category' ), true );

		// Term archives only list categories when the term has children, and
		// only read term-ID settings set on the term or its taxonomy

		if ( is_category() || is_tax() ) {
			$queried = get_queried_object();

			if ( get_term_children( $queried->term_id, $queried->taxonomy ) ) {
				if ( $category_loop )
					$loop['by_category'] = true;

				if ( ! empty( $loop['category']['hide_subcategory'] ) )
					$loop['subcategory'] = true;
			}

			foreach ( array( 'category_include', 'category_exclude', 'include_cats', 'exclude_cats' ) as $key )
				$loop[$key] = md_module( array( 'loop', $key ), null, array( 'inherit_post_type' => false ) );
		}
		elseif ( $category_loop )
			$loop['by_category'] = true;

		if ( md_has_builder() )
			$loop['has_builder'] = true;

		if ( md_has_sidebar() )
			$loop['has_sidebar'] = true;
	}

	// Passed args win, then the loop template fills in its defaults

	unset( $args['loop_defaults'] );

	$loop = array_merge( $loop, $args );
	$loop['loop'] = ! empty( $loop['loop'] ) ? $loop['loop'] : 'article';
	$template = $loops[$loop['loop']] ?? array();
	$loop = array_merge( $template['defaults'] ?? array(), $loop );
	$loop['post_type'] = $post_type;
	$loop['loop_type'] = $loop['loop_type'] ?? '';

	// Month view groups posts by date, except on single pages

	if ( $loop['loop_type'] === 'month' && empty( $loop['by_category'] ) && ( ! is_singular() || ! empty( $args['query'] ) ) )
		$loop['by_date'] = true;

	if ( empty( $loop['columns'] ) )
		$loop['columns'] = 1;

	// Pick the box style: the template's archive style, else the site and post type setting

	if ( ! isset( $loop['style'] ) ) {
		if ( ! empty( $template['archive_style'] ) && ( is_archive() || is_home() ) )
			$loop['style'] = $template['archive_style'];
		else
			$loop['style'] = md_loop_style( ! empty( $args['query'] ) ? array( 'body' => true ) : array() );
	}

	// Narrow layouts get slim items

	if ( ! empty( $template['is_slim'] ) || ! empty( $loop['has_sidebar'] ) || $loop['columns'] >= 3 || ( ! empty( $loop['by_category'] ) && ( $loop['category_columns'] ?? 1 ) > 1 ) )
		$loop['is_slim'] = true;

	// The style wraps each entry, or the whole group in list views and category listings

	if ( ! empty( $template['style_target'] ) )
		$loop['style_target'] = $template['style_target'];
	elseif ( isset( $loop['by_category'] ) && $loop['loop_type'] !== 'category_posts' )
		$loop['style_target'] = 'group';
	else
		$loop['style_target'] = 'entry';

	if ( ! empty( $template['args'] ) )
		$loop['template'] = $template['args'];

	// Wrapper classes are built once here for the templates to read

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
	$loops = md_filter_loops();

	if ( ! isset( $sort ) )
		return $loops;

	$data = array();

	foreach ( $loops as $id => $fields ) {
		if ( isset( $fields['hide'] ) )
			continue;

		if ( $sort == 'ids' )
			$data[] = $id;
		elseif ( $sort == 'options' )
			$data[$id] = $fields['name'];
	}

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

	// In the editor: the post's own style, then its post type's, then the site's

	if ( ! empty( $args['post_id'] ) ) {
		$post_type = $args['post_type'] ?: md_get_post_type( $args['post_id'] );
		$inherited = md_post_type_field( array( 'layout', 'content_style' ), $body, $post_type ) ?: $body;

		if ( ! empty( $args['exclude_single'] ) )
			return $inherited;

		return md_post_meta( array( 'layout', 'content_style' ), $args['post_id'] ) ?: $inherited;
	}

	// On the front end: the viewed page's style, then its post type's, then the site's

	$post_type_style = md_post_type_field( array( 'layout', 'content_style' ) );

	return md_module( array( 'layout', 'content_style' ), $post_type_style, array( 'id' => get_queried_object_id() ) ) ?: $body;
}

/**
 * Build loop wrapper classes.
 *
 * @since 6.0
 */

function md_loop_classes( $loop = array() ) {
	$loop = $loop ?: md_get_loop();
	$loops = md_loops();
	$post_type = str_replace( '_', '-', $loop['post_type'] );
	$style = $loop['style'];
	$target = $loop['style_target'];
	$category_box = $loop['loop_type'] === 'category_posts' && ! empty( $loop['by_category'] ) && $style === 'box';

	// When each category section is the box, its posts render Plain

	$loop_style = $category_box ? 'plain' : $style;

	// Loop classes

	$classes = array( 'loop', "loop-$post_type", 'loop-' . $loop['loop'], "{$loop_style}-style", "{$loop_style}-{$target}" );

	if ( $loop['columns'] > 1 || ( $loop['columns_mobile'] ?? 1 ) > 1 ) {
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
	$category_columns = (int) ( $loop['category_columns'] ?? 1 );
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

	// Each category section

	$category_classes = array( 'entry' );

	if ( $loop['loop_type'] === 'category' )
		$category_classes[] = "{$style}-{$target}";
	elseif ( $category_box )
		$category_classes[] = 'box-group';

	// Return class sets

	return array(
		'loop' => join( ' ', $classes ),
		'categories' => join( ' ', $categories_classes ),
		'category' => join( ' ', $category_classes )
	);
}

/**
 * Build per-item loop settings from page-level defaults.
 *
 * @since 6.0
 */

function md_loop_item( $loop = array(), $c = 1 ) {

	// Excerpt text, with defaults unless turned off

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

	// Use the post's own media position when the loop doesn't set one

	if ( md_has_media() && ! isset( $loop['featured_image'] ) ) {
		$media = md_get_media();
		$loop['featured_image'] = $media['position'];

		if ( ! empty( $media['image']['id'] ) )
			$loop['featured_image_id'] = $media['image']['id'];
	}
	else unset( $loop['featured_image_id'] );

	if ( ! isset( $loop['content'] ) )
		$loop['content'] = '';

	// The first X items use the Featured settings

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
		'featured_featured_image_size' => 'featured_image_size',
		'featured_featured_image_mobile' => 'featured_image_mobile',
		'featured_inherit' => 'inherit',
		'featured_excerpt_more' => 'excerpt_more',
		'featured_excerpt_length' => 'excerpt_length',
		'featured_read_more' => 'read_more',
		'featured_excerpt_settings' => 'excerpt_settings'
	);

	if ( empty( $loop['featured_featured_image_size'] ) )
		$loop['featured_image_size'] = 'full';

	// Each Featured setting that's set replaces its standard setting

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
 * Calculate classes to apply to post box.
 * Returns a string ($classes) to be used in post_class( $classes ) in template.
 *
 * @since 6.0
 */

function md_post_class( $loop = array(), $c = 1 ) {
	$classes = array( 'entry' );
	$cover = md_cover( 'post' );
	$loop = $loop ?: md_get_loop();
	$classes[] = ! empty( $loop['featured'] ) && isset( $loop['is_featured'] ) ? 'featured' : 'standard';

	if ( ! is_singular() ) {
		$classes[] = $c % 2 == 0 ? 'even' : 'odd';

		if ( is_sticky() )
			$classes[] = 'sticky';
	}

	// Image position classes, except for positions set beside the title

	$position = isset( $loop['featured_image'] ) ? md_loop_media_position( $loop ) : '';

	if ( $position && ! in_array( $position, array( 'remove', 'title_left', 'title_right', 'title_center' ), true ) )
		$classes = array_merge( $classes, md_get_image_position_classes( $position, array( 'mobile' => is_singular() ? md_post_meta( array( 'featured_media', 'mobile' ) ) : ( $loop['featured_image_mobile'] ?? '' ) ) ) );

	if ( isset( $cover['position'] ) )
		$classes[] = 'has-cover';

	return join( ' ', $classes );
}

/**
 * Get a Loop heading's tag. A loop with heading_offset (a labeled Query Loop)
 * drops each heading that many levels, so an h2 becomes an h3.
 *
 * @since 6.0.3
 */

function md_loop_heading( $level, $loop = array() ) {
	return 'h' . min( 6, $level + (int) ( $loop['heading_offset'] ?? 0 ) );
}

/**
 * Checks if the post content is enabled on page.
 *
 * @since 6.0
 */

function md_has_post_content() {
	if ( ( is_singular() || is_404() ) && md_module( array( 'layout', 'content', 'the_content' ) ) )
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
	$id = is_singular() ? ' id="the_content"' : '';
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
 * Hook custom content after Loop Item X.
 *
 * @since 5.1
 */

function md_hook_x_loop( $loop, $c ) {
	if ( ! empty( $loop['cta_x_loop'] ) && $c == $loop['cta_x_loop'] && $loop['paged'] == 1 )
		do_action( 'md_hook_x_loop', $loop );
}

/**
 * Create pagination for use on home and archives pages.
 *
 * @since 4.0
 */

function md_pagination( $loop = array() ) {
	if ( ! empty( $loop['no_pagination'] ) )
		return;

	$loop = $loop ?: md_get_loop();
	$query = ( $loop['query'] ?? null ) instanceof WP_Query ? $loop['query'] : null;

	// Single posts only paginate their own queries

	if ( is_singular() && ! $query )
		return;

	// Manual queries passed as arrays never borrow the main query's pages

	if ( isset( $loop['query'] ) && ! $query && ! isset( $loop['by_category'] ) )
		return;

	$big = 999999999;
	$type = $loop['pagination_type'] ?? md_module( array( 'loop', 'pagination' ) );
	$classes = $type == 'prev_next' ? 'prev-next' : 'numbers';

	// Count pages from categories, the loop's own query, or the main query

	if ( isset( $loop['by_category'] ) ) {
		$per_page = (int) ( $loop['category_per_page'] ?? 0 );
		$total = $per_page > 0 ? ceil( md_category_loop_count( $loop ) / $per_page ) : 1;
	}
	elseif ( $query )
		$total = $query->max_num_pages;
	else {
		global $wp_query;
		$total = $wp_query->max_num_pages;
	}

	if ( $total <= 1 )
		return;

	$page = $query ? max( 1, (int) $query->get( 'paged' ) ) : max( 1, get_query_var( 'paged' ) );
	$page_arg = ! empty( $loop['pagination_arg'] ) ? sanitize_key( $loop['pagination_arg'] ) : '';

	include md_template( 'features', 'loop/pagination', true );
}

/**
 * Get a list of Sticky posts by post type.
 *
 * @since 6.0
 */

function md_get_sticky( $post_type = null ) {
	$post_type = $post_type ?: get_post_type();
	$sticky = array();

	foreach ( get_option( 'sticky_posts', array() ) as $id )
		if ( $post_type === get_post_type( $id ) )
			$sticky[] = $id;

	return $sticky;
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
	$post_type = $args['post_type'] ?: get_query_var( 'post_type' ) ?: md_get_post_type();

	// Posts use plain date links; other post types add their own

	if ( $post_type && $post_type !== 'post' )
		$query_args['post_type'] = $post_type;

	// On a term archive, keep the date link inside that term

	if ( ! $args['taxonomy'] && ( is_category() || is_tag() || is_tax() ) ) {
		$term = get_queried_object();
		$args['taxonomy'] = $term->taxonomy;
		$args['term'] = $term->slug;
	}

	$taxonomy = $args['term'] ? get_taxonomy( $args['taxonomy'] ) : false;

	if ( $taxonomy && $taxonomy->query_var )
		$query_args[$taxonomy->query_var] = $args['term'];

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

	// Don't link a month heading to the month archive already being viewed

	if ( is_month() && absint( get_query_var( 'year' ) ) === $year && absint( get_query_var( 'monthnum' ) ) === $month )
		$url = '';

	return apply_filters( 'md_filter_loop_date', array(
		'month' => wp_date( 'Y-m', $timestamp ),
		'label' => wp_date( 'F Y', $timestamp ),
		'url' => $url
	), $post, $args );
}
