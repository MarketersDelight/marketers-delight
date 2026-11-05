<?php
/**
 * Handle MD file uploads: the IcoMoon icons import. Drop-in installs
 * use md_install_dropin().
 *
 * @since 5.2.3
 */

class md_files {

	/**
	 * Verify and dispatch file requests.
	 *
	 * @since 5.2.3
	 */

	public function file_action( $action = '' ) {
		if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'marketers_delight_nonce' ) )
			return;

		$action = $action ?: sanitize_key( $_POST['upload_action'] ?? '' );

		if ( ! $this->can_run( $action ) )
			return;

		$wp_filesystem = $this->filesystem();

		if ( ! $wp_filesystem )
			return;

		if ( $action === 'md_icons' )
			$this->upload_icons( $wp_filesystem );

		wp_die();
	}

	/**
	 * Check the capability required by a file action.
	 *
	 * @since 6.0
	 */

	private function can_run( $action ) {
		if ( $action === 'md_icons' )
			return current_user_can( 'manage_options' );

		return false;
	}

	/**
	 * Initialize and return the WordPress filesystem.
	 *
	 * @since 6.0
	 */

	private function filesystem() {
		$url = wp_nonce_url( 'admin.php?page=md_settings&tab=md_icons', 'marketers-delight' );
		$creds = request_filesystem_credentials( $url, '', false, false, null );

		if ( false === $creds || ! WP_Filesystem( $creds ) )
			return false;

		global $wp_filesystem;

		return $wp_filesystem;
	}

	/**
	 * Return a valid uploaded file of the expected extension.
	 *
	 * @since 6.0
	 */

	private function uploaded_file( $extension ) {
		$file = isset( $_FILES['file'] ) ? $_FILES['file'] : array();

		if (
			empty( $file['name'] ) ||
			! isset( $file['error'], $file['size'], $file['tmp_name'] ) ||
			$file['error'] !== UPLOAD_ERR_OK ||
			$file['size'] > wp_max_upload_size()
		)
			return false;

		$file['name'] = sanitize_file_name( $file['name'] );

		if ( strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) ) !== $extension )
			return false;

		return $file;
	}

	/**
	 * Import an IcoMoon JSON file.
	 *
	 * @since 5.2.3
	 */

	private function upload_icons( $wp_filesystem ) {
		$upload = $this->uploaded_file( 'json' );

		if ( ! $upload )
			return;

		$data = json_decode( $wp_filesystem->get_contents( $upload['tmp_name'] ), true );

		if ( empty( $data['icons'] ) || ! is_array( $data['icons'] ) )
			return;

		$custom_icons = array();
		$option = md_setting_part( 'icons' );
		$default_icons = md_get_icons( 'ids', true );

		foreach ( $data['icons'] as $icon ) {
			$properties = isset( $icon['properties'] ) && is_array( $icon['properties'] ) ? $icon['properties'] : array();
			$name = sanitize_key( $properties['name'] ?? '' );
			$code = absint( $properties['code'] ?? 0 );

			if ( ! $name || ! $code )
				continue;

			$custom_icons[] = $name;

			if ( ! in_array( $name, $default_icons, true ) )
				$option['icons']['data'][$name]['unicode'] = dechex( $code );
		}

		$option['custom_icons'] = $custom_icons;

		md_update_setting_part( $option );

		md_compile_css();
	}

}
