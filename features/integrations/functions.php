<?php

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
