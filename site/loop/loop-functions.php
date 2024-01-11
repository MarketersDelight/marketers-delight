<?php

/**
 * A list of Loops registered to MD's settings.
 *
 * @since 5.1
 */

function md_loops( $sort = null ) {
	$data = array();
	$loops = apply_filters( 'md_filter_loops', array(
		'default' => array(
			'name' => __( 'Default', 'md' )
		),
		'blocks' => array(
			'name' => __( 'Blocks', 'md' )
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
 * Get the Loop of the current page. If post_type parameter
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
 * The Main Loop used on all posts, pages, and archives.
 *
 * @since 4.1
 */

function md_query() {
	$queries = md_module( array( 'loop', 'query' ) );

	foreach ( $queries as $query_id => $fields )
		md_loop( array(
			'query' => $fields
		) );
}

function md_loop( $args = array() ) {
	$c = 1;
	$wrap_classes = array();
	$post_type = md_get_post_type();
	$loop_id = md_get_loop();
	$loops = md_loops();

	if ( isset( $args['query'] ) ) {
		$loop = $args['query'];

		if ( isset( $loop['post_type'] ) )
			$post_type = $loop['post_type'];

		if ( isset( $loop['archives'] ) )
			$loop_id = $loop['archives'];
	}
	else
		$loop = md_module( 'loop' );

	$posts_per_page = ! empty( $loop['posts_per_page'] ) ? $loop['posts_per_page'] : get_option( 'posts_per_page' );
	$columns = ! empty( $loop['columns'] ) ? $loop['columns'] : 1;
	$wrap_classes[] = "loop-{$post_type}";

	if ( $columns > 1 ) {
		$wrap_classes[] = 'columns';

		if ( md_has_sidebar() || $columns >= 3 )
			$wrap_classes[] = 'slim';
		elseif ( $columns == 2 )
			$wrap_classes[] = 'wide';
	}
	else
		$wrap_classes[] = 'standard';

	$wrap_classes = apply_filters( 'md_filter_loop_classes', $wrap_classes );
	$wrap_classes = ' ' . join( ' ', $wrap_classes );

	if ( isset( $args['sticky'] ) )
		include( md_template( 'loop/the-post', true ) );
	elseif ( ! empty( $loop['category_posts']['enable'] ) )
		include( md_template( 'loop/category-posts', true ) );
	elseif ( isset( $args['query'] ) )
		include( md_template( 'loop/query', true ) );
	elseif ( have_posts() ) {
		echo ! is_singular() ? "<div class=\"loop$wrap_classes\">" : '';

		md_hook_loop_top();

		while ( have_posts() ) {
			the_post();

			include( md_template( 'loop/the-post', true ) );
		}

		echo ! is_singular() ? '</div>' : '';
	}
	else
		md_404_template();
}

/**
 * Hook custom content after Loop Item X.
 *
 * @since 5.1
 */

function md_hook_x_loop( $c ) {
	$x_loop = md_module( array( 'loop', 'cta_x_loop' ) );
	$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

	if ( $c == $x_loop && $paged == 1 )
		do_action( 'md_hook_x_loop' );
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
	$classes[] = 'entry';

	// Remove excess WP classes
	$classes = array_diff( $classes, array(
		'hentry',
		'format-standard',
		'post-' . get_the_ID(),
		'type-' . get_post_type(),
		'status-' . get_post_status(),
		'format-' . get_post_format()
	) );

	// Apply classes to certain featured image positions
	$position = md_featured_image_position();
	$cover = md_cover();

	if ( ! empty( $cover['position'] ) )
		$classes[] = 'has-cover';

	if ( has_post_thumbnail() && in_array( $position, array( 'above_headline', 'below_headline' ) ) )
		$classes[] = 'image-' . str_replace( '_', '-', $position );

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
	$context = isset( $args['context'] ) ? $args['context'] : 'post';
	$cover = md_cover( $context );
	$category_posts = md_module( array( 'loop', 'category_posts', 'enable' ) );

	$title = get_the_title();
	$permalink = null;

	$h = is_singular() || $context == 'page' ? 'h1' : 'h2';

	if ( $context == 'post' && $category_posts )
		$h = 'h3';

	$classes = isset( $args['classes'] ) ? $args['classes'] : array();
	$classes[] = "$context-header";

	if ( ! empty( $cover['position'] ) && empty( $cover['hide_cover'] ) ) {
		$classes[] = 'cover';
		$classes[] = str_replace( '_', '-', $cover['position'] );

		if ( ! empty( $cover['display']['alternate'] ) )
			$classes[] = 'alt';
	}

	$classes = apply_filters( 'md_filter_headline_classes', $classes );
	$classes = join( ' ' , $classes );

	$style = isset( $cover['style'] ) ? md_style( $cover['style'] ) : '';

	if ( isset( $args['title'] ) )
		$title = $args['title'];

	if ( ! is_singular() && $context == 'post' )
		$permalink = get_permalink();

	include( md_template( 'headline', true ) );
}

/**
 * Get Cover attributes for any given page.
 *
 * @since 4.1
 * @renamed 5.6 (md_featured_image_style)
 */

function md_cover( $context = 'post' ) {
	$cover = array();

	if ( $context == 'page' )
		if ( is_category() || is_tax() )
			$cover = md_term_meta( 'page_cover' );
		else
			$cover = md_post_type_field( 'page_cover' );
	else
		$cover = md_post_meta( 'page_cover' );

	if ( ! empty( $cover['image'] ) )
		$cover['style'] = array(
			'bg_image' => esc_url( $cover['image']['url'] ),
//			'bg_size' => $cover['image'][1] < 500 ? 'auto' : 'cover'
			'bg_size' => 'auto'
		);

	if ( ! empty( $cover['position'] ) && $cover['position'] == 'header_cover_full' && ( $context !== 'post' || $context == 'post' && is_singular() ) ) {
		unset( $cover['style'] );
		$cover['display']['disable_cover'] = true;
	}

	return $cover;
}

/**
 * A simple and thorough check to detect Page Cover.
 *
 * @since 5.6
 */

function md_has_cover() {
	$cover = md_cover();

	if ( ! empty( $cover['id'] && $cover['position'] ) )
		return true;

	return false;
}

/**
 * Add Overlay HTML to covers.
 *
 * @since 4.8.6
 */

function md_overlay( $cover ) {
	if ( empty( $cover['position'] ) || ! empty( $cover['display']['disable_cover'] ) )
		return;

	$style = array();

	if ( ! empty( $cover['bg_color'] ) )
		$style['bg_color'] = $cover['bg_color'];

	echo '<div class="overlay"' . md_style( $style ) . '></div>';
}

/**
 * Show full content or excerpt of any given page.
 *
 * @since 5.1
 */

function md_the_content( $loop ) {
	if ( empty( $loop['read_more'] ) )
		$loop['read_more'] = __( 'Continue reading &rarr;', 'md' );

	md_hook_before_the_content();

	if ( ! is_singular() && empty( $loop['content'] ) ) {
		md_the_excerpt( $loop );
	?>
		<a href="<?php the_permalink(); ?>" class="more-link"><?php echo esc_html( $loop['read_more'] ); ?></a>
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

function md_content_text( $loop = array() ) {
	$classes = array( 'the-content' );

	if ( empty( $loop ) )
		$loop = md_module( 'loop' );

	if ( empty( $loop['content'] ) )
		$loop['content'] = md_post_type_field( array( 'loop', 'content' ) );

	if ( md_meta( array( 'layout', 'content', 'full' ) ) )
		$classes[] = 'full';

	$classes = apply_filters( 'md_the_content_classes', $classes );
	$classes = join( ' ', $classes );

	if ( get_the_content() && ( $loop['content'] !== 'hide' || is_singular() || is_404() ) )
		include( md_template( 'text', true ) );
}
