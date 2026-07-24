<?php
/**
 * Handle MD icon and Drop-in file actions.
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
		elseif ( $action === 'md_dropin' )
			$this->upload_dropin( $wp_filesystem );
		elseif ( $action === 'delete-dropin' )
			$this->delete_dropin( sanitize_file_name( $_POST['dropin_id'] ?? '' ), $wp_filesystem );

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

		if ( $action === 'md_dropin' )
			return current_user_can( 'install_plugins' ) && current_user_can( 'upload_plugins' );

		if ( $action === 'delete-dropin' )
			return current_user_can( 'delete_plugins' );

		return false;
	}

	/**
	 * Initialize and return the WordPress filesystem.
	 *
	 * @since 6.0
	 */

	private function filesystem() {
		$url = wp_nonce_url( 'admin.php?page=md_dropins', 'marketers-delight' );
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

	/**
	 * Extract and register a Drop-in ZIP.
	 *
	 * @since 5.3
	 */

	private function upload_dropin( $wp_filesystem ) {
		$upload = $this->uploaded_file( 'zip' );

		if ( ! $upload )
			return;

		$uploads_dir = MD_INSTALLED_DROPINS;

		if ( ! $wp_filesystem->exists( $uploads_dir ) ) {
			if ( ! $wp_filesystem->mkdir( $uploads_dir, FS_CHMOD_DIR ) )
				return;

			$this->protect_directory( $uploads_dir, $wp_filesystem );
		}

		$dropin_id = pathinfo( $upload['name'], PATHINFO_FILENAME );
		$dropin_dir = "$uploads_dir/$dropin_id";

		if ( ! $dropin_id || ! $this->path_is_contained( $dropin_dir, $uploads_dir ) )
			return;

		if ( $wp_filesystem->exists( $dropin_dir ) )
			$wp_filesystem->delete( $dropin_dir, true );

		if ( true !== unzip_file( $upload['tmp_name'], $uploads_dir ) )
			return;

		$dropins = md_dropins_setting();

		foreach ( (array) $wp_filesystem->dirlist( $uploads_dir ) as $file => $fields ) {
			$file = sanitize_file_name( $file );

			if ( $file && $this->path_is_contained( "$uploads_dir/$file", $uploads_dir ) )
				$dropins = $this->register_dropin( $file, $dropins, $wp_filesystem );
		}

		md_update_dropins( $dropins );
	}

	/**
	 * Read and save a Drop-in's configuration.
	 *
	 * @since 5.3
	 */

	private function register_dropin( $dropin_id, $dropins, $wp_filesystem ) {
		$dropin_dir = MD_INSTALLED_DROPINS . "/$dropin_id";
		$dropin_file = "$dropin_dir/$dropin_id.php";
		$config_file = "$dropin_dir/config.json";

		if ( ! $wp_filesystem->is_dir( $dropin_dir ) || ! $wp_filesystem->exists( $dropin_file ) )
			return $dropins;

		$this->protect_directory( $dropin_dir, $wp_filesystem );

		if ( ! $wp_filesystem->exists( $config_file ) )
			return $dropins;

		$data = json_decode( $wp_filesystem->get_contents( $config_file ), true );

		if ( ! is_array( $data ) )
			return $dropins;

		foreach ( $this->sanitize_dropin_config( $data ) as $setting => $value )
			$dropins['installed'][$dropin_id][$setting] = $value;

		if ( ! empty( $data['active'] ) && current_user_can( 'activate_plugins' ) )
			$dropins['installed'][$dropin_id]['status']['enable'] = true;

		if ( ! empty( $data['priority'] ) )
			$dropins['priority'][$dropin_id] = true;

		return $dropins;
	}

	/**
	 * Sanitize supported config.json values.
	 *
	 * @since 6.0
	 */

	private function sanitize_dropin_config( $data ) {
		$save = array();
		$urls = array( 'dropin_url', 'author_url', 'settings_url' );
		$text = array( 'name', 'author', 'version', 'description', 'icon', 'plugin_name', 'plugin_class', 'priority' );

		foreach ( $urls as $setting ) {
			$value = isset( $data[$setting] ) && is_scalar( $data[$setting] ) ? esc_url_raw( $data[$setting] ) : '';

			if ( $value )
				$save[$setting] = $value;
		}

		foreach ( $text as $setting ) {
			$value = isset( $data[$setting] ) && is_scalar( $data[$setting] ) ? sanitize_text_field( $data[$setting] ) : '';

			if ( $value !== '' )
				$save[$setting] = $value;
		}

		if ( ! empty( $data['colors'] ) && is_string( $data['colors'] ) ) {
			$colors = array_filter( array_map( 'sanitize_hex_color', array_map( 'trim', explode( ',', $data['colors'] ) ) ) );

			if ( $colors )
				$save['colors'] = implode( ', ', $colors );
		}

		if ( ! empty( $data['active'] ) )
			$save['active'] = true;

		return $save;
	}

	/**
	 * Delete a Drop-in and its saved state.
	 *
	 * @since 5.3
	 */

	private function delete_dropin( $dropin_id, $wp_filesystem ) {
		$uploads_dir = MD_INSTALLED_DROPINS;
		$dropin_dir = "$uploads_dir/$dropin_id";

		if ( ! $dropin_id || ! $this->path_is_contained( $dropin_dir, $uploads_dir ) )
			return;

		if ( $wp_filesystem->exists( $dropin_dir ) )
			$wp_filesystem->delete( $dropin_dir, true );

		$dropins = md_dropins_setting();
		$license = md_license_setting();

		unset( $dropins['installed'][$dropin_id] );
		unset( $license['updates']['dropins']["$dropin_id/$dropin_id.php"] );

		md_update_dropins( $dropins );
		md_update_license( $license );
		md_compile();
	}

	/**
	 * Confirm a path resolves inside a base directory.
	 *
	 * @since 6.0
	 */

	private function path_is_contained( $path, $base_dir ) {
		$real_base = realpath( $base_dir );

		if ( ! $real_base )
			return false;

		$real_path = realpath( $path );

		if ( $real_path )
			return $real_path === $real_base || strpos( $real_path, $real_base . DIRECTORY_SEPARATOR ) === 0;

		return strpos( wp_normalize_path( $path ), trailingslashit( wp_normalize_path( $real_base ) ) ) === 0;
	}

	/**
	 * Add an index file to discourage directory browsing.
	 *
	 * @since 5.3
	 */

	private function protect_directory( $directory, $wp_filesystem ) {
		$index = trailingslashit( $directory ) . 'index.php';

		if ( ! $wp_filesystem->exists( $index ) )
			$wp_filesystem->put_contents( $index, "<?php\n// Silence is golden.", FS_CHMOD_FILE );
	}

}
