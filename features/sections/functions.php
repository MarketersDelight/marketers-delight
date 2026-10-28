<?php
/**
 * Sections functions: how Archive Meta renders, and the Section Title
 * item. The Taxonomy Filter item lives in taxonomy-filter.php.
 *
 * @since 6.0
 */

/**
 * Get list of items that can be used in Archive Meta, optionally
 * limited to items available for a post type.
 *
 * @since 6.0
 */

function md_archive_meta_items( $post_type = null ) {
	$items = apply_filters( 'md_archive_meta_items', array(), $post_type );

	return $post_type === null ? $items : md_builder_items_for( $items, $post_type );
}

/**
 * Count the approved comments across everything the current archive, or
 * a given term, lists. The result is remembered for the request, so the
 * Comment Count meta and anything else showing it share one query.
 *
 * @since 6.0
 */

function md_archive_comment_total( $post_type, $term = null ) {
	static $totals = array();

	$queried = $term ?: get_queried_object();
	$term_id = $queried instanceof WP_Term ? $queried->term_id : 0;
	$key = implode( ',', (array) $post_type ) . ":$term_id";

	if ( isset( $totals[$key] ) )
		return $totals[$key];

	$args = array(
		'post_type' => $post_type,
		'post_status' => 'publish',
		'status' => 'approve',
		'type' => 'comment',
		'count' => true
	);

	if ( $term_id ) {
		$post_ids = get_posts( array(
			'post_type' => $post_type,
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'fields' => 'ids',
			'tax_query' => array( array(
				'taxonomy' => $queried->taxonomy,
				'terms' => $term_id
			) )
		) );

		if ( ! $post_ids )
			return $totals[$key] = 0;

		$args['post__in'] = $post_ids;
	}

	return $totals[$key] = (int) get_comments( $args );
}

/**
 * Emphasize the numbers in a plain text Archive Meta value. A value with
 * markup marks its own numbers, like a link or a strong date, so it's
 * left as it is.
 *
 * @since 6.0
 */

function md_archive_meta_value( $value ) {
	$value = wp_kses_post( $value );

	return strpos( $value, '<' ) === false ? preg_replace( '/\d[\d,.]*/', '<strong>$0</strong>', $value ) : $value;
}

/**
 * Link an Archive Meta value to a page, unless it's the page being
 * viewed. Numbers inside the link are emphasized here, since values
 * only get that outside markup.
 *
 * @since 6.1
 */

function md_archive_meta_link( $text, $url ) {
	if ( ! $url || is_wp_error( $url ) )
		return $text;

	$current = untrailingslashit( strtok( get_pagenum_link( 1, false ), '?#' ) );

	if ( untrailingslashit( strtok( $url, '?#' ) ) === $current )
		return $text;

	return '<a href="' . esc_url( $url ) . '">' . md_archive_meta_value( $text ) . '</a>';
}

/**
 * Count a term's published posts, including its child terms' posts, like
 * its archive page lists them. Posts in several child terms count once.
 *
 * @since 6.1
 */

function md_term_post_count( $term, $post_type = '' ) {
	$taxonomy = get_taxonomy( $term->taxonomy );

	$query = new WP_Query( array(
		'post_type' => $post_type ?: ( $taxonomy ? $taxonomy->object_type : 'any' ),
		'post_status' => 'publish',
		'posts_per_page' => 1,
		'fields' => 'ids',
		'tax_query' => array( array(
			'taxonomy' => $term->taxonomy,
			'terms' => $term->term_id,
			'include_children' => true
		) )
	) );

	return (int) $query->found_posts;
}

/**
 * Get Archive Meta markup for a builder area, keyed by the title position
 * each item is placed in. Items without a position are After Title. The
 * archives area measures the current archive; category_entry measures
 * the given term, with the settings of the post type its loop lists.
 *
 * @since 6.1
 */

