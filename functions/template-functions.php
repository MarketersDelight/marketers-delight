<?php

/**
 * Add important rel= tags to pagination prev/next links.
 *
 * @since 6.0
 */

add_filter( 'previous_posts_link_attributes', function() { return 'rel="prev"'; } );
add_filter( 'next_posts_link_attributes', function() { return 'rel="next"'; } );
add_filter( 'paginate_links_output', function( $html ) {
	return str_replace( 'class="next', 'rel="next" class="next', str_replace( 'class="prev', 'rel="prev" class="prev', $html ) );
} );

/**
 * Get MD font icons URL.
 *
 * @since 5.2.3
 */

function md_font_icons_url() {
	$file = MD_URL . 'md.woff2';

	if ( file_exists( get_stylesheet_directory() . '/md.woff2' ) )
		$file = get_stylesheet_directory_uri() . '/md.woff2';

	return $file;
}

/**
 * Return a full list of MD icons. Read documentation and see how to
 * filter in your own icons:
 * https://marketersdelight.com/font-icons/
 *
 * @since 4.9.3
 */

function md_icons( $show_defaults = null ) {
	$icons = locate_template( 'icons.php', true );
	$icons = $icons ? include $icons : array();
	$data = md_setting( array( 'icons', 'data' ), array() );
	$custom = md_setting( 'custom_icons', array() );

	if ( $show_defaults !== true && ! empty( $custom ) ) {
		foreach ( $icons as $icon => $fields )
			if ( ! in_array( $icon, $custom ) )
				unset( $icons[$icon] );

		$icons = array_merge( $icons, $data );
	}

	return $icons;
}

/**
 * Get Icons data in various formats.
 *
 * @since 5.0
 */

function md_get_icons( $sort = null, $show_defaults = null, $prefix = null ) {
	$icons = array();
	$prefix = isset( $prefix ) ? $prefix : '';

	foreach ( md_icons( $show_defaults ) as $icon => $fields ) {
		$icon = "$prefix{$icon}";

		if ( isset( $fields['label'] ) )
			$icons['options'][$icon] = $fields['label'];

		$icons['ids'][] = $icon;
	}

	if ( isset( $sort ) )
		$icons = $icons[$sort];

	return $icons;
}

/**
 * Render an MD font icon.
 *
 * @since 5.2.3
 */

function md_icon( $icon, $args = null ) {
	$style = array();
	$title = '';
	$classes[] = "md-icon-{$icon}";

	if ( isset( $args['classes'] ) )
		$classes[] = $args['classes'];

	if ( isset( $args['color'] ) )
		$style['color'] = $args['color'];

	if ( isset( $args['title'] ) )
		$title = ' title="' . esc_attr( $args['title'] ) . '"';

	$classes = join( ' ', $classes );

	if ( is_bool( $args ) )
		return esc_attr( $classes );

	return '<i class="' . esc_attr( $classes ) . '"' . md_style( $style ) . $title . '></i>';
}

/**
 * Compile the needed Google Fonts by associated font weights.
 * Returns Google Font URL by default, set $format to 'ids'
 * to list font and weights by ID only.
 *
 * @since 4.8
 */

function md_google_fonts() {
	$parts = array();
	$fonts = md_web_fonts( 'google' );

	foreach ( $fonts as $name => $weights ) {
		$normal = $italic = array();

		foreach ( $weights as $w ) {
			if ( substr( $w, -1 ) === 'i' )
				$italic[] = substr( $w, 0, -1 );
			else
				$normal[] = $w;
		}

		if ( ! empty( $italic ) ) {
			$combos = array();

			foreach ( $normal as $w )
				$combos[] = "0,$w";

			foreach ( $italic as $w )
				$combos[] = "1,$w";

			sort( $combos );

			$parts[] = urlencode( $name ) . ':ital,wght@' . implode( ';', $combos );
		}
		elseif ( ! empty( $normal ) )
			$parts[] = urlencode( $name ) . ':wght@' . implode( ';', $normal );
		else
			$parts[] = urlencode( $name );
	}

	return 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', $parts ) . '&display=swap';
}

/**
 * Compile list of Google fonts and weights to load
 * per page based on Typography design selections.
 * Can show only `google_fonts` or `typekit` or all.
 *
 * @since 4.8
 */

