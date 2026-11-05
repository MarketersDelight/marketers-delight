<?php
/**
 * Progress output for the Update Drop-ins screen, one or many Drop-ins
 * at a time. Modelled on core's Bulk_Plugin_Upgrader_Skin.
 *
 * @since 6.2
 */

class Bulk_Dropin_Upgrader_Skin extends Bulk_Upgrader_Skin {

	public $dropin_slug = '';
	public $dropin_name = '';

	/**
	 * Name the Drop-in about to be updated.
	 *
	 * @since 6.2
	 */

	public function set_dropin( $slug, $name ) {
		$this->dropin_slug = $slug;
		$this->dropin_name = $name;
	}

	/**
	 * Strings for the bulk header and each Drop-in's result.
	 *
	 * @since 6.2
	 */

	public function add_strings() {
		parent::add_strings();

		$this->upgrader->strings['skin_before_update_header'] = __( 'Updating Drop-in %1$s (%2$d/%3$d)', 'md' );
	}

	/**
	 * Header before one Drop-in's update.
	 *
	 * @since 6.2
	 */

	public function before( $title = '' ) {
		parent::before( $this->dropin_name );
	}

	/**
	 * Result after one Drop-in's update.
	 *
	 * @since 6.2
	 */

	public function after( $title = '' ) {
		parent::after( $this->dropin_name );
	}

	/**
	 * Links back to the Drop-ins and Updates screens once every Drop-in
	 * has run.
	 *
	 * @since 6.2
	 */

	public function bulk_footer() {
		parent::bulk_footer();

		$actions = array(
			'dropins_page' => '<a href="' . esc_url( self_admin_url( 'admin.php?page=md_dropins' ) ) . '" target="_parent">' . esc_html__( 'Go to Drop-ins', 'md' ) . '</a>',
			'updates_page' => '<a href="' . esc_url( self_admin_url( 'update-core.php' ) ) . '" target="_parent">' . esc_html__( 'Go to WordPress Updates page', 'md' ) . '</a>'
		);

		if ( ! current_user_can( 'update_core' ) && ! current_user_can( 'update_plugins' ) )
			unset( $actions['updates_page'] );

		$this->feedback( implode( ' | ', $actions ) );
	}

}

/**
 * Output for one Drop-in on the Install Drop-ins screen: a heading and
 * core's installer messages. The screen draws its own wrap and the
 * Activate links, so several Drop-ins can install one after another.
 *
 * @since 6.2
 */

class Dropin_Install_Skin extends WP_Upgrader_Skin {

	public $dropin_slug = '';
	public $dropin_name = '';
	public $started = false;

	/**
	 * Name the Drop-in about to be installed.
	 *
	 * @since 6.2
	 */

	public function set_dropin( $slug, $name ) {
		$this->dropin_slug = $slug;
		$this->dropin_name = $name;
	}

	public function header() {}

	public function footer() {}

	/**
	 * Heading for this Drop-in.
	 *
	 * @since 6.2
	 */

	public function before() {
		if ( $this->started )
			return;

		$this->started = true;

		echo '<h2>' . esc_html( sprintf( __( 'Installing %s', 'md' ), $this->dropin_name ) ) . '</h2>';
	}

}
