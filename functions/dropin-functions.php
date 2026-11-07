<?php
/**
 * Reading Drop-ins: which are active, their details, icons and
 * translations, and the daily update check's hooks. Safe to call from
 * anywhere: front end, admin, cron or REST. Functions that change
 * Drop-ins live in api/dropins/manage.php.
 *
 * @since 6.2
 */

/**
 * Check if MD has a specific feature enabled.
 *
 * @since 4.5
 */

function md_has( $dropin ) {
	return in_array( $dropin, md_get_dropins( 'active' ), true );
}

/**
 * Installed Drop-in slugs in load order: all, `active` or `inactive`. Details
 * and load priority come from md_get_dropin_details().
 *
 * @since 5.3
 */

function md_dropin_list( $dropins, $status = null ) {
	$state = isset( $dropins['installed'] ) && is_array( $dropins['installed'] ) ? $dropins['installed'] : array();
	$details = md_get_dropin_details();
	$list = $priority = array();

	foreach ( $details as $dropin => $fields ) {
		$enabled = ! empty( $state[$dropin]['status']['enable'] );

		if ( $status === null || ( $status === 'active' && $enabled ) || ( $status === 'inactive' && ! $enabled ) ) {
			if ( is_numeric( $fields['LoadPriority'] ) )
				$priority[(int) $fields['LoadPriority']][] = $dropin;
			else
				$list[] = $dropin;
		}
	}

	ksort( $priority );
	$sorted_priority = array();

	foreach ( $priority as $group )
		$sorted_priority = array_merge( $sorted_priority, $group );

	return array_merge( $sorted_priority, $list );
}

/**
 * Active, inactive or all installed Drop-in slugs. Active ones come from
 * the compact front-end settings, so the front end never reads files.
 *
 * @since 5.3
 */

function md_get_dropins( $status = null ) {
	if ( $status !== 'active' )
		return md_dropin_list( md_dropins_setting(), $status );

	$dropins = md_setting_part( array( 'dropins' ) );
	$active = isset( $dropins['dropins']['active'] ) ? $dropins['dropins']['active'] : array();

	return is_array( $active ) ? $active : array();
}

/**
 * The header keys a Drop-in's main file can declare. Its docblock is the
 * one source of a Drop-in's details.
 *
 * @since 6.2
 */

function md_dropin_headers() {
	return array(
		'Name' => 'Drop-in Name',
		'DropinURI' => 'Drop-in URI',
		'Version' => 'Version',
		'Description' => 'Description',
		'Slug' => 'Drop-in Slug',
		'Author' => 'Author',
		'AuthorURI' => 'Author URI',
		'TextDomain' => 'Text Domain',
		'RequiresWP' => 'Requires at least',
		'RequiresPHP' => 'Requires PHP',
		'RequiresMD' => 'Requires MD',
		'Icon' => 'Icon',
		'Colors' => 'Colors',
		'SettingsURL' => 'Settings URL',
		'LoadPriority' => 'Load Priority',
		'ActivateOnInstall' => 'Activate On Install'
	);
}

/**
 * Every installed Drop-in's details, or one's, read from files once per
 * request. The front end never calls this: it loads from the saved active
 * list.
 *
 * @since 6.2
 */

function md_get_dropin_details( $slug = null, $refresh = false ) {
	static $details = null;

	if ( $refresh )
		$details = null;

	if ( $details === null ) {
		$details = array();
		$folders = is_dir( MD_INSTALLED_DROPINS ) ? scandir( MD_INSTALLED_DROPINS ) : false;

		foreach ( $folders ? $folders : array() as $folder ) {
			if ( $folder[0] === '.' || ! preg_match( '/^[a-z0-9_-]+$/', $folder ) )
				continue;

			$data = md_read_dropin_details( MD_INSTALLED_DROPINS . "/$folder", $folder );

			if ( $data )
				$details[$folder] = $data;
		}

		ksort( $details );
	}

	if ( $slug === null )
		return $details;

	return isset( $details[$slug] ) ? $details[$slug] : false;
}

/**
 * Forget the details read this request, after files change.
 *
 * @since 6.2
 */

function md_flush_dropin_details() {
	md_get_dropin_details( null, true );
}

