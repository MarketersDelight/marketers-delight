<?php
/**
 * This class powered the MD + EDD licensing system. Adds
 * license field to MD settings, connects to EDD/MD to pull
 * license data on activation/deactivation. Also runs and checks
 * the user's connected license with a transient to pull most recent data.
 *
 * @since 4.7
 */

class md_license extends md_api {

	public $remote_api_url;
	public $theme_slug;
	public $version;
	public $item_name;
	public $author;
	public $download_id;
	public $renew_url;

	/**
	 * Configure updater, assign properties, fire hooks, and
	 * start the Updater engine into the MDAPI.
	 *
	 * @since 4.7
	 */

	public function construct() {
		global $pagenow;
		$this->suite = $this->_id = 'md_settings';
		$config = array(
			'remote_api_url' => MD_THEME_UPDATER_URL,
			'theme_slug' => 'marketers-delight',
			'version' => MD_VERSION,
			'item_name' => MD_THEME_NAME,
			'author' => MD_THEME_AUTHOR,
			'download_id' => 63289,
			'renew_url' => '',
			'license' => ''
		);

		add_action( 'init', array( $this, 'after_update' ) );
		add_action( 'md_hook_settings_col2_top', array( $this, 'license' ) );
		add_filter( 'http_request_args', array( $this, 'disable_wp_requests' ), 5, 2 );

		$this->remote_api_url = $config['remote_api_url'];
		$this->theme_slug = sanitize_key( $config['theme_slug'] );
		$this->version = $config['version'];
		$this->item_name = $config['item_name'];
		$this->author = $config['author'];
		$this->download_id = $config['download_id'];
		$this->renew_url = $config['renew_url'];

		// Populate version fallback
		if ( '' == $config['version'] ) {
			$theme = wp_get_theme( $this->theme_slug );
			$this->version = $theme->get( 'Version' );
		}
		add_action( 'admin_init', array( $this, 'actions' ) );
		add_action( 'admin_init', array( $this, 'updater' ) );
	}

	/**
	 * Run after upgrade processes.
	 *
	 * @sincd 4.8.1
	 */

	public function after_update() {
		$version = md_setting( array( 'version' ) );

		if ( empty( $version ) || $version < MD_VERSION ) {
			include_once( 'versions.php' );

			if ( $version < '4.9' )
				marketers_delight_49();
			elseif ( $version < '4.9.6' )
				marketers_delight_496();
			elseif ( $version < '5.1' )
				marketers_delight_51();
			elseif ( $version < '5.2.1' )
				marketers_delight_521();
			elseif ( $version < '5.3' )
				marketers_delight_53();

			if ( $version >= '5.0' ) {
				$option = md_setting();
				$option['version'] = MD_VERSION;
				if ( file_exists( MD_DROPINS ) )
					$option['move_dropins'] = true;
				update_option( 'marketers_delight', $option );
			}

			md_compile_css();
		}
	}

	/**
	 * Run EDD Updater class.
	 *
	 * since 4.5
	 */

	public function updater() {
		if ( ! current_user_can( 'manage_options' ) )
			return;

		if ( md_setting( array( 'license', 'status' ) ) != 'valid' )
			return;

		if ( ! class_exists( 'EDD_Theme_Updater' ) )
			include( 'updater-class.php' );

		new EDD_Theme_Updater(
			array(
				'remote_api_url' => $this->remote_api_url,
				'version' => $this->version,
				'license' => trim( md_setting( array( 'settings', 'license_key' ) ) ),
				'item_name' => $this->item_name,
				'author' => $this->author
			),
			array(
				'update-notice' => esc_js( __( "Updating MD will lose any customizations you\'ve made to the core files. Be sure to backup any changes to a Child Theme before updating. 'Cancel' to stop, 'OK' to update.", 'md' ) ),
				'update-available' => '<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>'
			)
		 );
	}

	/**
	 * Checks if a license action was submitted.
	 *
	 * @since 4.7
	 */

	public function actions() {
		if ( md_setting( 'version' ) < MD_VERSION )
			return;
			
		$check = get_transient( 'md_license' );
		$license = md_setting( array( 'license', 'status' ) );

		if ( ! empty( $_POST['md_license_activate'] ) )
			$this->activate_license();
		elseif ( ! empty( $_POST['md_license_deactivate'] ) )
			$this->deactivate_license();

		if ( empty( $check ) && $license != 'deactivated' && $license != 'expired' )
			$this->check_license();
	}

	/**
	 * Makes a call to the EDD API.
	 *
	 * @since 4.7
	 */

	public function get_api_response( $api_params ) {
		$response = wp_remote_post( $this->remote_api_url, array(
			'timeout' => 15,
			'sslverify' => (bool) apply_filters( 'edd_sl_api_request_verify_ssl', true ),
			'body' => $api_params
		) );

		if ( is_wp_error( $response ) )
			wp_die( $response->get_error_message(), __( 'Error' ) . $response->get_error_code() );

		return $response;
	}

	/**
	 * Check license status when called.
	 *
	 * @since 4.7
	 */