function md_get_archive_meta( $area = 'archives', $term = null, $post_type = '' ) {
	if ( $area === 'archives' && ! is_home() && ! is_archive() )
		return array();

	$post_type = $post_type ?: md_get_post_type();
	$builder = md_get_post_type_builder( 'archive_meta', $post_type );
	$items = md_archive_meta_items( $post_type );
	$positions = $meta = array();

	foreach ( $builder as $fields ) {
		if ( md_builder_row_hidden( $fields ) )
			continue;

		if ( ( $fields['builder_area'] ?? '' ) !== $area )
			continue;

		$type = $fields['builder_type'] ?? '';
		$callback = $items[$type]['callback'] ?? null;

		if ( ! is_callable( $callback ) )
			continue;

		$value = call_user_func( $callback, $fields, $post_type, $term );

		if ( ! $value )
			continue;

		$position = ! empty( $fields['position'] ) ? $fields['position'] : 'after_title';

		if ( ! isset( $positions[$position] ) )
			$positions[$position] = '';

		$icon = ! empty( $items[$type]['meta_icon'] ) ? md_icon( $items[$type]['meta_icon'] ) : '';

		$positions[$position] .= '<span class="byline-item">' . $icon . '<span class="byline-value">' . md_archive_meta_value( $value ) . '</span></span>';
	}

	foreach ( $positions as $position => $output )
		$meta[$position] = '<div class="byline ' . esc_attr( str_replace( '_', '-', $position ) ) . ' archive-meta">' . $output . '</div>';

	return $meta;
}

/**
 * Row fields and their save rules.
 *
 * @since 6.1
 */

function md_section_title_fields() {
	return array(
		'section_title' => array( 'type' => 'text' ),
		'section_text' => array( 'type' => 'textarea' ),
		'section_heading' => array(
			'type' => 'select',
			'options' => array( 'h2', 'h3' )
		),
		'section_display' => array(
			'type' => 'checkbox',
			'options' => array( 'archive_meta' )
		),
		'section_media' => md_featured_media_fields(),
		'section_cover' => md_page_cover_fields(),
		'section_cta' => md_page_cta_fields()
	);
}

/**
 * Turn a row's settings into the data md_title_template() takes. A row is
 * literal: nothing inherits from the page it's shown on.
 *
 * @since 6.1
 */

function md_section_title_data( $row ) {
	$media = $row['section_media'] ?? array();
	$cover = $row['section_cover'] ?? array();

	if ( ! empty( $media['image']['id'] ) ) {
		$media['media_type'] = 'image';
		$media['position'] = ! empty( $media['position'] ) ? $media['position'] : 'title_left';
	}
	else $media = array();

	if ( ! empty( $cover['bg_color'] ) || ! empty( $cover['photo']['id'] ) )
		$cover['position'] = 'headline_cover';
	else
		$cover = array();

	return array(
		'title' => $row['section_title'] ?? '',
		'description' => $row['section_text'] ?? '',
		'media' => $media,
		'cover' => $cover,
		'cta' => $row['section_cta'] ?? array(),
		'identity' => array(),
		'archive_meta' => ! empty( $row['section_display']['archive_meta'] ) ? md_get_archive_meta() : array(),
		'header_cover' => false,
		'has_sidebar' => false,
		'loop' => array()
	);
}

/**
 * Render a Section Title row.
 *
 * @since 6.1
 */

function md_section_title( $row, $post_type, $id, $main_query ) {
	$data = md_section_title_data( $row );

	if ( ! $data['title'] && ! $data['description'] && ! $data['media'] )
		return;

	$data['has_sidebar'] = md_has_sidebar();

	md_title_template( 'section', $data, array(
		'tag' => 'section',
		'heading' => ! empty( $row['section_heading'] ) ? $row['section_heading'] : 'h2'
	) );
}

/**
 * Render a Section Title row's controls.
 *
 * @since 6.1
 */

function md_section_title_admin_fields( $group, $type, $fields ) {
	$prefix = 'md_section_title_' . sanitize_key( $group );
	$media_fields = $fields->within( array( 'builder', $group, 'section_media' ) );
	$media_prefix = "{$prefix}_media";
	$cta_fields = $fields->within( array( 'builder', $group, 'section_cta' ) );
	$cta_prefix = "{$prefix}_cta";

	include md_template( 'features', 'sections/admin/section-title', true );
}
