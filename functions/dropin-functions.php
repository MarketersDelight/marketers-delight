<?php

/**
 * Check if MD has a specific feature enabled.
 *
 * @since 4.5
 */

function md_has( $dropin ) {
	return in_array( $dropin, md_get_dropins( 'active' ), true );
}

/**
 * Returns list of enabled Drop-ins, sortable by a variety of statuses.
 * $status === active, inactive, files
 * `files` returns list of drop-ins in Drop-in CodeBlock format.
 *
 * @since 5.3
 */

function md_dropin_list( $dropins, $status = null ) {
	$installed = isset( $dropins['installed'] ) && is_array( $dropins['installed'] ) ? $dropins['installed'] : array();

	if ( $status === 'files' ) {
		$files = array();

		foreach ( $installed as $dropin => $fields ) {
			foreach ( $fields as $header => $field ) {
				$title = ucwords( $header );
				$files["$dropin/$dropin.php"][$title] = $field;
			}

			$files["$dropin/$dropin.php"]['ID'] = $dropin;
		}

		return $files;
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

	return $dropins;
}

/**
 * Return enabled, disabled, or installed Drop-ins.
 *
 * Active Drop-ins come from the compact frontend settings projection. Full
 * inventory is loaded only for admin and upgrader operations.
 *
 * @since 5.3
 */

function md_get_dropins( $status = null, $key = null ) {
	if ( $status === 'active' ) {
		$dropins = md_setting_part( array( 'dropins' ) );
		$active = isset( $dropins['dropins']['active'] ) ? $dropins['dropins']['active'] : array();
		$dropins = is_array( $active ) ? $active : array();
	}
	else
		$dropins = md_dropin_list( md_dropins_setting(), $status );

	if ( $status === 'files' && ! empty( $key ) )
		return isset( $dropins["$key/$key.php"] ) ? $dropins["$key/$key.php"] : array();

	return ! empty( $key ) ? ( isset( $dropins[$key] ) ? $dropins[$key] : null ) : $dropins;
}

/**
 * Run this function to activate drop-ins to the MD Drop-ins
 * Manager. Not recommended for use outside of upgrader utilities.
 *
 * @since 5.4
 */

function md_activate_dropin( $dropin ) {
	$dropins = md_dropins_setting();

	if ( empty( $dropins['installed'][$dropin] ) )
		return false;

	$dropins['installed'][$dropin]['status']['enable'] = true;

	md_update_dropins( $dropins );
}

/**
 * Check if any given drop-in is active by looking up drop-in
 * file path (ex: pass `dropin-name/dropin-name.php` as $path).
 *
 * @since 5.4
 */

function md_is_dropin_active( $path ) {
	$active = md_get_dropins( 'active' );
	$basename = str_replace( '.php', '', basename( $path ) );

	return in_array( $basename, $active, true );
}