/**
 * Read one Drop-in folder's details: its header, with any config.json
 * values on top.
 *
 * @since 6.2
 */

function md_read_dropin_details( $directory, $slug ) {
	$file = trailingslashit( $directory ) . "$slug.php";

	if ( ! is_file( $file ) || is_link( $file ) )
		return false;

	$data = md_merge_dropin_config( $directory, get_file_data( $file, md_dropin_headers(), 'dropin' ) );

	if ( $data['Name'] === '' )
		return false;

	$data = md_sanitize_dropin_details( $data );
	$data['slug'] = $slug;
	$data['file'] = "$slug/$slug.php";

	return $data;
}

/**
 * Deprecated: Drop-ins that still ship a config.json have its values
 * used over their header, as before 6.2. Drop-ins made for 6.2 and later
 * put everything in their header and ship no config.json.
 *
 * @since 6.2
 */

function md_merge_dropin_config( $directory, $data ) {
	$file = trailingslashit( $directory ) . 'config.json';

	if ( ! is_file( $file ) || is_link( $file ) )
		return $data;

	$config = json_decode( (string) file_get_contents( $file ), true );

	if ( ! is_array( $config ) )
		return $data;

	$keys = array(
		'name' => 'Name',
		'author' => 'Author',
		'version' => 'Version',
		'description' => 'Description',
		'dropin_url' => 'DropinURI',
		'author_url' => 'AuthorURI',
		'settings_url' => 'SettingsURL',
		'icon' => 'Icon',
		'colors' => 'Colors',
		'priority' => 'LoadPriority',
		'active' => 'ActivateOnInstall'
	);

	foreach ( $keys as $config_key => $header ) {
		if ( ! isset( $config[$config_key] ) || ! is_scalar( $config[$config_key] ) || $config[$config_key] === '' )
			continue;

		$data[$header] = is_bool( $config[$config_key] ) ? ( $config[$config_key] ? 'true' : '' ) : (string) $config[$config_key];
	}

	return $data;
}

/**
 * Clean a Drop-in's details for display and storage.
 *
 * @since 6.2
 */

function md_sanitize_dropin_details( $data ) {
	$clean = array();

	foreach ( md_dropin_headers() as $key => $header ) {
		$value = isset( $data[$key] ) && is_scalar( $data[$key] ) ? $data[$key] : '';
		$clean[$key] = sanitize_text_field( $value );
	}

	$clean['DropinURI'] = esc_url_raw( $clean['DropinURI'] );
	$clean['AuthorURI'] = esc_url_raw( $clean['AuthorURI'] );
	$clean['SettingsURL'] = esc_url_raw( $clean['SettingsURL'] );

	$is_dashicon = preg_match( '/^dashicons-[a-z0-9-]+$/', $clean['Icon'] );
	$is_image = preg_match( '/^[A-Za-z0-9_-][A-Za-z0-9._-]*\.(svg|png|jpe?g|webp|gif)$/i', $clean['Icon'] );

	if ( ! $is_dashicon && ! $is_image )
		$clean['Icon'] = '';

	$colors = array();

	foreach ( explode( ',', $clean['Colors'] ) as $color ) {
		$color = sanitize_hex_color( trim( $color ) );

		if ( $color && count( $colors ) < 2 )
			$colors[] = $color;
	}

	$clean['Colors'] = implode( ', ', $colors );

	if ( is_numeric( $clean['LoadPriority'] ) )
		$clean['LoadPriority'] = (string) (int) $clean['LoadPriority'];
	else
		$clean['LoadPriority'] = '';

	$activate = strtolower( $clean['ActivateOnInstall'] );
	$clean['ActivateOnInstall'] = $activate === 'true' || $activate === 'yes' || $activate === '1';

	return $clean;
}

/**
 * A Drop-in's icon tile, as the Drop-ins and Updates screens show it. $size:
 * small (24px), large (40px) or the default 32px.
 *
 * @since 6.2
 */

function md_get_dropin_icon( $slug, $size = '' ) {
	$dropin = md_get_dropin_details( $slug );
	$icon = ! empty( $dropin['Icon'] ) ? $dropin['Icon'] : '';
	$image = $icon && strpos( $icon, 'dashicons-' ) !== 0 && is_file( MD_INSTALLED_DROPINS . "/$slug/$icon" ) ? MD_INSTALLED_DROPINS_URL . "/$slug/" . rawurlencode( $icon ) : '';

	return md_dropin_icon_tile( $image ? $image : $icon, ! empty( $dropin['Colors'] ) ? $dropin['Colors'] : '', $size );
}

