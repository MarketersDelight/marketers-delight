<?php
/**
 * Changing Drop-ins: activate, install, update, delete, claim and
 * validate packages. Loaded wherever settings load, so the Drop-ins
 * screen and abilities share it. Reading Drop-ins lives in
 * functions/dropin-functions.php.
 *
 * @since 6.2
 */

/**
 * Whether a Drop-in's Requires PHP, WordPress and MD versions are met.
 *
 * @since 6.2
 */

function md_check_dropin_requirements( $data ) {
	if ( ! is_php_version_compatible( isset( $data['RequiresPHP'] ) ? $data['RequiresPHP'] : null ) )
		return new WP_Error( 'incompatible_php_required_version', sprintf( __( 'This Drop-in requires PHP %s.', 'md' ), $data['RequiresPHP'] ) );

	if ( ! is_wp_version_compatible( isset( $data['RequiresWP'] ) ? $data['RequiresWP'] : null ) )
		return new WP_Error( 'incompatible_wp_required_version', sprintf( __( 'This Drop-in requires WordPress %s.', 'md' ), $data['RequiresWP'] ) );

	if ( ! empty( $data['RequiresMD'] ) && version_compare( MD_VERSION, $data['RequiresMD'], '<' ) )
		return new WP_Error( 'incompatible_md_required_version', sprintf( __( 'This Drop-in requires Marketers Delight %s.', 'md' ), $data['RequiresMD'] ) );

	return true;
}

/**
 * Turn Drop-ins on or off in saved state, with no capability check; callers
 * use md_activate_dropins() and md_deactivate_dropins(). The CSS compiles
 * once, on the next load.
 *
 * @since 6.2
 */

function md_set_dropins_status( $slugs, $enable ) {
	$dropins = md_dropins_setting();
	$results = array();

	foreach ( (array) $slugs as $slug ) {
		$slug = sanitize_key( $slug );
		$details = md_get_dropin_details( $slug );

		if ( ! $details ) {
			$results[$slug] = new WP_Error( 'md_dropin_missing', __( 'That Drop-in is not installed.', 'md' ) );
			continue;
		}

		if ( $enable ) {
			$requirements = md_check_dropin_requirements( $details );

			if ( is_wp_error( $requirements ) ) {
				$results[$slug] = $requirements;
				continue;
			}

			$dropins['installed'][$slug]['status']['enable'] = true;
		}
		else
			unset( $dropins['installed'][$slug]['status']['enable'] );

		$results[$slug] = true;
	}

	md_update_dropins( $dropins );
	update_option( 'md_compile_after_upgrade', true, false );

	return $results;
}

/**
 * Activate one or more installed Drop-ins.
 *
 * @since 6.2
 */

function md_activate_dropins( $slugs ) {
	if ( ! current_user_can( 'activate_plugins' ) )
		return new WP_Error( 'md_cannot_activate', __( 'Sorry, you are not allowed to activate Drop-ins.', 'md' ) );

	return md_set_dropins_status( $slugs, true );
}

/**
 * Deactivate one or more Drop-ins.
 *
 * @since 6.2
 */

function md_deactivate_dropins( $slugs ) {
	if ( ! current_user_can( 'activate_plugins' ) )
		return new WP_Error( 'md_cannot_deactivate', __( 'Sorry, you are not allowed to deactivate Drop-ins.', 'md' ) );

	return md_set_dropins_status( $slugs, false );
}

/**
 * Install a Drop-in from a ZIP path or a licensed package URL. An
 * installed Drop-in is only replaced when `overwrite` is set; otherwise a
 * `md_dropin_exists` error carries both versions for the replace screen.
 *
 * @since 6.2
 */

