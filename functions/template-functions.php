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

	echo
		"<$html class=\"" . esc_attr( $classes ) . "\"$label>" .
		'<button class="scroller-arrow scroller-arrow-prev" aria-label="' . __( 'Scroll left', 'md' ) . '">' . md_icon( 'angle-left' ) . '</button>' .
		'<div class="scroller-list">';

	foreach ( $args['items'] as $item )
		echo $item;

	echo
		'</div>' .
		'<button class="scroller-arrow scroller-arrow-next" aria-label="' . __( 'Scroll right', 'md' ) . '">' . md_icon( 'angle-right' ) . '</button>' .
		"</$html>";

	if ( ! $js_enqueued ) {
		wp_add_inline_script( 'marketers-delight', 'MD.scrollerNav();' );
		$js_enqueued = true;
	}
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
		elseif ( is_home() || is_post_type_archive() )
			$context = 'archive';

	$tokens = md_parse_tokens( array( 'context' => $context, 'text' => $text )  );

	return empty( $tokens ) ? $text : strtr( $text, $tokens );
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

			if ( strpos( $text, '{parent}' ) !== false )
				$tokens['{parent}'] = $term->parent ? get_term( $term->parent, $term->taxonomy )->name : '';

			if ( strpos( $text, '{total}' ) !== false && $obj_type )
				$tokens['{total}'] = wp_count_posts( $obj_type )->publish;
		}
		else $tokens = array();
	}
	elseif ( $context === 'archive' ) {
		$definitions = array(
			'{name}' => __( 'Post type plural name', 'md' ),
			'{label}' => __( 'Post type singular label', 'md' ),
			'{url}' => __( 'Archive URL', 'md' ),
			'{total}' => __( 'Total posts', 'md' )
		);

		if ( $list )
			return apply_filters( 'md_parse_tokens', $definitions, $args );

		$tokens = array_fill_keys( array_keys( $definitions ), '' );
		$post_type = get_post_type_object( get_queried_object()->name ?? '' );

		if ( $post_type ) {
			$tokens = array(
				'{name}' => $post_type->labels->name,
				'{label}' => $post_type->labels->singular_name,
				'{url}' => get_post_type_archive_link( $post_type->name ),
			);

			if ( strpos( $text, '{total}' ) !== false )
				$tokens['{total}'] = wp_count_posts( $post_type->name )->publish;
		}
		else $tokens = array();
	}

	return apply_filters( 'md_parse_tokens', $tokens, $args );
}

/**
 * A function to access Block Editor colors.
 *
 * since 4.9
 */

function md_editor_colors() {
	$colors = new md_design_colors;

	return $colors->editor_colors();
}

/**
 * Resolve the active color palette, filterable for extensions.
 *
 * since 6.0
 */

function md_color_palette() {
	$colors = new md_design_colors;

	return apply_filters( 'md_color_palette', $colors->active_palette() );
}

/**
 * Return a usable color value. Resolves palette key references to their hex.
 *
 * @since 6.0
 */

function md_color_hex( $value ) {
	if ( empty( $value ) )
		return '';

	$palette = md_color_palette();

	return isset( $palette[$value] ) ? $palette[$value]['hex'] : $value;
}

/**
 * Returns the block editor color class for a palette reference, e.g. has-primary-color.
 * Returns empty string for direct hex/rgba values.
 *
 * @since 6.0
 */

function md_color_class( $value = '', $type = 'color' ) {
	$palette = md_color_palette();

	if ( empty( $value ) || ! isset( $palette[$value] ) )
		return '';

	$suffixes = array(
		'color' => 'color',
		'bg_color' => 'background-color',
		'border_color' => 'border-color'
	);

	$suffix = isset( $suffixes[$type] ) ? $suffixes[$type] : $type;

	return 'has-' . sanitize_html_class( $value ) . '-' . $suffix;
}

/**
 * It can be tedious to apply classes and styles separately, so get
 * your color value with this function to get a resolved state.
 *
 * @since 6.0
 */

function md_get_color( $value, $type = 'color' ) {
	$class = empty( $value ) ? '' : md_color_class( $value, $type );

	return array(
		'class' => $class,
		'style' => $class || empty( $value ) ? array() : array( $type => $value )
	);
}

/**
 * Return inline style selector with sanitized values.
 *
 * @since 5.0
 */

function md_style( $fields ) {
	$attributes = array();

	if ( ! empty( $fields['vars'] ) )
		foreach ( $fields['vars'] as $prop => $val )
			$attributes[] = '--' . esc_attr( $prop ) . ': ' . esc_attr( $val ) . ';';

	if ( ! empty( $fields['bg_color'] ) ) {
		$val = md_color_hex( $fields['bg_color'] );
		if ( $val )
			$attributes[] = 'background-color: ' . esc_attr( $val ) . ';';
	}

	if ( ! empty( $fields['bg_image'] ) )
		$attributes[] = 'background-image: url(' . esc_url( $fields['bg_image'] ) . ');';

	if ( ! empty( $fields['bg_size'] ) )
		$attributes[] = 'background-size: ' . esc_attr( $fields['bg_size'] ) . ';';

	if ( ! empty( $fields['border'][0] ) ) {
		$border_style = ! empty( $fields['border'][1] ) ? $fields['border'][1] : 'solid';

		if ( ! empty( $fields['border'][2] ) )
			$attributes[] = 'border: ' . esc_attr( $fields['border'][0] ) . 'px ' . esc_attr( $border_style ) . ' ' . esc_attr( $fields['border'][2] ) . ';';
		else
			$attributes[] = 'border-width: ' . esc_attr( $fields['border'][0] ) . 'px; border-style: ' . esc_attr( $border_style ) . ';';
	}

	if ( ! empty( $fields['border_color'] ) ) {
		$val = md_color_hex( $fields['border_color'] );

		if ( $val )
			$attributes[] = 'border-color: ' . esc_attr( $val ) . ';';
	}

	if ( ! empty( $fields['border_radius'] ) )
		$attributes[] = 'border-radius: ' . intval( $fields['border_radius'] ) . 'px';

	if ( ! empty( $fields['color'] ) ) {
		$val = md_color_hex( $fields['color'] );
		if ( $val )
			$attributes[] = 'color: ' . esc_attr( $val ) . ';';
	}

	if ( ! empty( $fields['flex'] ) )
		$attributes[] = 'flex: ' . esc_attr( $fields['flex'] ) . ';';

	if ( ! empty( $fields['height'] ) )
		$attributes[] = 'height: ' . esc_attr( $fields['height'] ) . 'px;';

	if ( ! empty( $fields['max_width'] ) )
		$attributes[] = 'max-width: ' . esc_attr( $fields['max_width'] ) . ';';

	if ( ! empty( $fields['width'] ) )
		$attributes[] = 'width: ' . esc_attr( $fields['width'] ) . ( isset( $fields['width_unit'] ) ? $fields['width_unit'] : 'px' ) . ';';

	return ! empty( $attributes ) ? ' style="' . implode( '', $attributes ) . '"' : '';
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
