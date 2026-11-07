<?php

require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
require_once __DIR__ . '/upgrader-skin.php';

/**
 * Install and update Drop-ins with WordPress's upgrader. Use
 * md_install_dropin() and md_upgrade_dropins() rather than this class
 * directly.
 *
 * @since 5.4
 */

class MD_Dropin_Upgrader extends WP_Upgrader {

	public $new_dropin_data = array();
	private $expected_dropin = '';
	private $staged_package = '';
	public $installed_dropin = '';
	public $replaced_dropin = false;

	/**
	 * Set strings for various parts of the Upgrader interface (thanks, Core!)
	 *
	 * @since 5.4
	 */

	public function upgrade_strings() {
		$this->strings['up_to_date'] = __( 'The Drop-in is at the latest version.', 'md' );
		$this->strings['no_package'] = __( 'Update package not available.', 'md' );
		$this->strings['downloading_package'] = sprintf( __( 'Downloading update from %s&#8230;', 'md' ), '<span class="code">%s</span>' );
		$this->strings['unpack_package'] = __( 'Unpacking the update&#8230;', 'md' );
		$this->strings['remove_old'] = __( 'Removing the old version of the Drop-in&#8230;', 'md' );
		$this->strings['remove_old_failed'] = __( 'Could not remove the old Drop-in.', 'md' );
		$this->strings['process_failed'] = __( 'Drop-in update failed.', 'md' );
		$this->strings['process_success'] = __( 'Drop-in updated successfully.', 'md' );
		$this->strings['process_bulk_success'] = __( 'Drop-ins updated successfully.', 'md' );
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
		$this->strings['installing_package'] = __( 'Installing the Drop-in&#8230;', 'md' );
		$this->strings['remove_old'] = __( 'Removing the current Drop-in&#8230;', 'md' );
		$this->strings['remove_old_failed'] = __( 'Could not remove the current Drop-in.', 'md' );
		$this->strings['no_files'] = __( 'The Drop-in contains no files.', 'md' );
		$this->strings['process_failed'] = __( 'Drop-in installation failed.', 'md' );
		$this->strings['process_success'] = __( 'Drop-in installed successfully.', 'md' );
		$this->strings['process_success_specific'] = __( 'Successfully installed the Drop-in <strong>%1$s %2$s</strong>.', 'md' );
	}

	/**
	 * Update several Drop-ins in one run with one skin, with maintenance
	 * mode on while any active Drop-in's files are replaced.
	 *
	 * @since 6.2
	 */

	public function bulk_upgrade( $slugs, $args = array() ) {
		$this->init();
		$this->bulk = true;
		$this->upgrade_strings();

		$current = md_dropin_updates();
		$results = array();

		add_filter( 'upgrader_source_selection', array( $this, 'check_package' ) );

		$this->skin->header();
		$this->skin->done_header = true;

		$connected = $this->fs_connect( array( WP_CONTENT_DIR, MD_INSTALLED_DROPINS ) );

		if ( ! $connected ) {
			$this->skin->footer();
			remove_filter( 'upgrader_source_selection', array( $this, 'check_package' ) );

			return false;
		}

		$this->skin->bulk_header();

		$maintenance = false;

		foreach ( $slugs as $slug )
			if ( md_has( $slug ) && ! empty( $current["$slug/$slug.php"] ) )
				$maintenance = true;

		if ( $maintenance )
			$this->maintenance_mode( true );

		$this->update_count = count( $slugs );
		$this->update_current = 0;

		foreach ( $slugs as $slug ) {
			$this->update_current++;
			$path = "$slug/$slug.php";
			$details = md_get_dropin_details( $slug );

			if ( method_exists( $this->skin, 'set_dropin' ) )
				$this->skin->set_dropin( $slug, $details ? $details['Name'] : $slug );

			if ( ! $details || empty( $current[$path]['package'] ) || ! is_string( $current[$path]['package'] ) ) {
				$this->skin->set_result( 'up_to_date' );
				$this->skin->before();
				$this->skin->feedback( 'up_to_date' );
				$this->skin->after();
				$results[$slug] = true;

				continue;
			}

			$this->expected_dropin = $slug;

			$result = $this->run( array(
				'package' => $current[$path]['package'],
				'destination' => MD_INSTALLED_DROPINS . "/$slug",
				'clear_destination' => true,
				'clear_working' => true,
				'is_multi' => true,
				'hook_extra' => array(
					'dropin' => $path,
					'type' => 'dropin',
					'action' => 'update',
					'temp_backup' => array( 'slug' => $slug, 'src' => MD_INSTALLED_DROPINS, 'dir' => 'dropins' )
				)
			) );

			$results[$slug] = is_array( $result ) ? true : $result;

			if ( false === $result )
				break;
		}

		$this->maintenance_mode( false );

		$this->skin->bulk_footer();
		$this->skin->footer();

		remove_filter( 'upgrader_source_selection', array( $this, 'check_package' ) );

		return $results;
	}

	/**
	 * Install a Drop-in from a local ZIP or a package URL. Without
	 * `overwrite_package`, an installed Drop-in returns a
	 * `md_dropin_exists` error holding both versions.
	 *
	 * @since 5.4
	 */

