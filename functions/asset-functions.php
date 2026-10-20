<?php
/**
 * Call this function to load MD template files. Checks the /templates/ folder
 * in child themes first, if not found loads file from parent theme.
 *
 * As of MD5.2 parent templates can now be stored outside of the main
 * templates folder but still be overridden from the child theme templates folder.
 *
 * For example, to change the path to /wp-content/md-dropins/ for calling Drop-in
 * templates, use the following: md_template( 'dropins', 'admin/meta-box' );
 *
 * Built-in feature templates live with their owner and use the feature slug as
 * the first file segment, ex: md_template( 'features', 'loop/the-post' ).
 * Frontend templates sit at templates/ and admin views at templates/admin/.
 *
 * As of MD6.0, this function no longer looks for the deprecated /content/ folder.
 *
 * Set $path to true to return the file path instead.
 *
 * @since 5.0
 */

function md_template( $file, $path = null, $include = null ) {
	$dir = '';

	if ( is_string( $path ) ) {
		$dir = $file;
		$file = $path;
	}

	if ( validate_file( $file ) !== 0 || ( $dir && validate_file( $dir ) !== 0 ) )
		return;

	$template = locate_template( "templates/$file.php" );

	if ( ! $template ) {
		$directory = MD_DIR;

		if ( $dir == 'dropins' && file_exists( MD_INSTALLED_DROPINS) )
			$directory = MD_INSTALLED_DROPINS;
		else
			$directory = "$directory{$dir}";

		$directory = trailingslashit( $directory );
		$template_path = '';
		$parts = explode( '/', $file );
		$file_key = count( $parts ) - 1;

		foreach ( $parts as $part_key => $part )
			if ( $part_key == 0 )
				$template_path .= "$part/templates/";
			elseif ( $part_key != $file_key )
				$template_path .= "$part/";
			else
				$template_path .= "$part.php";

		$file = $template_path;

		$template = "{$directory}{$file}";

		if ( ! file_exists( $template ) )
			return;
	}

	if ( ( isset( $path ) && ! is_string( $path ) ) || isset( $include ) )
		return $template;

	return load_template( esc_attr( $template ), false );
}

/**
 * Call this function to load CSS or JS template from child theme
 * or use default templates.
 *
 * @since 6.0
 */

function md_asset( $type, $file, $path = null, $include = null ) {
	$type = $type === 'js' ? 'js' : 'css';
	$extension = ".{$type}";
	$dir = '';
	$directory = MD_DIR;

	if ( isset( $path ) && is_string( $path ) ) {
		$dir = $file;
		$file = $path;
	}

	$file = trim( $file, '/' );
	$parts = explode( '/', $file );
	$locate_file = ! empty( $dir ) ? $parts[0] : $file;
	$template = locate_template( "$type/$locate_file.php" );

	if ( ! $template ) {
		$template_path = implode( '/', $parts );

		if ( $dir == 'dropins' && file_exists( MD_INSTALLED_DROPINS ) ) {
			$dir = '';
			$directory = MD_INSTALLED_DROPINS;
		}

		$template = trailingslashit( $directory );

		if ( ! empty( $dir ) )
			$template .= trailingslashit( trim( $dir, '/' ) );

		$template .= $template_path;
	}

	if ( file_exists( "$template.php" ) )
		$template .= '.php';
	elseif ( file_exists( "$template$extension" ) )
		$template .= $extension;
	elseif ( ! file_exists( $template ) )
		return;

	if ( ( isset( $path ) && ! is_string( $path ) ) || isset( $include ) )
		return $template;

	return load_template( $template, false );
}

/**
 * Call this function to load CSS template from child theme
 * or use default templates.
 *
 * @since 5.1.1
 */

function md_css( $file, $path = null, $include = null ) {
	return md_asset( 'css', $file, $path, $include );
}

/**
 * Call this function to load JS template from child theme
 * or use default templates.
 *
 * @since 5.4.2
 */

function md_js( $file, $path = null, $include = null ) {
	return md_asset( 'js', $file, $path, $include );
}

/**
 * Pass dynamic data into scripts.
 *
 * @since 4.9
 * @moved 5.3.3
 */

function md_localize_scripts( $data ) {
	$scripts = array();
	$design_colors = new md_design_colors;

	if ( in_array( 'colors' , $data ) )
		foreach ( $design_colors->editor_colors() as $group => $fields )
			$scripts['colors'][] = esc_attr( $fields['color'] );

	if ( in_array( 'block_colors' , $data ) )
		foreach ( $design_colors->editor_colors() as $group => $fields ) {
			$scripts['colors']['slug'][$fields['slug']] = esc_attr( $fields['color'] );
			$scripts['colors']['hex'][$fields['color']] = esc_attr( $fields['slug'] );
		}

	if ( in_array( 'icons', $data ) )
		foreach ( md_icons() as $icon => $fields ) {
			$label = ! empty( $fields['label'] ) ? $fields['label'] : $icon;
			$scripts['icons'][] = array(
				'label' => esc_html( $label ),
				'value' => esc_attr( "md-icon-$icon" )
			);
		}

	return apply_filters( 'md_filter_blocks_scripts', $scripts, $data );
}

/**
 * Format JS object to pass to various MD scripts.
 *
 * @since 5.0
 */

function md_js_object( $args ) {
	$string = '';
	$g = 1;
	$g_total = count( $args );

	foreach ( $args as $group => $fields ) {
		$f = 1;
		$string .= "'" . esc_js( $group ) . "':{";
		$f_total = count( $fields );

		foreach ( $fields as $key => $value ) {
			$string .= "'" . esc_js( $key ) . "':'" . esc_js( $value ) . "'";

			if ( $f < $f_total )
				$string .= ',';

			$f++;
		}

		$string .= '}' . ( $g < $g_total ? ',' : '' );

		$g++;
	}

	return $string;
}

/**
 * Compile MD CSS + JS files at the same time.
 *
 * @since 5.4.2
 */

function md_compile( $delete = null ) {
	$css = md_compile_css( $delete );

	if ( is_wp_error( $css ) )
		return $css;

	$js = md_compile_js();

	if ( is_wp_error( $js ) )
		return $js;

	return true;
}

/**
 * Use this function to recompile MD's dynamic CSS. Based on user
 * selection, CSS will be recompiled to <head> or printed to
 * style.css. Only call on save actions or in design mode where
 * you need the CSS to be constantly rebuilt. Never run live.
 *
 * @since 4.8
 */

function md_compile_css( $delete = null ) {
	$css = new md_css;

	return $css->compile( $delete );
}

/**
 * Identical to md_compile_css(), when run this function
 * rebuilds and prints new contents to the ND scripts.js file.
 *
 * @since 5.4.2
 */

function md_compile_js( $delete = null ) {
	$js = new md_js;

	return $js->compile( $delete );
}

/**
 * Get the last time file was updated from stylesheet path.
 *
 * @since 4.8.4
 */

function md_ver( $file, $path = null ) {
	$path = isset( $path ) ? $path : MD_DIR;
	$file = $path . $file;

	return is_file( $file ) ? filemtime( $file ) : MD_VERSION;
}