/**
 * An icon tile from a dashicon or an image URL and a `bg, color` pair.
 * Used for Drop-ins that aren't installed yet, whose details come from
 * MD.com.
 *
 * @since 6.2
 */

function md_dropin_icon_tile( $icon, $colors = '', $size = '' ) {
	$colors = $colors ? array_map( 'trim', explode( ',', $colors ) ) : array();
	$style = md_style( array(
		'bg_color' => isset( $colors[0] ) ? sanitize_hex_color( $colors[0] ) : '',
		'color' => isset( $colors[1] ) ? sanitize_hex_color( $colors[1] ) : ''
	) );

	if ( preg_match( '#^https?://#', $icon ) )
		$inner = '<img src="' . esc_url( $icon ) . '" alt="">';
	else
		$inner = '<i class="dashicons ' . esc_attr( preg_match( '/^dashicons-[a-z0-9-]+$/', $icon ) ? $icon : 'dashicons-admin-plugins' ) . '"></i>';

	return '<span class="md-dropin-icon' . ( $size ? ' md-dropin-icon-' . esc_attr( $size ) : '' ) . '"' . $style . ' aria-hidden="true">' . $inner . '</span>';
}

/**
 * Load a Drop-in's translations from its languages/ folder. The header is
 * only read when the Drop-in ships that folder, to find its Text Domain.
 *
 * @since 6.0
 */

function md_load_dropin_textdomain( $dropin ) {
	$languages = md_dropin_languages_path( $dropin );

	if ( ! is_dir( $languages ) )
		return false;

	$headers = get_file_data( MD_INSTALLED_DROPINS . "/$dropin/$dropin.php", array( 'TextDomain' => 'Text Domain' ), 'dropin' );
	$domain = $headers['TextDomain'] ?: "md-$dropin";

	return load_textdomain( $domain, "$languages/$domain-" . determine_locale() . '.mo' );
}

/**
 * Get the path to a Drop-in's translations folder.
 *
 * @since 6.0
 */

function md_dropin_languages_path( $dropin ) {
	return MD_INSTALLED_DROPINS . "/$dropin/languages";
}

/**
 * Whether a license key is saved. Sites without one never contact MD.com.
 *
 * @since 6.2
 */

function md_has_license_key() {
	$key = md_license_setting( 'key', '' );

	return is_string( $key ) && trim( $key ) !== '';
}

/**
 * Check MD.com for theme and Drop-in updates and save the result. Runs
 * daily from cron. Sites without a license key never contact MD.com:
 * they install Drop-ins by uploading ZIPs only.
 *
 * @since 6.2
 */

function md_check_dropin_updates() {
	if ( ! md_has_license_key() )
		return 'skipped';

	if ( ! class_exists( 'md_license' ) )
		require_once MD_DIR . 'api/license.php';

	$requests = new md_license;

	return $requests->check_updates();
}

add_action( 'md_check_updates', 'md_check_dropin_updates' );

/**
 * Stop the daily update check when MD stops being the active theme.
 *
 * @since 6.2
 */

function md_clear_update_check() {
	wp_clear_scheduled_hook( 'md_check_updates' );
}

add_action( 'switch_theme', 'md_clear_update_check' );

/**
 * Tell WordPress about a waiting MD theme update, from the last license
 * check. Runs whenever WordPress rebuilds its theme updates, including on
 * cron, so the update isn't lost between admin visits.
 *
 * @since 4.7
 */

function md_theme_update_transient( $transient ) {
	$update = md_license_setting( array( 'updates', 'theme' ) );

	if ( ! is_object( $transient ) || empty( $update['new_version'] ) )
		return $transient;

	if ( version_compare( MD_VERSION, $update['new_version'], '<' ) )
		$transient->response['marketers-delight'] = $update;
	else
		$transient->no_update['marketers-delight'] = $update;

	return $transient;
}

add_filter( 'pre_set_site_transient_update_themes', 'md_theme_update_transient' );