function md_install_dropin( $package, $args = array() ) {
	$args = wp_parse_args( $args, array(
		'overwrite' => false,
		'skin' => null
	) );
	$is_url = (bool) preg_match( '#^https?://#i', (string) $package );

	if ( ! current_user_can( 'install_plugins' ) || ( ! $is_url && ! current_user_can( 'upload_plugins' ) ) )
		return new WP_Error( 'md_cannot_install', __( 'Sorry, you are not allowed to install Drop-ins.', 'md' ) );

	$filesystem = md_dropin_filesystem();

	if ( ! $filesystem )
		return new WP_Error( 'md_filesystem', __( 'Could not access the file system.', 'md' ) );

	if ( ! $filesystem->exists( MD_INSTALLED_DROPINS ) ) {
		if ( ! $filesystem->mkdir( MD_INSTALLED_DROPINS, FS_CHMOD_DIR ) )
			return new WP_Error( 'md_mkdir', __( 'Could not create the Drop-ins directory.', 'md' ) );

		md_protect_dropin_directory( MD_INSTALLED_DROPINS, $filesystem );
	}

	require_once MD_DIR . 'api/dropins/upgrader.php';

	$skin = $args['skin'] ? $args['skin'] : new WP_Ajax_Upgrader_Skin();
	$upgrader = new MD_Dropin_Upgrader( $skin );
	$result = $upgrader->install( $package, array(
		'overwrite_package' => (bool) $args['overwrite']
	) );

	if ( is_wp_error( $result ) )
		return $result;

	if ( ! $result ) {
		$errors = method_exists( $skin, 'get_errors' ) ? $skin->get_errors() : null;

		return $errors && $errors->has_errors() ? $errors : new WP_Error( 'md_install_failed', __( 'Could not install the Drop-in.', 'md' ) );
	}

	$slug = $upgrader->installed_dropin;

	md_protect_dropin_directory( MD_INSTALLED_DROPINS . "/$slug", $filesystem );
	md_flush_dropin_details();

	$details = md_get_dropin_details( $slug );
	$dropins = md_dropins_setting();
	$license = md_license_setting();
	$replaced = $upgrader->replaced_dropin;

	if ( ! $replaced && ! empty( $details['ActivateOnInstall'] ) && current_user_can( 'activate_plugins' ) && md_check_dropin_requirements( $details ) === true )
		$dropins['installed'][$slug]['status']['enable'] = true;

	unset( $license['available'][$slug] );

	md_update_dropins( $dropins );
	md_update_license( $license );
	update_option( 'md_compile_after_upgrade', true, false );

	return array(
		'slug' => $slug,
		'name' => $details['Name'],
		'version' => $details['Version'],
		'active' => ! empty( $dropins['installed'][$slug]['status']['enable'] ),
		'replaced' => $replaced
	);
}

/**
 * Update Drop-ins from the last update check's packages. Pass a skin to show
 * progress. Returns each slug's result: true, false or a WP_Error.
 *
 * @since 6.2
 */

function md_upgrade_dropins( $slugs, $skin = null ) {
	if ( ! current_user_can( 'update_plugins' ) )
		return new WP_Error( 'md_cannot_update', __( 'Sorry, you are not allowed to update Drop-ins.', 'md' ) );

	require_once ABSPATH . 'wp-admin/includes/admin.php';
	require_once MD_DIR . 'api/dropins/upgrader.php';

	$slugs = md_clean_dropin_slugs( $slugs );
	$upgrader = new MD_Dropin_Upgrader( $skin ? $skin : new Automatic_Upgrader_Skin() );
	$results = $upgrader->bulk_upgrade( $slugs );

	if ( ! is_array( $results ) )
		return new WP_Error( 'md_update_failed', __( 'Could not update the Drop-ins.', 'md' ) );

	md_flush_dropin_details();
	md_update_dropins( md_dropins_setting() );
	update_option( 'md_compile_after_upgrade', true, false );

	return $results;
}

/**
 * Delete inactive Drop-ins: their files, saved state and update entry.
 * Active Drop-ins are refused, as core refuses active plugins.
 *
 * @since 6.2
 */

