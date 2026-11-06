<?php
/**
 * The Drop-ins screen: installed, Available and Pro Drop-ins in one list
 * with uploads in place, and the delete confirmation. Actions, installs
 * and updates run in md_dropin_actions.
 *
 * @since 5.2.1
 */

class md_dropins extends md_api {

	public $_option = 'marketers_delight_dropins';

	/**
	 * Register the screen's assets.
	 *
	 * @since 6.0
	 */

	public function actions() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Register admin page.
	 *
	 * @since 5.2.1
	 */

	public function register() {
		$name = __( 'Drop-ins', 'md' );

		return array(
			'admin_page' => array(
				'name' => $name . md_dropin_update_badge(),
				'parent_slug' => 'md_settings',
				'capability' => 'activate_plugins',
				'position' => 2,
				'admin_header' => true,
				'admin_tab' => $name,
				'tab_name' => $name,
				'form' => false,
				'self_tab' => true,
				'bar_content' => array( $this, 'bar_content' ),
				'bar_actions' => array( $this, 'bar_actions' )
			)
		);
	}

	/**
	 * The list's views, in the header row in place of the screen's tab.
	 *
	 * @since 6.2
	 */

	public function bar_content() {
		return self::is_list() ? self::table()->bar_views() : '';
	}

	/**
	 * Search and Upload Drop-ins, on the right of the header row, on the
	 * list only.
	 *
	 * @since 6.2
	 */

