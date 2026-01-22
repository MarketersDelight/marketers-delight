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
					'license_key' => array( 'type' => 'text' ),
					'404_page' => array( 'type' => 'number' ),
					'head' => array(
						'type' => 'checkbox',
						'options' => array( 'blocks', 'optimize', 'wpjson', 'oembed', 'widgets' )
					),
					'css' => array(
						'type' => 'checkbox',
						'options' => array( 'inline', 'child' )
					),
					'webfonts' => array(
						'type' => 'checkbox',
						'options' => array( 'loader' )
					),
					'sidebars' => array(
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
			$url = esc_url( $this->license['remote_api_url'] ) . '/checkout/?edd_license_key=' . $license . '&download_id=' . $this->license['download_id'];
			$message['status'] = __( 'Expired', 'md' );
			$message['text'] = 'Your <b>MD license key has expired!</b> Renew now to get MD updates sent to your site. <a href="' . esc_url( $url ) . '" class="md-renew-link" target="_blank">' . __( 'Renew now (save 40%).', 'md' ) . '</a>';
		}

		return $message;
	}

	/**
	 * Updater template for license key and updates details.
	 *
	 * @since 4.7
	 */

	public function updater( $option = null ) {
		$update_data = array();
		if ( empty( $option ) )
			$option = md_setting();
		$license_message = $this->license_message();
		$license_status = md_setting( array( 'license', 'status' ) );
		$slug = $this->license['theme_slug'];
		$theme = ! empty( $option['license']['updates']['theme'] ) ? $option['license']['updates']['theme'] : array();
		$dropins = ! empty( $option['license']['updates']['dropins'] ) ? $option['license']['updates']['dropins'] : array();

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

	/**
	 * Widget areas for new sidebar areas.
	 *
	 * @since 4.6.2
	 */

	public function sidebars( $group, $field ) {
		$this->fields->field( array( $group, $field, 'name' ), array(
			'type' => 'text',
			'placeholder' => __( 'Enter sidebar name...', 'md' ),
			'classes' => 'md-focus'
		) );
	}

}

new md_settings;