function md_web_fonts( $show_type = null ) {
	$fonts = array();
	$typography = md_setting( 'typography' );
	$headings = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'header', 'sidebar_title', 'footer_title' );
	$areas = array_merge( array( 'body', 'site_title', 'site_tagline', 'sidebar', 'footer' ), $headings );
	$body = $typography['body'] ?? array();
	$h1 = $typography['h1'] ?? array();
	$body_t = $body['font_type'] ?? '';
	$body_f = $body['font_family'] ?? '';
	$bold = $body['bold'] ?? '';
	$h1_t = $h1['font_type'] ?? '';
	$h1_f = $h1['font_family'] ?? '';

	foreach ( $areas as $area ) {
		$a = $typography[$area] ?? array();
		$type = $a['font_type'] ?? '';
		$family = $a['font_family'] ?? '';
		$weight = $a['font_weight'] ?? '';
		$style = $a['font_style'] ?? '';

		if ( ! empty( $weight ) ) {
			$weights = array( $weight );

			if ( $type == 'google' && ! empty( $style ) )
				$weights[] = "{$weight}i";

			if ( empty( $family ) ) {
				if ( in_array( $area, $headings ) && ! empty( $h1_f ) )
					$fonts[$h1_t][$h1_f] = array_merge( $fonts[$h1_t][$h1_f] ?? array(), $weights );
				elseif ( ! empty( $body_f ) )
					$fonts[$body_t][$body_f] = array_merge( $fonts[$body_t][$body_f] ?? array(), $weights );
			}
			else $fonts[$type][$family] = array_merge( $fonts[$type][$family] ?? array(), $weights );
		}
		elseif ( ! empty( $family ) )
			$fonts[$type][$family] = array();

		if ( $area == 'body' && $bold )
			$fonts[$type][$family][] = $bold;
	}

	foreach ( $fonts as $type => $families )
		foreach ( $families as $family => $weights )
			$fonts[$type][$family] = array_unique( $weights );

	return isset( $show_type ) ? ( $fonts[$show_type] ?? '' ) : $fonts;
}

/**
 * Replace archive tokens in a text string.
 *
 * @since 6.0
 */

function md_parse_text( $text, $context = '' ) {
	if ( empty( $text ) )
		return $text;

	if ( empty( $context ) ) {
		if ( is_tax() || is_category() || is_tag() )
			$context = 'term';
		elseif ( is_home() || is_post_type_archive() )
			$context = 'archive';
	}

	$tokens = md_parse_tokens( array( 'context' => $context, 'text' => $text ) );

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
		$term   = get_queried_object();

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
				$tokens['{total}'] = (int) wp_count_posts( $obj_type )->publish;
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

		$tokens    = array_fill_keys( array_keys( $definitions ), '' );
		$post_type = get_post_type_object( get_queried_object()->name ?? '' );

		if ( $post_type ) {
			$tokens = array(
				'{name}' => $post_type->labels->name,
				'{label}' => $post_type->labels->singular_name,
				'{url}' => get_post_type_archive_link( $post_type->name ),
			);

			if ( strpos( $text, '{total}' ) !== false )
				$tokens['{total}'] = (int) wp_count_posts( $post_type->name )->publish;
		}
		else $tokens = array();
	}

	return apply_filters( 'md_parse_tokens', $tokens, $args );
}

/**
 * Return inline style selector with sanitized values.
 *
 * @since 5.0
 */

function md_style( $fields ) {
	$attributes = array();

	if ( ! empty( $fields['bg_color'] ) )
		$attributes[] = 'background-color:' . esc_attr( $fields['bg_color'] ) . ';';

	if ( ! empty( $fields['bg_image'] ) )
		$attributes[] = 'background-image:url(' . esc_url( $fields['bg_image'] ) . ');';

	if ( ! empty( $fields['bg_size'] ) )
		$attributes[] = 'background-size:' . esc_attr( $fields['bg_size'] ) . ';';

	if ( ! empty( $fields['border_color'] ) )
		$attributes[] = 'border-color:' . esc_attr( $fields['border_color'] ) . ';';

	if ( ! empty( $fields['border'][2] ) ) {
		$border_width = ! empty( $fields['border'][0] ) ? $fields['border'][0] : 1;
		$border_style = ! empty( $fields['border'][1] ) ? $fields['border'][1] : 'solid';
		$attributes[] = 'border:' . esc_attr( $border_width ) . 'px ' . esc_attr( $border_style ) . ' ' . esc_attr( $fields['border'][2] ) . ';';
	}

	if ( ! empty( $fields['color'] ) )
		$attributes[] = 'color:' . esc_attr( $fields['color'] ) . ';';

	if ( ! empty( $fields['width'] ) )
		$attributes[] = 'width:' . esc_attr( $fields['width'] ) . ( isset( $fields['width_unit'] ) ? $fields['width_unit'] : 'px' ) . ';';

	if ( ! empty( $fields['max_width'] ) )
		$attributes[] = 'max-width:' . esc_attr( $fields['max_width'] ) . ';';

	if ( ! empty( $fields['flex'] ) )
		$attributes[] = 'flex:' . esc_attr( $fields['flex'] ) . ';';

	if ( ! empty( $fields['height'] ) )
		$attributes[] = 'height:' . esc_attr( $fields['height'] ) . 'px;';

	return ! empty( $attributes ) ? ' style="' . implode( '', $attributes ) . '"' : '';
}