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

	if ( in_array( 'colors' , $data ) )
		foreach ( md_editor_colors() as $group => $fields )
			$scripts['colors'][] = esc_attr( $fields['color'] );

	if ( in_array( 'block_colors' , $data ) )
		foreach ( md_editor_colors() as $group => $fields ) {
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
		$string .= "$group:{";
		$f_total = count( $fields );

		foreach ( $fields as $key => $value ) {
			$string .= "$key:'$value'";

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
	md_compile_css();
	md_compile_js();
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
	$css->compile( $delete );
}

/**
 * Identical to md_compile_css(), when run this function
 * rebuilds and prints new contents to the ND scripts.js file.
 *
 * @since 5.4.2
 */

function md_compile_js( $delete = null ) {
	$js = new md_js;
	$js->compile( $delete );
}

/**
 * Get the last time file was updated from stylesheet path.
 *
 * @since 4.8.4
 */

function md_ver( $file, $path = null ) {
	$path = isset( $path ) ? $path : MD_DIR;

	return date( 'ymds', filemtime( $path . $file ) );
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

