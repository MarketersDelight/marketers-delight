<?php

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
 * Enqueue MD's integrated web font services to <head> when needed.
 *
 * @since 4.8
 */

function md_webfonts_loader() {
	$typekit = md_setting( array( 'integrations', 'api_keys', 'typekit' ) );
	$fonts = md_web_fonts();
	$has_typekit = ( ! empty( $typekit ) && ! empty( $fonts['typekit'] ) ) ? true : false;
	$has_google = ( ! empty( $fonts['google'] ) ) ? true : false;

	if ( $has_google || $has_typekit )
		return
			"\t<script>WebFontConfig={" . ( $has_google ? 'google:{families:[' .  md_google_fonts( 'ids' ) . ']},' : '' ) . ( $has_typekit ? 'typekit:{id:\'' . esc_attr( $typekit['key'] ) . '\'}' : '' ) . '};(function(d){var wf=d.createElement(\'script\'),s=d.scripts[0];wf.src=\'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js\';wf.async=true;s.parentNode.insertBefore(wf,s);})(document);' . "</script>\n";
}

/**
 * Compile the needed Google Fonts by associated font weights.
 * Returns Google Font URL by default, set $format to 'ids'
 * to list font and weights by ID only.
 *
 * @since 4.8
 */

function md_google_fonts( $format = null ) {
	$string = '';
	$f = 1;
	$fonts = md_web_fonts( 'google' );
	$total_fonts = count( $fonts );
	$google = 'https://fonts.googleapis.com/css?family=';

	foreach ( $fonts as $name => $weights ) {
		$string .= $format == 'ids' ? "'" : '';
		$string .= $name;

		if ( ! empty( $weights ) ) {
			$w = 1;
			$total_weights = count( $weights );
			$string .= ':';

			foreach ( $weights as $weight ) {
				$string .= $weight . ( $w < $total_weights ? ',' : '' );
				$w++;
			}
		}

		$string .= $format == 'ids' ? "'" : '';

		if ( $f < $total_fonts )
			$string .= $format == 'ids' ? ',' : '|';

		$f++;
	}

	if ( $format == 'ids' )
		return $string;
	else
		return $google . urlencode( $string ) . '&display=swap';
}

/**
 * Compile list of Google fonts and weights to load
 * per page based on Typography design selections.
 * Can show only `google_fonts` or `typekit` or all.
 *
 * @since 4.8
 */

function md_web_fonts( $show_type = null ) {
	$font_s = '';
	$fonts = array();
	$headings = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'header', 'sidebar_title', 'footer_title' );
	$areas = array_merge( array( 'body', 'site_title', 'site_tagline', 'sidebar', 'footer' ), $headings );
	$body_t = md_setting( array( 'typography', 'body', 'font_type' ) );
	$body_f = md_setting( array( 'typography', 'body', 'font_family' ) );
	$body_w = md_setting( array( 'typography', 'body', 'font_weight' ) );
	$bold = md_setting( array( 'typography', 'body', 'bold' ) );
	$h1_t = md_setting( array( 'typography', 'h1', 'font_type' ) );
	$h1_f = md_setting( array( 'typography', 'h1', 'font_family' ) );
	$h1_w = md_setting( array( 'typography', 'h1', 'font_weight' ) );

	foreach ( $areas as $area ) {
		$type = md_setting( array( 'typography', $area, 'font_type' ) );
		$family = md_setting( array( 'typography', $area, 'font_family' ) );
		$weight = md_setting( array( 'typography', $area, 'font_weight' ) );
		$style = md_setting( array( 'typography', $area, 'font_style' ) );

		if ( ! empty( $weight ) ) {
			if ( $type == 'google' && ! empty( $style ) )
				$font_s = "{$weight}i";

			if ( empty( $family ) ) {
				// Make hx inherit h1
				if ( in_array( $area, $headings ) && ! empty( $h1_f ) ) {
					$fonts[$h1_t][$h1_f][] = $weight;
//					if ( $font_s )
//						$fonts[$h1_t][$h1_f][] = $font_s;
				}
				// Make font inherit body values
				elseif ( ! empty( $body_f ) ) {
					$fonts[$body_t][$body_f][] = $weight;
//					if ( $font_s )
//						$fonts[$body_t][$body_f][] = $font_s;
				}
			}
			else {
				// Directly assign setting to value
				$fonts[$type][$family][] = $weight;
				$fonts[$type][$family] = array_unique( $fonts[$type][$family] );
//				if ( $font_s )
//					$fonts[$type][$family][] = $font_s;
			}
		}
		elseif ( ! empty( $family ) )
			$fonts[$type][$family] = array();

		if ( $area == 'body' && $bold )
			$fonts[$type][$family][] = $bold;
	}

	if ( isset( $show_type ) )
		$show = ! empty( $fonts[$show_type] ) ? $fonts[$show_type] : '';
	else
		$show = $fonts;

	return $show;
}

/**
 * Return inline style selector with sanitized values.
 *
 * @since 5.0
 */

function md_style( $fields ) {
	$style = '';
	$attributes = array();

	if ( ! empty( $fields['bg_color'] ) )
		$attributes['bg_color'] = 'background-color:' . esc_attr( $fields['bg_color'] ) . ';';

	if ( ! empty( $fields['bg_image'] ) )
		$attributes['bg_image'] = 'background-image:url(' . esc_url( $fields['bg_image'] ) . ');';

	if ( ! empty( $fields['bg_size'] ) )
		$attributes['bg_size'] = 'background-size:' . esc_attr( $fields['bg_size'] ) . ';';

	if ( ! empty( $fields['border_color'] ) )
		$attributes['border_color'] = 'border-color:' . esc_attr( $fields['border_color'] ) . ';';

	if ( isset( $fields['border'] ) && ! empty( $fields['border'][2] ) ) {
		$border_width = ! empty( $fields['border'][0] ) ? $fields['border'][0] : 1;
		$border_style = ! empty( $fields['border'][1] ) ? $fields['border'][1] : 'solid';
		$border_color = ! empty( $fields['border'][2] ) ? $fields['border'][2] : '#1e1e1e';
		$attributes['border'] = 'border:' . esc_attr( $border_width ) . 'px ' . esc_attr( $border_style ) . ' ' . esc_attr( $border_color ) . ';';
	}

	if ( ! empty( $fields['color'] ) )
		$attributes['color'] = 'color:' . esc_attr( $fields['color'] ) . ';';

	if ( ! empty( $fields['width'] ) )
		$attributes['width'] = 'width:' . esc_attr( $fields['width'] ) . ( isset( $fields['width_unit'] ) ? $fields['width_unit'] : 'px' ) . ';';

	if ( ! empty( $fields['max_width'] ) )
		$attributes['max_width'] = 'max-width:' . esc_attr( $fields['max_width'] ) . ';';

	if ( ! empty( $fields['flex'] ) )
		$attributes['flex'] = 'flex:' . esc_attr( $fields['flex'] ) . ';';

	if ( ! empty( $fields['height'] ) )
		$attributes['height'] = 'height:' . esc_attr( $fields['height'] ) . 'px;';

	if ( ! empty( $attributes ) )
		$style = ' style="' . join( '', $attributes ) . '"';

	return $style;
}