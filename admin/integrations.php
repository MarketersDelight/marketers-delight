<?php
/**
 * Create Integrations admin settings and run all connection processes.
 *
 * @since 4.9
 */

class md_integrations extends md_api {

	/**
	 * Gather array of integrations to include in interface.
	 *
	 * @since 4.9
	 */

	public function data( $args = null ) {
		$integrations = apply_filters( 'md_integrations', array() );
		$integrations['typekit'] = array(
			'name' => __( 'Adobe Fonts', 'md' ),
			'url' => '#',
			'type' => 'site',
			'refresh' => false,
			'labels' => array(
				'api_key' => __( 'Kit ID', 'md' )
			),
			'icon_name' => 'adobe-fonts.gif',
			'message' => sprintf( __( 'Your Font Kit is now loading on your site! Go to the <a href="%s">Fonts and Typography</a> section in the Site Design panel to assign fonts to your design.', 'md' ), admin_url( 'customize.php?autofocus[section]=md_design[typography]' ) )
		);
		$integrations['google_analytics'] = array(
			'name' => __( 'Google Analytics', 'md' ),
			'url' => 'https://support.google.com/analytics/answer/1008080#trackingID',
			'type' => 'site',
			'refresh' => false,
			'labels' => array(
				'api_key' => __( 'Tracking ID', 'md' )
			),
			'icon_name' => 'stats-icon.png',
			'message' => __( 'The Google Analytics tracking code is now loading on your site! For best performance the script has been placed at the bottom of every page.', 'md' )
		);

		return empty( $args ) ? $integrations : $this->sort( $integrations, $args );
	}

	/**
	 * Return integrations data in various ways.
	 *
	 * @since 4.9
	 */

	public function sort( $integrations, $args ) {
		$show = array();

		// Show services by 'type'
		if ( isset( $args['key'] ) )
			foreach ( $integrations as $id => $fields )
				if ( $fields['type'] == $args['key'] )
					$show[$id] = $fields;

		// Show single service
		if ( isset( $args['service'] ) )
			foreach ( $integrations as $id => $fields )
				if ( $args['service'] == $id )
					$show[$id] = $fields;

		return $show;
	}

	/**
	 * Run actions and filters.
	 *
	 * @since 5.0
	 */

	public function actions() {
		add_action( "wp_ajax_{$this->_id}", array( $this, 'connect' ) );
		add_action( "wp_ajax_nopriv_{$this->_id}", array( $this, 'connect' ) );
	}

	/**
	 * Create admin page.
	 *
	 * @since 5.0
	 */

	public function register() {
		return array(
			'admin_page' => array(
				'name' => __( 'Site Integrations', 'md' ),
				'tab_name' => __( 'Integrations', 'md' ),
				'position' => 4,
				'parent_slug' => 'tools.php',
				'admin_header' => true
			)
		);
	}

	/**
	 * Admin page template.
	 *
	 * @since 4.9
	 */

	public function admin_page() {
		include md_template( 'admin/integrations', true );
	}

	/**
	 * Initiate Integrations script.
	 *
	 * @since 4.9
	 */

	public function admin_scripts() { ?>
		<script>MD.integrations();</script>
	<?php }

	/**
	 * Return template as individual integration boxes.
	 *
	 * @since 4.9
	 */

	public function admin_template( $args = null ) {
		$integrations = $this->data( $args );
		$option = md_setting( array( 'integrations' ) );
		$error = isset( $args['error'] ) ? true : '';

		foreach ( $integrations as $id => $fields ) {
			$icon_path = isset( $fields['icon_path'] ) ? $fields['icon_path'] : MD_URL . 'admin/images';
			$icon_name = isset( $fields['icon_name'] ) ? $fields['icon_name'] : str_replace( '_', '-', "{$id}.png" );
			$icon_url = "$icon_path/$icon_name";
			$api_key_label = ! empty( $fields['labels']['api_key'] ) ? $fields['labels']['api_key'] : __( 'API Key', 'md' );
			$account_url_label = ! empty( $fields['labels']['account_url'] ) ? $fields['labels']['account_url'] : __( 'Account URL', 'md' );

			include md_template( 'admin/integration', true );
		}
	}

	/**
	 * Run the AJAX action to connect or disconnect to integrations.
	 *
	 * @since 4.1
	 */

	public function connect() {
		wp_parse_str( wp_unslash( $_POST['form'] ), $form );

		$form = $this->sanitize()->recursive( $form );

		if ( ! wp_verify_nonce( $form['_wpnonce'], $form['option_page'] . '-options' ) )
			die ( __( 'Sorry, there was an error during the connection process. Please try again.', 'md' ) );

		$option = md_setting();
		$api_keys = $form['marketers_delight']['integrations']['api_keys'];
		$integration = esc_attr( $_POST['integration'] );
		$action = esc_attr( $_POST['action_type'] );
		$api_url = isset( $api_keys[$integration]['account_url'] ) ? $api_keys[$integration]['account_url'] : null;

		if ( $action == 'connect' || $action == 'refresh' ) {
			do_action( 'md_integrations_actions', $integration, $api_keys );
			if ( in_array( $integration, array( 'typekit', 'google_analytics' ) ) )
				$this->save_api_key( $integration, $option, $api_keys[$integration]['key'], $api_url );
		}
		elseif ( $action == 'disconnect' )
			$this->disconnect( $integration, $option );

		$this->admin_template( array( 'service' => $integration ) );

		die();
	}

	/**
	 * Disconnect integration from site and delete all data.
	 *
	 * @since 4.1
	 */

	public function disconnect( $integration, $option ) {
		unset( $option['integrations']['services'][$integration] );
		unset( $option['integrations']['api_keys'][$integration] );
		unset( $option['integrations']['enabled'][$integration] );

		update_option( 'marketers_delight', $option );
	}

	/**
	 * Save API Key only.
	 *
	 * @since 4.9
	 */

	public function save_api_key( $service, $option, $api_key, $api_url = null ) {
		if ( empty( $api_key ) )
			$this->error( $service );

		$option['integrations']['api_keys'][$service]['key'] = esc_attr( $api_key );

		if ( $api_url )
			$option['integrations']['api_keys'][$service]['url'] = esc_attr( $api_url );

		$option['integrations']['enabled'][$service] = true;

		update_option( 'marketers_delight', $option );
	}

	/**
	 * In case of error, show this message.
	 *
	 * @since 4.1
	 */

	public function error( $service ) {
		$this->admin_template( array( 'service' => $service, 'error' => true ) );

		die();
	}

}

new md_integrations;
