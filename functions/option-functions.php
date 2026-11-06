<?php

/**
 * Return ID without MD_ prefix.
 *
 * @since 5.0
 */

function md_clean_id( $id ) {
	return ( ! empty( $id ) ? preg_replace( '/^' . preg_quote( 'md_', '/' ) . '/', '', $id ) : '' );
}

/**
 * Default post type screens MD metaboxes are added to.
 *
 * @since 4.3.5
 */

function md_post_type_meta() {
	return apply_filters( 'md_post_type_meta', array( 'post', 'page' ) );
}

/**
 * Public post types with MD settings, as options for a post type select.
 * Child/helper types inherit another type's settings and are excluded.
 * Pass search to leave out types excluded from search.
 *
 * @since 6.1
 */

function md_post_type_options( $context = '' ) {
	$options = array();

	foreach ( array_unique( md_post_type_meta() ) as $post_type ) {
		$object = get_post_type_object( $post_type );

		if ( $post_type === 'page' || ! $object || ! $object->public || ! $object->publicly_queryable || ! empty( $object->md_settings_parent ) )
			continue;

		if ( $context === 'search' && $object->exclude_from_search )
			continue;

		$options[$post_type] = $object->labels->name;
	}

	return $options;
}

/**
 * Default taxonomy screens MD metaboxes are added to.
 *
 * @since 4.5.4
 */

function md_edit_term_meta() {
	return apply_filters( 'md_edit_term_meta', array( 'category' ) );
}

/**
 * Taxonomies that have global settings tabs on post-type admin pages.
 *
 * @since 6.0
 */

function md_taxonomy_meta() {
	return apply_filters( 'md_taxonomy_meta', array() );
}

/**
 * A collection of all registered components and fields for build and save.
 *
 * @since 5.0
 */

function md_register( $group = null ) {
	$data = apply_filters( 'md_register', array() );

	if ( isset( $group ) )
		return ! empty( $data[$group] ) ? $data[$group] : array();

	return $data;
}

/**
 * Whether this request registers settings fields: wp-admin, and the
 * Abilities API and MCP routes, which read and save them. Front end pages
 * and other REST routes don't pay for them.
 *
 * @since 6.2
 */

function md_loads_settings() {
	if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) )
		return true;

	$route =isset( $_GET['rest_route'] ) ? $_GET['rest_route'] : ( isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '' );

	return (bool) apply_filters( 'md_loads_settings', is_string( $route ) && preg_match( '#/(wp-abilities|mcp)/#', $route ) );
}

/**
 * Rebuild registered fields from what's saved now. Field options can list
 * saved values (custom sidebars, palette colors, Share icons), and they're
 * built once per request, so a save that adds one needs this before another
 * save can use it.
 *
 * @since 6.2
 */

function md_refresh_fields() {
	md_option_cache( null, true );

	do_action( 'md_refresh_fields' );
}

/**
 * Get all registered MD Collections or one Collection definition.
 *
 * @since 6.0
 */

function md_collections( $id = null ) {
	$collections = apply_filters( 'md_filter_collections', array() );

	if ( isset( $id ) ) {
		$id = sanitize_key( $id );

		return $collections[$id] ?? array();
	}

	return $collections;
}

/**
 * Get a normalized field or full item from a registered MD Collection.
 *
 * Collection fields may be backed by native post columns, taxonomies,
 * or registered post meta. This accessor keeps those storage details
 * out of frontend templates.
 *
 * @since 6.0
 */

function md_collection_field( $collection, $field = null, $id = null, $default = null ) {
	$collection = new md_collection( $collection );

	return $collection->get( $field, $id, $default );
}

/**
 * Pull data from the Marketers Delight options array. For
 * best performance, always pull MD settings from here.
 *
 * @since 4.7
 */

function md_setting( $keys = null, $default = null ) {
	return md_option( 'marketers_delight', $keys, $default );
}

/**
 * Default settings can be set with a filter, so not a bad idea
 * to cache when modifying the main options array.
 *
 * @since 6.0
 */

function md_setting_defaults( $refresh = false ) {
	return md_option_defaults( 'marketers_delight', $refresh );
}

/**
 * Merge an option over the defaults registered for it. The merge walks the
 * whole tree, so each option is held for the request and rebuilt only when
 * either side changes. Flushing with no option clears every cached option.
 *
 * @since 6.0
 */

function md_option_cache( $option = null, $flush = false ) {
	static $merged = array();

	if ( $flush ) {
		if ( is_null( $option ) )
			$merged = array();
		else
			unset( $merged[$option] );

		return null;
	}

	if ( ! array_key_exists( $option, $merged ) )
		$merged[$option] = array_replace_recursive(
			md_option_defaults( $option ),
			(array) get_option( $option, array() )
		);

	return $merged[$option];
}

/**
 * Resolve the defaults registered for an option. The main settings keep the
 * established md_setting_defaults filter; custom options use a filter named
 * md_setting_defaults_{$option}.
 *
 * @since 6.0
 */

function md_option_defaults( $option, $refresh = false ) {
	static $defaults = array();

	if ( $refresh || ! array_key_exists( $option, $defaults ) ) {
		$filter = $option === 'marketers_delight' ? 'md_setting_defaults' : "md_setting_defaults_{$option}";
		$values = apply_filters( $filter, array() );

		$defaults[$option] = is_array( $values ) ? $values : array();

		if ( $refresh )
			md_option_cache( $option, true );
	}

	return $defaults[$option];
}

