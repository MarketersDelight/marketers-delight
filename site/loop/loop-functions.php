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
		'cards' => array(
			'name' => __( 'Cards', 'md' ),
			'columns' => true
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

function md_loop() {
	$c = 1;
	$post_type = md_get_post_type();
	$loops = md_loops();
	$loop = md_get_loop();
	$byline = md_get_byline();
	$featured = md_module( array( 'loop', 'featured' ), '0' );
	$columns = md_module( array( 'loop', 'columns' ), 2 );

	$category_posts = md_module( array( 'loop', 'category_posts', 'enable' ) );

	if ( $category_posts ) {
		$taxonomies = get_object_taxonomies( $post_type );
		$taxonomy = ! empty( $taxonomies[0] ) ? $taxonomies[0] : '';
		$categories = get_terms( $taxonomy );

		if ( ! empty( $categories ) )
			include( md_template( 'loops/category-posts', true ) );
		else
			md_404_template();
	}
	elseif ( have_posts() ) {
		echo ! is_singular() ? '<div class="loop">' : '';

		while ( have_posts() ) {
			the_post();

			if ( ! empty( $loops[$loop]['dropin'] ) )
				include( md_template( 'dropins', "{$loop}/loop-{$loop}", true ) );
			elseif ( ! empty( $loops[$loop] ) )
				include( md_template( 'loops/loop' . ( $loop == 'default' ? '' : "-{$loop}" ), true ) );
			else
				include( md_template( 'loops/loop', true ) );

			md_hook_x_loop( $c );

			$c++;
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
	$classes[] = 'post-box';

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
	if ( get_the_title() && ! md_meta( array( 'layout', 'content', 'headline' ) ) )
		return true;
}

/**
 * Displays the headline of any post/page.
 *
 * @since 4.1
 */

function md_headline( $args = array() ) {
	$context = isset( $args['context'] ) ? $args['context'] : 'post';
	$cover = md_cover( $context );

	$h = is_singular() || $context == 'page' ? 'h1' : 'h2';
	$caption = '';
	$title = get_the_title();
	$permalink = null;

	$classes = isset( $args['classes'] ) ? $args['classes'] : array();
	$classes[] = "$context-header";

	if ( ! empty( $cover['position'] ) ) {
		if ( empty( $cover['hide_cover'] ) ) {
			$classes[] = 'cover';
			$classes[] = str_replace( '_', '-', $cover['position'] );

			if ( ! empty( $cover['display']['alternate'] ) )
				$classes[] = 'alt';
		}

		if ( is_singular() && ! empty( $cover['image']['id'] ) )
			$caption = md_get_caption( $cover['image']['id'] );
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
 * Checks for content headline.
 *
 * @since 4.1
 */

function md_has_headline_cover() {
	$cover = md_cover();

	return is_singular() && ! empty( $cover['position'] ) && in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) ? true : false;
}

/**
 * Checks if byline is enabled.
 *
 * @since 4.1
 */

function md_has_byline() {
	$add_byline = md_post_meta( array( 'layout', 'content', 'add_byline' ) );
	$remove_byline = md_post_meta( array( 'layout', 'content', 'byline' ) );

	if ( ( ! is_page() && ! is_404() && ! $remove_byline ) || ( is_page() && $add_byline ) )
		return true;
}

/**
 * Get user byline settings based on page location.
 *
 * @since 5.1
 */

function md_get_byline() {
	$byline = md_post_type_field( array( 'loop', 'byline' ), array() );

	if ( is_singular() ) {
		$single_byline = md_post_type_field( array( 'single', 'byline' ), array() );

		if ( ! empty( $single_byline ) )
			$byline = $single_byline;
	}

	if ( is_category() || is_tax() ) {
		$category_byline = md_term_meta( array( 'loop', 'byline' ), null, array() );

		if ( ! empty( $category_byline ) )
			$byline = $category_byline;
	}

	return array_keys( $byline );
}

/**
 * Get the general position of the current Page/Item byline.
 *
 * @since 5.6
 */

function md_get_byline_position() {
	$byline_position = md_post_type_field( array( 'loop', 'byline_position' ), 'before_headline' );

	if ( is_singular() ) {
		$single_byline_position = md_post_type_field( array( 'single', 'byline_position' ) );

		if ( $single_byline_position )
			$byline_position = $single_byline_position;
	}

	return $byline_position;
}

/**
 * Active list of byline items. Compares preset byline items (can
 * also be filtered in/out) with user settings).
 *
 * @since 4.5
 */

function md_byline_items( $sort = null ) {
	$items = apply_filters( 'md_filter_byline_items', array(
		'badge' => __( 'Add <b>New!</b> Badge', 'md' ),
		'avatar' => __( 'Add <b>Avatar</b>', 'md' ),
		'author' => __( 'Remove <b>Author</b>', 'md' ),
		'date' => __( 'Remove <b>Date</b>', 'md' ),
		'category' => __( 'Add <b>Category</b>', 'md' ),
		'comments' => __( 'Remove <b>Comments</b>', 'md' ),
		'last-updated' => __( 'Add <b>Last Updated</b>', 'md' ),
		'edit' => __( 'Remove <b>Edit</b>', 'md' )
	) );
	$settings = md_get_byline();
	$byline = array_diff( $items, array_keys( $settings ) );

	if ( isset( $sort ) ) {
		$data = array();

		foreach ( $byline as $id => $label )
			if ( $sort == 'ids' )
				$data[] = $id;

		return $data;
	}

	return $byline;
}

/**
 * Display template of individual byline items with
 * optional arguments.
 *
 * @since 5.1
 */

function md_byline_item( $item, $args = array() ) {
	$post_type = get_post_type();
	$post_id = get_the_ID();
	$author_id = get_the_author_meta( 'ID' );
	$byline = md_get_byline();
	$template = locate_template( "templates/byline/$item.php" );

	if ( isset( $args['post_id'] ) )
		$post_id = $args['post_id'];

	if ( isset( $args['author_id'] ) )
		$author_id = $args['author_id'];

	if ( $template )
		include( md_template( "byline/$item", true ) );
}

/**
 * A list of classes to add to the sidebar.
 *
 * @since 4.5
 */

function md_byline_classes() {
	$classes[] = 'byline';
	$classes = apply_filters( 'md_filter_byline_classes', $classes );

	return join( ' ', $classes );
}

/**
 * Output post byline template.
 *
 * @since 4.0
 */

function md_byline( $args = array() ) {
	$classes = md_byline_classes();
	$byline_items = md_byline_items();

	include( md_template( 'byline/byline', true ) );
}

/**
 * Show full content or excerpt of any given page.
 *
 * @since 5.1
 */

function md_the_content( $content = null) {
	$loop = md_get_loop();

	if ( $content == null ) {
		$default = md_post_type_field( array( 'loop', 'content' ) );
		$content = md_module( array( 'loop', 'content' ), $default );
	}

	$read_more = md_read_more_text();

	md_hook_before_the_content();
?>

	<?php if ( ! is_singular() && empty( $content ) ) : ?>

		<?php the_excerpt(); ?>

		<a href="<?php the_permalink(); ?>" class="more-link"><?php echo esc_html( $read_more ); ?></a>

	<?php else : ?>

		<?php the_content( $read_more ); ?>

		<?php wp_link_pages(); ?>

	<?php endif; ?>

<?php }

/**
 * Displays post/page content text.
 *
 * @since 4.1
 */

function md_content_text() {
	$classes = array( 'the-content' );
	$default = md_post_type_field( array( 'loop', 'content' ) );
	$content = md_module( array( 'loop', 'content' ), $default );
	$full = md_meta( array( 'layout', 'content', 'full' ) );
	$read_more = md_read_more_text();

	if ( $full )
		$classes[] = 'full';

	$classes = apply_filters( 'md_the_content_classes', $classes );
	$classes = join( ' ', $classes );

	if ( $content !== 'hide' || is_singular() || is_404() )
		include( md_template( 'text', true ) );
}

/**
 * Change Read More text to user settings.
 *
 * @since 5.1
 */

function md_read_more_text() {
	$read_more = md_module( array( 'loop', 'read_more' ) );

	return ! empty( $read_more ) ? md_text_field( $read_more ) : __( 'Continue reading &rarr;', 'md' );
}

/**
 * Filter length of excerpts + more text of loops.
 *
 * @since 4.5
 */

function md_excerpt_length() {
	$words = md_module( array( 'loop', 'excerpt_length' ) );
	$words = ! empty( $words ) ? $words : 55;

	return apply_filters( 'md_filter_excerpt_length', esc_attr( $words ) );
}

add_filter( 'excerpt_length', 'md_excerpt_length' );

/**
 * Filter trailing excerpt more text.
 *
 * @since 4.5
 */

function md_excerpt_more( $more ) {
    return md_module( array( 'loop', 'excerpt_more' ), '[...]' );
}

add_filter( 'excerpt_more', 'md_excerpt_more' );
