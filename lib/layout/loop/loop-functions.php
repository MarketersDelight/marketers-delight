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
		'teasers' => array(
			'name' => __( 'Teasers', 'md' ),
			'columns' => true
		),
		'blocks' => array(
			'name' => __( 'Blocks', 'md' )
		)
	) );

	if ( isset( $sort ) ) {
		foreach ( $loops as $id => $fields )
			if ( $sort == 'ids' )
				$data[] = $id;
			elseif ( $sort == 'options' )
				$data[$id] = $fields['name'];
	}
	else
		$data = $loops;

	return $data;
}

/**
 * Get the Loop on the current page.
 *
 * Simplified in 5.6 to no longer pull setting values and
 * strictly return loop type of current page only.
 *
 * @since 4.6.4
 */

function md_get_loop() {
	$default = 'default';
	$post_type = md_get_post_type();
	$loops = md_loops();

	if ( ! empty( $loops[$post_type] ) )
		$default = $post_type;

	if ( has_filter( 'md_filter_loop_type' ) )
		$loop = apply_filters( 'md_filter_loop_type', $default );
	else
		$loop = md_module( array( 'loop', 'archives' ), $default );

	return $loop;
}

/**
 * The Main Loop used on all posts, pages, and archives.
 *
 * @since 4.1
 */

function md_loop() {
	$c = 1;
	$h = md_html( 'h' );
	$html = md_html( 'article' );
	$loops = md_loops();
	$type = md_get_loop();
	$byline_position = md_module( array( 'loop', 'byline_position' ) );
	$content = md_module( array( 'loop', 'content' ) );
	$featured = md_module( array( 'loop', 'featured' ), '0' );
	$columns = md_module( array( 'loop', 'columns' ), 2 );
	$byline = md_get_byline();

	echo ! is_singular() ? '<div class="loop">' : '';

	if ( ! empty( $loops[$type]['dropin'] ) )
		include( md_template( 'dropins', "{$type}/loop-{$type}", true ) );
	else
		include( md_template( 'loops/loop' . ( $type == 'default' ? '' : "-{$type}" ), true ) );

	echo ! is_singular() ? '</div>' : '';
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

	if ( ! empty( $cover['position'] ) ) {
		$classes[] = 'has-cover';

		if ( is_singular() && $cover['position'] == 'headline_cover' )
			$classes[] = 'has-headline-cover';
	}

	if ( has_post_thumbnail() && ! empty( $position ) ) {
		$classes[] = 'has-image';

		if ( $position == 'above_headline' )
			$classes[] = 'has-top-image';
		elseif ( in_array( $position, array( '', 'left', 'right' ) ) )
			$classes[] = 'has-inline-image';
	}

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
 * Determines needed classes for a headline type element. Spacing,
 * padding, featured image styles, etc.
 *
 * @since 4.1
 */

function md_headline_classes( $args = array() ) {
	$classes = array();
	$classes[] = 'headline-wrap';

	if ( ! isset( $args['hide_cover'] ) ) {
		$cover_classes = md_cover_classes();

		if ( ! empty( $cover_classes ) )
			$classes[] = $cover_classes;
	}

	$classes = join( ' ', $classes );

	return apply_filters( 'md_filter_headline_classes', esc_attr( $classes ) );
}

/**
 * Displays the headline of any post/page.
 *
 * @since 4.1
 */

function md_headline() {
	$h = md_html( 'h' );

	include( md_template( 'headline', true ) );
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
		'last-updated' => __( 'Add <b>Last Updated</b>', 'md' ),
		'category' => __( 'Add <b>Category</b>', 'md' ),
		'comments' => __( 'Remove <b>Comments</b>', 'md' ),
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

	if ( $content == null )
		$content = md_module( array( 'loop', 'content' ) );

	$read_more = md_read_more_text();

	md_hook_before_the_content();
?>

	<?php if ( ! is_singular() && $content == 'excerpt' ) : ?>

		<?php the_excerpt(); ?>

		<?php if ( $loop == 'default' ) : ?>
			<a href="<?php the_permalink(); ?>" class="more-link"><?php echo esc_html( $read_more ); ?></a>
		<?php endif; ?>

	<?php else : ?>

		<?php the_content( $read_more ); ?>

		<?php if ( is_singular() ) : ?>
			<?php wp_link_pages(); ?>
		<?php endif; ?>

	<?php endif; ?>

<?php }

/**
 * Displays post/page content text.
 *
 * @since 4.1
 */

function md_content_text() {
	$content = md_module( array( 'loop', 'content' ) );
	$read_more = md_read_more_text();

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
