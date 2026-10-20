<?php

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
 * Check whether a palette color or hex is light enough for dark text. Dark
 * text reads better above a relative luminance of about 0.18, light text
 * below it. Anything that isn't a palette color or hex counts as dark.
 *
 * @since 6.0.3
 */

function md_color_is_light( $color ) {
	$hex = preg_replace( '/^#?([a-f0-9])([a-f0-9])([a-f0-9])$/i', '$1$1$2$2$3$3', md_color_hex( $color ) );

	if ( ! preg_match( '/^#?([a-f0-9]{6})/i', $hex, $match ) )
		return false;

	$luminance = 0;
	$weights = array( 0.2126, 0.7152, 0.0722 );

	foreach ( str_split( $match[1], 2 ) as $index => $channel ) {
		$value = hexdec( $channel ) / 255;
		$value = $value <= 0.03928 ? $value / 12.92 : pow( ( $value + 0.055 ) / 1.055, 2.4 );
		$luminance += $weights[$index] * $value;
	}

	return $luminance > 0.18;
}

/**
 * Read a block's colors into one setting per color: a palette slug or a hex
 * value. Older blocks saved a palette pick beside the color as a slug
 * ({color}Slug) or a class ({color}Class); those win and are removed. An
 * empty color takes its block.json default.
 *
 * @since 6.0.3
 */

function md_block_colors( $attributes, $block_name, $colors ) {
	$palette = md_color_palette();
	$block = WP_Block_Type_Registry::get_instance()->get_registered( $block_name );

	foreach ( $colors as $color ) {
		$slug = $attributes["{$color}Slug"] ?? '';

		if ( ! isset( $palette[$slug] ) && preg_match( '/^has-(.+?)-(?:background-|border-)?color$/', $attributes["{$color}Class"] ?? '', $match ) )
			$slug = $match[1];

		if ( isset( $palette[$slug] ) )
			$attributes[$color] = $slug;

		if ( empty( $attributes[$color] ) )
			$attributes[$color] = $block->attributes[$color]['default'] ?? '';

		unset( $attributes["{$color}Slug"], $attributes["{$color}Class"] );
	}

	return $attributes;
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
 * Return a full list of MD icons. Read documentation and see how to
 * filter in your own icons:
 * https://marketersdelight.com/font-icons/
 *
 * @since 4.9.3
 */

function md_icons( $show_defaults = null ) {
	$icons = locate_template( 'api/icons.php', true );
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
 * Scale a body text value down to Small (step 1) or Tiny (step 2). Small is 0.9
 * of body, and Tiny is 0.85 of Small. Font sizes and line heights both use it,
 * so Body, Small and Tiny always keep the same proportions.
 *
 * @since 6.0
 */

function md_text_scale( $value, $step = 1 ) {
	$ratios = array( 1, 0.9, 0.9 * 0.85 );

	return round( $value * $ratios[$step] );
}

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