	public function install( $package, $args = array() ) {
		$parsed_args = wp_parse_args( $args, array( 'overwrite_package' => false ) );

		$this->installed_dropin = $this->expected_dropin = '';
		$this->replaced_dropin = false;

		$this->init();
		$this->install_strings();

		$connected = $this->fs_connect( array( WP_CONTENT_DIR, MD_INSTALLED_DROPINS ) );

		if ( ! $connected || is_wp_error( $connected ) )
			return $connected;

		$downloaded = '';

		if ( preg_match( '#^https?://#i', (string) $package ) ) {
			$downloaded = $this->download_package( $package );

			if ( is_wp_error( $downloaded ) )
				return $downloaded;

			$package = $downloaded;
		}

		$working_directory = parent::unpack_package( $package, false );

		if ( is_wp_error( $working_directory ) ) {
			$this->delete_download( $downloaded );

			return $working_directory;
		}

		global $wp_filesystem;

		$directory = str_replace( trailingslashit( $wp_filesystem->wp_content_dir() ), trailingslashit( WP_CONTENT_DIR ), $working_directory );

		$dropin_slug = $this->discover_package( $directory );

		if ( ! is_wp_error( $dropin_slug ) && ! $parsed_args['overwrite_package'] && is_dir( MD_INSTALLED_DROPINS . "/$dropin_slug" ) )
			$dropin_slug = $this->exists_error( $dropin_slug, $directory );

		if ( is_wp_error( $dropin_slug ) ) {
			$wp_filesystem->delete( $working_directory, true );
			$this->delete_download( $downloaded );

			return $dropin_slug;
		}

		$this->expected_dropin = $dropin_slug;
		$this->installed_dropin = $dropin_slug;
		$this->staged_package = $working_directory;
		$this->replaced_dropin = is_dir( MD_INSTALLED_DROPINS . "/$dropin_slug" );

		add_filter( 'upgrader_source_selection', array( $this, 'check_package' ) );

		$hook_extra = array( 'type' => 'dropin', 'action' => 'install' );

		if ( $this->replaced_dropin )
			$hook_extra['temp_backup'] = array( 'slug' => $dropin_slug, 'src' => MD_INSTALLED_DROPINS, 'dir' => 'dropins' );

		$result = $this->run( array(
			'package' => $package,
			'destination' => MD_INSTALLED_DROPINS . '/' . $dropin_slug,
			'clear_destination' => $parsed_args['overwrite_package'],
			'clear_working' => true,
			'hook_extra' => $hook_extra
		) );

		remove_filter( 'upgrader_source_selection', array( $this, 'check_package' ) );

		if ( $wp_filesystem->exists( $working_directory ) )
			$wp_filesystem->delete( $working_directory, true );

		$this->staged_package = '';
		$this->delete_download( $downloaded );

		if ( ! $result || is_wp_error( $result ) )
			return $result;

		if ( $this->replaced_dropin )
			do_action( 'upgrader_overwrote_package', $package, $this->new_dropin_data, 'dropin' );

		return true;
	}

	/**
	 * The error for an upload of an installed Drop-in, with the current
	 * and uploaded details the replace screen compares.
	 *
	 * @since 6.2
	 */

	private function exists_error( $slug, $directory ) {
		$current = md_get_dropin_details( $slug );
		$uploaded = md_read_dropin_details( trailingslashit( $directory ) . $slug, $slug );
		$keys = array( 'Name', 'Version', 'Author', 'RequiresWP', 'RequiresPHP', 'RequiresMD' );
		$data = array( 'slug' => $slug, 'current' => array(), 'uploaded' => array() );

		foreach ( $keys as $key ) {
			$data['current'][$key] = $current ? $current[$key] : '';
			$data['uploaded'][$key] = $uploaded ? $uploaded[$key] : '';
		}

		return new WP_Error( 'md_dropin_exists', __( 'This Drop-in is already installed.', 'md' ), $data );
	}

	/**
	 * Remove a package downloaded from a URL.
	 *
	 * @since 6.2
	 */

	private function delete_download( $file ) {
		if ( $file && is_file( $file ) )
			wp_delete_file( $file );
	}

	/**
	 * Identify a manual upload by its single top-level Drop-in folder.
	 *
	 * @since 6.0.1
	 *
	 * @param string $directory Local path to the unpacked ZIP contents.
	 * @return string|WP_Error Validated Drop-in slug or a package validation error.
	 */

	private function discover_package( $directory ) {
		$entries = is_dir( $directory ) && ! is_link( $directory ) ? scandir( $directory ) : false;
		$entries = $entries === false ? array() : array_values( array_diff( $entries, array( '.', '..', '__MACOSX' ) ) );

		if ( count( $entries ) !== 1 )
			return new WP_Error( 'invalid_dropin_structure', __( 'The ZIP must contain one Drop-in folder.', 'md' ) );

		$slug = $entries[0];
		$folder = trailingslashit( $directory ) . $slug;

		if ( ! preg_match( '/^[a-z0-9_-]+$/', $slug ) || ! is_dir( $folder ) || is_link( $folder ) )
			return new WP_Error( 'invalid_dropin_structure', __( 'The ZIP must contain one valid Drop-in folder.', 'md' ) );

		$details = md_validate_dropin_package( $folder, $slug );

		return is_wp_error( $details ) ? $details : $slug;
	}

	/**
	 * Reuse the manual upload already unpacked for slug discovery.
	 *
	 * @since 6.0.1
	 */

	public function unpack_package( $package, $delete_package = true ) {
		if ( $this->staged_package ) {
			$directory = $this->staged_package;
			$this->staged_package = '';

			return $directory;
		}

		return parent::unpack_package( $package, $delete_package );
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

}
