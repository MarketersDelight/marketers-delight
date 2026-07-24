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
		$dropins = md_setting( array( 'dropins', 'active' ), array() );
		$dropins = is_array( $dropins ) ? $dropins : array();
	}
	else
		$dropins = md_dropin_list( md_dropins_setting(), $status );

	if ( $status === 'files' && ! empty( $key ) )
		return isset( $dropins["$key/$key.php"] ) ? $dropins["$key/$key.php"] : array();

	return ! empty( $key ) ? ( isset( $dropins[$key] ) ? $dropins[$key] : null ) : $dropins;
}
