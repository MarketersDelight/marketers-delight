<?php
/**
 * Everything the Drop-ins screens run: list actions, the Install and
 * Update Drop-ins screens, AJAX uploads and updates, the daily update
 * check and Drop-in counts on Dashboard → Updates. Each calls the
 * operations in admin/dropins/functions.php.
 *
 * @since 6.2
 */

class md_dropin_actions {

	/**
	 * Hook the actions in.
	 *
	 * @since 6.2
	 */

	public function __construct() {
		add_action( 'admin_init', array( $this, 'handle_action' ) );
		add_action( 'admin_init', array( $this, 'schedule' ) );
		add_action( 'wp_ajax_md_install_dropin', array( $this, 'ajax_install' ) );
		add_action( 'wp_ajax_md_update_dropin', array( $this, 'ajax_update' ) );
		add_action( 'update-custom_install-md-dropins', array( $this, 'install_screen' ) );
		add_action( 'update-custom_update-md-dropins', array( $this, 'update_screen' ) );
		add_action( 'core_upgrade_preamble', array( $this, 'core_updates' ) );
		add_filter( 'wp_get_update_data', array( $this, 'update_data' ) );
	}

	/**
	 * Run a list action (single row link or bulk), then redirect back with
	 * a notice, as core's plugins.php does. Delete only runs once its
	 * confirmation screen is answered.
	 *
	 * @since 6.2
	 */

	public function handle_action() {
		if ( ! isset( $_REQUEST['page'] ) || $_REQUEST['page'] !== 'md_dropins' )
			return;

		$action = md_dropins::action();

		if ( in_array( $action, array( 'activate', 'deactivate', 'delete' ), true ) ) {
			$slugs = md_requested_dropins( 'dropin' );
			check_admin_referer( "md-dropin-{$action}_" . ( $slugs ? $slugs[0] : '' ) );
		}
		elseif ( in_array( $action, array( 'activate-selected', 'deactivate-selected', 'update-selected', 'delete-selected', 'install-selected' ), true ) ) {
			check_admin_referer( 'bulk-dropins' );
			$slugs = md_requested_dropins( 'checked' );
			$action = str_replace( '-selected', '', $action );
		}
		else
			return;

		if ( ! $slugs )
			$this->redirect( array( 'md_message' => 'none' ) );

		if ( $action === 'update' || $action === 'install' ) {
			wp_safe_redirect( add_query_arg( array(
				'action' => $action . '-md-dropins',
				'dropins' => implode( ',', $slugs ),
				'_wpnonce' => wp_create_nonce( 'bulk-dropins' )
			), self_admin_url( 'update.php' ) ) );

			exit;
		}

		if ( $action === 'delete' && empty( $_REQUEST['verify-delete'] ) )
			return;

		if ( $action === 'activate' )
			$results = md_activate_dropins( $slugs );
		elseif ( $action === 'deactivate' )
			$results = md_deactivate_dropins( $slugs );
		else
			$results = md_delete_dropins( $slugs );

		if ( is_wp_error( $results ) )
			wp_die( esc_html( $results->get_error_message() ) );

		$done = count( array_filter( $results, array( $this, 'is_done' ) ) );

		$this->redirect( array(
			'md_message' => $action,
			'md_count' => $done,
			'md_errors' => count( $results ) - $done,
			'dropin_status' => isset( $_REQUEST['dropin_status'] ) ? sanitize_key( $_REQUEST['dropin_status'] ) : false
		) );
	}

	/**
	 * Whether one Drop-in's result succeeded.
	 *
	 * @since 6.2
	 */

	private function is_done( $result ) {
		return $result === true;
	}

	/**
	 * Redirect to the Drop-ins list and stop.
	 *
	 * @since 6.2
	 */

	private function redirect( $args ) {
		wp_safe_redirect( md_dropins::url( array_filter( $args, 'strlen' ) ) );

		exit;
	}

	/**
	 * Open an Install or Update Drop-ins screen under Theme → Drop-ins.
	 *
	 * @since 6.2
	 */

