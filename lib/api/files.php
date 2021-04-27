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

			if ( ! $wp_filesystem->exists( $uploads_dir ) )
				$wp_filesystem->mkdir( $uploads_dir );

			if ( $wp_filesystem->exists( "$uploads_dir/$dir_name" ) )
				$wp_filesystem->delete( "$uploads_dir/$dir_name", true );

			if ( unzip_file( $files['file']['tmp_name'], $uploads_dir ) ) {
				$option = md_setting();
				$files = $wp_filesystem->dirlist( $uploads_dir );
				foreach ( $files as $file => $fields ) {
					$upload_file = "$uploads_dir/$file/$file.php";
					if ( $wp_filesystem->exists( $upload_file ) ) {
						$config = "$uploads_dir/$file/config.json";
						if ( $wp_filesystem->exists( $config ) ) {
							$json = $wp_filesystem->get_contents( $config );
							$data = json_decode( $json, true );
							foreach ( array( 'name', 'author', 'version', 'description', 'dropin_url', 'author_url', 'settings_url', 'icon', 'colors', 'plugin_name', 'plugin_class' ) as $setting )
								if ( ! empty ( $data[$setting] ) )
									$option['dropins']['installed'][$file][$setting] = $data[$setting];
						}
					}
				}
				update_option( 'marketers_delight', $option );
			}
		}
		elseif ( $action == 'md_icons' && $extension == 'json' ) {
			$json = $wp_filesystem->get_contents( $files['file']['tmp_name'] );
			$file = json_decode( $json );
			$this->update_icons( $file );
		}
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

}