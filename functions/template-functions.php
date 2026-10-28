<?php

/**
 * Pass a list of links to create a scrolling navigation element.
 *
 * @since 6.0
 */

function md_scroller_nav( $args = array() ) {
	static $js_enqueued = false;

	$classes = array( 'scroller-nav' );
	$html = ! empty( $args['html'] ) && $args['html'] === 'nav' ? 'nav' : 'div';
	$label = ! empty( $args['label'] ) ? ' aria-label="' . esc_attr( $args['label'] ) . '"' : '';

	if ( isset( $args['classes'] ) )
		$classes[] = $args['classes'];

	$classes = join( ' ', $classes );

	$output =
		"<$html class=\"" . esc_attr( $classes ) . "\"$label>" .
		'<button class="scroller-arrow scroller-arrow-prev" aria-label="' . __( 'Scroll left', 'md' ) . '">' . md_icon( 'angle-left' ) . '</button>' .
		'<div class="scroller-list">' .
		join( '', $args['items'] ) .
		'</div>' .
		'<button class="scroller-arrow scroller-arrow-next" aria-label="' . __( 'Scroll right', 'md' ) . '">' . md_icon( 'angle-right' ) . '</button>' .
		"</$html>";

	if ( ! $js_enqueued ) {
		wp_add_inline_script( 'marketers-delight', 'MD.scrollerNav();' );
		$js_enqueued = true;
	}

	if ( isset( $args['echo'] ) && ! $args['echo'] )
		return $output;

	echo $output;
}

/**
 * Replace archive tokens in a text string.
 *
 * @since 6.0
 */

function md_parse_text( $text, $context = '' ) {
	if ( empty( $text ) )
		return $text;

	if ( empty( $context ) )
		if ( is_tax() || is_category() || is_tag() )
			$context = 'term';
		elseif ( is_home() || is_post_type_archive() || is_author() )
			$context = 'archive';

	$tokens = md_parse_tokens( array( 'context' => $context, 'text' => $text )  );

	return empty( $tokens ) ? $text : strtr( $text, $tokens );
}

/**
 * Word a count from an item's Text and Singular Text settings, falling
 * back to the given singular and plural text. {count} places the number;
 * text without it gets the number in front. Pass $number to print the
 * number with markup of its own.
 *
 * @since 6.1
 */

