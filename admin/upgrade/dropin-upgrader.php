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
	private $expected_dropin = '';

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
		$dropin_slug = str_replace( '.php', '', basename( $dropin ) );

		if ( $dropin !== "$dropin_slug/$dropin_slug.php" || ! preg_match( '/^[a-z0-9_-]+$/', $dropin_slug ) )
			return new WP_Error( 'invalid_dropin', __( 'Invalid Drop-in path.', 'md' ) );

		$this->expected_dropin = $dropin_slug;

		$defaults = array(
			'clear_update_cache' => true,
		);
		$parsed_args = wp_parse_args( $args, $defaults );

		$this->init();
		$this->upgrade_strings();

		$current = md_license_setting( array( 'updates', 'dropins' ) );

		if ( empty( $current[$dropin] ) ) {
			$this->skin->before();
			$this->skin->set_result( false );
			$this->skin->error( 'up_to_date' );
			$this->skin->after();

			return false;
		}

		$r = $current[$dropin];

		if ( empty( $r['package'] ) || ! is_string( $r['package'] ) )
			return new WP_Error( 'no_package', $this->strings['no_package'] );

		add_filter( 'upgrader_pre_install', array( $this, 'deactivate_dropin_before_upgrade' ), 10, 2 );
		add_filter( 'upgrader_pre_install', array( $this, 'active_before' ), 10, 2 );
		add_filter( 'upgrader_source_selection', array( $this, 'check_package' ) );
		add_filter( 'upgrader_post_install', array( $this, 'active_after' ), 10, 2 );

		$result = $this->run( array(
			'package' => $r['package'],
			'destination' => MD_INSTALLED_DROPINS . "/$dropin_slug",
			'clear_destination' => true,
			'clear_working' => true,
			'hook_extra' => array(
				'dropin' => $dropin,
				'type' => 'dropin',
				'action' => 'update',
				'temp_backup' => array( 'slug' => $dropin_slug, 'src' => MD_INSTALLED_DROPINS, 'dir' => 'dropins' )
			)
		) );

		// Cleanup our hooks, in case something else does a upgrade on this connection.

		remove_filter( 'upgrader_pre_install', array( $this, 'deactivate_dropin_before_upgrade' ) );
		remove_filter( 'upgrader_pre_install', array( $this, 'active_before' ) );
		remove_filter( 'upgrader_source_selection', array( $this, 'check_package' ) );
		remove_filter( 'upgrader_post_install', array( $this, 'active_after' ) );

		if ( ! $result || is_wp_error( $result ) )
			return $result;

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
		$dropin_slug = $parsed_args['dropin'] ?? '';

		if ( ! is_string( $dropin_slug ) || ! preg_match( '/^[a-z0-9_-]+$/', $dropin_slug ) )
			return new WP_Error( 'invalid_dropin', __( 'Invalid Drop-in package name.', 'md' ) );

		$this->expected_dropin = $dropin_slug;

		$this->init();
		$this->install_strings();

		add_filter( 'upgrader_source_selection', array( $this, 'check_package' ) );

		$hook_extra = array( 'type' => 'dropin', 'action' => 'install' );

		if ( $parsed_args['overwrite_package'] && is_dir( MD_INSTALLED_DROPINS . "/$dropin_slug" ) )
			$hook_extra['temp_backup'] = array( 'slug' => $dropin_slug, 'src' => MD_INSTALLED_DROPINS, 'dir' => 'dropins' );

		$result = $this->run( array(
			'package' => $package,
			'destination' => MD_INSTALLED_DROPINS . '/' . $dropin_slug,
			'clear_destination' => $parsed_args['overwrite_package'],
			'clear_working' => true,
			'hook_extra' => $hook_extra
		) );

		remove_filter( 'upgrader_source_selection', array( $this, 'check_package' ) );

		if ( ! $result || is_wp_error( $result ) )
			return $result;

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
		global $wp_filesystem;

		$this->new_dropin_data = array();

		if ( is_wp_error( $source ) )
			return $source;

		$content_dir = trailingslashit( $wp_filesystem->wp_content_dir() );
		$working_directory = str_replace( $content_dir, trailingslashit( WP_CONTENT_DIR ), $source );

		if ( $working_directory === $source && strpos( $source, trailingslashit( WP_CONTENT_DIR ) ) !== 0 )
			return new WP_Error( 'invalid_dropin_source', __( 'Could not inspect the Drop-in package.', 'md' ) );

		$data = md_validate_dropin_package( $working_directory, $this->expected_dropin );

		if ( is_wp_error( $data ) )
			return $data;

		$this->new_dropin_data = $data;

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

		if ( ! md_is_dropin_active( $dropin ) )
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

		if ( ! md_is_dropin_active( $dropin ) )
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

}
