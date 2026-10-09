<?php
/**
 * Settings admin panel.
 *
 * @since 4.3
 */

class md_settings extends md_api {

	public $license;

	/**
	 * Run dashboard actions.
	 *
	 * @since 4.7
	 */

	public function actions() {
		$requests = new md_requests;
		$this->license = $requests->license();

		add_action( 'md_license_updater', array( $this, 'updater' ), 10, 2 );
	}

	/**
	 * Register admin page.
	 *
	 * @since 5.0
	 */

	public function register() {
		return array(
			'admin_page' => array(
				'name' => __( 'Edit Theme', 'md' ),
				'tab_name' => __( 'Settings', 'md' ),
				'admin_header' => true,
				'position' => 1,
				'parent_slug' => 'themes.php',
				'icon' => 'dashicons-marketers-delight',
				'fields' => array(
					'404_page' => array( 'type' => 'number' ),
					'head' => array(
						'type' => 'checkbox',
						'options' => array( 'blocks', 'optimize', 'wpjson', 'oembed', 'widgets' )
					),
					'css' => array(
						'type' => 'checkbox',
						'options' => array( 'inline', 'child', 'critical' )
					),
					'sidebars' => array(
						'type' => 'group',
						'group_key_lowercase' => true, // widgets must save all lowercase
						'fields' => array(
							'name' => array( 'type' => 'text' )
						)
					),
					'panels' => array(
						'type' => 'group',
						'group_key_lowercase' => true, // widgets must save all lowercase
						'fields' => array(
							'name' => array( 'type' => 'text' )
						)
					)
				)
			)
		);
	}

	/**
	 * Add license message depending on status.
	 *
	 * @since 4.7
	 */

	public function license_message() {
		$message = array();
		$status = md_license_setting( 'status' );

		$message['status'] = __( 'Inactive', 'md' );

		if ( $status == 'valid' ) {
			$expire = md_license_setting( 'expire' );
			$sites = md_license_setting( 'sites' );
			$limit = md_license_setting( 'limit' );
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
			$message['status'] = __( 'Expired', 'md' );
			$message['text'] = sprintf(
				__( 'Your <b>MD license key has expired!</b> Renew it from your <a href="%s" class="md-renew-link" target="_blank" rel="noopener noreferrer">MD account</a> to continue receiving updates.', 'md' ),
				esc_url( $this->license['remote_api_url'] . '/downloads/' )
			);
		}

		if ( ! empty( $status ) && $status !== 'valid' && empty( $message['text'] ) )
			$message['text'] = __( 'This license key could not be activated for this site. Check the key or contact MD support.', 'md' );

		return $message;
	}

	/**
	 * Updater template for license key and updates details.
	 *
	 * @since 4.7
	 */

	public function updater( $license = null, $check_status = '' ) {
		$license = is_array( $license ) ? $license : md_license_setting();
		$license_message = $this->license_message();
		$slug = $this->license['theme_slug'];
		$theme = ! empty( $license['updates']['theme'] ) ? $license['updates']['theme'] : array();
		$dropins = ! empty( $license['updates']['dropins'] ) ? $license['updates']['dropins'] : array();

		include md_template( 'admin/license-key', true );
	}

	/**
	 * Build admin fields.
	 *
	 * @since 4.5
	 */

	public function admin_page() {
		$page404 = $this->fields->get_field( array( 'settings', '404_page' ) );

		include md_template( 'admin/dashboard', true );
	}

}

new md_settings;
