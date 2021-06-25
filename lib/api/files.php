<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

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
		if ( ! wp_verify_nonce( $_POST['nonce'], 'marketers_delight_nonce' ) )
			return;

		$url = wp_nonce_url( 'admin.php?page=md_dropins', 'marketers-delight' );

		if ( false === ( $creds = request_filesystem_credentials( $url, '', false, false, null ) ) )
			return;

		if ( ! WP_Filesystem( $creds ) ) {
			request_filesystem_credentials( $url, '', true, false, null );
			return;
		}

		global $wp_filesystem;
		$dropin_id = ! empty( $_POST['dropin_id'] ) ? $_POST['dropin_id'] : '';
		$action = ! empty( $_POST['upload_action'] ) ? $_POST['upload_action'] : '';
		if ( isset( $args['action'] ) )
			$action = $args['action'];

		if ( in_array( $action, array( 'md_icons', 'md_dropin' ) ) )
	 		$this->file_upload( $action, $_FILES, $wp_filesystem, array(
		 		'accept' => ! empty( $_POST['accept'] ) ? $_POST['accept'] : array()
	 		) );
	 	elseif ( $action == 'move-dropins' )
	 		$this->move_dropins( $wp_filesystem );
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
		if ( ( empty( $files['file']['name'] ) ) && ! empty( $args['accept'] ) && ( $files['file']['error'] > 0 || $files['file']['size'] >= wp_max_upload_size() ) )
			return;

		$accept = explode( ',', trim( str_replace( '.', '', $args['accept'] ) ) );
		$parts = explode( '.', $files['file']['name'] );
		$extension = end( $parts );
		$dir_name = str_replace( ".$extension", '', $files['file']['name'] );

		if ( ! in_array( $extension, $accept ) )
			return;

		if ( $action == 'md_dropin' && $extension == 'zip' ) {
			$uploads_dir = MD_INSTALLED_DROPINS;
			$option = md_setting();

			if ( ! $wp_filesystem->exists( $uploads_dir ) ) {
				$wp_filesystem->mkdir( $uploads_dir, 0777 );
				$this->create_protection_file( $uploads_dir );
			}

			if ( $wp_filesystem->exists( "$uploads_dir/$dir_name" ) )
				$wp_filesystem->delete( "$uploads_dir/$dir_name", true );

			if ( unzip_file( $files['file']['tmp_name'], $uploads_dir ) ) {
				$uploaded_files = $wp_filesystem->dirlist( $uploads_dir );
				foreach ( $uploaded_files as $file => $fields )
					$option = $this->activate_dropin( $file, $uploads_dir, $option, $wp_filesystem );
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
	 * Move Core Drop-ins to /wp-content/md-dropins/ folder.
	 *
	 * @since 5.3
	 */

	public function move_dropins( $wp_filesystem ) {
		$core_dir = MD_DROPINS_DIR;
		$installed_dir = MD_INSTALLED_DROPINS;
		$option = md_setting();
		if ( $wp_filesystem->exists( $core_dir ) ) {
			if ( $wp_filesystem->exists( $installed_dir ) )
				$wp_filesystem->delete( $core_dir, true );
			else {
				$core_dropins = $wp_filesystem->dirlist( $core_dir );
				if ( ! $wp_filesystem->exists( $installed_dir ) ) {
					$wp_filesystem->mkdir( $installed_dir, 0777 );
					$this->create_protection_file( $installed_dir );
				}
				foreach ( $core_dropins as $file => $fields ) {
					$wp_filesystem->move( "{$core_dir}$file", "$installed_dir/$file" );
					$option = $this->activate_dropin( $file, $installed_dir, $option, $wp_filesystem );
				}
				if ( empty( $wp_filesystem->dirlist( $core_dir ) ) )
					$wp_filesystem->delete( $core_dir );
			}
		}
		unset( $option['dropins']['features'] );
		unset( $option['dropins']['move_dropins'] );
		unset( $option['dropins']['migrate_dropins'] );
		$option['dropins']['moved_dropins'] = true;
		update_option( 'marketers_delight', $option );
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
		$old_dropins = $this->old_dropins();

		if ( $wp_filesystem->is_dir( $upload_dir ) )
			$this->create_protection_file( $upload_dir );
		if ( $wp_filesystem->exists( $upload_file ) ) {
			$config = "$uploads_dir/$file/config.json";
			if ( $wp_filesystem->exists( $config ) ) {
				$json = $wp_filesystem->get_contents( $config );
				$data = json_decode( $json, true );
				foreach ( array( 'name', 'author', 'version', 'description', 'dropin_url', 'author_url', 'settings_url', 'icon', 'colors', 'plugin_name', 'plugin_class', 'priority', 'active' ) as $setting ) {
					if ( ! empty( $data[$setting] ) )
						$option['dropins']['installed'][$file][$setting] = $data[$setting];
					if ( ( $setting == 'active' && ! empty( $data[$setting] ) ) || in_array( $file, $old_dropins ) )
						$option['dropins']['installed'][$file]['status']['enable'] = true;
					if ( ! empty( $data[$setting]['priority'] ) )
						$option['dropins']['priority'][$file] = true;
				}
			}
		}
		return $option;
	}

	/**
	 * If Drop-ins data from versions older than MD5.3 exist, move
	 * them into the new format. Backwards compatibility method.
	 *
	 * @since 5.3
	 */

	public function old_dropins() {
		$old_dropins = array_keys( md_setting( array( 'dropins', 'features' ), array() ) );
		if ( ! empty( $old_dropins ) ) {
			$old_dropins[] = 'optins';
			$old_dropins[] = 'share';
			if ( in_array( 'admin_bar', $old_dropins ) )
				$old_dropins[] = 'admin-bar';
		}
		return $old_dropins;
	}

	/**
	 * Run delete drop-in action to delete all files and
	 * scrub data from MD settings.
	 *
	 * @since 5.3
	 */

	public function delete_dropin( $dropin_id, $wp_filesystem ) {
		$uploads_dir = MD_INSTALLED_DROPINS;
		$option = md_setting();
		if ( $wp_filesystem->exists( "$uploads_dir/$dropin_id" ) )
			$wp_filesystem->delete( "$uploads_dir/$dropin_id", true );
		unset( $option['dropins']['installed'][$dropin_id] );
		update_option( 'marketers_delight', $option );
		md_compile_css();
	}

	/**
	 * Run icons processes for icons file upload.
	 *
	 * @since 5.2.3
	 */
	
	public function update_icons( $file ) {
		$custom_icons = array();
		$option = md_setting();
		$icons = md_icons();
		$default_icons_ids = md_get_icons( 'ids', true );
		foreach ( $file->icons as $icon => $fields ) {
			$name = esc_attr( $fields->properties->name );
			$custom_icons[] = esc_attr( $name );
			if ( ! in_array( $name, $default_icons_ids ) )
				$option['icons']['data'][$name]['unicode'] = esc_attr( dechex( $fields->properties->code ) );
		}
		$option['custom_icons'] = $custom_icons;
		update_option( 'marketers_delight', $option );
		md_compile_css();
	}

	/**
	 * Create blank index file if not found.
	 *
	 * @since 5.3
	 */

	public function create_protection_file( $upload_dir = MD_DROPINS_DIR ) {
		if ( ! file_exists( "$upload_dir/index.php" ) && wp_is_writable( $upload_dir ) )
			file_put_contents( "$upload_dir/index.php", "<?php\n// Silence is golden." );
	}

}