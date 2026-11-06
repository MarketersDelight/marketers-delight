<?php
/**
 * The MD license: activating and checking it, and finding theme and
 * Drop-in updates through it. Sites without a key never call out.
 *
 * @since 5.4
 */

class md_license {

	/**
	 * Run license actions sent through AJAX.
	 *
	 * @since 5.2.3
	 */

	public function request() {
		if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'marketers_delight_nonce' ) || ! current_user_can( 'manage_options' ) )
			return;

		$license_key = isset( $_POST['license_key'] ) ? sanitize_text_field( wp_unslash( $_POST['license_key'] ) ) : '';

		if ( isset( $_POST['action_type'] ) ) {
			$action_type = sanitize_key( $_POST['action_type'] );

			if ( in_array( $action_type, array( 'activate-license', 'deactivate-license', 'check-updates' ), true ) ) {
				$license = md_license_setting();
				$check_status = '';

				if ( $action_type === 'activate-license' )
					$license = $this->activate_license( $license_key, $license );
				elseif ( $action_type === 'deactivate-license' )
					$license = $this->deactivate_license( $license );
				elseif ( $action_type === 'check-updates' )
					$license = $this->check_for_updates( $license, $check_status );

				if ( ! is_array( $license ) )
					wp_die();

				md_update_license( $license );

				if ( $action_type === 'activate-license' && ( $license['status'] ?? '' ) === 'valid' ) {
					$updates = $this->check_for_updates( $license );

					if ( is_array( $updates ) ) {
						$license = $updates;
						md_update_license( $license );
					}
				}

				do_action( 'md_license_updater', $license, $check_status );
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
		else {
			$update_data = json_decode( wp_remote_retrieve_body( $response ) );
			if ( ! is_object( $update_data ) )
				$update_data = (object) array( 'failed' => true );
		}

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
	 * Checks the current status of the license key.
	 *
	 * @since 4.7
	 */

	private function check_license( $license ) {
		$license_key = isset( $license['key'] ) ? trim( $license['key'] ) : '';

		if ( ! $license_key ) {
			$license['status'] = 'error';
			$license['last_sync'] = time();

			return $license;
		}

	 	$license_input = $this->license();
	 	$license_data = $this->get_api( array(
			'edd_action' => 'check_license',
			'license' => $license_key,
			'item_id' => $license_input['download_id'],
			'url' => home_url()
		) );

		if ( ! empty( $license_data->failed ) ) {
			$license['status'] = 'failed';
			unset( $license['updates'] );
			unset( $license['dropins'] );
		}
		elseif ( ! empty( $license_data->success ) ) {
			$license['status'] = sanitize_text_field( $license_data->license );
			$license['expire'] = sanitize_text_field( $license_data->expires );
			$license['sites'] = sanitize_text_field( $license_data->site_count );
			$license['limit'] = sanitize_text_field( $license_data->license_limit );
		}
		else {
			$license['status'] = sanitize_key( $license_data->license ?? 'invalid' );
			unset( $license['updates'], $license['dropins'] );
		}

		$license['last_sync'] = time();

		return $license;
	}

	/**
	 * Activates license key and sets license key status to options.
	 *
	 * @since 4.7
	 */

	private function activate_license( $license_key, $license ) {
		$license_key = trim( $license_key );

		if ( ! $license_key ) {
			$license['status'] = 'error';

			return $license;
		}

		$license = array( 'key' => $license_key );
		$license_input = $this->license();
		$license_data = $this->get_api( array(
			'edd_action' => 'activate_license',
			'license' => $license_key,
			'item_id' => $license_input['download_id'],
			'url' => home_url()
		) );

		if ( ! empty( $license_data->failed ) )
			$license['status'] = 'failed';
		elseif ( ! empty( $license_data->success ) ) {
			$license['status'] = sanitize_text_field( $license_data->license );
			$license['expire'] = sanitize_text_field( $license_data->expires );
			$license['sites'] = sanitize_text_field( $license_data->site_count );
			$license['limit'] = sanitize_text_field( $license_data->license_limit );
		}
		else
			$license['status'] = sanitize_key( $license_data->error ?? $license_data->license ?? 'invalid' );

		return $license;
	}

	/**
	 * Deactivate license key, deletes license data, and delete
	 * any updates data.
	 *
	 * @since 4.7
	 */

	private function deactivate_license( $license ) {
		$license_key = trim( $license['key'] ?? '' );
		$status = 'deactivated';

	 	if ( $license_key ) {
		 	$license_data = $this->get_api( array(
				'edd_action' => 'deactivate_license',
				'license' => $license_key,
				'item_id' => $this->license( 'download_id' ),
				'url' => home_url()
			) );

			if ( ! empty( $license_data->failed ) || empty( $license_data->success ) ) {
				$license['status'] = 'failed';
				return $license;
			}

			if ( ! empty( $license_data->license ) )
				$status = sanitize_text_field( $license_data->license );
		}

		$license = array( 'status' => $status );

		delete_site_transient( 'update_themes' );

		return $license;
	}

	/**
	 * Delete theme update data after update.
	 *
	 * @since 4.7
	 */

	public function delete_theme_update( $upgrader, $hook_extra ) {
		$theme_slug = $this->license()['theme_slug'];
		if ( ( $hook_extra['type'] ?? '' ) !== 'theme' || ( $hook_extra['action'] ?? '' ) !== 'update' )
			return;
		$themes = $hook_extra['themes'] ?? array( $hook_extra['theme'] ?? '' );
		if ( ! in_array( $theme_slug, $themes, true ) )
			return;

		$license = md_license_setting();

		unset( $license['updates']['theme'] );

		md_update_license( $license );
	}

	/**
	 * Check for updates to various components that can be updated.
	 * Works based on license key status and returns an organized array
	 * to be saved to site with known updates.
	 *
	 * @since 5.4
	 */

	private function check_for_updates( $license = null, &$check_status = null ) {
		$license = is_array( $license ) ? $license : md_license_setting();
		$check_status = 'failed';
		$license = $this->check_license( $license );
		$license_status = ! empty( $license['status'] ) ? $license['status'] : 'invalid';

		if ( $license_status === 'valid' ) {
			$license_input = $this->license();
			$response = $this->get_api( array(
				'edd_action'  => 'get_version',
				'license' => trim( $license['key'] ),
				'item_id' => $license_input['download_id'],
				'url' => home_url(),
				'slug' => $license_input['theme_slug'],
				'version' => $license_input['version'],
				'author' => $license_input['author'],
				'php_version' => phpversion(),
				'wp_version' => get_bloginfo( 'version' )
			) );

			if ( ! empty( $response->failed ) || empty( $response->new_version ) )
				return $license;

			$check_status = 'success';
			$license['last_check'] = time();

			$update_data = $response;

			unset( $license['updates'], $license['dropins'], $license['available'] );

			if ( ! empty( $update_data->package ) && version_compare( $license_input['version'], $update_data->new_version, '<' ) )
				$license['updates']['theme'] = array(
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

			if ( ! empty( $update_data->dropins ) )
				$license = $this->dropin_updates( $license, $update_data->dropins );
		}
		else unset( $license['updates'] );

		delete_site_transient( 'update_themes' );

		return $license;
	}

	/**
	 * Drop-ins from an update check: each one MD.com lists, Available ones
	 * this site hasn't installed (and installed ones a higher plan covers,
	 * for the upgrade banner), and updates for installed ones.
	 *
	 * @since 6.2
	 */

	private function dropin_updates( $license, $dropins ) {
		md_flush_dropin_details();

		foreach ( $dropins as $fields ) {
			if ( empty( $fields->slug ) )
				continue;

			$slug = sanitize_key( $fields->slug );
			$installed = md_get_dropin_details( $slug );
			$available = $this->available_dropin( $slug, $fields );
			$license['dropins'][] = $slug;

			if ( $available && ( ! $installed || $available['status'] === 'upgrade' ) )
				$license['available'][$slug] = $available;

			if ( ! $installed )
				continue;

			$new_version = ! empty( $fields->version ) ? $fields->version : $installed['Version'];

			if ( ! empty( $fields->package ) && version_compare( $installed['Version'], $new_version, '<' ) )
				$license['updates']['dropins']["$slug/$slug.php"] = array(
					'name' => sanitize_text_field( $fields->name ),
					'slug' => $slug,
					'new_version' => sanitize_text_field( $new_version ),
					'package' => esc_url_raw( $fields->package )
				);
		}

		return $license;
	}

	/**
	 * One Drop-in the license can see but this site hasn't installed:
	 * `claimed` (in the customer's orders, with a package), `included` (the
	 * plan covers it; MD.com claims it on install) or `upgrade` (a higher
	 * plan covers it). Responses from before MD.com sent a status only list
	 * claimed Drop-ins, so a package alone means claimed.
	 *
	 * @since 6.2
	 */

	private function available_dropin( $slug, $fields ) {
		$status = isset( $fields->status ) ? sanitize_key( $fields->status ) : ( empty( $fields->package ) ? '' : 'claimed' );

		if ( ! in_array( $status, array( 'claimed', 'included', 'upgrade' ), true ) )
			return false;

		if ( $status === 'claimed' && empty( $fields->package ) )
			return false;

		return array_filter( array(
			'name' => sanitize_text_field( $fields->name ?? $slug ),
			'slug' => $slug,
			'status' => $status,
			'plan' => sanitize_text_field( $fields->plan ?? '' ),
			'version' => sanitize_text_field( $fields->version ?? '' ),
			'description' => sanitize_text_field( $fields->description ?? '' ),
			'icon' => sanitize_text_field( $fields->icon ?? '' ),
			'colors' => sanitize_text_field( $fields->colors ?? '' ),
			'upgrade_url' => esc_url_raw( $fields->upgrade_url ?? '' ),
			'ready' => isset( $fields->ready ) ? ( $fields->ready ? '1' : '0' ) : '',
			'package' => esc_url_raw( $fields->package ?? '' )
		), 'strlen' );
	}

	/**
	 * Ask MD.com to claim a Drop-in the license's plan includes, and return
	 * its package URL.
	 *
	 * @since 6.2
	 */

	public function claim_dropin( $slug ) {
		$license = md_license_setting();

		if ( ( $license['status'] ?? '' ) !== 'valid' || empty( $license['key'] ) )
			return new WP_Error( 'md_license_invalid', __( 'Activate your license key to install this Drop-in.', 'md' ) );

		$response = $this->get_api( array(
			'edd_action' => 'md_claim_dropin',
			'license' => trim( $license['key'] ),
			'item_id' => $this->license( 'download_id' ),
			'url' => home_url(),
			'slug' => $slug
		) );

		if ( ! empty( $response->failed ) || empty( $response->success ) || empty( $response->package ) )
			return new WP_Error( 'md_claim_failed', ! empty( $response->message ) ? sanitize_text_field( $response->message ) : __( 'Marketers Delight could not add this Drop-in to your account. Please try again.', 'md' ) );

		return esc_url_raw( $response->package );
	}

	/**
	 * Check for updates and save what was found. Returns `success` or
	 * `failed`. Used by cron and md_check_dropin_updates().
	 *
	 * @since 6.2
	 */

	public function check_updates() {
		$check_status = 'failed';
		$license = $this->check_for_updates( md_license_setting(), $check_status );

		if ( is_array( $license ) )
			md_update_license( $license );

		return $check_status;
	}

}
