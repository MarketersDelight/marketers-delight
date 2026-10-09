<?php
/**
 * The Skin that goes along with the Drop-in Upgrader system.
 * Meant to fill out details like after upgrade actions, and
 * other elements of Drop-in upgrades.
 *
 * @since 5.4
 */

class Dropin_Upgrader_Skin extends WP_Upgrader_Skin {

	public $dropin = '';
	public $dropin_active = false;
	public $dropin_network_active = false;

	/**
	 * Set defaults and properties.
	 *
	 * @since 5.4
	 */

	public function __construct( $args = array() ) {
		$defaults = array(
			'url' => '',
			'dropin' => '',
			'nonce' => '',
			'title' => __( 'Update Drop-in' ),
		);
		$args = wp_parse_args( $args, $defaults );
		$this->dropin = $args['dropin'];
		$this->dropin_active = $this->dropin_network_active = md_is_dropin_active( $this->dropin );

		parent::__construct( $args );
	}

	/**
	 * Action to perform following a single drop-in update.
	 *
	 * @since 5.4
	 */

	public function after() {
		if ( ! $this->result || is_wp_error( $this->result ) ) {
			parent::after();
			return;
		}

		$dropin_info = $this->dropin;
		$dropin_slug = str_replace( '.php', '', basename( $dropin_info ) );

		$this->dropin = $this->upgrader->dropin_info();

		// Update MD Drop-ins data
		$dropin_slug = str_replace( '.php', '', basename( $dropin_info ) );
		$dropin_path = MD_INSTALLED_DROPINS . "/$dropin_info";

		if ( ! is_file( $dropin_path ) ) {
			parent::after();
			return;
		}

		$dropins = md_dropins_setting();
		$license = md_license_setting();
		$new_dropin = md_get_dropin_data( $dropin_path );
		$new_version = ! empty( $new_dropin['Version'] ) ? $new_dropin['Version'] : '';

		if ( $new_version )
			$dropins['installed'][$dropin_slug]['version'] = sanitize_text_field( $new_version );

		if ( $new_version ) {
			unset( $license['updates']['dropins'][$dropin_info] );
			md_update_dropins( $dropins );
			md_update_license( $license );
		}

		md_compile();

		// Back to WP processors

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

		if ( $this->dropin_active || ! $this->result || is_wp_error( $this->result ) || ! current_user_can( 'activate_plugins' ) )
			unset( $update_actions['activate_dropin'] );

		$update_actions = apply_filters( 'update_dropin_complete_actions', $update_actions, $this->dropin );

		if ( ! empty( $update_actions ) )
			$this->feedback( implode( ' | ', (array) $update_actions ) );

	}
}
