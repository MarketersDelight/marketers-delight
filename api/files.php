<?php
/**
 * Create file uploader class and actions.
 *
 * @since 5.2.3
 */

class md_files {

	/**
	 * Determine and run file actions.
	 *
	 * @since 5.2.3
	 */

	public function file_action( $args = null ) {
		if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'marketers_delight_nonce' ) || ! current_user_can( 'manage_options' ) )
			return;

		$action = ! empty( $_POST['upload_action'] ) ? $_POST['upload_action'] : '';
		if ( isset( $args['action'] ) )
			$action = $args['action'];

		if ( $action === 'md_dropin' && ( ! current_user_can( 'install_plugins' ) || ! current_user_can( 'upload_plugins' ) ) )
			return;

		if ( $action === 'delete-dropin' && ! current_user_can( 'delete_plugins' ) )
			return;

		$url = wp_nonce_url( 'admin.php?page=md_dropins', 'marketers-delight' );

		if ( false === ( $creds = request_filesystem_credentials( $url, '', false, false, null ) ) )
			return;

		if ( ! WP_Filesystem( $creds ) ) {
			request_filesystem_credentials( $url, '', true, false, null );
			return;
		}

		global $wp_filesystem;
		$dropin_id = ! empty( $_POST['dropin_id'] ) ? sanitize_file_name( $_POST['dropin_id'] ) : '';

		if ( in_array( $action, array( 'md_icons', 'md_dropin' ) ) )
			$this->file_upload( $action, $_FILES, $wp_filesystem, array(
				'accept' => $action === 'md_dropin' ? array( 'zip' ) : array( 'json' )
			) );
		elseif ( $action == 'delete-dropin' )
			$this->delete_dropin( $dropin_id, $wp_filesystem );

		wp_die();
	}

	/**
	 * Run specialty file uploads.
	 *
	 * @since 5.3
	 */

	public function file_upload( $action, $files, $wp_filesystem, $args ) {
		if (
			empty( $files['file']['name'] ) ||
			! isset( $files['file']['error'], $files['file']['size'] ) ||
			$files['file']['error'] !== UPLOAD_ERR_OK ||
			$files['file']['size'] > wp_max_upload_size()
		)
			return;

		$accept = $args['accept'];
		$file_name = sanitize_file_name( $files['file']['name'] );
		$parts = explode( '.', $file_name );
		$extension = end( $parts );
		$dir_name = str_replace( ".$extension", '', $file_name );

		if ( ! in_array( $extension, $accept ) )
			return;

		if ( $action == 'md_dropin' && $extension == 'zip' ) {
			$uploads_dir = MD_INSTALLED_DROPINS;
			$option = md_setting_part( 'dropins' );

			if ( ! $wp_filesystem->exists( $uploads_dir ) ) {
				$wp_filesystem->mkdir( $uploads_dir, FS_CHMOD_DIR );
				$this->create_protection_file( $uploads_dir );
			}

			if ( ! $dir_name || ! $this->path_is_contained( "$uploads_dir/$dir_name", $uploads_dir ) )
				return;

			if ( $wp_filesystem->exists( "$uploads_dir/$dir_name" ) )
				$wp_filesystem->delete( "$uploads_dir/$dir_name", true );

			if ( true === unzip_file( $files['file']['tmp_name'], $uploads_dir ) ) {
				$uploaded_files = $wp_filesystem->dirlist( $uploads_dir );
				foreach ( $uploaded_files as $file => $fields ) {
					$file = sanitize_file_name( $file );
					if ( $file && $this->path_is_contained( "$uploads_dir/$file", $uploads_dir ) )
						$option = $this->activate_dropin( $file, $uploads_dir, $option, $wp_filesystem );
				}
			}

			update_option( 'marketers_delight', $option );
		}
		elseif ( $action == 'md_icons' && $extension == 'json' ) {
			$json = $wp_filesystem->get_contents( $files['file']['tmp_name'] );
			$file = json_decode( $json );
			$this->update_icons( $file );
		}
	}

	/**
	 * Verify Drop-in into MD's system by saving config data,
	 * activate if told to.
	 *
	 * @since 5.3
	 */

	public function activate_dropin( $file, $uploads_dir, $option, $wp_filesystem ) {
		$upload_file = "$uploads_dir/$file/$file.php";
		$upload_dir = "$uploads_dir/$file";

		if ( $wp_filesystem->is_dir( $upload_dir ) )
			$this->create_protection_file( $upload_dir );

		if ( $wp_filesystem->exists( $upload_file ) ) {
			$config = "$uploads_dir/$file/config.json";

			if ( $wp_filesystem->exists( $config ) ) {
				$json = $wp_filesystem->get_contents( $config );
				$data = json_decode( $json, true );

				if ( ! is_array( $data ) )
					return $option;

				$url_settings = array( 'dropin_url', 'author_url', 'settings_url' );

				foreach ( array( 'name', 'author', 'version', 'description', 'dropin_url', 'author_url', 'settings_url', 'icon', 'colors', 'plugin_name', 'plugin_class', 'priority', 'active' ) as $setting ) {
					if ( ! empty( $data[$setting] ) ) {
						if ( in_array( $setting, $url_settings, true ) )
							$value = esc_url_raw( $data[$setting] );
						elseif ( $setting === 'colors' ) {
							$colors = array_filter( array_map( 'sanitize_hex_color', array_map( 'trim', explode( ',', $data[$setting] ) ) ) );
							$value = implode( ', ', $colors );
						}
						elseif ( $setting === 'active' )
							$value = (bool) $data[$setting];
						else
							$value = sanitize_text_field( $data[$setting] );

						if ( ! empty( $value ) )
							$option['dropins']['installed'][$file][$setting] = $value;
					}
					if ( $setting == 'active' && ! empty( $data[$setting] ) && current_user_can( 'activate_plugins' ) )
						$option['dropins']['installed'][$file]['status']['enable'] = true;
					if ( $setting === 'priority' && ! empty( $data[$setting] ) )
						$option['dropins']['priority'][$file] = true;
				}
			}
		}

		return $option;
	}

	/**
	 * Run delete drop-in action to delete all files and
	 * scrub data from MD settings.
	 *
	 * @since 5.3
	 */

	public function delete_dropin( $dropin_id, $wp_filesystem ) {
		$uploads_dir = MD_INSTALLED_DROPINS;
		$option = md_setting_part( array( 'dropins', 'license' ) );

		if ( ! $dropin_id || ! $this->path_is_contained( "$uploads_dir/$dropin_id", $uploads_dir ) )
			return;

		if ( $wp_filesystem->exists( "$uploads_dir/$dropin_id" ) )
			$wp_filesystem->delete( "$uploads_dir/$dropin_id", true );

		unset( $option['dropins']['installed'][$dropin_id] );
		unset( $option['license']['updates']['dropins']["$dropin_id/$dropin_id.php"] );

		update_option( 'marketers_delight', $option );

		md_compile();
	}

	/**
	 * Run icons processes for icons file upload.
	 *
	 * @since 5.2.3
	 */

	public function update_icons( $file ) {
		$custom_icons = array();
		$option = md_setting_part( 'icons' );
		$default_icons_ids = md_get_icons( 'ids', true );

		foreach ( $file->icons as $icon => $fields ) {
			$name = sanitize_text_field( $fields->properties->name );
			$custom_icons[] = sanitize_text_field( $name );

			if ( ! in_array( $name, $default_icons_ids ) )
				$option['icons']['data'][$name]['unicode'] = esc_attr( dechex( $fields->properties->code ) );
		}

		$option['custom_icons'] = $custom_icons;

		update_option( 'marketers_delight', $option );
		md_compile_css();
	}

	/**
	 * Verify a target path resolves to somewhere inside the given
	 * base directory, guarding against traversal via crafted
	 * filenames/drop-in IDs.
	 *
	 * @since 6.0
	 */

	public function path_is_contained( $path, $base_dir ) {
		$real_base = realpath( $base_dir );

		if ( ! $real_base )
			return false;

		$real_path = realpath( $path );

		if ( $real_path )
			return strpos( $real_path, $real_base . DIRECTORY_SEPARATOR ) === 0 || $real_path === $real_base;

		return strpos( wp_normalize_path( $path ), wp_normalize_path( $real_base ) . '/' ) === 0;
	}

	/**
	 * Create blank index file if not found.
	 *
	 * @since 5.3
	 */

	public function create_protection_file( $upload_dir = MD_INSTALLED_DROPINS ) {
		if ( ! file_exists( "$upload_dir/index.php" ) && wp_is_writable( $upload_dir ) )
			file_put_contents( "$upload_dir/index.php", "<?php\n// Silence is golden." );
	}

}