function md_delete_dropins( $slugs ) {
	if ( ! current_user_can( 'delete_plugins' ) )
		return new WP_Error( 'md_cannot_delete', __( 'Sorry, you are not allowed to delete Drop-ins.', 'md' ) );

	$filesystem = md_dropin_filesystem();

	if ( ! $filesystem )
		return new WP_Error( 'md_filesystem', __( 'Could not access the file system.', 'md' ) );

	$dropins = md_dropins_setting();
	$license = md_license_setting();
	$results = array();

	foreach ( (array) $slugs as $slug ) {
		$slug = sanitize_key( $slug );
		$directory = MD_INSTALLED_DROPINS . "/$slug";

		if ( ! $slug || ! is_dir( $directory ) || ! md_path_is_contained( $directory, MD_INSTALLED_DROPINS ) ) {
			$results[$slug] = new WP_Error( 'md_dropin_missing', __( 'That Drop-in is not installed.', 'md' ) );
			continue;
		}

		if ( ! empty( $dropins['installed'][$slug]['status']['enable'] ) ) {
			$results[$slug] = new WP_Error( 'md_dropin_active', __( 'Deactivate the Drop-in before deleting it.', 'md' ) );
			continue;
		}

		if ( ! $filesystem->delete( $directory, true ) ) {
			$results[$slug] = new WP_Error( 'md_delete_failed', __( 'Could not delete the Drop-in files.', 'md' ) );
			continue;
		}

		unset( $dropins['installed'][$slug], $license['updates']['dropins']["$slug/$slug.php"] );
		$results[$slug] = true;
	}

	md_flush_dropin_details();
	md_update_dropins( $dropins );
	md_update_license( $license );

	return $results;
}

/**
 * Licensed Drop-ins this site can install but hasn't, from the last update
 * check. Pass `upgrade` for those a higher plan adds. Sites without a license
 * have none.
 *
 * @since 6.2
 */

function md_available_dropins( $statuses = array( 'claimed', 'included' ) ) {
	$available = md_license_setting( 'available', array() );
	$found = array();

	foreach ( is_array( $available ) ? $available : array() as $slug => $dropin ) {
		$status = isset( $dropin['status'] ) ? $dropin['status'] : ( empty( $dropin['package'] ) ? '' : 'claimed' );

		if ( ! in_array( $status, (array) $statuses, true ) || md_get_dropin_details( $slug ) )
			continue;

		if ( isset( $dropin['ready'] ) && $dropin['ready'] === '0' )
			continue;

		$dropin['status'] = $status;
		$found[$slug] = $dropin;
	}

	return $found;
}

/**
 * Drop-ins a higher plan covers, installed or not, each with `installed` set.
 * Starter sites use it for the upgrade banner.
 *
 * @since 6.2
 */

function md_plan_upgrade_dropins() {
	$available = md_license_setting( 'available', array() );
	$found = array();

	foreach ( is_array( $available ) ? $available : array() as $slug => $dropin ) {
		if ( ( isset( $dropin['status'] ) ? $dropin['status'] : '' ) !== 'upgrade' )
			continue;

		$dropin['installed'] = (bool) md_get_dropin_details( $slug );
		$found[$slug] = $dropin;
	}

	return $found;
}

/**
 * Install a Drop-in from the Available list. One the plan includes but
 * the customer hasn't claimed is claimed on MD.com first, in the same
 * request, so it never has to be fetched from the account page.
 *
 * @since 6.2
 */

function md_install_available_dropin( $slug, $args = array() ) {
	$available = md_available_dropins();

	if ( empty( $available[$slug] ) )
		return new WP_Error( 'md_dropin_unavailable', __( 'That Drop-in is not available to install.', 'md' ) );

	if ( ! current_user_can( 'install_plugins' ) )
		return new WP_Error( 'md_cannot_install', __( 'Sorry, you are not allowed to install Drop-ins.', 'md' ) );

	$package = isset( $available[$slug]['package'] ) ? $available[$slug]['package'] : '';

	if ( $available[$slug]['status'] === 'included' ) {
		if ( ! class_exists( 'md_license' ) )
			require_once MD_DIR . 'api/license.php';

		$requests = new md_license;
		$package = $requests->claim_dropin( $slug );

		if ( is_wp_error( $package ) )
			return $package;

		$license = md_license_setting();
		$license['available'][$slug]['status'] = 'claimed';
		$license['available'][$slug]['package'] = $package;
		md_update_license( $license );
	}

	if ( ! $package )
		return new WP_Error( 'md_dropin_unavailable', __( 'That Drop-in is not available to install.', 'md' ) );

	return md_install_dropin( $package, $args );
}

/**
 * The admin menu's update count bubble, for Drop-ins with updates waiting.
 *
 * @since 6.2
 */

function md_dropin_update_badge() {
	$count = count( md_dropin_updates() );

	return $count ? ' <span class="update-plugins count-' . $count . '"><span class="plugin-count">' . $count . '</span></span>' : '';
}

