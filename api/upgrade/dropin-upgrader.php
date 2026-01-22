<?php

require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
require_once 'dropin-upgrader-skin.php';
require_once 'dropin-installer-skin.php';

/**
 * Run installation, upgrade, activate, and other various upgrades
 * related to MD Drop-ins. Note, this system is wokring in tangent with
 * the original MD install/activate Drop-in system and exclusively
 * handles upgrading Drop-ins only. This functionality is planned to expand
 * in future MD releases.
 *
 * @since 5.4
 */

class MD_Dropin_Upgrader extends WP_Upgrader {

	/**
	 * These properties definitely do stuff.
	 */

	public $result;
	public $bulk = false;
	public $new_dropin_data = array();

	/**
	 * Set strings for various parts of the Upgrader interface (thanks, Core!)
	 *
	 * @since 5.4
	 */

	public function upgrade_strings() {
		$this->strings['up_to_date'] = __( 'The dropin is at the latest version.', 'md' );
		$this->strings['no_package'] = __( 'Update package not available.', 'md' );
		$this->strings['downloading_package'] = sprintf( __( 'Downloading update from %s&#8230;', 'md' ), '<span class="code">%s</span>' );
		$this->strings['unpack_package'] = __( 'Unpacking the update&#8230;', 'md' );
		$this->strings['remove_old'] = __( 'Removing the old version of the dropin&#8230;', 'md' );
		$this->strings['remove_old_failed'] = __( 'Could not remove the old dropin.', 'md' );
		$this->strings['process_failed'] = __( 'Drop-in update failed.', 'md' );
		$this->strings['process_success'] = __( 'Drop-in updated successfully.', 'md' );
		$this->strings['process_bulk_success'] = __( 'Drop-in updated successfully.', 'md' );
		$this->strings['skin_before_update_header'] = __( 'Updating Drop-in %1$s (%2$d/%3$d)', 'md' );
	}

	/**
	 * Set strings for various parts of the Upgrader interface (thanks, Core!)
	 *
	 * @since 5.4
	 */

	public function install_strings() {
		$this->strings['no_package'] = __( 'Installation package not available.', 'md' );
		$this->strings['downloading_package'] = sprintf( __( 'Downloading installation package from %s&#8230;', 'md' ), '<span class="code">%s</span>' );
		$this->strings['unpack_package'] = __( 'Unpacking the package&#8230;', 'md' );
		$this->strings['installing_package'] = __( 'Installing the drop-in&#8230;', 'md' );
		$this->strings['remove_old'] = __( 'Removing the current drop-in&#8230;', 'md' );
		$this->strings['remove_old_failed'] = __( 'Could not remove the current drop-in.', 'md' );
		$this->strings['no_files'] = __( 'The drop-in contains no files.', 'md' );
		$this->strings['process_failed'] = __( 'Drop-in installation failed.', 'md' );
		$this->strings['process_success'] = __( 'Drop-in installed successfully.', 'md' );
		$this->strings['process_success_specific'] = __( 'Successfully installed the drop-in <strong>%1$s %2$s</strong>.', 'md' );

		if ( ! empty( $this->skin->overwrite ) ) {
			if ( 'update-dropin' === $this->skin->overwrite ) {
				$this->strings['installing_package'] = __( 'Updating the drop-in&#8230;', 'md' );
				$this->strings['process_failed'] = __( 'Drop-in update failed.', 'md' );
				$this->strings['process_success'] = __( 'Drop-in updated successfully.', 'md' );
			}

			if ( 'downgrade-dropin' === $this->skin->overwrite ) {
				$this->strings['installing_package'] = __( 'Downgrading the drop-in&#8230;', 'md' );
				$this->strings['process_failed'] = __( 'Drop-in downgrade failed.', 'md' );
				$this->strings['process_success'] = __( 'Drop-in downgraded successfully.', 'md' );
			}
		}
	}

	/**
	 * Master upgrade method uses data about new updates from MD settings.
	 *
	 * @since 5.4
	 */

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
	 * The main method for uploading an sending Drop-in files through install.
	 *
	 * @since 5.4
	 */

	public function install( $package, $args = array() ) {
		$defaults = array(
			'clear_update_cache' => true,
			'overwrite_package' => false
		);
		$parsed_args = wp_parse_args( $args, $defaults );

		$this->init();
		$this->install_strings();

		add_filter( 'upgrader_source_selection', array( $this, 'check_package' ) );

		$this->run( array(
			'package' => $package,
			'destination' => MD_INSTALLED_DROPINS . '/' . $parsed_args['dropin'],
			'clear_destination' => $parsed_args['overwrite_package'],
			'clear_working' => true,
			'hook_extra' => array(
				'type' => 'dropin',
				'action' => 'install'
			)
		) );

		remove_filter( 'upgrader_source_selection', array( $this, 'check_package' ) );

		if ( ! $this->result || is_wp_error( $this->result ) )
			return $this->result;

		if ( $parsed_args['overwrite_package'] ) {
			do_action( 'upgrader_overwrote_package', $package, $this->new_dropin_data, 'dropin' );
		}

		return true;
	}

