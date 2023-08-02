<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Call this function to load MD template files. Checks the /templates/ folder
 * in child themes first, if not found loads file from parent theme.
 *
 * As of MD5.2 parent templates can now be stored outside of the main
 * templates folder but still be overridden from the child theme templates folder.
 * This is to be used for Core Drop-in functions, but will probably be changed
 * as of the creation of md_dropin_template() in MD5.3.
 *
 * As of MD5.6, this function no longer looks for the deprecated /content/ folder.
 *
 * Set $path to true to return the file path instead.
 *
 * @since 5.0
 */

function md_template( $file, $path = null, $include = null ) {
	$dir = $template_path = '';
	$directory = MD_DIR;

	if ( isset( $path ) && is_string( $path ) ) {
		$dir = $file;
		$file = $path;
	}

	$template = locate_template( "templates/$file.php" );

	if ( ! $template && ! empty( $dir ) ) {
		$parts = explode( '/', $file );
		$total = count( $parts );
		$parts_keys = array_keys( $parts );
		$file_key = end( $parts_keys );

		foreach ( $parts as $part_key => $part )
			if ( $part_key == 0 )
				$template_path .= "$part/templates/";
			elseif ( $part_key != $file_key )
				$template_path .= "$part/";
			else
				$template_path .= "$part.php";

		if ( $dir == 'dropins' && file_exists( MD_INSTALLED_DROPINS ) ) {
			$dir = '';
			$directory = MD_INSTALLED_DROPINS;
		}

		$template = "{$directory}$dir/$template_path";

		if ( ! file_exists( $template ) )
			return;
	}

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
	$dir = $template_path = '';
	$directory = MD_DIR;

	if ( isset( $path ) && is_string( $path ) ) {
		$dir = $file;
		$file = $path;
	}

	if ( ! empty( $dir ) ) {
		$parts = explode( '/', $file );
		$file = $parts[0];
		$parts_keys = array_keys( $parts );
		$file_key = end( $parts_keys );
	}

	$template = locate_template( "css/$file.php" );

	if ( ! $template ) {
		foreach ( $parts as $part_key => $part )
			if ( $part_key != $file_key )
				$template_path .= "$part/";
			else
				$template_path .= $part;

		if ( $dir == 'dropins' && file_exists( MD_INSTALLED_DROPINS ) ) {
			$dir = '';
			$directory = MD_INSTALLED_DROPINS;
		}

		$template = "{$directory}$dir/$template_path";
	}

	if ( file_exists( "$template.php" ) )
		$template .= '.php';
	elseif ( file_exists( "$template.css" ) )
		$template .= '.css';

	if ( ( isset( $path ) && ! is_string( $path ) ) || isset( $include ) )
		return $template;

	return load_template( $template, false );
}

/**
 * Call this function to load JS template from child theme
 * or use default templates.
 *
 * @since 5.4.2
 */

