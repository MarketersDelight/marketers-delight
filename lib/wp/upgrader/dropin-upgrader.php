<?php

require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
require_once( 'dropin-upgrader-skin.php' );
require_once( 'dropin-installer-skin.php' );

/**
 * Creates various routines for upgrading Drop-ins.
 *
 * @since 5.4
 */

class MD_Dropin_Upgrader extends WP_Upgrader {
	
	public $result;
	public $bulk = false;
	public $new_dropin_data = array();

	public function upgrade_strings() {
		$this->strings['up_to_date'] = __( 'The dropin is at the latest version.' );
		$this->strings['no_package'] = __( 'Update package not available.' );
		$this->strings['downloading_package']  = sprintf( __( 'Downloading update from %s&#8230;' ), '<span class="code">%s</span>' );
		$this->strings['unpack_package']       = __( 'Unpacking the update&#8230;' );
		$this->strings['remove_old']           = __( 'Removing the old version of the dropin&#8230;' );
		$this->strings['remove_old_failed']    = __( 'Could not remove the old dropin.' );
		$this->strings['process_failed']       = __( 'Drop-in update failed.' );
		$this->strings['process_success']      = __( 'Drop-in updated successfully.' );
		$this->strings['process_bulk_success'] = __( 'Drop-in updated successfully.' );
		$this->strings['skin_before_update_header'] = __( 'Updating Drop-in %1$s (%2$d/%3$d)' );
	}

	/**
	 * Initialize the installation strings.
	 *
	 * @since 2.8.0
	 */
	public function install_strings() {
		$this->strings['no_package'] = __( 'Installation package not available.' );
		/* translators: %s: Package URL. */
		$this->strings['downloading_package'] = sprintf( __( 'Downloading installation package from %s&#8230;' ), '<span class="code">%s</span>' );
		$this->strings['unpack_package']      = __( 'Unpacking the package&#8230;' );
		$this->strings['installing_package']  = __( 'Installing the drop-in&#8230;' );
		$this->strings['remove_old']          = __( 'Removing the current drop-in&#8230;' );
		$this->strings['remove_old_failed']   = __( 'Could not remove the current drop-in.' );
		$this->strings['no_files']            = __( 'The drop-in contains no files.' );
		$this->strings['process_failed']      = __( 'Drop-in installation failed.' );
		$this->strings['process_success']     = __( 'Drop-in installed successfully.' );
		/* translators: 1: Dropin name, 2: Dropin version. */
		$this->strings['process_success_specific'] = __( 'Successfully installed the drop-in <strong>%1$s %2$s</strong>.' );

		if ( ! empty( $this->skin->overwrite ) ) {
			if ( 'update-dropin' === $this->skin->overwrite ) {
				$this->strings['installing_package'] = __( 'Updating the drop-in&#8230;' );
				$this->strings['process_failed']     = __( 'Drop-in update failed.' );
				$this->strings['process_success']    = __( 'Drop-in updated successfully.' );
			}

			if ( 'downgrade-dropin' === $this->skin->overwrite ) {
				$this->strings['installing_package'] = __( 'Downgrading the drop-in&#8230;' );
				$this->strings['process_failed']     = __( 'Drop-in downgrade failed.' );
				$this->strings['process_success']    = __( 'Drop-in downgraded successfully.' );
			}
		}
	}

	public function upgrade( $dropin, $args = array() ) {
		$defaults = array(
			'clear_update_cache' => true,
		);
		$parsed_args = wp_parse_args( $args, $defaults );

		$this->init();
		$this->upgrade_strings();

		$current = md_setting( array( 'license', 'updates', 'dropins' ) );

		if ( empty( $current[$dropin] ) ) {
			$this->skin->before();
			$this->skin->set_result( false );
			$this->skin->error( 'up_to_date' );
			$this->skin->after();
			return false;
		}

		// Get the URL to the zip file.
		$r = $current[$dropin];

		add_filter( 'upgrader_pre_install', array( $this, 'deactivate_dropin_before_upgrade' ), 10, 2 );
		add_filter( 'upgrader_pre_install', array( $this, 'active_before' ), 10, 2 );
		add_filter( 'upgrader_clear_destination', array( $this, 'delete_old_dropin' ), 10, 4 );
		add_filter( 'upgrader_post_install', array( $this, 'active_after' ), 10, 2 );

		$dropin_slug = str_replace( '.php', '', basename( $dropin ) );

		$this->run( array(
			'package' => $r['package'],
			'destination' => MD_INSTALLED_DROPINS . "/$dropin_slug",
			'clear_destination' => true,
			'clear_working' => true,
			'hook_extra' => array(
				'dropin' => $dropin,
				'type' => 'dropin',
				'action' => 'update'
			)
		) );

		// Cleanup our hooks, in case something else does a upgrade on this connection.
		remove_filter( 'upgrader_pre_install', array( $this, 'deactivate_dropin_before_upgrade' ) );
		remove_filter( 'upgrader_pre_install', array( $this, 'active_before' ) );
		remove_filter( 'upgrader_clear_destination', array( $this, 'delete_old_dropin' ) );
		remove_filter( 'upgrader_post_install', array( $this, 'active_after' ) );

		if ( ! $this->result || is_wp_error( $this->result ) )
			return $this->result;

		return true;
	}

