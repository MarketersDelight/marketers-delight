<?php

/**
 * Check if MD has a specific feature enabled.
 *
 * @since 4.5
 */

function md_has( $dropin ) {
	return in_array( $dropin, md_get_dropins( 'active' ) );
}

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
 * Pull data from the Marketers Delight options array. For
 * best performance, always pull MD settings from here.
 *
 * @since 4.7
 */

function md_setting( $keys = null, $default = null ) {
	$c = 0;
	$option = get_option( 'marketers_delight' );
	$defaults = apply_filters( 'md_setting_defaults', array() );

	if ( ! empty( $defaults ) )
		$option = array_replace_recursive( $defaults, (array) $option );

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
 * To pull data from custom options, use this function with
 * your declared option key name.
 *
 * @since 6.0
 */

function md_option( $option, $keys = null, $default = null ) {
	$c = 0;
	$data = get_option( $option, array() );

	if ( isset( $keys ) ) {
		if ( is_string( $keys ) )
			$keys = (array) $keys;
		foreach ( $keys as $key ) {
			$data = ! empty( $data[$key] ) ? $data[$key] : ( $c == 0 ? array() : $default );
			$c++;
		}
	}

	return $data;
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

	$id = apply_filters( 'md_setting_id', $id );
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
		$id = isset( $_GET['tag_ID'] ) ? intval( $_GET['tag_ID'] ) : '';
	else
		$id = isset( $id ) ? $id : get_queried_object_id();

	$id = apply_filters( 'md_setting_id', $id );
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
 * @since 6.0
 */

function md_post_type_field( $keys = null, $default = null, $post_type = null ) {
	if ( ! isset( $post_type ) )
		$post_type = md_get_post_type();

	if ( is_string( $keys ) )
		$keys = (array) $keys;

	array_unshift( $keys, $post_type );

	return md_setting( $keys, $default );
}

/**
 * Get taxonomy-level global settings (middle tier between post type archive and individual term).
 *
 * @since 6.0
 */

function md_taxonomy_field( $keys = null, $default = null, $post_type = null, $taxonomy = null ) {
	if ( ! $post_type )
		$post_type = md_get_post_type();

	if ( ! $taxonomy ) {
		$queried = get_queried_object();
		$taxonomy = isset( $queried->taxonomy ) ? $queried->taxonomy : null;
	}

	if ( ! $taxonomy )
		return $default;

	if ( is_string( $keys ) )
		$keys = (array) $keys;

	return md_setting( array_merge( (array) $post_type, (array) $taxonomy, (array) $keys ), $default );
}

/**
 * Access user meta.
 *
 * @since 5.3.1
 */

function md_user_meta( $keys = null, $id = null, $default = null ) {
	if ( empty( $id ) )
		if ( is_admin() )
			$id = isset( $_GET['user_id'] ) ? intval( $_GET['user_id'] ) : '';
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
 * Get meta field from either single or term pages.
 *
 * @since 4.7
 */

function md_meta( $keys = null, $id = null, $default = null ) {
	if ( is_string( $id ) || is_int( $id ) )
		$id = esc_attr( $id );

	$id = apply_filters( 'md_setting_id', $id );

	if ( is_category() || is_tax() )
		return md_term_meta( $keys, $id, $default );
	else
		return md_post_meta( $keys, $id, $default );
}

/**
 * Safely get Block fields.
 *
 * @since 4.9
 */

function md_has_class( $slug, $type ) {
	return $slug ? 'has-' . $slug . '-' . $type : '';
}

/**
 * Get module field that is either on single term or post
 * pages, or return global setting as fallback.
 *
 * $default and $id are reversed order relative to other get functions
 * due to lesser use of $id here.
 *
 * @since 4.7
 */

function md_module( $keys = null, $default = null, $id = null ) {
	$id = apply_filters( 'md_setting_id', $id );

	if ( is_home() || is_post_type_archive() || is_author() )
		$option = md_post_type_field( $keys, $default );
	elseif ( is_category() || is_tax() ) {
		$option = md_term_meta( $keys, $id, null );

		if ( is_null( $option ) )
			$option = md_taxonomy_field( $keys, null );

		if ( is_null( $option ) )
			$option = md_post_type_field( $keys, $default );
	}
	elseif ( is_singular() || is_404() )
		$option = md_post_meta( $keys, $id, $default );
	else
		$option = md_setting( $keys, $default );

	return $option;
}

/**
 * A function to access Block Editor colors.
 *
 * since 4.9
 */

function md_editor_colors() {
	$design = new md_design;
	return $design->editor_colors();
}

/**
 * Get the current post type of a page. This function exists to cover
 * up a bug that changes the global Loop ID of the first post in the Loop
 * when a Loop Query is modified to combine two post types.
 *
 * @since 6.0
 */

function md_get_post_type( $post_id = null ) {
	$post_type = get_post_type( $post_id );

	if ( $post_type == 'stream_activity' )
		$post_type = 'stream';
	elseif ( is_404() )
		$post_type = 'error';

	return $post_type;
}

/**
 * Builder fields are flexible, repeatable settings groups with
 * extra layout details attached to the builder groups.
 * See Header or Byline for examples of how to construct Builder layouts.
 *
 * @since 6.0
 */

function md_get_builder( $id, $type = null, $key = null ) {
	$rows = md_setting( array( $id, 'builder' ) );
	$builder = array(
		'data' => array(),
		'elements' => array(),
		'locations' => array(),
		'fields' => array()
	);

	if ( $rows ) {
		$builder['fields'] = $rows;
		$elements = apply_filters( "md_{$id}_builder_elements", array() );

		foreach ( $rows as $row_id => $fields ) {
			if ( empty( $fields['builder_type'] ) || ! isset( $fields['builder_area'] ) )
				continue;

			$row_type = $fields['builder_type'];
			$row_area = $fields['builder_area'];
			$item = array( 'type' => $row_type, 'id' => $row_id );

			if ( ! empty( $elements[$row_type]['render'] ) )
				$item['render'] = $elements[$row_type]['render'];

			$builder['data'][$row_area][] = $item;
			$builder['elements'][$row_type][] = $row_id;
			$builder['locations'][$row_id] = $row_area;
		}
	}

	if ( $type )
		$builder = isset( $builder[$type] ) ? $builder[$type] : array();

	if ( ! empty( $key ) )
		$builder = ! empty( $builder[$key] ) ? $builder[$key] : array();

	return $builder;
}

/**
 * A list of post types and taxonomies to enable MD Optins features to.
 *
 * @since 5.0
 */

function md_optins_locations( $sort = null ) {
	$locations = array();
	$data = apply_filters( 'md_optins_locations', array(
		'sitewide' => __( 'Sitewide', 'md' ),
		'front' => __( 'Front Page', 'md' ),
		'home' => __( 'Blog Page', 'md' ),
		'post' => __( 'All Posts', 'md' ),
		'category' => __( 'All Categories', 'md' ),
		'page' => __( 'All Pages', 'md' ),
		'author' => __( 'All Author Pages', 'md' ),
		'search' => __( 'Search Results', 'md' )
	) );

	if ( isset( $sort ) ) {
		foreach ( $data as $id => $label ) {
			if ( $sort == 'ids' )
				$locations[] = $id;
			elseif ( $sort == 'options' )
				$locations[$id] = $label;
		}
	}
	else $locations = $data;

	return $locations;
}

/**
 * Get a list of Sticky posts by post type.
 *
 * @since 6.0
 */

function md_get_sticky( $post_type = null ) {
	if ( empty( $post_type ) )
		$post_type = get_post_type();

	$sticky = array();

	foreach ( get_option( 'sticky_posts', array() ) as $id )
		if ( $post_type === get_post_type( $id ) )
			$sticky[] = $id;

	return $sticky;
}

/**
 * Returns list of enabled Drop-ins, sortable by a variety of statuses.
 * $status === active, inactive, files
 * `files` returns list of drop-ins in Drop-in CodeBlock format.
 *
 * @since 5.3
 */

function md_get_dropins( $status = null, $key = null ) {
	$installed = md_setting( array( 'dropins', 'installed' ), array() );

	if ( $status === 'files' ) {
		$dropins = array();

		foreach ( $installed as $dropin => $fields ) {
			foreach ( $fields as $header => $field ) {
				$title = ucwords( $header );
				$dropins["$dropin/$dropin.php"][$title] = $field;
			}

			$dropins["$dropin/$dropin.php"]['ID'] = $dropin;
		}

		return ! empty( $key ) ? $dropins["$key/$key.php"] : $dropins;
	}

	$dropins = $priority = array();

	foreach ( $installed as $dropin => $fields ) {
		$enabled = ! empty( $fields['status']['enable'] );

		if ( $status === null || ( $status === 'active' && $enabled ) || ( $status === 'inactive' && ! $enabled ) ) {
			if ( is_numeric( $fields['priority'] ?? null ) )
				$priority[$fields['priority']][] = sanitize_key( $dropin );
			else
				$dropins[] = sanitize_key( $dropin );
		}
	}

	ksort( $priority );
	$sorted_priority = array();

	foreach ( $priority as $group )
		$sorted_priority = array_merge( $sorted_priority, $group );

	$dropins = array_merge( $sorted_priority, $dropins );

	return ! empty( $key ) ? $dropins[$key] : $dropins;
}