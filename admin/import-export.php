<?php
/**
 * Settings → Import & Export: download this site's MD settings as a file,
 * or upload one, check what it holds and import the selected parts. The
 * file format and the work are md_transfer's (api/transfer.php).
 *
 * @since 6.2
 */

class md_import_export extends md_api {

	const MENU_SLUG = 'admin.php?page=md_settings&tab=md_import_export';

	/**
	 * Register the admin page.
	 *
	 * @since 6.2
	 */

	public function register() {
		$name = __( 'Import & Export', 'md' );

		return array(
			'admin_page' => array(
				'name' => $name,
				'parent' => 'md_settings',
				'order' => 100,
				'form' => false
			)
		);
	}

	/**
	 * Form handlers, through admin-post.php.
	 *
	 * @since 6.2
	 */

	public function actions() {
		add_action( 'admin_post_md_export', array( $this, 'export' ) );
		add_action( 'admin_post_md_import_upload', array( $this, 'upload' ) );
		add_action( 'admin_post_md_import', array( $this, 'import' ) );
		add_action( 'admin_post_md_import_cancel', array( $this, 'cancel' ) );
		add_action( 'admin_menu', array( $this, 'menu_link' ), 20 );
		add_filter( 'submenu_file', array( $this, 'menu_current' ) );
	}

	/**
	 * Link to the Settings tab from the Theme menu, last in the list.
	 *
	 * @since 6.2
	 */

	public function menu_link() {
		$name = __( 'Import & Export', 'md' );

		add_submenu_page( 'md_settings', $name, $name, 'manage_options', self::MENU_SLUG );
	}

	/**
	 * Show the Theme menu link as current on the tab.
	 *
	 * @since 6.2
	 */

	public function menu_current( $submenu_file ) {
		return isset( $_GET['tab'] ) && $_GET['tab'] === $this->_id ? self::MENU_SLUG : $submenu_file;
	}

	/**
	 * Draw the page: the export box, and the import box as an upload
	 * field or, with a file uploaded, its check step.
	 *
	 * @since 6.2
	 */

	public function admin_page() {
		$sections = md_transfer::sections();
		$file = get_transient( self::key( 'file' ) );
		$check = is_array( $file ) ? md_transfer::check( $file ) : null;
		$notice = get_transient( self::key( 'notice' ) );

		delete_transient( self::key( 'notice' ) );

		include md_template( 'admin/import-export', true );
	}

	/**
	 * Download the selected sections as a file.
	 *
	 * @since 6.2
	 */

	public function export() {
		self::verify( 'md_export' );

		self::download( isset( $_POST['md_sections'] ) ? array_map( 'sanitize_key', (array) wp_unslash( $_POST['md_sections'] ) ) : array() );
	}

	/**
	 * Send this site's values for the given sections as a file.
	 *
	 * @since 6.2
	 */

	private static function download( $requested ) {
		$sections = array_values( array_intersect( array_keys( md_transfer::sections() ), $requested ) );

		if ( empty( $sections ) )
			self::back( 'error', __( 'Select at least one section.', 'md' ) );

		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . md_transfer::file_name() );

		echo wp_json_encode( md_transfer::export( $sections ), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
		exit;
	}

	/**
	 * Read an uploaded file into a one-hour transient for this user. The
	 * file itself is never stored.
	 *
	 * @since 6.2
	 */

	public function upload() {
		self::verify( 'md_import_upload' );

		$upload = isset( $_FILES['md_import_file'] ) && is_array( $_FILES['md_import_file'] ) ? $_FILES['md_import_file'] : array();

		if ( empty( $upload['tmp_name'] ) || ! empty( $upload['error'] ) || ! is_uploaded_file( $upload['tmp_name'] ) )
			self::back( 'error', __( 'Choose a file to upload.', 'md' ) );

		if ( strtolower( pathinfo( (string) $upload['name'], PATHINFO_EXTENSION ) ) !== 'json' || $upload['size'] > md_transfer::MAX_SIZE )
			self::back( 'error', __( 'Upload a .json file under 2 MB, exported from Settings → Import & Export.', 'md' ) );

		$file = md_transfer::read( file_get_contents( $upload['tmp_name'] ) );

		if ( is_wp_error( $file ) )
			self::back( 'error', $file->get_error_message() );

		set_transient( self::key( 'file' ), $file, HOUR_IN_SECONDS );
		self::back();
	}

	/**
	 * Import the selected parts of the uploaded file.
	 *
	 * @since 6.2
	 */

	public function import() {
		self::verify( 'md_import' );

		// The backup button shares this form: every setting this site has now

		if ( isset( $_POST['md_backup'] ) )
			self::download( array_keys( md_transfer::sections() ) );

		$file = get_transient( self::key( 'file' ) );

		if ( ! is_array( $file ) )
			self::back( 'error', __( 'The uploaded file has expired. Upload it again.', 'md' ) );

		$parts = isset( $_POST['md_parts'] ) ? array_map( 'sanitize_text_field', (array) wp_unslash( $_POST['md_parts'] ) ) : array();

		if ( empty( $parts ) )
			self::back( 'error', __( 'Select at least one part.', 'md' ) );

		$result = md_transfer::import( $file, $parts );

		delete_transient( self::key( 'file' ) );
		self::back( 'success', sprintf( __( 'Imported: %s.', 'md' ), implode( ', ', $result['imported'] ) ), $result['skipped'] );
	}

	/**
	 * Forget the uploaded file.
	 *
	 * @since 6.2
	 */

	public function cancel() {
		self::verify( 'md_import_cancel' );

		delete_transient( self::key( 'file' ) );
		self::back();
	}

	/**
	 * Every handler needs manage_options (the file holds code fields and
	 * every setting) and its nonce.
	 *
	 * @since 6.2
	 */

	private static function verify( $action ) {
		if ( ! current_user_can( 'manage_options' ) )
			wp_die( esc_html__( 'Sorry, you are not allowed to import or export settings.', 'md' ), 403 );

		check_admin_referer( $action );
	}

	/**
	 * Back to the page, with a notice for the next page load.
	 *
	 * @since 6.2
	 */

	private static function back( $type = '', $message = '', $items = array() ) {
		if ( $message )
			set_transient( self::key( 'notice' ), array(
				'type' => $type,
				'message' => $message,
				'items' => $items
			), HOUR_IN_SECONDS );

		wp_safe_redirect( self::url() );
		exit;
	}

	/**
	 * Show a message, with the setting names it marks with backticks as
	 * code.
	 *
	 * @since 6.2
	 */

	public static function message( $text ) {
		return preg_replace( '/`([^`]+)`/', '<code>$1</code>', esc_html( $text ) );
	}

	/**
	 * This user's transient names.
	 *
	 * @since 6.2
	 */

	private static function key( $name ) {
		return "md_import_{$name}_" . get_current_user_id();
	}

	/**
	 * The page's URL.
	 *
	 * @since 6.2
	 */

	public static function url() {
		return admin_url( self::MENU_SLUG );
	}

}

new md_import_export;