	/**
	 * Install a dropin package.
	 *
	 * @since 2.8.0
	 * @since 3.7.0 The `$args` parameter was added, making clearing the dropin update cache optional.
	 *
	 * @param string $package The full local path or URI of the package.
	 * @param array  $args {
	 *     Optional. Other arguments for installing a dropin package. Default empty array.
	 *
	 *     @type bool $clear_update_cache Whether to clear the dropin updates cache if successful.
	 *                                    Default true.
	 * }
	 * @return bool|WP_Error True if the installation was successful, false or a WP_Error otherwise.
	 */
	public function install( $package, $args = array() ) {
		$defaults = array(
			'clear_update_cache' => true,
			'overwrite_package' => false, // Do not overwrite files.
		);
		$parsed_args = wp_parse_args( $args, $defaults );

		$this->init();
		$this->install_strings();

		add_filter( 'upgrader_source_selection', array( $this, 'check_package' ) );

		$this->run(
			array(
				'package' => $package,
				'destination' => MD_INSTALLED_DROPINS . '/' . $parsed_args['dropin'],
				'clear_destination' => $parsed_args['overwrite_package'],
				'clear_working' => true,
				'hook_extra' => array(
					'type' => 'dropin',
					'action' => 'install'
				)
			)
		);

		remove_filter( 'upgrader_source_selection', array( $this, 'check_package' ) );

		if ( ! $this->result || is_wp_error( $this->result ) )
			return $this->result;

		if ( $parsed_args['overwrite_package'] ) {
			do_action( 'upgrader_overwrote_package', $package, $this->new_dropin_data, 'dropin' );
		}

		return true;
	}

	public function dropin_info() {
		if ( ! is_array( $this->result ) ) {
			return false;
		}
		if ( empty( $this->result['destination_name'] ) ) {
			return false;
		}

		// Ensure to pass with leading slash.
		$dropin = md_get_dropins( 'files', $this->result['destination_name'] );

		if ( empty( $dropin ) )
			return false;

		return $this->result['destination_name'] . '/' . $dropin['ID'] . '.php';
	}

	/**
	 * Checks that the source package contains a valid dropin.
	 *
	 * Hooked to the {@see 'upgrader_source_selection'} filter by Plugin_Upgrader::install().
	 *
	 * @since 3.3.0
	 *
	 * @global WP_Filesystem_Base $wp_filesystem WordPress filesystem subclass.
	 * @global string             $wp_version    The WordPress version string.
	 *
	 * @param string $source The path to the downloaded package source.
	 * @return string|WP_Error The source as passed, or a WP_Error object on failure.
	 */
	public function check_package( $source ) {
		global $wp_filesystem, $wp_version;

		$this->new_dropin_data = array();

		if ( is_wp_error( $source ) ) {
			return $source;
		}

		$dropins_dir = $wp_filesystem->wp_content_dir() . 'md-dropins/';
		$working_directory = str_replace( $dropins_dir, trailingslashit( MD_INSTALLED_DROPINS ), $source );
/*
		print_r( $dropins_dir ); echo '<BR><BR>';
		print_r( trailingslashit( MD_INSTALLED_DROPINS ) ); echo '<BR><BR>';
		print_r( $working_directory ); echo '<BR><BR>';
		print_r( $source ); echo '<BR><BR>';
*/
		if ( ! is_dir( $working_directory ) ) { // Sanity check, if the above fails, let's not prevent installation.
			return $source;
		}

		// Check that the folder contains at least 1 valid dropin.
		$files = glob( $working_directory . '*.php' );

		if ( $files ) {
			foreach ( $files as $file ) {
				$info = md_get_dropin_data( $file, false, false );
				if ( ! empty( $info['Name'] ) ) {
					$this->new_dropin_data = $info;
					break;
				}
			}
		}
		
		if ( empty( $this->new_dropin_data ) ) {
			return new WP_Error( 'incompatible_archive_no_dropins', $this->strings['incompatible_archive'], __( 'No valid drop-ins were found.' ) );
		}

		$requires_php = isset( $info['RequiresPHP'] ) ? $info['RequiresPHP'] : null;
		$requires_wp  = isset( $info['RequiresWP'] ) ? $info['RequiresWP'] : null;

		if ( ! is_php_version_compatible( $requires_php ) ) {
			$error = sprintf(
				/* translators: 1: Current PHP version, 2: Version required by the uploaded dropin. */
				__( 'The PHP version on your server is %1$s, however the uploaded dropin requires %2$s.' ),
				phpversion(),
				$requires_php
			);

			return new WP_Error( 'incompatible_php_required_version', $this->strings['incompatible_archive'], $error );
		}

		if ( ! is_wp_version_compatible( $requires_wp ) ) {
			$error = sprintf(
				/* translators: 1: Current WordPress version, 2: Version required by the uploaded dropin. */
				__( 'Your WordPress version is %1$s, however the uploaded drop-in requires %2$s.' ),
				$wp_version,
				$requires_wp
			);

			return new WP_Error( 'incompatible_wp_required_version', $this->strings['incompatible_archive'], $error );
		}

		return $source;
	}