	private function screen_start( $heading, $class = '' ) {
		global $title, $parent_file, $submenu_file;

		$title = $heading;
		$parent_file = 'md_settings';
		$submenu_file = 'md_dropins';

		require_once ABSPATH . 'wp-admin/admin-header.php';
		require_once MD_DIR . 'admin/dropins/upgrader.php';

		echo '<div class="wrap' . ( $class ? ' ' . esc_attr( $class ) : '' ) . '"><h1>' . esc_html( $title ) . '</h1>';
	}

	/**
	 * Close an Install or Update Drop-ins screen.
	 *
	 * @since 6.2
	 */

	private function screen_end() {
		echo '</div>';

		require_once ABSPATH . 'wp-admin/admin-footer.php';
	}

	/**
	 * The Install Drop-ins screen: update.php?action=install-md-dropins with
	 * Available Drop-ins in `dropins`. Claims included ones on MD.com, then
	 * installs each with core's installer output.
	 *
	 * @since 6.2
	 */

	public function install_screen() {
		if ( ! current_user_can( 'install_plugins' ) )
			wp_die( __( 'Sorry, you are not allowed to install Drop-ins on this site.', 'md' ) );

		check_admin_referer( 'bulk-dropins' );

		$slugs = md_requested_dropins( 'dropins' );

		if ( ! $slugs )
			wp_die( __( 'Please select a Drop-in to install.', 'md' ) );

		$available = md_available_dropins();

		$this->screen_start( _n( 'Install Drop-in', 'Install Drop-ins', count( $slugs ), 'md' ), 'md-dropins-install' );

		foreach ( $slugs as $slug ) {
			$name = isset( $available[$slug]['name'] ) ? $available[$slug]['name'] : $slug;
			$skin = new Dropin_Install_Skin();
			$skin->set_dropin( $slug, $name );
			$skin->before();

			$result = md_install_available_dropin( $slug, array( 'skin' => $skin ) );

			if ( is_wp_error( $result ) && ! $skin->result )
				$skin->error( $result );

			if ( is_array( $result ) && $result['active'] )
				echo '<p>' . esc_html( sprintf( __( '%s is active.', 'md' ), $name ) ) . '</p>';
			elseif ( is_array( $result ) && current_user_can( 'activate_plugins' ) )
				echo '<p><a href="' . esc_url( md_dropin_activate_url( $result['slug'] ) ) . '" class="button button-primary">' . esc_html( sprintf( __( 'Activate %s', 'md' ), $name ) ) . '</a></p>';
		}

		echo '<p><a href="' . esc_url( md_dropins::url( array( 'dropin_status' => 'inactive' ) ) ) . '">' . esc_html__( 'Go to Drop-ins', 'md' ) . '</a></p>';

		$this->screen_end();
	}

	/**
	 * The Update Drop-ins screen: update.php?action=update-md-dropins with
	 * the Drop-ins in `dropins` (comma list or array). The single-update
	 * links from MD 6.1 and older (`dropin=slug/slug.php`) still work.
	 *
	 * @since 5.4
	 */

	public function update_screen() {
		if ( ! current_user_can( 'update_plugins' ) )
			wp_die( __( 'Sorry, you are not allowed to update Drop-ins for this site.', 'md' ) );

		$slugs = md_requested_dropins( 'dropins' );

		if ( ! $slugs && ! empty( $_GET['dropin'] ) ) {
			$dropin = sanitize_text_field( wp_unslash( $_GET['dropin'] ) );
			check_admin_referer( 'upgrade-dropin_' . $dropin );
			$slugs = array( sanitize_key( str_replace( '.php', '', basename( $dropin ) ) ) );
		}
		else
			check_admin_referer( 'bulk-dropins' );

		if ( ! $slugs )
			wp_die( __( 'Please select a Drop-in to update.', 'md' ) );

		$this->screen_start( _n( 'Update Drop-in', 'Update Drop-ins', count( $slugs ), 'md' ) );

		md_upgrade_dropins( $slugs, new Bulk_Dropin_Upgrader_Skin( array(
			'nonce' => 'bulk-dropins',
			'url' => 'update.php?action=update-md-dropins&dropins=' . urlencode( implode( ',', $slugs ) )
		) ) );

		$this->screen_end();
	}

	/**
	 * Install one uploaded Drop-in ZIP. `overwrite` replaces an installed
	 * copy (the Replace button).
	 *
	 * @since 6.2
	 */

