<?php

class Dropin_Upgrader_Skin extends WP_Upgrader_Skin {

	public $dropin = '';
	public $dropin_active = false;
	public $dropin_network_active = false;

	public function __construct( $args = array() ) {
		$defaults = array(
			'url'    => '',
			'dropin' => '',
			'nonce'  => '',
			'title'  => __( 'Update Drop-in' ),
		);
		$args     = wp_parse_args( $args, $defaults );

		$this->dropin = $args['dropin'];
		$this->dropin_active = $this->dropin_network_active = md_is_dropin_active( $this->dropin );
//		$this->plugin_network_active = is_plugin_active_for_network( $this->plugin );

		parent::__construct( $args );
	}

	/**
	 * Action to perform following a single dropin update.
	 *
	 * @since 2.8.0
	 */
	public function after() {
		$dropin_info = $this->dropin;
		$dropin_slug = str_replace( '.php', '', basename( $dropin_info ) );
		$this->dropin = $this->upgrader->dropin_info();

		// Update MD Drop-ins data

		$dropin_slug = str_replace( '.php', '', basename( $dropin_info ) );
		$dropin_path = MD_INSTALLED_DROPINS . "/$dropin_info";
		$option = md_setting();
		$new_dropin = md_get_dropin_data( $dropin_path );
		$new_version = ! empty( $new_dropin['Version'] ) ? $new_dropin['Version'] : '';
		$option['dropins']['installed'][$dropin_slug]['version'] = esc_htmL( $new_version );

		unset( $option['license']['updates']['dropins'][$dropin_info] );

		update_option( 'marketers_delight', $option );

		md_compile_css();
		
		// Back to WP processors

		if ( ! empty( $this->dropin ) && ! is_wp_error( $this->result ) && $this->dropin_active ) {
			printf(
				'<iframe title="%s" style="border:0;overflow:hidden" width="100%%" height="170" src="%s"></iframe>',
				esc_attr__( 'Update progress' ),
				wp_nonce_url( 'update.php?action=activate-dropin&networkwide=' . $this->dropin_network_active . '&dropin=' . urlencode( $this->dropin ), 'activate-dropin_' . $this->dropin )
			);
		}

		$this->decrement_update_count( 'dropin' );

		$update_actions = array(
			'activate_dropin' => sprintf(
				'<a href="%s" target="_parent">%s</a>',
				wp_nonce_url( 'admin.php?page=md_dropins&action=activate&amp;dropin=' . urlencode( $dropin_slug ), "activate-dropin_$dropin_info" ),
				__( 'Activate Drop-in' )
			),
			'dropins_page' => sprintf( '<a href="%s" target="_parent">%s</a>', self_admin_url( 'admin.php?page=md_dropins' ), __( 'Go back to to Drop-ins Manager', 'md' )
			)
		);

		if ( $this->dropin_active || ! $this->result || is_wp_error( $this->result ) || ! current_user_can( 'activate_plugin', $this->dropin ) )
			unset( $update_actions['activate_dropin'] );

		$update_actions = apply_filters( 'update_dropin_complete_actions', $update_actions, $this->dropin );

		if ( ! empty( $update_actions ) )
			$this->feedback( implode( ' | ', (array) $update_actions ) );

	}
}