<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Call this function to load MD template files. Checks the /content/
 * and /templates/ folder in child themes first, if not found loads
 * file from parent theme. MD5.0 the /content/ folder was renamed to
 * /templates/ so the double check is for fallback support.
 *
 * As of MD5.2 parent templates can now be stored outside of the main
 * templates folder but still be overridden from the child theme templates folder.
 * This is to be used for Core Drop-in functions, but will probably be changed
 * as of the creation of md_dropin_template() in MD5.3.
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

	$template = locate_template( array(
		"content/$file.php",
		"templates/$file.php"
	) );

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
 * Get meta field of either single or term pages.
 *
 * @since 4.7
 */

function md_meta( $keys = null, $id = null, $default = null ) {
	if ( is_string( $id ) || is_int( $id ) )
		$id = esc_attr( $id );
	elseif ( is_home() )
		$id = get_queried_object_id();

	if ( is_category() || is_tax() )
		return md_term_meta( $keys, $id, $default );
	else
		return md_post_meta( $keys, $id, $default );
}

/**
 * Get module field that is either on single term or post
 * pages, or return global setting as fallback.
 *
 * @since 4.7
 */

function md_module( $keys = null ) {
	if ( is_category() || is_tax() )
		$meta = md_term_meta( $keys );
	else
		$meta = md_post_meta( $keys );

	if ( ! empty( $meta ) )
		$field = $meta;
	else
		$field = md_setting( $keys );

	return $field;
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
 * Return ID without MD_ prefix.
 *
 * @since 5.0
 */

function md_clean_id( $id ) {
	return preg_replace( '/^' . preg_quote( 'md_', '/' ) . '/', '', $id );
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
 * Returns list of enabled Drop-ins.
 *
 * @since 5.3
 */

function md_get_dropins( $status = null ) {
	$dropins = $priority = array();
	foreach ( md_setting( array( 'dropins', 'installed' ), array() ) as $dropin => $fields )
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
	$dropins = array_merge( $priority, $dropins );
	return $dropins;
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
 * Outputs the menu name assigned to the specified Menu area.
 *
 * @since 4.0
 */

function md_get_menu_name( $menu ) {
	$menus = get_nav_menu_locations();
	$menu_object = wp_get_nav_menu_object( $menus[$menu] );
	$menu_name = isset( $menu_object->name ) ? $menu_object->name : __( 'Menu', 'md' );
	return esc_html( $menu_name );
}

/**
 * Counts how many fields are active in Main menu. Minimum to show = 2.
 *
 * @since 4.1
 */

function md_main_menu_items() {
	return count( array_filter( array(
		has_nav_menu( 'main' ), // menu
		md_main_menu_has_search(),
		has_nav_menu( 'social' ), // social
		apply_filters( 'md_filter_main_menu_items', '' )
	) ) );
}

function md_main_menu_has_search() {
	return ! md_setting( array( 'header', 'main_menu', 'disable', 'search' ) ) ? true : false;
}

/**
 * Returns custom page nav menu.
 *
 * @since 4.1
 */

function md_main_menu_custom_menu() {
	if ( is_category() || is_tax() )
		return md_term_meta( array( 'layout', 'main_menu_menu' ) );
	else
		return md_post_meta( array( 'layout', 'main_menu_menu' ) );
}

/**
 * Set the type of Loop to display on any given page.
 *
 * @since 4.6.4
 */

function md_get_loop( $keys = null ) {
	$loop = md_setting( array( 'loop' ) );
	$type = 'default';
	$global = md_setting( array( 'loop', 'archives' ), $type );

	if ( has_filter( 'md_filter_loop_type' ) )
		$type = apply_filters( 'md_filter_loop_type', $type );

	if ( is_category() || is_tax() ) {
		$term = md_term_meta( array( 'loop', 'archives' ) );
		if ( $term ) {
			$loop = md_term_meta( 'loop' );
			if ( ! has_filter( 'md_filter_loop_type' ) )
				$type = md_term_meta( array( 'loop', 'archives' ) );
		}
		elseif ( ! has_filter( 'md_filter_loop_type' ) )
			$type = $global;
	}
	elseif ( ( is_home() || is_post_type_archive() || is_search() ) && ( ! empty( $global ) || $global == 'default' ) && ! has_filter( 'md_filter_loop_type' ) )
		$type = $global;
	elseif ( is_singular() ) {
		$loop['byline'] = md_setting( array( 'content', 'byline' ) );
		$loop['byline_position'] = md_setting( array( 'content', 'byline_position' ) );
	}

	if ( isset( $keys ) ) {
		$c = 0;
		$fields = array();
		if ( $keys == 'fields' )
			return $loop;
		if ( is_string( $keys ) )
			$keys = (array) $keys;
		foreach ( $keys as $key ) {
			$fields = ! empty( $loop[$key] ) ? $loop[$key] : ( $c == 0 ? array() : '' );
			$c++;
		}
		return $fields;
	}

	return $type;
}

/**
 * Get user byline settings based on page location.
 *
 * @since 5.1
 */

function md_get_byline() {
	$byline = md_get_loop( array( 'loop', 'byline' ) );

	if ( empty( $byline ) )
		$byline = array();

	// do not use filter
	if ( has_filter( 'md_filter_custom_byline' ) ) {
		$filter = apply_filters( 'md_filter_custom_byline', array() );
		$byline = array_merge( $byline, $filter );
	}

	return array_keys( $byline );
}

/**
 * Returns content item HTML container.
 *
 * @since 4.1
 */

function md_content_item_headline_html() {
	return md_has_headline_cover() ? 'div' : 'header';
}

/**
 * Strips pingbacks count from comment count.
 *
 * @since 4.0
 */

function md_real_comment_count( $count ) {
	if ( ! is_admin() ) {
		global $id;
		$status = get_comments( "status=approve&post_id=$id" );
		$comments_by_type = separate_comments( $status );
		return count( $comments_by_type['comment'] );
	}
	else
		return $count;
}

/**
 * Get active sidebar ID for current page.
 *
 * @since 4.6.2.1
 */

function md_get_sidebar_id() {
	$global = md_get_global_sidebar_id();

	// single chosen custom sidebar
	if ( md_meta( array( 'layout', 'custom_sidebar' ) ) )
		$name = md_meta( array( 'layout', 'custom_sidebar' ) );
	// any type of global sidebar
	elseif ( ! empty( $global ) )
		$name = $global;
	// default sidebar
	else
		$name = 'sidebar-main';

	return $name;
}

/**
 * Get active global sidebar ID for current page.
 *
 * @since 4.6.2.1
 */

function md_get_global_sidebar_id() {
	$post_types = $term = array();
	$id = get_queried_object_id();
	$sidebars = md_sidebars();
	$option = md_setting( array( 'sidebars' ) );

	// post types
	$post_type = get_post_type();
	foreach ( $sidebars as $type => $pages )
		$post_types[] = $type;

	// taxonomies
	$taxonomies = get_taxonomies( array( 'public' => true ) );
	$terms = wp_get_post_terms( $id, $taxonomies );
	foreach ( $terms as $term_count => $fields ) {
		if ( md_term_meta( array( 'layout', 'entries_sidebar' ), $fields->term_id ) ) {
			$term['taxonomy'] = $fields->taxonomy;
			$term['term_id'] = $fields->term_id;
		}
	}
	$taxonomy = ! empty( $term['taxonomy'] ) ? $term['taxonomy'] : '';
	$term_id = ! empty( $term['term_id'] ) ? $term['term_id'] : '';
	$tax_var = get_query_var( 'taxonomy' );

	// single posts in category sidebar
	if ( is_singular() && has_term( $term_id, $taxonomy ) && md_term_meta( array( 'layout', 'entries_sidebar' ), $term_id ) != '' )
		$name = md_term_meta( array( 'layout', 'entries_sidebar' ), $term_id );
	// global post types archive sidebar
	elseif ( ( is_home() || is_post_type_archive( $post_type ) ) && ! empty( $sidebars[$post_type]['archive'] ) && ! empty( $option["{$post_type}_archive"] ) )
		$name = $option["{$post_type}_archive"];
	// global post types single sidebar
	elseif ( is_singular( $post_type ) && ! empty( $sidebars[$post_type]['single'] ) && ! empty( $option["{$post_type}_single"] ) )
		$name = $option["{$post_type}_single"];
	elseif ( ( is_category() || is_tax() ) && ! empty( $sidebars[$post_type][$tax_var] ) && ! empty( $option["{$post_type}_{$tax_var}"] ) )
		$name = $option["{$post_type}_{$tax_var}"];
	else
		$name = '';

	return $name;
}

/**
 * Outputs main sidebar or custom sidebar.
 *
 * @since 4.1
 */

function md_sidebar() {
	$name = md_get_sidebar_id();
	dynamic_sidebar( $name );
}

/**
 * Return a list of sidebar names by unique IDs.
 *
 * @since 4.6.2
 */

function md_get_sidebars() {
	$sidebars = array();
	$areas = md_setting( array( 'sidebars', 'areas' ) );

	if ( ! empty( $areas ) )
		foreach ( $areas as $area => $fields )
			$sidebars[$area] = $fields['name'];

	return array_filter( $sidebars );
}

/**
 * Returns an array of active widget areas with the md-footer-col prefix.
 *
 * @since 4.0
 */

function md_footer_columns() {
	$columns = array();
	foreach ( array_filter( wp_get_sidebars_widgets() ) as $area => $widgets )
		if ( substr( $area, 0, 13 ) == 'md-footer-col' )
			$columns[] = $area;
	return count( $columns );
}

/**
 * Get page data from different page types.
 *
 * @since 4.6
 */

function md_page_data() {
	if ( is_category() || is_tax() ) {
		$term = get_queried_object();
		$id = $term->term_id;
		return array(
			'title' => get_cat_name( $id ),
			'link' => get_category_link( $id ),
			'image' => md_term_meta( array( 'featured_image', 'image', 'url' ) ),
			'excerpt' => strip_tags( category_description( $id ) )
		);
	}
	else {
		$id = get_queried_object_id();
		return array(
			'title' => get_the_title( $id ),
			'link' => get_permalink( $id ),
			'image' => get_the_post_thumbnail_url( $id ),
			'excerpt' => get_post_field( 'post_excerpt', $id )
		);
	}
}