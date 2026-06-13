<?php
/**
 * A collection of requests to update MD theme, drop-ins, and
 * other upgrader actions.
 *
 * @since 5.4
 */

class md_requests {

	/**
	 * Run various MD actions sent through AJAX.
	 *
	 * @since 5.2.3
	 */

	public function request() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'marketers_delight_nonce' ) || ! current_user_can( 'manage_options' ) )
			return;

		$option = md_setting();
		$item_id = isset( $_POST['dropin_id'] ) ? sanitize_key( $_POST['dropin_id'] ) : '';

		if ( isset( $_POST['action_type'] ) ) {
			$action_type = esc_attr( $_POST['action_type'] );

			if ( $action_type == 'delete-dropin' )
				$this->delete_dropin();
			elseif ( $action_type == 'reset-icons' ) {
				$option = $this->reset_icons( $option );
				update_option( 'marketers_delight', $option );
			}
			elseif ( in_array( $action_type, array( 'activate-license', 'deactivate-license', 'check-updates' ) ) ) {
				if ( $action_type == 'activate-license' )
					$option = $this->activate_license( $item_id, $option );
				elseif ( $action_type == 'deactivate-license' )
					$option = $this->deactivate_license( $item_id, $option );
				elseif ( $action_type == 'check-updates' )
					$option = $this->check_for_updates( $option );

				update_option( 'marketers_delight', $option );

				$dashboard = new md_settings;
				$dashboard->updater( $option );
			}
		}

		wp_die();
	}

	/**
	 * Makes a call to the MD.com API for details about license
	 * and any available downloads.
	 *
	 * @since 4.7
	 */

	private function get_api( $api_params ) {
		$update_data = new stdClass;
	 	$license_input = $this->license();
		$response = wp_remote_post( $license_input['remote_api_url'], array(
			'timeout' => 15,
			'sslverify' => (bool) apply_filters( 'edd_sl_api_request_verify_ssl', true ),
			'body' => $api_params
		) );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) )
			$update_data->failed = true;
		else
			$update_data = json_decode( wp_remote_retrieve_body( $response ) );

		return $update_data;
	}

	/**
	 * Organize known theme license information for API calls.
	 *
	 * @since 5.4
	 */

	public function license( $field = null ) {
		$license = array(
			'download_id' => 63289,
			'theme_slug' => 'marketers-delight',
			'remote_api_url' => MD_THEME_UPDATER_URL,
			'version' => MD_VERSION,
			'item_name' => MD_THEME_NAME,
			'author' => MD_THEME_AUTHOR
		);

		if ( empty( $license['version'] ) ) {
			$theme = wp_get_theme( $license['theme_slug'] );
			$license['version'] = $theme->get( 'Version' );
		}

		if ( isset( $field ) )
			$license = $license[$field];

		return $license;
	}

	/**
	 * Reset custom font icons.
	 *
	 * @since 5.4
	 */

	private function reset_icons( $option ) {
		$option['icons'] = $option['custom_icons'] = array();

		return $option;
	}

	/**
	 * Checks the current status of the license key.
	 *
	 * @since 4.7
	 */

	private function check_license( $option ) {
	 	$license_input = $this->license();
	 	$license_data = $this->get_api( array(
			'edd_action' => 'check_license',
			'license' => trim( $option['settings']['license_key'] ),
			'item_name' => urlencode( $license_input['item_name'] ),
			'url' => home_url()
		) );

		if ( ! empty( $license_data->error ) ) {
			$option['license']['status'] = 'error';
			unset( $option['license']['updates'] );
			unset( $option['license']['dropins'] );
		}
		elseif ( ! empty( $license_data->success ) ) {
			$option['license']['status'] = $license_data->license;
			$option['license']['expire'] = $license_data->expires;
			$option['license']['sites'] = $license_data->site_count;
			$option['license']['limit'] = $license_data->license_limit;
		}

		$option['license']['last_sync'] = time();

		return $option;
	}

	/**
	 * Activates license key and sets license key status to options.
	 *
	 * @since 4.7
	 */

	private function activate_license( $license_key, $option ) {
	 	$license = md_setting( array( 'settings', 'license_key' ), $license_key );

	 	if ( ! empty( $license ) ) {
			$option['license'] = array();
			$license_input = $this->license();
			$license_data = $this->get_api( array(
				'edd_action' => 'activate_license',
				'license' => trim( $license ),
				'item_name' => urlencode( $license_input['item_name'] )
			) );

			if ( ! empty( $license_data->error ) ) {
				$option['license'] = 'error';
				unset( $option['license']['updates'] );
				unset( $option['license']['dropins'] );
			}
			elseif ( ! empty( $license_data->success ) ) {
				$option['license']['status'] = esc_attr( $license_data->license );
				$option['license']['expire'] = esc_attr( $license_data->expires );
				$option['license']['sites'] = esc_attr( $license_data->site_count );
				$option['license']['limit'] = esc_attr( $license_data->license_limit );
				$option['settings']['license_key'] = esc_attr( $license );
			}
		}
		else $option['license']['status'] = 'error';

		return $option;
	}

	/**
	 * Deactivate license key, deletes license data, and delete
	 * any updates data.
	 *
	 * @since 4.7
	 */

	private function deactivate_license( $license_key, $option ) {
	 	$license = md_setting( array( 'settings', 'license_key' ), $license_key );

	 	if ( ! empty( $license ) ) {
		 	$license_data = $this->get_api( array(
				'edd_action' => 'deactivate_license',
				'license' => trim( $license ),
				'item_name' => urlencode( $this->license( 'item_name' ) )
			) );

			if ( ! empty( $license_data->failed ) )
				return false;
		}

		unset( $option['settings']['license_key'] );
		unset( $option['license']['sites'] );
		unset( $option['license']['expire'] );
		unset( $option['license']['limit'] );
		unset( $option['license']['updates'] );
		unset( $option['license']['dropins'] );

		$option['license']['status'] = esc_attr( $license_data->license );

		delete_site_transient( 'update_themes' );
//		delete_site_transient( 'update_md_dropins' );

		return $option;
	}

	/**
	 * Set update data for theme, simply adds new theme details
	 * to WP update_themes transient.
	 *
	 * @since 4.7
	 */

	public function set_theme_update( $transient ) {
		$updates = md_setting( array( 'license', 'updates' ) );
		$license_input = $this->license();
		$theme_slug = $license_input['theme_slug'];

		if ( ! empty( $updates['theme'] ) )
			if ( version_compare( $license_input['version'], $updates['theme']['new_version'], '<' ) )
				$transient->response[$theme_slug] = $updates['theme'];
			else
				$transient->no_update[$theme_slug] = $updates['theme'];

		return $transient;
	}

	/**
	 * Delete theme update data after update.
	 *
	 * @since 4.7
	 */

	public function delete_theme_update() {
		$option = md_setting();

		unset( $option['license']['updates']['theme'] );

		update_option( 'marketers_delight', $option );
	}

	/**
	 * Check for updates to various components that can be updated.
	 * Works based on license key status and returns an organized array
	 * to be saved to site with known updates.
	 *
	 * @since 5.4
	 */

	private function check_for_updates( $option = null ) {
		if ( empty( $option ) )
			$option = md_setting();

		$option = $this->check_license( $option );
		$license_status = ! empty( $option['license']['status'] ) ? $option['license']['status'] : 'invalid';

		if ( $license_status == 'valid' ) {
			$license_input = $this->license();
			$response = $this->get_api( array(
				'edd_action'  => 'get_version',
				'license' => trim( md_setting( array( 'settings', 'license_key' ) ) ),
				'name' => $license_input['item_name'],
				'slug' => $license_input['theme_slug'],
				'version' => $license_input['version'],
				'author' => $license_input['author'],
				'php_version' => phpversion(),
				'wp_version' => get_bloginfo( 'version' )
			) );

			if ( ! empty( $response->failed ) )
				return false;

			$update_data = $response;

			unset( $option['license']['updates'] );
			unset( $option['license']['dropins'] );

			// Has update

			if ( ! empty( $update_data ) ) {

				// Theme

				if ( version_compare( $license_input['version'], $update_data->new_version, '<' ) )
					$option['license']['updates']['theme'] = array(
						'name' => sanitize_text_field( $update_data->name ),
						'theme' => sanitize_key( $license_input['theme_slug'] ),
						'new_version' => sanitize_text_field( $update_data->new_version ),
						'stable_version' => sanitize_text_field( $update_data->stable_version ),
						'slug' => sanitize_key( $update_data->slug ),
						'url' => esc_url_raw( $update_data->url ),
						'last_updated' => sanitize_text_field( $update_data->last_updated ),
						'package' => esc_url_raw( $update_data->package ),
						'download_link'  => esc_url_raw( $update_data->download_link )
					);

				// drop-ins
				if ( ! empty( $update_data->dropins ) ) {
					$installed_dropins = md_setting( array( 'dropins', 'installed' ) );

					foreach ( $update_data->dropins as $dropin_id => $dropin_fields ) {
						if ( ! isset( $dropin_fields->slug ) || empty( $installed_dropins[$dropin_fields->slug] ) )
							continue;

						$dropin_slug = sanitize_key( $dropin_fields->slug );
						$dropin_version = ! empty( $installed_dropins[$dropin_slug]['version'] ) ? sanitize_text_field( $installed_dropins[$dropin_slug]['version'] ) : '';
						$new_version = ! empty( $dropin_fields->version ) ? $dropin_fields->version : $dropin_version;
						$option['license']['dropins'][] = $dropin_slug;

						if ( version_compare( $dropin_version, $new_version, '<' ) )
							$option['license']['updates']['dropins']["$dropin_slug/$dropin_slug.php"] = array(
								'name' => sanitize_text_field( $dropin_fields->name ),
								'slug' => $dropin_slug,
								'version' => sanitize_text_field( $new_version ),
								'new_version' => sanitize_text_field( $new_version ),
								'package' => esc_url_raw( $dropin_fields->package )
							);
					}
				}

			}

		}
		else unset( $option['license']['updates'] );

		delete_site_transient( 'update_themes' );
//		delete_site_transient( 'update_md_dropins' );

		return $option;
	}

	/**
	 * Processes uploaded drop-in file when installed through <form>.
	 *
	 * @since 5.4
	 */

	public function upload_dropin() {
		if ( ! current_user_can( 'manage_options' ) )
			wp_die( __( 'Sorry, you are not allowed to install drop-ins on this site.' ) );

		check_admin_referer( 'dropin-upload' );

		$title = __( 'Upload Drop-in', 'md' );
		$parent_file = 'plugins.php';
		$submenu_file = 'plugin-install.php';
		$file_upload = new File_Upload_Upgrader( 'dropinzip', 'package' );

		require_once ABSPATH . 'wp-admin/admin-header.php';

		$filename = esc_html( basename( $file_upload->filename ) );
		$title = sprintf( __( 'Installing drop-in from uploaded file: %s', 'md' ), $filename );
		$nonce = 'dropin-upload';
		$url = add_query_arg( array( 'package' => $file_upload->id ), 'update.php?action=upload-md-dropin' );
		$type = 'upload';
		$overwrite = isset( $_GET['overwrite'] ) ? sanitize_text_field( $_GET['overwrite'] ) : '';
		$overwrite = in_array( $overwrite, array( 'update-dropin', 'downgrade-dropin' ), true ) ? $overwrite : '';

		$upgrader = new MD_Dropin_Upgrader( new Dropin_Installer_Skin( compact( 'type', 'title', 'nonce', 'url', 'overwrite' ) ) );
		$result = $upgrader->install( $file_upload->package, array(
			'overwrite_package' => $overwrite,
			'dropin' => str_replace( '.zip', '', $filename )
		) );

		if ( $result || is_wp_error( $result ) )
			$file_upload->cleanup();

		require_once ABSPATH . 'wp-admin/admin-footer.php';
	}

	/**
	 * Called during a single Drop-in upgrade initialization.
	 *
	 * @since 5.4
	 */

	public function update_dropin() {
		if ( ! current_user_can( 'manage_options' ) )
			wp_die( __( 'Sorry, you are not allowed to update drop-ins for this website.', 'md' ) );

		if ( empty( $_GET['dropin'] ) )
			wp_die( __( 'Please select a dropin to update.', 'md' ) );

		$dropin = esc_attr( $_GET['dropin'] );

		check_admin_referer( 'upgrade-dropin_' . $dropin );

		$title = __( 'Update Drop-in' );
		$parent_file = 'plugins.php';
		$submenu_file = 'plugins.php';

		wp_enqueue_script( 'updates' );

		require_once ABSPATH . 'wp-admin/admin-header.php';

		$nonce = 'update-dropin_' . $dropin;
		$url = 'update.php?action=update-md-dropins&dropin=' . urlencode( $dropin );

		$upgrader = new MD_Dropin_Upgrader( new Dropin_Upgrader_Skin( compact( 'title', 'nonce', 'url', 'dropin' ) ) );
		$upgrader->upgrade( $dropin );

		require_once ABSPATH . 'wp-admin/admin-footer.php';
	}

	/**
	 * Cancel the drop-in upload process if user chooses to bail.
	 *
	 * @since 5.4
	 */

	public function cancel_dropin_overwrite() {
		if ( ! current_user_can( 'upload_plugins' ) )
			wp_die( __( 'Sorry, you are not allowed to install drop-ins on this site.' ) );

		check_admin_referer( 'dropin-upload-cancel-overwrite' );

		// Make sure the attachment still exists, or File_Upload_Upgrader will call wp_die()
		// that shows a generic "Please select a file" error.
		if ( ! empty( $_GET['package'] ) ) {
			$attachment_id = (int) $_GET['package'];

			if ( get_post( $attachment_id ) ) {
				$file_upload = new File_Upload_Upgrader( 'dropinzip', 'package' );
				$file_upload->cleanup();
			}
		}

		wp_redirect( self_admin_url( 'admin.php?page=md_dropins' ) );
		exit;
	}

	/**
	 * Run delete Drop-in file actions.
	 *
	 * @since 5.4
	 */

	private function delete_dropin() {
		$files = new md_files;
		$files->file_action( array( 'action' => 'delete-dropin' ) );
	}

	/**
	 * BULK Drop-ins updater return value.
	 *
	 * @since 5.4
	 */
/*
	private function dropins_updater() {
		if ( ! current_user_can( 'update_plugins' ) )
			wp_die( __( 'Sorry, you are not allowed to update plugins for this site.' ) );

	//	check_admin_referer( 'bulk-update-plugins' );

		if ( isset( $_GET['plugins'] ) ) {
			$plugins = explode( ',', stripslashes( $_GET['plugins'] ) );
		} elseif ( isset( $_POST['checked'] ) ) {
			$plugins = (array) $_POST['checked'];
		} else {
			$plugins = array();
		}

		$plugins = array_map( 'urldecode', $plugins );

		$url   = 'update.php?action=update-md-dropins&amp;plugins=' . urlencode( implode( ',', $plugins ) );
		$nonce = 'bulk-update-md-dropins';

		wp_enqueue_script( 'updates' );

		iframe_header();

		$upgrader = new MD_Dropin_Upgrader( new Bulk_Plugin_Upgrader_Skin( compact( 'nonce', 'url' ) ) );
		$upgrader->bulk_upgrade( $plugins );

		iframe_footer();
	}
*/
}