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
		),
		'docs' => array(
			'name' => __( 'Docs', 'md' )
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

	echo ! is_singular() ? '<div class="loop">' : '';

	if ( ! empty( $loops[$type]['dropin'] ) )
		include( md_template( 'dropins', "{$type}/{$type}-loop", true ) );
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
    return md_setting( array( 'loop', 'excerpt_more' ), '[...]' );
}

add_filter( 'excerpt_more', 'md_excerpt_more' );
