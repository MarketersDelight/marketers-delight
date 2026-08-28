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
 * Get all registered MD Collections or one Collection definition.
 *
 * @since 6.0
 */

function md_collections( $id = null ) {
	$collections = apply_filters( 'md_filter_collections', array() );

	if ( isset( $id ) )
		return $collections[sanitize_key( $id )] ?? array();

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
	$defaults = md_setting_defaults();
	$option = get_option( 'marketers_delight', array() );
	$option = array_replace_recursive( $defaults, (array) $option );

	if ( is_null( $keys ) )
		return $option;

	foreach ( (array) $keys as $key ) {
		if ( ! is_array( $option ) || ! array_key_exists( $key, $option ) )
			return $default;

		$option = $option[$key];
	}

	return $option;
}

/**
 * Default settings can be set with a filter, so not a bad idea
 * to cache when modifying the main options array.
 *
 * @since 6.0
 */

function md_setting_defaults( $refresh = false ) {
	static $defaults = null;

	if ( $refresh || is_null( $defaults ) )
		$defaults = apply_filters( 'md_setting_defaults', array() );

	return is_array( $defaults ) ? $defaults : array();
}

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
 * Read private integration data from its current storage branch.
 *
 * @since 6.0
 */

function md_integration_setting( $keys = null, $default = null ) {
	$integrations = get_option( 'marketers_delight_integrations', array() );
	$integrations = is_array( $integrations ) ? $integrations : array();

	if ( ! isset( $keys ) )
		return $integrations;

	foreach ( (array) $keys as $key ) {
		if ( ! is_array( $integrations ) || ! array_key_exists( $key, $integrations ) )
			return $default;

		$integrations = $integrations[$key];
	}

	return $integrations;
}

/**
 * Combine private credentials with public runtime data for admin interfaces.
 *
 * @since 6.0
 */

function md_integration_data() {
	$integrations = md_setting( 'integrations', array() );
	$integrations = is_array( $integrations ) ? $integrations : array();
	$api_keys = md_integration_setting( 'api_keys', array() );

	if ( $api_keys )
		$integrations['api_keys'] = array_replace(
			isset( $integrations['api_keys'] ) && is_array( $integrations['api_keys'] ) ? $integrations['api_keys'] : array(),
			$api_keys
		);

	return $integrations;
}

/**
 * Update the current integration storage branch.
 *
 * @since 6.0
 */

function md_update_integrations( $integrations ) {
	if ( ! is_array( $integrations ) )
		return false;

	if ( null === get_option( 'marketers_delight_integrations', null ) )
		return add_option( 'marketers_delight_integrations', $integrations, '', false );

	return update_option( 'marketers_delight_integrations', $integrations );
}

/**
 * Update one integration's private and public data together.
 *
 * Private data is stored in marketers_delight_integrations. Public data is
 * stored by branch and service in marketers_delight[integrations].
 *
 * @since 6.0
 */

function md_update_integration( $service, $private = null, $public = array() ) {
	$service = sanitize_key( $service );

	if ( ! $service || ( ! is_null( $private ) && ! is_array( $private ) ) || ! is_array( $public ) )
		return false;

	$private_settings = md_integration_setting();
	$runtime = md_setting( 'integrations', array() );
	$runtime = is_array( $runtime ) ? $runtime : array();

	if ( is_array( $private ) )
		$private_settings['api_keys'][$service] = $private;

	foreach ( $public as $branch => $value ) {
		$branch = sanitize_key( $branch );

		if ( ! $branch )
			continue;

		if ( is_null( $value ) )
			unset( $runtime[$branch][$service] );
		else
			$runtime[$branch][$service] = $value;
	}

	$private_updated = md_update_integrations( $private_settings );
	$public_updated = md_update_setting_part( array( 'integrations' => $runtime ) );

	return $private_updated || $public_updated;
}

/**
 * Remove one integration from private and public storage.
 *
 * @since 6.0
 */

function md_delete_integration( $service ) {
	$service = sanitize_key( $service );

	if ( ! $service )
		return false;

	$private = md_integration_setting();
	$runtime = md_setting( 'integrations', array() );
	$runtime = is_array( $runtime ) ? $runtime : array();

	unset( $private['api_keys'][$service] );

	foreach ( $runtime as $branch => $services )
		if ( is_array( $services ) )
			unset( $runtime[$branch][$service] );

	$private_updated = md_update_integrations( $private );
	$public_updated = md_update_setting_part( array( 'integrations' => $runtime ) );

	return $private_updated || $public_updated;
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

	$option = array( 'dropins' => $dropins );

	if ( null === get_option( 'marketers_delight_dropins', null ) )
		$updated = add_option( 'marketers_delight_dropins', $option, '', false );
	else
		$updated = update_option( 'marketers_delight_dropins', $option );

	md_sync_active_dropins( $dropins );

	return $updated;
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
 * Sync the frontend Drop-in list after a Settings API form save.
 *
 * @since 6.0
 */

function md_save_dropins_option( $option ) {
	$dropins = isset( $option['dropins'] ) && is_array( $option['dropins'] ) ? $option['dropins'] : array();

	md_sync_active_dropins( $dropins );

	return $option;
}

add_filter( 'md_save_marketers_delight_dropins', 'md_save_dropins_option' );

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