	/**
	 * Returns Drop-in file path.
	 *
	 * @since 5.4
	 */

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
	 * Make sure the Drop-in package contains valid file format.
	 *
	 * @since 5.4
	 */

	public function check_package( $source ) {
		global $wp_filesystem, $wp_version;

		$this->new_dropin_data = array();

		if ( is_wp_error( $source ) )
			return $source;

		$dropins_dir = $wp_filesystem->wp_content_dir() . 'md-dropins/';
		$working_directory = str_replace( $dropins_dir, trailingslashit( MD_INSTALLED_DROPINS ), $source );

		if ( ! is_dir( $working_directory ) )
			return $source;

		$files = glob( $working_directory . '*.php' );

		if ( $files )
			foreach ( $files as $file ) {
				$info = md_get_dropin_data( $file, false, false );
				if ( ! empty( $info['Name'] ) ) {
					$this->new_dropin_data = $info;
					break;
				}
			}

		if ( empty( $this->new_dropin_data ) )
			return new WP_Error( 'incompatible_archive_no_dropins', $this->strings['incompatible_archive'], __( 'No valid drop-ins were found.' ) );

		$requires_php = isset( $info['RequiresPHP'] ) ? $info['RequiresPHP'] : null;
		$requires_wp  = isset( $info['RequiresWP'] ) ? $info['RequiresWP'] : null;

		if ( ! is_php_version_compatible( $requires_php ) ) {
			$error = sprintf( __( 'The PHP version on your server is %1$s, however the uploaded dropin requires %2$s.' ), phpversion(), $requires_php );
			return new WP_Error( 'incompatible_php_required_version', $this->strings['incompatible_archive'], $error );
		}

		if ( ! is_wp_version_compatible( $requires_wp ) ) {
			$error = sprintf( __( 'Your WordPress version is %1$s, however the uploaded drop-in requires %2$s.' ), $wp_version, $requires_wp );
			return new WP_Error( 'incompatible_wp_required_version', $this->strings['incompatible_archive'], $error );
		}

		return $source;
	}

	/**
	 * Runs before Drop-in activation, specifically to enable maintenace
	 * mode on Drop-in upgrade.
	 *
	 * @since 5.4
	 */

	public function active_before( $return, $dropin ) {
		if ( is_wp_error( $return ) )
			return $return;

		if ( ! wp_doing_cron() )
			return $return;

		$dropin = isset( $dropin['dropin'] ) ? $dropin['dropin'] : '';

		if ( md_is_dropin_active( $dropin ) )
			return $return;

		if ( ! $this->bulk )
			$this->maintenance_mode( true );

		return $return;
	}

	/**
	 * Runs after Drop-in activation, specifically to disable maintenace
	 * mode on Drop-in upgrade.
	 *
	 * @since 5.4
	 */

	public function active_after( $return, $dropin ) {
		if ( is_wp_error( $return ) )
			return $return;

		if ( ! wp_doing_cron() )
			return $return;

		$dropin = isset( $dropin['dropin'] ) ? $dropin['dropin'] : '';

		if ( md_is_dropin_active( $dropin ) )
			return $return;

		if ( ! $this->bulk )
			$this->maintenance_mode( false );

		return $return;
	}

	/**
	 * Deactivates a dropin before it is upgraded.
	 *
	 * @since 5.4
	 */

	public function deactivate_dropin_before_upgrade( $return, $dropin ) {
		if ( is_wp_error( $return ) )
			return $return;

		if ( wp_doing_cron() )
			return $return;

		$dropin = isset( $dropin['dropin'] ) ? $dropin['dropin'] : '';

		if ( empty( $dropin ) )
			return new WP_Error( 'bad_request', $this->strings['bad_request'] );

		return $return;
	}

	/**
	 * Delete old dropin after success.
	 *
	 * @since 5.4
	 */

	public function delete_old_dropin( $removed, $local_destination, $remote_destination, $dropin ) {
		global $wp_filesystem;

		if ( is_wp_error( $removed ) )
			return $removed;

		$dropin = isset( $dropin['dropin'] ) ? $dropin['dropin'] : '';

		if ( empty( $dropin ) )
			return new WP_Error( 'bad_request', $this->strings['bad_request'] );

		$dropins_dir = MD_INSTALLED_DROPINS;
		$this_dropin_dir = trailingslashit( dirname( "$dropins_dir/$dropin" ) );

		if ( ! $wp_filesystem->exists( $this_dropin_dir ) )
			return $removed;

		if ( strpos( $dropin, '/' ) && $this_dropin_dir !== $dropins_dir )
			$deleted = $wp_filesystem->delete( $this_dropin_dir, true );
		else
			$deleted = $wp_filesystem->delete( "$dropins_dir/$dropin" );

		if ( ! $deleted )
			return new WP_Error( 'remove_old_failed', $this->strings['remove_old_failed'] );

		return true;
	}

}