	public function bar_actions() {
		if ( ! self::is_list() )
			return '';

		$table = self::table();
		$search = isset( $_REQUEST['s'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : '';
		$upload = current_user_can( 'upload_plugins' ) ? '<button type="button" class="button button-primary md-upload-toggle" aria-expanded="false" aria-controls="md_dropin_upload">' . esc_html__( 'Upload Drop-ins', 'md' ) . '</button>' : '';

		return '<div class="md-save-actions md-dropins-actions">' .
			'<form method="get" class="md-dropins-search" role="search">' .
				'<input type="hidden" name="page" value="md_dropins" />' .
				( $table->status !== 'all' ? '<input type="hidden" name="dropin_status" value="' . esc_attr( $table->status ) . '" />' : '' ) .
				'<label class="screen-reader-text" for="md_dropin_search">' . esc_html__( 'Search Drop-ins', 'md' ) . '</label>' .
				'<input type="search" id="md_dropin_search" name="s" value="' . esc_attr( $search ) . '" placeholder="' . esc_attr__( 'Search Drop-ins…', 'md' ) . '" />' .
			'</form>' .
			$upload .
		'</div>';
	}

	/**
	 * The requested list action, from the top or bottom bulk menu.
	 *
	 * @since 6.2
	 */

	public static function action() {
		$action = isset( $_REQUEST['action'] ) ? sanitize_key( $_REQUEST['action'] ) : '';

		if ( ( ! $action || $action === '-1' ) && isset( $_REQUEST['action2'] ) )
			$action = sanitize_key( $_REQUEST['action2'] );

		return $action === '-1' ? '' : $action;
	}

	/**
	 * Whether the screen shows the list: not another tab, not the delete
	 * confirmation.
	 *
	 * @since 6.2
	 */

	public static function is_list() {
		return empty( $_GET['tab'] ) && ! in_array( self::action(), array( 'delete', 'delete-selected' ), true );
	}

	/**
	 * The list table, built once for the header row and the list.
	 *
	 * @since 6.2
	 */

	public static function table() {
		static $table = null;

		if ( $table === null ) {
			require_once MD_DIR . 'admin/dropins/list-table.php';

			$table = new md_dropins_list_table;
			$table->prepare_items();
		}

		return $table;
	}

	/**
	 * The Drop-ins screen URL, with optional query args.
	 *
	 * @since 6.2
	 */

	public static function url( $args = array() ) {
		return add_query_arg( $args, self_admin_url( 'admin.php?page=md_dropins' ) );
	}

	/**
	 * The notice for the last list action.
	 *
	 * @since 6.2
	 */

	public static function notice() {
		$message = isset( $_GET['md_message'] ) ? sanitize_key( $_GET['md_message'] ) : '';
		$count = isset( $_GET['md_count'] ) ? absint( $_GET['md_count'] ) : 0;
		$errors = isset( $_GET['md_errors'] ) ? absint( $_GET['md_errors'] ) : 0;
		$text = '';

		if ( $message === 'activate' )
			$text = sprintf( _n( '%d Drop-in activated.', '%d Drop-ins activated.', $count, 'md' ), $count );
		elseif ( $message === 'deactivate' )
			$text = sprintf( _n( '%d Drop-in deactivated.', '%d Drop-ins deactivated.', $count, 'md' ), $count );
		elseif ( $message === 'delete' )
			$text = sprintf( _n( '%d Drop-in deleted.', '%d Drop-ins deleted.', $count, 'md' ), $count );
		elseif ( $message === 'none' )
			$text = __( 'Please select at least one Drop-in.', 'md' );

		if ( ! $text )
			return '';

		if ( $errors )
			$text .= ' ' . sprintf( _n( '%d Drop-in could not be changed.', '%d Drop-ins could not be changed.', $errors, 'md' ), $errors );

		return '<div class="notice ' . ( $errors || $message === 'none' ? 'notice-warning' : 'notice-success' ) . ' is-dismissible"><p>' . esc_html( $text ) . '</p></div>';
	}

	/**
	 * The upgrade banner: MD Pro for sites without a license key, or the
	 * Drop-ins a higher plan adds or keeps updated for licensed ones.
	 *
	 * @since 6.2
	 */

	public static function upsell() {
		if ( ! current_user_can( 'install_plugins' ) )
			return '';

		if ( ! md_has_license_key() )
			return self::upsell_box( '', __( 'Get every Drop-in with MD Pro.', 'md' ), esc_html__( 'Install them right here in one click, with automatic updates.', 'md' ), MD_THEME_UPDATER_URL . '/pricing/', __( 'See MD Pro', 'md' ) );

		$upgrades = md_plan_upgrade_dropins();

		if ( ! $upgrades )
			return '';

		$missing = $installed = array();

		foreach ( $upgrades as $slug => $dropin )
			if ( $dropin['installed'] )
				$installed[$slug] = $dropin;
			else
				$missing[$slug] = $dropin;

		$list = $missing ? $missing : $installed;
		$first = reset( $list );
		$plan = ! empty( $first['plan'] ) ? $first['plan'] : 'Pro';
		$url = ! empty( $first['upgrade_url'] ) ? $first['upgrade_url'] : MD_THEME_UPDATER_URL . '/account/';
		$count = count( $list );
		$shown = array_slice( $list, 0, 4 );
		$more = $count - count( $shown );
		$names = implode( ', ', wp_list_pluck( $shown, 'name' ) );
		$tiles = '';

		foreach ( $shown as $dropin )
			$tiles .= md_dropin_icon_tile( isset( $dropin['icon'] ) ? $dropin['icon'] : '', isset( $dropin['colors'] ) ? $dropin['colors'] : '', 'small' );

		if ( $missing ) {
			$headline = sprintf( _n( 'MD %1$s adds %2$d more Drop-in.', 'MD %1$s adds %2$d more Drop-ins.', $count, 'md' ), $plan, $count );
			$detail = esc_html( $more ? sprintf( __( '%s and more.', 'md' ), $names ) : $names . '.' ) . ' <a href="' . esc_url( self::url( array( 'dropin_status' => 'more' ) ) ) . '">' . esc_html__( 'See them', 'md' ) . '</a>';
		}
		else {
			$headline = sprintf( __( 'Keep your %1$s Drop-ins up to date with MD %1$s.', 'md' ), $plan );
			$detail = esc_html( $more ? sprintf( _n( '%1$s and %2$d more update with a %3$s license.', '%1$s and %2$d more update with a %3$s license.', $more, 'md' ), $names, $more, $plan ) : sprintf( __( '%1$s update with a %2$s license.', 'md' ), $names, $plan ) );
		}

		return self::upsell_box( $tiles, $headline, $detail, $url, sprintf( __( 'Upgrade to %s', 'md' ), $plan ) );
	}

	/**
	 * The upgrade banner's markup. $detail is escaped HTML.
	 *
	 * @since 6.2
	 */

	private static function upsell_box( $tiles, $headline, $detail, $url, $button ) {
		return
			'<div class="md-dropins-upsell">'.
				( $tiles ? '<span class="md-dropins-upsell-tiles">' . $tiles . '</span>' : '' ).
				'<p><strong>' . esc_html( $headline ) . '</strong> ' . $detail . '</p>'.
				'<a href="' . esc_url( $url ) . '" class="button" target="_blank" rel="noopener noreferrer">' . esc_html( $button ) . '</a>'.
			'</div>';
	}

	/**
	 * Load the Drop-ins script on the Drop-ins and Settings screens.
	 *
	 * @since 6.2
	 */

	public function enqueue( $hook_suffix ) {
		if ( strpos( $hook_suffix, 'md_dropins' ) === false && strpos( $hook_suffix, 'md_settings' ) === false )
			return;

		$script = 'admin/js/dropins.js';

		wp_enqueue_script( 'md-dropins', MD_URL . $script, array( 'jquery' ), md_ver( $script ), true );
		wp_localize_script( 'md-dropins', 'MDDropins', array(
			'nonce' => wp_create_nonce( 'md-dropins' ),
			'updatesNonce' => wp_create_nonce( 'updates' ),
			'theme' => get_template(),
			'text' => array(
				'installing' => __( 'Installing…', 'md' ),
				'refresh' => __( 'Show them in the list', 'md' ),
				'installed' => __( 'Installed', 'md' ),
				'activated' => __( 'Installed and activated', 'md' ),
				'replaced' => __( 'Replaced', 'md' ),
				'exists' => __( 'Already installed', 'md' ),
				'replace' => __( 'Replace current with uploaded', 'md' ),
				'cancel' => __( 'Cancel', 'md' ),
				'current' => __( 'Current', 'md' ),
				'uploaded' => __( 'Uploaded', 'md' ),
				'version' => __( 'Version', 'md' ),
				'requiresMD' => __( 'Requires MD', 'md' ),
				'failed' => __( 'Could not install the Drop-in.', 'md' ),
				'activate' => __( 'Activate', 'md' ),
				'updated' => __( 'Updated to %s', 'md' ),
				'updating' => __( 'Updating…', 'md' ),
				'updateFailed' => __( 'Update failed.', 'md' ),
				'retry' => __( 'Retry', 'md' ),
				'updateAll' => __( 'Update all (%d)', 'md' ),
				'updateAllConfirm' => __( 'NOTICE: You are about to update everything listed here. Any changes made directly to the Marketers Delight parent theme or to Drop-in files in /md-dropins/ will be overwritten. Your child theme stays untouched.', 'md' ),
				'updates' => __( '%d Updates', 'md' ),
				'update' => __( '1 Update', 'md' ),
				'upToDate' => __( 'Up to date', 'md' ),
				'item' => __( '%s item', 'md' ),
				'items' => __( '%s items', 'md' )
			)
		) );
	}

	/**
	 * The Drop-ins list, or the delete confirmation.
	 *
	 * @since 5.2.1
	 */

	public function admin_page() {
		$action = self::action();

		if ( in_array( $action, array( 'delete', 'delete-selected' ), true ) && current_user_can( 'delete_plugins' ) ) {
			$slugs = md_requested_dropins( $action === 'delete' ? 'dropin' : 'checked' );
			$details = md_get_dropin_details();
			$state = md_dropins_setting();

			include md_template( 'admin/dropins/delete', true );

			return;
		}

		$table = self::table();

		include md_template( 'admin/dropins/list', true );
	}

	/**
	 * The Update Drop-ins screen URL for one or more Drop-ins.
	 *
	 * @since 6.2
	 */

	public static function update_url( $slugs ) {
		return wp_nonce_url( self_admin_url( 'update.php?action=update-md-dropins&dropins=' . rawurlencode( implode( ',', (array) $slugs ) ) ), 'bulk-dropins' );
	}

	/**
	 * The link that activates one installed Drop-in from the list.
	 *
	 * @since 6.2
	 */

	public static function activate_url( $slug ) {
		return wp_nonce_url( add_query_arg( array( 'action' => 'activate', 'dropin' => $slug ), self_admin_url( 'admin.php?page=md_dropins' ) ), "md-dropin-activate_$slug" );
	}

}

new md_dropins;