/**
 * Rebuild one option on the next read. Hooked to the generic option actions,
 * which pass the option name, so keys a Drop-in invents at runtime are
 * covered without knowing them up front.
 *
 * @since 6.0
 */

function md_flush_option_cache( $option ) {
	md_option_cache( $option, true );
}

/**
 * Drop every cached option, ex: the current site changed underneath them.
 *
 * @since 6.0
 */

function md_flush_option_caches() {
	md_option_cache( null, true );
}

add_action( 'added_option', 'md_flush_option_cache' );
add_action( 'updated_option', 'md_flush_option_cache' );
add_action( 'deleted_option', 'md_flush_option_cache' );
add_action( 'switch_blog', 'md_flush_option_caches' );

/**
 * Get raw top-level branches of the MD setting without merging defaults.
 * The returned slice can be inspected or modified before an option update.
 *
 * @since 6.0
 */

function md_setting_part( $keys ) {
	$stored = get_option( 'marketers_delight', array() );
	$slice = array();

	foreach ( (array) $keys as $key )
		$slice[$key] = isset( $stored[$key] ) ? $stored[$key] : array();

	return $slice;
}

/**
 * Merge changed top-level branches into the raw stored option before saving.
 *
 * Unlike Settings API form saves, programmatic writes can run on frontend,
 * REST, or early admin requests where the registered sanitize callback is not
 * available to merge a partial option safely.
 *
 * @since 6.0
 */

function md_update_setting_part( $new ) {
	if ( ! is_array( $new ) )
		return false;

	$option = get_option( 'marketers_delight', array() );
	$option = is_array( $option ) ? $option : array();

	foreach ( $new as $key => $value )
		$option[$key] = $value;

	return update_option( 'marketers_delight', $option );
}

/**
 * Read license state from its current storage branch.
 *
 * @since 6.0
 */

function md_license_setting( $keys = null, $default = null ) {
	$license = get_option( 'marketers_delight_license', array() );

	if ( ! isset( $keys ) )
		return is_array( $license ) ? $license : array();

	foreach ( (array) $keys as $key ) {
		if ( ! is_array( $license ) || ! array_key_exists( $key, $license ) )
			return $default;

		$license = $license[$key];
	}

	return $license;
}

/**
 * Update the current license storage branch.
 *
 * @since 6.0
 */

function md_update_license( $license ) {
	if ( ! is_array( $license ) )
		return false;

	if ( null === get_option( 'marketers_delight_license', null ) )
		return add_option( 'marketers_delight_license', $license, '', false );

	return update_option( 'marketers_delight_license', $license );
}

/**
 * Read installed Drop-in data from its current storage branch.
 *
 * @since 6.0
 */

function md_dropins_setting( $keys = null, $default = null ) {
	$option = get_option( 'marketers_delight_dropins', array() );
	$dropins = isset( $option['dropins'] ) && is_array( $option['dropins'] ) ? $option['dropins'] : array();

	if ( ! isset( $keys ) )
		return $dropins;

	foreach ( (array) $keys as $key ) {
		if ( ! is_array( $dropins ) || ! array_key_exists( $key, $dropins ) )
			return $default;

		$dropins = $dropins[$key];
	}

	return $dropins;
}

/**
 * Update the current Drop-in storage branch.
 *
 * @since 6.0
 */

function md_update_dropins( $dropins ) {
	if ( ! is_array( $dropins ) )
		return false;

	$dropins = md_dropins_state( $dropins );
	$option = array( 'dropins' => $dropins );

	if ( null === get_option( 'marketers_delight_dropins', null ) )
		$updated = add_option( 'marketers_delight_dropins', $option, '', false );
	else
		$updated = update_option( 'marketers_delight_dropins', $option );

	md_sync_active_dropins( $dropins );

	return $updated;
}

/**
 * Keep only site state in the Drop-ins option: which Drop-ins are on.
 * Details are read from each Drop-in's files (md_get_dropin_details()),
 * so copies saved by MD 6.1 and older are dropped on the next write.
 *
 * @since 6.2
 */

function md_dropins_state( $dropins ) {
	$installed = isset( $dropins['installed'] ) && is_array( $dropins['installed'] ) ? $dropins['installed'] : array();
	$state = array();

	foreach ( $installed as $slug => $fields )
		if ( ! empty( $fields['status']['enable'] ) )
			$state['installed'][sanitize_key( $slug )]['status']['enable'] = true;

	return $state;
}

/**
 * Keep only the ordered list of active Drop-ins in frontend settings.
 *
 * @since 6.0
 */

function md_sync_active_dropins( $dropins ) {
	return md_update_setting_part( array(
		'dropins' => array(
			'active' => md_dropin_list( $dropins, 'active' )
		)
	) );
}

/**
 * To pull data from custom options, use this function with
 * your declared option key name.
 *
 * @since 6.0
 */

function md_option( $option, $keys = null, $default = null ) {
	$data = md_option_cache( $option );

	if ( is_null( $keys ) )
		return $data;

	foreach ( (array) $keys as $key ) {
		if ( ! is_array( $data ) || ! array_key_exists( $key, $data ) )
			return $default;

		$data = $data[$key];
	}

	return $data;
}
