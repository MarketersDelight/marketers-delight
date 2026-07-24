<?php
/**
 * One-time option storage migrations.
 *
 * @since 6.0
 */

/**
 * Move the license key, status, and update data out of frontend settings.
 *
 * @since 6.0
 */

function md_migrate_license_storage() {
	$settings = get_option( 'marketers_delight', array() );
	$settings = is_array( $settings ) ? $settings : array();
	$stored = get_option( 'marketers_delight_license', null );
	$license = is_array( $stored ) ? $stored : array();
	$changed = false;

	if ( isset( $settings['license'] ) && is_array( $settings['license'] ) ) {
		$license = array_replace_recursive( $settings['license'], $license );
		unset( $settings['license'] );
		$changed = true;
	}

	if ( isset( $settings['settings']['license_key'] ) ) {
		$license['key'] = sanitize_text_field( $settings['settings']['license_key'] );
		unset( $settings['settings']['license_key'] );
		$changed = true;
	}

	if ( null === $stored || $changed )
		md_update_license( $license );

	if ( $changed )
		update_option( 'marketers_delight', $settings );
}

add_action( 'admin_init', 'md_migrate_license_storage', 5 );

/**
 * Keep only credentials in private integration storage and runtime data in
 * frontend settings.
 *
 * @since 6.0
 */

function md_migrate_integrations_storage() {
	$version = (int) get_option( 'marketers_delight_storage_version', 0 );

	if ( $version >= 3 )
		return;

	$settings = get_option( 'marketers_delight', array() );
	$settings = is_array( $settings ) ? $settings : array();
	$runtime = isset( $settings['integrations'] ) && is_array( $settings['integrations'] ) ? $settings['integrations'] : array();
	$stored = get_option( 'marketers_delight_integrations', null );
	$legacy = is_array( $stored ) ? $stored : $runtime;
	$private = array();

	if ( isset( $legacy['api_keys'] ) && is_array( $legacy['api_keys'] ) )
		$private['api_keys'] = $legacy['api_keys'];

	foreach ( array( 'services', 'enabled' ) as $branch )
		if ( isset( $legacy[$branch] ) && is_array( $legacy[$branch] ) )
			$runtime[$branch] = $legacy[$branch];

	$public_keys = array();

	foreach ( array( 'typekit', 'google_analytics' ) as $service )
		if ( isset( $private['api_keys'][$service]['key'] ) ) {
			$public_keys[$service]['key'] = $private['api_keys'][$service]['key'];
			unset( $private['api_keys'][$service] );
		}

	if ( $public_keys )
		$runtime['api_keys'] = $public_keys;
	else
		unset( $runtime['api_keys'] );

	md_update_integrations( $private );
	md_update_setting_part( array( 'integrations' => $runtime ) );
	update_option( 'marketers_delight_storage_version', 3, false );
}

add_action( 'admin_init', 'md_migrate_integrations_storage', 5 );

/**
 * Recursively remove values with no stored meaning while preserving valid
 * zero values.
 *
 * @since 6.0
 */

function md_migrate_prune_empty_settings( $settings ) {
	if ( ! is_array( $settings ) )
		return $settings;

	foreach ( $settings as $key => $value ) {
		$value = md_migrate_prune_empty_settings( $value );

		if ( $value === '' || $value === false || $value === null || $value === array() )
			unset( $settings[$key] );
		else
			$settings[$key] = $value;
	}

	return $settings;
}

/**
 * Restore the sparse main option used before empty field values were stored.
 *
 * @since 6.0
 */

function md_migrate_sparse_settings() {
	$version = (int) get_option( 'marketers_delight_storage_version', 0 );

	if ( $version >= 4 )
		return;

	$settings = get_option( 'marketers_delight', array() );
	$settings = is_array( $settings ) ? $settings : array();
	$clean = md_migrate_prune_empty_settings( $settings );

	if ( $clean !== $settings )
		update_option( 'marketers_delight', $clean );

	update_option( 'marketers_delight_storage_version', 4, false );
}

add_action( 'admin_init', 'md_migrate_sparse_settings', 6 );

/**
 * Move full Drop-in inventory out of the frontend settings option.
 *
 * This runs before Drop-in files load so active Drop-ins continue working
 * on the first request after an update.
 *
 * @since 6.0
 */

function md_migrate_dropins_storage() {
	$settings = get_option( 'marketers_delight', array() );
	$settings = is_array( $settings ) ? $settings : array();

	if ( isset( $settings['dropins']['active'] ) )
		return;

	$dropins = isset( $settings['dropins'] ) && is_array( $settings['dropins'] ) ? $settings['dropins'] : array();
	$stored = get_option( 'marketers_delight_dropins', null );

	if ( null === $stored )
		add_option( 'marketers_delight_dropins', array( 'dropins' => $dropins ), '', false );
	elseif ( isset( $stored['dropins'] ) && is_array( $stored['dropins'] ) )
		$dropins = $stored['dropins'];

	md_sync_active_dropins( $dropins );
}