function md_count_text( $count, $fields, $singular, $plural, $number = '' ) {
	$template = $count === 1 ? $singular : $plural;

	if ( ! empty( $fields['name'] ) )
		$template = $count === 1 && ! empty( $fields['singular_name'] ) ? $fields['singular_name'] : $fields['name'];

	if ( strpos( $template, '{count}' ) === false )
		$template = '{count} ' . $template;

	return str_replace( '{count}', $number ?: esc_html( number_format_i18n( $count ) ), esc_html( $template ) );
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
 * Get token key→value map (or key list) for archive text replacement.
 *
 * @since 6.0
 */

function md_parse_tokens( $args = array() ) {
	$tokens = array();
	$context = $args['context'] ?? '';
	$text = $args['text'] ?? '';
	$list = ! empty( $args['list'] );

	if ( $context === 'term' ) {
		$definitions = array(
			'{name}' => __( 'Category name', 'md' ),
			'{description}' => __( 'Category description', 'md' ),
			'{url}' => __( 'Category URL', 'md' ),
			'{slug}' => __( 'Category slug', 'md' ),
			'{count}' => __( 'Total posts in category', 'md' ),
			'{total}' => __( 'Total published posts', 'md' ),
			'{taxonomy}' => __( 'Taxonomy label', 'md' ),
			'{label}' => __( 'Post type singular label', 'md' ),
			'{plural}' => __( 'Post type plural label', 'md' ),
			'{parent}' => __( 'Parent category name', 'md' )
		);

		if ( $list )
			return apply_filters( 'md_parse_tokens', $definitions, $args );

		$tokens = array_fill_keys( array_keys( $definitions ), '' );
		$term = get_queried_object();

		if ( $term instanceof WP_Term ) {
			$taxonomy  = get_taxonomy( $term->taxonomy );
			$obj_type  = ! empty( $taxonomy->object_type ) ? $taxonomy->object_type[0] : '';
			$post_type = $obj_type ? get_post_type_object( $obj_type ) : null;

			$tokens = array(
				'{name}' => $term->name,
				'{description}' => $term->description,
				'{url}' => get_term_link( $term ),
				'{slug}' => $term->slug,
				'{count}' => $term->count,
				'{label}' => $post_type ? $post_type->labels->singular_name : '',
				'{taxonomy}' => $taxonomy ? $taxonomy->labels->singular_name : '',
				'{plural}' => $post_type ? $post_type->labels->name : ''
			);

			if ( strpos( $text, '{count}' ) !== false && $taxonomy && $taxonomy->hierarchical && get_term_children( $term->term_id, $term->taxonomy ) )
				$tokens['{count}'] = md_term_post_count( $term );

			if ( strpos( $text, '{parent}' ) !== false )
				$tokens['{parent}'] = $term->parent ? get_term( $term->parent, $term->taxonomy )->name : '';

			if ( strpos( $text, '{total}' ) !== false && $obj_type )
				$tokens['{total}'] = wp_count_posts( $obj_type )->publish;
		}
		else $tokens = array();
	}
	elseif ( in_array( $context, array( 'archive', 'author' ), true ) ) {
		$is_author = $context === 'author' || is_author();
		$definitions = $is_author
			? array(
				'{name}' => __( 'Author display name', 'md' ),
				'{label}' => __( 'Author label', 'md' ),
				'{url}' => __( 'Author archive URL', 'md' ),
				'{total}' => __( 'Total posts by this author', 'md' )
			)
			: array(
				'{name}' => __( 'Post type plural name', 'md' ),
				'{label}' => __( 'Post type singular label', 'md' ),
				'{url}' => __( 'Archive URL', 'md' ),
				'{total}' => __( 'Total posts', 'md' )
			);

		if ( $list )
			return apply_filters( 'md_parse_tokens', $definitions, $args );

		$tokens = array_fill_keys( array_keys( $definitions ), '' );
		$queried = get_queried_object();

		if ( $is_author && $queried && isset( $queried->ID, $queried->display_name ) ) {
			$tokens = array(
				'{name}' => $queried->display_name,
				'{label}' => __( 'Author', 'md' ),
				'{url}' => get_author_posts_url( $queried->ID )
			);

			if ( strpos( $text, '{total}' ) !== false )
				$tokens['{total}'] = count_user_posts( $queried->ID, 'post', true );
		}
		else {
			$post_type = get_post_type_object( $queried->name ?? '' );

			if ( ! $post_type )
				$tokens = array();
			else {
				$tokens = array(
					'{name}' => $post_type->labels->name,
					'{label}' => $post_type->labels->singular_name,
					'{url}' => get_post_type_archive_link( $post_type->name ),
				);

				if ( strpos( $text, '{total}' ) !== false )
					$tokens['{total}'] = wp_count_posts( $post_type->name )->publish;
			}
		}
	}

	return apply_filters( 'md_parse_tokens', $tokens, $args );
}

/**
 * Setup visibility filter with various conditions.
 *
 * @since 6.0
 */

function md_visibility_conditions() {
	return apply_filters( 'md_visibility_conditions', array(
		'logged_in' => array(
			'label' => __( 'Logged in users only', 'md' ),
			'check' => function() { return is_user_logged_in(); }
		),
		'logged_out' => array(
			'label' => __( 'Logged out users only', 'md' ),
			'check' => function() { return ! is_user_logged_in(); }
		),
		'desktop' => array(
			'label' => __( 'Show on desktop only', 'md' ),
			'class' => 'show-desktop'
		),
		'mobile' => array(
			'label' => __( 'Show on mobile only', 'md' ),
			'class' => 'show-mobile'
		)
	) );
}

/**
 * Check visibility conditions applied to an element and show/hide.
 *
 * @since 6.0
 */

function md_check_condition( $values ) {
	if ( empty( $values ) )
		return true;

	foreach ( md_visibility_conditions() as $key => $item ) {
		if ( empty( $values[$key] ) || ! isset( $item['check'] ) )
			continue;

		if ( ! call_user_func( $item['check'] ) )
			return false;
	}

	return true;
}

/**
 * Some visibility conditions are HTML class based, and are compiled here.
 *
 * @since 6.0
 */

function md_get_visibility_classes( $values ) {
	if ( empty( $values ) )
		return array();

	$classes = array();

	foreach ( md_visibility_conditions() as $key => $item )
		if ( ! empty( $values[$key] ) && isset( $item['class'] ) )
			$classes[] = $item['class'];

	return $classes;
}

/**
 * Return a theme or Drop-in template through the [md_template] shortcode.
 *
 * @since 6.0
 */

function md_template_shortcode( $atts, $content = null ) {
	ob_start();

	$atts = shortcode_atts( array(
		'name' => '',
		'dropin_name' => ''
	), $atts, 'md_template' );

	if ( ! empty( $atts['dropin_name'] ) )
		include md_template( 'dropins', $atts['dropin_name'], true );
	elseif ( ! empty( $atts['name'] ) )
		include md_template( $atts['name'], true );

	return ob_get_clean();
}

/**
 * Allow safe HTML in a saved text field.
 *
 * @since 4.0
 */

function md_text_field( $string ) {
	return wp_kses_post( $string );
}