	/**
	 * Turns on maintenance mode before attempting to background update an active dropin.
	 *
	 * Hooked to the {@see 'upgrader_pre_install'} filter by Plugin_Upgrader::upgrade().
	 *
	 * @since 5.4.0
	 *
	 * @param bool|WP_Error $return Upgrade offer return.
	 * @param array         $plugin Plugin package arguments.
	 * @return bool|WP_Error The passed in $return param or WP_Error.
	 */

	public function active_before( $return, $dropin ) {
		if ( is_wp_error( $return ) ) {
			return $return;
		}

		// Only enable maintenance mode when in cron (background update).
		if ( ! wp_doing_cron() ) {
			return $return;
		}

		$dropin = isset( $dropin['dropin'] ) ? $dropin['dropin'] : '';

		// Only run if dropin is active.
		if ( md_is_dropin_active( $dropin ) )
			return $return;

		// Change to maintenance mode. Bulk edit handles this separately.
		if ( ! $this->bulk ) {
			$this->maintenance_mode( true );
		}

		return $return;
	}

	/**
	 * Turns off maintenance mode after upgrading an active dropin.
	 *
	 * Hooked to the {@see 'upgrader_post_install'} filter by Plugin_Upgrader::upgrade().
	 *
	 * @since 5.4.0
	 *
	 * @param bool|WP_Error $return Upgrade offer return.
	 * @param array         $dropin Plugin package arguments.
	 * @return bool|WP_Error The passed in $return param or WP_Error.
	 */
	public function active_after( $return, $dropin ) {
		if ( is_wp_error( $return ) ) {
			return $return;
		}

		// Only disable maintenance mode when in cron (background update).
		if ( ! wp_doing_cron() ) {
			return $return;
		}

		$dropin = isset( $dropin['dropin'] ) ? $dropin['dropin'] : '';

		// Only run if dropin is active
		if ( md_is_dropin_active( $dropin ) )
			return $return;

		// Time to remove maintenance mode. Bulk edit handles this separately.
		if ( ! $this->bulk ) {
			$this->maintenance_mode( false );
		}


		return $return;
	}

	/**
	 * Deactivates a dropin before it is upgraded.
	 *
	 * Hooked to the {@see 'upgrader_pre_install'} filter by Plugin_Upgrader::upgrade().
	 *
	 * @since 2.8.0
	 * @since 4.1.0 Added a return value.
	 *
	 * @param bool|WP_Error $return Upgrade offer return.
	 * @param array         $dropin Dropin package arguments.
	 * @return bool|WP_Error The passed in $return param or WP_Error.
	 */
	public function deactivate_dropin_before_upgrade( $return, $dropin ) {
		if ( is_wp_error( $return ) )
			return $return;

		if ( wp_doing_cron() )
			return $return;

		$dropin = isset( $dropin['dropin'] ) ? $dropin['dropin'] : '';

		if ( empty( $dropin ) ) {
			return new WP_Error( 'bad_request', $this->strings['bad_request'] );
		}

		return $return;
	}

	public function delete_old_dropin( $removed, $local_destination, $remote_destination, $dropin ) {
		global $wp_filesystem;

		if ( is_wp_error( $removed ) ) {
			return $removed; // Pass errors through.
		}

		$dropin = isset( $dropin['dropin'] ) ? $dropin['dropin'] : '';
		if ( empty( $dropin ) ) {
			return new WP_Error( 'bad_request', $this->strings['bad_request'] );
		}

//		$dropins_dir     = $wp_filesystem->wp_plugins_dir(); // <!--------------------------------------------------------
		$dropins_dir = MD_INSTALLED_DROPINS;
		$this_dropin_dir = trailingslashit( dirname( "$dropins_dir/$dropin" ) );

		if ( ! $wp_filesystem->exists( $this_dropin_dir ) ) { // If it's already vanished.
			return $removed;
		}

		// If dropin is in its own directory, recursively delete the directory.
		// Base check on if dropin includes directory separator AND that it's not the root dropin folder.
		if ( strpos( $dropin, '/' ) && $this_dropin_dir !== $dropins_dir ) {
			$deleted = $wp_filesystem->delete( $this_dropin_dir, true );
		} else {
			$deleted = $wp_filesystem->delete( "$dropins_dir/$dropin" );
		}

		if ( ! $deleted ) {
			return new WP_Error( 'remove_old_failed', $this->strings['remove_old_failed'] );
		}

		return true;
	}

}