	public function check_license() {
	 	$option = md_setting();
	 	$response = $this->get_api_response( array(
			'edd_action' => 'check_license',
			'license' => trim( md_setting( array( 'settings', 'license_key' ) ) ),
			'item_name' => urlencode( $this->item_name ),
			'url' => home_url()
		) );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) )
			$option['license']['status'] = 'error';
		else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( ! empty( $license_data->success ) ) {
				$option['license']['status'] = $license_data->license;
				$option['license']['expire'] = $license_data->expires;
				$option['license']['sites'] = $license_data->site_count;
				$option['license']['limit'] = $license_data->license_limit;
			}
		}

		update_option( 'marketers_delight', $option );
		set_transient( 'md_license', true, DAY_IN_SECONDS );
	}

	/**
	 * Activates license key.
	 *
	 * @since 4.7
	 */

	 public function activate_license() {
	 	$option = md_setting();
	 	$response = $this->get_api_response( array(
			'edd_action' => 'activate_license',
			'license' => trim( md_setting( array( 'settings', 'license_key' ) ) ),
			'item_name' => urlencode( $this->item_name )
		) );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) )
			$option['license']['status'] = 'error';
		else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( ! empty( $license_data->success ) ) {
				$option['license']['status'] = $license_data->license;
				$option['license']['expire'] = $license_data->expires;
				$option['license']['sites']  = $license_data->site_count;
				$option['license']['limit']  = $license_data->license_limit;
			}
			elseif ( $license_data->success === false )
				$option['license']['status'] = $license_data->error;
		}

		update_option( 'marketers_delight', $option );
	}

	/**
	 * Activates license key.
	 *
	 * @since 4.7
	 */

	 public function deactivate_license() {
	 	$option = md_setting();
	 	$response = $this->get_api_response( array(
			'edd_action' => 'deactivate_license',
			'license'    => trim( md_setting( array( 'settings', 'license_key' ) ) ),
			'item_name'  => urlencode( $this->item_name )
		) );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) )
			$option['license']['status'] = 'error';
		else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( ! empty( $license_data->license ) ) {
				$option['license']['status'] = $license_data->license;
				unset( $option['license']['sites'] );
				unset( $option['license']['expire'] );
				unset( $option['license']['limit'] );
			}
		}

		update_option( 'marketers_delight', $option );
	}

	/**
	 * License meta box.
	 *
	 * @since 4.8.4
	 */

	public function license() {
		$license = trim( md_setting( array( 'settings', 'license_key' ) ) );
		$status = md_setting( array( 'license', 'status' ) );
		$message = $this->message();
		include( MD_DIR . 'lib/admin/settings/dashboard/templates/license-key.php' );
	}

	/**
	 * Add license message depending on status.
	 *
	 * @since 4.7
	 */

	public function message() {
		$message = array();
		$status = md_setting( array( 'license', 'status' ) );

		$message['status'] = __( 'Inactive', 'md' );

		if ( $status == 'valid' ) {
			$expire = md_setting( array( 'license', 'expire' ) );
			$sites = md_setting( array( 'license', 'sites' ) );
			$limit = md_setting( array( 'license', 'limit' ) );
			if ( $expire == 'lifetime' )
				$expires = '<b>Lifetime</b>';
			else
				$expires = date_i18n( get_option( 'date_format' ), strtotime( $expire, current_time( 'timestamp' ) ) );
			$message['status'] = __( 'Active', 'md' );
			$message['text']   = "Expires: <b>$expires</b> &nbsp;&middot;&nbsp; Active sites: <b>{$sites}/{$limit}</b>";
		}

		if ( empty( $status ) || $status == 'missing' || $status == 'deactivated' )
			$message['text'] = __( 'Please activate your valid <b>MD license key</b> to enable new updates sent to your site.' );

		if ( $status == 'failed' )
			$message['text'] = __( 'Something went wrong. Please try to connect your license key again.', 'md' );

		if ( $status == 'disabled' || $status == 'revoked' ) {
			$message['status'] = __( 'Disabled', 'md' );
			$message['text'] = __( 'Your license key has been disabled. Please contact MD support for more information.', 'md' );
		}

		if ( $status == 'no_activations_left' )
			$message['text'] = sprintf( __( 'You\'ve reached your license activation limit. Please purchase more sites or disable other sites from your <a href="%s" target="_blank">MD account</a>.', 'md' ), 'https://marketersdelight.com/downloads/' );

		if ( $status == 'expired' ) {
			$license = trim( md_setting( array( 'settings', 'license_key' ) ) );
			$url = esc_url( $this->remote_api_url ) . '/checkout/?edd_license_key=' . $license . '&download_id=' . $this->download_id;
			$message['status'] = __( 'Expired', 'md' );
			$message['text'] = 'Your <b>MD license key has expired!</b> Renew now to get MD updates sent to your site. <a href="' . esc_url( $url ) . '" class="md-renew-link" target="_blank">' . __( 'Renew now (save 40%).', 'md' ) . '</a>';
		}

		return $message;
	}

	/**
	 * Disable requests to wp.org repository.
	 *
	 * @since 4.7
	 */

	public function disable_wp_requests( $r, $url ) {
		if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) )
			return $r;

		$themes = json_decode( $r['body']['themes'] );
		$parent = get_option( 'template' );
		$child = get_option( 'stylesheet' );

		unset( $themes->themes->$parent );
		unset( $themes->themes->$child );

		$r['body']['themes'] = json_encode( $themes );

		return $r;
	}

}

new md_license;