function md_js( $file, $path = null, $include = null ) {
	$dir = $template_path = '';
	$directory = MD_DIR;

	if ( isset( $path ) && is_string( $path ) ) {
		$dir = $file;
		$file = $path;
	}

	if ( ! empty( $dir ) ) {
		$parts = explode( '/', $file );
		$file = $parts[0];
		$parts_keys = array_keys( $parts );
		$file_key = end( $parts_keys );
	}

	$template = locate_template( "js/$file.php" );

	if ( ! $template ) {
		foreach ( $parts as $part_key => $part )
			if ( $part_key != $file_key )
				$template_path .= "$part/";
			else
				$template_path .= $part;

		if ( $dir == 'dropins' && file_exists( MD_INSTALLED_DROPINS ) ) {
			$dir = '';
			$directory = MD_INSTALLED_DROPINS;
		}

		$template = "{$directory}$dir/$template_path";
	}

	if ( file_exists( "$template.php" ) )
		$template .= '.php';
	elseif ( file_exists( "$template.js" ) )
		$template .= '.js';

	if ( ( isset( $path ) && ! is_string( $path ) ) || isset( $include ) )
		return $template;

	return load_template( $template, false );
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
 * Outputs inline JavaScript to footer.
 *
 * @since 4.0
 */

if ( ! function_exists( 'md_inline_js' ) ) :
	function md_inline_js() {
		if ( md_has_menu() )
			wp_add_inline_script( 'marketers-delight', "\tMD.headerMenu();" );
		if ( is_singular() && md_has_comments() )
			wp_add_inline_script( 'marketers-delight', "\tMD.toggle( 'comment' );" );
	}
endif;

/**
 * Pull data from the Marketers Delight options array. For
 * best performance, always pull MD settings from here.
 *
 * @since 4.7
 */

function md_setting( $keys = null, $default = null ) {
	$c = 0;
	$option = get_option( 'marketers_delight' );

	if ( empty( $option ) )
		$option = array();

	if ( isset( $keys ) ) {
		if ( is_string( $keys ) )
			$keys = (array) $keys;
		foreach ( $keys as $key ) {
			$option = ! empty( $option[$key] ) ? $option[$key] : ( $c == 0 ? array() : $default );
			$c++;
		}
	}

	return $option;
}

/**
 * A simple way to get various levels of post meta.
 *
 * @since 4.7
 */

function md_post_meta( $keys = null, $id = null, $default = null ) {
	if ( is_string( $id ) || is_int( $id ) )
		$id = $id;
	else
		$id = $id == true ? get_queried_object_id() : get_the_ID();

	$meta = get_post_meta( $id, 'marketers_delight', true );

	if ( empty( $meta ) )
		$meta = array();

	if ( isset( $keys ) ) {
		if ( is_string( $keys ) )
			$keys = (array) $keys;
		foreach ( $keys as $key )
			$meta = ! empty( $meta[$key] ) ? $meta[$key] : $default;
	}

	return $meta;
}

/**
 * Quickly access taxonomy field data (deprecates md_tax_data()).
 *
 * @since 4.7
 */

function md_term_meta( $keys = null, $id = null, $default = null ) {
	if ( is_admin() )
		$id = isset( $_GET['tag_ID'] ) ? $_GET['tag_ID'] : '';
	else
		$id = isset( $id ) ? $id : get_queried_object_id();

	$meta = get_term_meta( $id, 'marketers_delight', true );

	if ( empty( $meta ) )
		$meta = array();

	if ( isset( $keys ) ) {
		if ( is_string( $keys ) )
			$keys = (array) $keys;
		foreach ( $keys as $key )
			$meta = ! empty( $meta[$key] ) ? $meta[$key] : $default;
	}

	return $meta;
}

/**
 * Get Post Type specific admin fields.
 *
 * @since 5.6
 */

function md_post_type_field( $keys = null, $default = null ) {
	$post_type = get_post_type();

	if ( ! is_post_type_archive( $post_type ) && ! is_home() )
		return;

	if ( is_string( $keys ) )
		$keys = (array) $keys;

	array_unshift( $keys, $post_type );

	return md_setting( $keys, $default );
}

/**
 * Get meta field from either single or term pages.
 *
 * @since 4.7
 */

function md_meta( $keys = null, $id = null, $default = null ) {
	if ( is_string( $id ) || is_int( $id ) )
		$id = esc_attr( $id );

	if ( is_category() || is_tax() )
		return md_term_meta( $keys, $id, $default );
	else
		return md_post_meta( $keys, $id, $default );
}

/**
 * Access user meta.
 *
 * @since 5.3.1
 */

function md_user_meta( $keys = null, $id = null, $default = null ) {
	if ( empty( $id ) )
		if ( is_admin() )
			$id = isset( $_GET['user_id'] ) ? esc_attr( $_GET['user_id'] ) : '';
		else
			$id = get_current_user_id();

	$meta = get_user_meta( $id, 'marketers_delight', true );

	if ( empty( $meta ) )
		$meta = array();

	if ( isset( $keys ) ) {
		if ( is_string( $keys ) )
			$keys = (array) $keys;
		foreach ( $keys as $key )
			$meta = ! empty( $meta[$key] ) ? $meta[$key] : $default;
	}

	return $meta;
}

/**
 * Get module field that is either on single term or post
 * pages, or return global setting as fallback.
 *
 * @since 4.7
 */

function md_module( $keys = null, $default = null ) {
	if ( is_home() || is_post_type_archive() )
		$option = md_post_type_field( $keys, $default );
	elseif ( is_category() || is_tax() )
		$option = md_term_meta( $keys, null, $default );
	elseif ( is_singular() )
		$option = md_post_meta( $keys, null, $default );
	else
		$option = md_setting( $keys, $default );

	return $option;
}

/**
 * Safely get Block fields.
 *
 * @since 4.9
 */

function md_block_field( $attributes, $field ) {
	return ! empty( $attributes[$field] ) ? $attributes[$field] : '';
}

/**
 * Check if MD has a specific feature enabled.
 *
 * @since 4.5
 */

function md_has( $dropin ) {
	$enabled = md_get_dropins( 'active' );
	if ( in_array( $dropin, $enabled ) )
		return true;
}

/**
 * Return ID without MD_ prefix.
 *
 * @since 5.0
 */

function md_clean_id( $id ) {
	return ( ! empty( $id ) ? preg_replace( '/^' . preg_quote( 'md_', '/' ) . '/', '', $id ) : '' );
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
 * Run KSES with MD approved HTML tags.
 *
 * @since 5.2.2
 */

function md_text_field( $string ) {
	$sanitize = new md_sanitize;
	return wp_kses( $string, $sanitize->_allowed_html );
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
 * Returns list of enabled Drop-ins, sortable by a variety of statuses.
 * $status === active, inactive, files
 * `files` returns list of drop-ins in Drop-in CodeBlock format.
 *
 * @since 5.3
 */

function md_get_dropins( $status = null, $key = null ) {
	$dropins = $priority = array();

	foreach ( md_setting( array( 'dropins', 'installed' ), array() ) as $dropin => $fields ) {
		if (
			( ( $status == null || $status == 'active' ) && ! empty( $fields['status']['enable'] ) ) ||
			( ( $status == 'inactive' ) && empty( $fields['status']['enable'] ) ) ||
			$status == null
		) {
			if ( isset( $fields['priority'] ) )
				$priority[] = esc_attr( $dropin );
			else
				$dropins[] = esc_attr( $dropin );
		}
		elseif ( $status == 'files' ) {
			foreach ( $fields as $header => $field ) {
				$header = ucwords( $header );
				$dropins["$dropin/$dropin.php"][$header] = $field;
			}
			$dropins["$dropin/$dropin.php"]['ID'] = $dropin;
		}
	}

	$dropins = array_merge( $priority, $dropins );

	if ( ! empty( $key ) ) {
		if ( $status == 'files' )
			return $dropins["$key/$key.php"];
		return $dropins[$key];
	}

	return $dropins;
}

/**
 * Organize array of lists for use in options.
 *
 * @since 4.9
 */

function md_email_data( $atts = null ) {
	$email = md_setting( array( 'integrations', 'services' ) );

	if ( empty( $email ) )
		return array();

	if ( isset( $atts['show'] ) ) {
		$ids = array();
		if ( isset( $atts['empty_label' ] ) )
			$ids[''] = __( 'Use default email list...', 'md' );
		// Return list of IDs
		if ( $atts['show'] == 'ids' ) {
			foreach ( $email as $service => $lists )
				foreach ( $lists as $list => $fields )
					$ids[] = $list;
			if ( isset( $atts['custom_html'] ) )
				$ids[] = 'custom_html';
			return $ids;
		}
		// Return IDs by name
		elseif ( $atts['show'] == 'names' ) {
			$label = '';
			foreach ( $email as $service => $lists )
				foreach ( $lists as $list => $fields ) {
					if ( isset( $atts['label'] ) )
						$label = esc_html( ' (' . $service . ')' );
					$ids[$list] = $fields['name'] . $label;
				}
			return $ids;
		}
		// Return service by ID
		elseif ( $atts['show'] == 'service' ) {
			foreach ( $email as $service => $lists )
				foreach ( $lists as $list => $fields )
					$ids[$list] = $service;
			return $ids;
		}
	}

	if ( isset( $atts['custom_html' ] ) )
		$email['other']['custom_html']['name'] = __( 'Custom HTML', 'md' );

	return $email;
}

/**
 * Get MD Popups data in various formats.
 *
 * @since 5.0
 */

function md_get_popups( $show = null ) {
	$popups = array();
	$option = md_setting( array( 'popups', 'popups' ), array() );

	if ( ! empty( $option ) ) {
		foreach ( $option as $popup => $fields )
			if ( ! empty( $popup ) )
				if ( $show == 'ids' )
					$popups[] = $popup;
				elseif ( $show == 'options' )
					$popups[$popup] = $fields['name'];
		return $popups;
	}

	return $option;
}