	public function ajax_install() {
		check_ajax_referer( 'md-dropins', 'nonce' );

		$file = isset( $_FILES['file'] ) ? $_FILES['file'] : array();

		if ( empty( $file['tmp_name'] ) || ! isset( $file['error'] ) || $file['error'] !== UPLOAD_ERR_OK || strtolower( pathinfo( sanitize_file_name( $file['name'] ), PATHINFO_EXTENSION ) ) !== 'zip' )
			wp_send_json_error( array( 'message' => __( 'Choose a valid Drop-in ZIP file.', 'md' ) ), 400 );

		$result = md_install_dropin( $file['tmp_name'], array( 'overwrite' => ! empty( $_POST['overwrite'] ) ) );

		if ( is_wp_error( $result ) )
			wp_send_json_error( array(
				'code' => $result->get_error_code(),
				'message' => wp_strip_all_tags( $result->get_error_message() ),
				'data' => $result->get_error_data()
			), $result->get_error_code() === 'md_dropin_exists' ? 409 : 400 );

		$result['activate_url'] = ! $result['active'] && current_user_can( 'activate_plugins' ) ? md_dropin_activate_url( $result['slug'] ) : '';

		wp_send_json_success( $result );
	}

	/**
	 * Update one Drop-in for the dashboard's Updates box.
	 *
	 * @since 6.2
	 */

	public function ajax_update() {
		check_ajax_referer( 'md-dropins', 'nonce' );

		$slug = isset( $_POST['slug'] ) ? sanitize_key( $_POST['slug'] ) : '';
		$updates = md_dropin_updates();

		if ( ! $slug || empty( $updates["$slug/$slug.php"] ) )
			wp_send_json_error( array( 'message' => __( 'No update is available for this Drop-in.', 'md' ) ), 400 );

		require_once MD_DIR . 'admin/dropins/upgrader.php';

		$skin = new WP_Ajax_Upgrader_Skin();
		$results = md_upgrade_dropins( array( $slug ), $skin );

		if ( is_wp_error( $results ) )
			wp_send_json_error( array( 'message' => $results->get_error_message() ), 403 );

		$result = isset( $results[$slug] ) ? $results[$slug] : false;

		if ( $result !== true ) {
			$errors = $skin->get_errors();
			$message = is_wp_error( $result ) ? $result->get_error_message() : ( $errors->has_errors() ? $errors->get_error_message() : __( 'Update failed.', 'md' ) );

			wp_send_json_error( array( 'message' => wp_strip_all_tags( $message ) ), 500 );
		}

		$details = md_get_dropin_details( $slug );

		wp_send_json_success( array(
			'slug' => $slug,
			'version' => $details ? $details['Version'] : ''
		) );
	}

	/**
	 * Check licensed sites for updates once a day, so update counts, the
	 * menu badge and Dashboard → Updates stay current. Sites without a
	 * license key have nothing to check.
	 *
	 * @since 6.2
	 */

	public function schedule() {
		$scheduled = wp_next_scheduled( 'md_check_updates' );

		if ( ! md_has_license_key() ) {
			if ( $scheduled )
				wp_clear_scheduled_hook( 'md_check_updates' );
		}
		elseif ( ! $scheduled )
			wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', 'md_check_updates' );
	}

	/**
	 * Drop-in updates on Dashboard → Updates, posting to the same Update
	 * Drop-ins screen as the list's bulk action.
	 *
	 * @since 6.2
	 */

	public function core_updates() {
		if ( ! current_user_can( 'update_plugins' ) )
			return;

		$updates = md_dropin_updates();

		include md_template( 'admin/dropins/core-updates', true );
	}

	/**
	 * Count Drop-in updates in the admin bar and Dashboard → Updates
	 * totals.
	 *
	 * @since 6.2
	 */

	public function update_data( $data ) {
		if ( ! current_user_can( 'update_plugins' ) )
			return $data;

		$count = count( md_dropin_updates() );

		if ( ! $count )
			return $data;

		$data['counts']['md_dropins'] = $count;
		$data['counts']['total'] += $count;
		$data['title'] = trim( $data['title'] . ', ' . sprintf( _n( '%d Drop-in Update', '%d Drop-in Updates', $count, 'md' ), $count ), ', ' );

		return $data;
	}

}

new md_dropin_actions;
