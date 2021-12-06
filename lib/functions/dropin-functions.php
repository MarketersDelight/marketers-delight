<?php
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
 * Return a list of details as entered from Drop-in DocBlock.
 *
 * @note Based off core function get_plugin_data()
 * @since 5.4
 */

function md_get_dropin_data( $dropin_file ) {
	$default_headers = array(
		'Name' => 'Drop-in Name',
		'DropinURI' => 'Drop-in URI',
		'Version' => 'Version',
		'Description' => 'Description',
		'Slug' => 'Drop-in Slug',
		'Author' => 'Author',
		'AuthorURI' => 'Author URI',
		'TextDomain' => 'Text Domain',
		'DomainPath' => 'Domain Path',
		'Network' => 'Network',
		'RequiresWP' => 'Requires at least',
		'RequiresPHP' => 'Requires PHP',
		'UpdateURI' => 'Update URI',
		'_sitewide'   => 'Site Wide Only'
	);

	$dropin_data = get_file_data( $dropin_file, $default_headers, 'dropin' );

	if ( ! $dropin_data['Network'] && $dropin_data['_sitewide'] ) {
		_deprecated_argument( __FUNCTION__, '3.0.0', sprintf( __( 'The %1$s drop-in header is deprecated. Use %2$s instead.' ), '<code>Site Wide Only: true</code>', '<code>Network: true</code>' ) );
		$dropin_data['Network'] = $dropin_data['_sitewide'];
	}

	$dropin_data['Network'] = ( 'true' === strtolower( $dropin_data['Network'] ) );
	unset( $dropin_data['_sitewide'] );

	// If no text domain is defined fall back to the plugin slug.
	if ( ! $dropin_data['TextDomain'] ) {
		$dropin_slug = $dropin_data['Slug'];
		if ( '.' !== $dropin_slug && false === strpos( $dropin_slug, '/' ) )
			$dropin_data['TextDomain'] = $dropin_slug;
	}

	return $dropin_data;
}

/**
 * Run this function to activate drop-ins to the MD Drop-ins
 * Manager. Not recommended for use outside of upgrader utilities.
 *
 * @since 5.4
 */

function md_activate_dropin( $dropin ) {
	$option = md_setting();
	if ( empty( $option['dropins']['installed'][$dropin] ) )
		return false;
	$option['dropins']['installed'][$dropin]['status']['enable'] = true;
	update_option( 'marketers_delight', $option );
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
	if ( in_array( $basename, $active ) )
		return true;
	return false;
}