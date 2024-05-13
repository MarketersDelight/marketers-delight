<?php

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
 * Get MD font icons URL.
 *
 * @since 5.2.3
 */

function md_font_icons_url() {
	$file = MD_URL . 'assets/md.woff';

	if ( file_exists( get_stylesheet_directory() . '/md.woff' ) )
		$file = get_stylesheet_directory_uri() . '/md.woff';

	return $file;
}

/**
 * Print icons CSS styles by class names.
 *
 * @since 6.0
 */

function md_icons_css() {
	foreach ( md_icons() as $icon => $fields ) {
		if ( ! isset( $fields['unicode'] ) )
			continue;

		$selectors = '';

		if ( isset( $fields['classes'] ) )
			foreach ( $fields['classes'] as $selector )
				$selectors .= ",{$selector}:before";

		echo '.md-icon-' . $icon . ":before{$selectors}{content:'\\" . $fields['unicode'] . '\'}';
	}
}

/**
 * Return a list of MD icons. Read documentation and see how to
 * filter in your own icons:
 * https://marketersdelight.com/font-icons/
 *
 * @since 4.9.3
 */

function md_icons( $show_defaults = null ) {
	$icons = locate_template( 'assets/icons.php', true );
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
