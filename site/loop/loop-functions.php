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
			'name' => __( 'Simple List', 'md' ),
			'description' => __( 'A simplified list with compact images.', 'md' ),
			'image' => MD_URL . 'lib/admin/images/loop-list.png'
		),
		'icons' => array(
			'name' => __( 'Icon Cards', 'md' ),
			'description' => __( 'Small cards with a focus on the image thumbnail.', 'md' ),
			'image' => MD_URL . 'lib/admin/images/loop-icons.png'
		),
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
 * @since 5.6
 */

function md_query( $position = null ) {
	$queries = md_module( array( 'loop', 'query' ), array() );

	foreach ( $queries as $query_id => $loop ) {
		if ( isset( $position ) && $loop['position'] !== $position )
			continue;

		include( md_template( 'loop/query', true ) );
	}
}

function md_query_before_loop() {
	md_query( 'before_loop' );
}
add_action( 'md_hook_before_content_box', 'md_query_before_loop', 20 );

function md_query_after_loop() {
	md_query( 'after_loop' );
}
add_action( 'md_hook_before_footer', 'md_query_after_loop' );

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
	else
		$loop = md_module( 'loop' );

	$loop['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	$posts_per_page = ! empty( $loop['posts_per_page'] ) ? $loop['posts_per_page'] : get_option( 'posts_per_page' );

	$columns = ! empty( $loop['columns'] ) ? $loop['columns'] : 1;
	$wrap_classes[] = "loop-{$post_type}";

	if ( $columns > 1 ) {
		$wrap_classes[] = 'columns';

		if ( ( md_has_sidebar() && ! isset( $args['has_sidebar'] ) || isset( $args['has_sidebar'] ) ) || $columns >= 3 )
			$wrap_classes[] = 'slim';
		elseif ( $columns == 2 )
			$wrap_classes[] = 'wide';
	}

	$wrap_classes = apply_filters( 'md_filter_loop_classes', $wrap_classes );
	$wrap_classes = ' ' . join( ' ', $wrap_classes );

	if ( isset( $args['sticky'] ) )
		include( md_template( 'loop/the-post', true ) );
	elseif ( ! empty( $loop['category_posts']['enable'] ) )
		include( md_template( 'loop/category-posts', true ) );
	elseif ( isset( $args['query'] ) ) {
		echo ! is_singular() ? "<div class=\"loop$wrap_classes\">" : '';
		include( md_template( 'loop/the-query', true ) );
		echo ! is_singular() ? '</div>' : '';
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
			md_pagination();
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

	$classes[] = 'entry';

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

	$image_args = array();
	$context = isset( $args['context'] ) ? $args['context'] : 'post';
	$loop = isset( $args['loop'] ) ? $args['loop'] : array();
	$classes = isset( $args['classes'] ) ? $args['classes'] : array();

	$cover = md_cover( $context );
	$style = isset( $cover['style'] ) ? md_style( $cover['style'] ) : '';

	$classes[] = "$context-header";
	$classes = array_merge( md_cover_classes( $cover ), $classes );
	$classes = apply_filters( 'md_filter_headline_classes', $classes );
	$classes = join( ' ' , $classes );

	if ( isset( $loop['is_featured'] ) )
		$image_args['position'] = $loop['is_featured'];
	elseif ( isset( $args['featured_image'] ) )
		$image_args['position'] = md_post_meta( array( 'featured_image', 'position' ), null, $args['featured_image'] );

	if ( $context == 'post' ) {
		$image_args['show'] = 'above_headline';

		md_featured_image( $image_args );
	}

	include( md_template( 'headline', true ) );

	if ( $context == 'post' ) {
		$image_args['show'] = 'below_headline';

		md_featured_image( $image_args );
	}
}

/**
 * Show full content or excerpt of any given page.
 *
 * @since 5.1
 */

function md_the_content( $loop ) {
	$class = '';

	if ( empty( $loop['read_more'] ) )
		$loop['read_more'] = __( 'Continue reading &rarr;', 'md' );

	if ( ! empty( $loop['read_more_style'] ) )
		$class = ' button';

	md_hook_before_the_content();

	if ( ! is_singular() && ( empty( $loop['content'] ) || $loop['content'] == 'excerpt' ) ) {
		md_the_excerpt( $loop );
	?>
		<p class="read-more"><a href="<?php the_permalink(); ?>" class="more-link<?php echo $class; ?>"><?php echo esc_html( $loop['read_more'] ); ?></a></p>
	<?php } else {
		the_content( $loop['read_more'] );
		wp_link_pages();
	}

	md_hook_after_the_content();
}

/**
 * Create our own Excerpt with native WP functions so
 * we can modify length and more without use of filters.
 *
 * @since 5.6
 */

function md_the_excerpt( $loop ) {
	$excerpt = get_the_excerpt();
	$num_words = 55;
	$more = '[...]';

	if ( isset( $loop['excerpt_more'] ) )
		$more = $loop['excerpt_more'];

	if ( isset( $loop['excerpt_length'] ) )
		$num_words = $loop['excerpt_length'];

	$excerpt = wp_trim_words( $excerpt, $num_words, $more );

	echo wpautop( $excerpt );
}

/**
 * Displays post/page content text.
 *
 * @since 4.1
 */

function md_content_text( $loop ) {
	$classes = array( 'the-content' );
	$image_args = array( 'inline' => true );

	if ( isset( $loop['is_featured'] ) ) {
		$loop['content'] = ! empty( $loop['featured_content'] ) ? $loop['featured_content'] : 'excerpt';

		if ( ! empty( $loop['featured_featured_image'] ) )
			$loop['featured_image'] = $loop['featured_featured_image'];

		if ( ! empty( $loop['featured_read_more'] ) )
			$loop['read_more'] = $loop['featured_read_more'];

		if ( ! empty( $loop['featured_read_more_style'] ) )
			$loop['read_more_style'] = $loop['featured_read_more_style'];

		if ( ! empty( $loop['featured_excerpt_more'] ) )
			$loop['excerpt_more'] = $loop['featured_excerpt_more'];

		if ( ! empty( $loop['featured_excerpt_length'] ) )
			$loop['excerpt_length'] = $loop['featured_excerpt_length'];
	}

	if ( empty( $loop['content'] ) )
		if ( isset( $loop['is_query'] ) )
			$loop['content'] = 'excerpt';
		else
			$loop['content'] = md_post_type_field( array( 'loop', 'content' ), 'excerpt' );

	if ( ! empty( $loop['featured_image'] ) )
		$image_args['position'] = md_post_meta( array( 'featured_image', 'position' ), null, $loop['featured_image'] );

	if ( md_meta( array( 'layout', 'content', 'full' ) ) )
		$classes[] = 'full';

	$classes = apply_filters( 'md_the_content_classes', $classes );
	$classes = join( ' ', $classes );

	if ( get_the_content() && ( $loop['content'] !== 'hide' || is_singular() || is_404() ) )
		include( md_template( 'text', true ) );

	md_byline( 'after_post', array(
		'classes' => 'post-footer',
		'loop' => $loop
	) );
}