/**
 * Drop-in updates found by the last check, keyed by `slug/slug.php`.
 * Only versions newer than the installed files count, so an update
 * applied another way (a ZIP upload, a deploy) stops showing at once.
 *
 * @since 6.2
 */

function md_dropin_updates() {
	$updates = md_license_setting( array( 'updates', 'dropins' ), array() );
	$found = array();

	foreach ( is_array( $updates ) ? $updates : array() as $path => $update ) {
		$slug = isset( $update['slug'] ) ? $update['slug'] : '';
		$details = $slug ? md_get_dropin_details( $slug ) : false;

		if ( $details && ! empty( $update['new_version'] ) && version_compare( $details['Version'], $update['new_version'], '<' ) )
			$found[$path] = array_merge( $update, array( 'name' => $details['Name'], 'installed_version' => $details['Version'] ) );
	}

	return $found;
}

/**
 * Drop-in slugs sent with a request, as a comma list or an array.
 *
 * @since 6.2
 */

function md_requested_dropins( $key ) {
	$value = isset( $_REQUEST[$key] ) ? wp_unslash( $_REQUEST[$key] ) : array();
	$value = is_array( $value ) ? $value : explode( ',', (string) $value );

	return md_clean_dropin_slugs( $value );
}

/**
 * A list of Drop-in slugs, cleaned, without blanks or repeats.
 *
 * @since 6.2
 */

function md_clean_dropin_slugs( $slugs ) {
	$clean = array();

	foreach ( (array) $slugs as $slug ) {
		$slug = sanitize_key( $slug );

		if ( $slug !== '' && ! in_array( $slug, $clean, true ) )
			$clean[] = $slug;
	}

	return $clean;
}

/**
 * Initialize and return the WordPress filesystem without asking for
 * credentials.
 *
 * @since 6.2
 */

function md_dropin_filesystem() {
	global $wp_filesystem;

	require_once ABSPATH . 'wp-admin/includes/file.php';

	if ( $wp_filesystem )
		return $wp_filesystem;

	ob_start();
	$creds = request_filesystem_credentials( self_admin_url( 'admin.php?page=md_dropins' ), '', false, false, null );
	ob_end_clean();

	if ( false === $creds || ! WP_Filesystem( $creds ) )
		return false;

	return $wp_filesystem;
}

/**
 * Confirm a path resolves inside a base directory.
 *
 * @since 6.0
 */

function md_path_is_contained( $path, $base_dir ) {
	$real_base = realpath( $base_dir );

	if ( ! $real_base )
		return false;

	$real_path = realpath( $path );

	if ( $real_path )
		return $real_path !== $real_base && strpos( $real_path, $real_base . DIRECTORY_SEPARATOR ) === 0;

	return strpos( wp_normalize_path( $path ), trailingslashit( wp_normalize_path( $real_base ) ) ) === 0;
}

/**
 * Add an index file to discourage directory browsing.
 *
 * @since 5.3
 */

function md_protect_dropin_directory( $directory, $filesystem ) {
	$index = trailingslashit( $directory ) . 'index.php';

	if ( ! $filesystem->exists( $index ) )
		$filesystem->put_contents( $index, "<?php\n// Silence is golden.", FS_CHMOD_FILE );
}

/**
 * Validate a staged Drop-in before it replaces installed files, and
 * return its details. A Drop-in that names itself in config.json only is
 * still accepted.
 *
 * @since 6.0
 */

function md_validate_dropin_package( $directory, $slug ) {
	if ( ! preg_match( '/^[a-z0-9_-]+$/', $slug ) )
		return new WP_Error( 'invalid_dropin_slug', __( 'Invalid Drop-in package name.', 'md' ) );

	$file = trailingslashit( $directory ) . "$slug.php";

	if ( ! is_file( $file ) || is_link( $file ) )
		return new WP_Error( 'invalid_dropin_package', sprintf( __( 'The package must contain %s.', 'md' ), "$slug.php" ) );

	$details = md_read_dropin_details( $directory, $slug );

	if ( ! $details || ( $details['Slug'] !== '' && $details['Slug'] !== $slug ) )
		return new WP_Error( 'invalid_dropin_identity', __( 'The package does not match the selected Drop-in.', 'md' ) );

	$requirements = md_check_dropin_requirements( $details );

	return is_wp_error( $requirements ) ? $requirements : $details;
}
