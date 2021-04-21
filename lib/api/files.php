<?php
/**
 * Create file uploader class and actions.
 *
 * @since 5.2.3
 */

class md_files {
	
	/**
	 * Run hooks and filters on class instantiation.
	 *
	 * @since 5.2.3
	 */

	public function __construct() {
		if ( is_admin() ) {
			add_action( 'wp_ajax_md_file', array( $this, 'action' ) );
			add_action( 'wp_ajax_nopriv_md_file', array( $this, 'action' ) );
		}
	}

	/**
	 * Run AJAX action for MD file uploads.
	 *
	 * @since 5.2.3
	 */
	
	public function action() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'marketers_delight_nonce' ) )
			return;
	
		$max_upload_size = wp_max_upload_size();
	
		if ( ( empty( $_FILES['file']['name'] ) ) && ! empty( $_POST['accept'] ) && ( $_FILES['file']['error'] > 0 || $_FILES['file']['size'] >= $max_upload_size ) )
			return;

		$accept = explode( ',', trim( str_replace( '.', '', $_POST['accept'] ) ) );
		$parts = explode( '.', $_FILES['file']['name'] );
		$extension = end( $parts );

		if ( ! in_array( $extension, $accept ) )
			return;

		$zip_name = str_replace( ".$extension", '', $_FILES['file']['name'] );
		$upload_action = ! empty( $_POST['upload_action'] ) ? $_POST['upload_action'] : '';

		if ( $extension == 'zip' ) {
			$uploads_dir = MD_INSTALLED_DROPINS;
			$zip_file = basename( $_FILES['file']['name'] );
			$zip_path = "$uploads_dir/$zip_file";

			if ( ! file_exists( $uploads_dir ) )
				wp_mkdir_p( $uploads_dir );

			if ( move_uploaded_file( $_FILES['file']['tmp_name'], $zip_path ) ) {
				$zip = new ZipArchive;
				if ( $zip->open( $zip_path ) ) {
					$zip->extractTo( $uploads_dir );
					$zip->close();
				}
				$files = scandir( $uploads_dir );
				$option = md_setting();
				foreach ( $files as $file ) {
					$upload_file = "$uploads_dir/$file/$file.php";
					if ( file_exists( $upload_file ) ) {
						$config = "$uploads_dir/$file/config.json";
						$option['installed_dropins'][] = esc_attr( $file );
						if ( file_exists( $config ) ) {
							$json = file_get_contents( $config );
							$data = json_decode( $json, true );
							foreach ( array( 'name', 'author', 'version', 'description', 'dropin_url', 'author_url', 'settings_url', 'icon', 'colors', 'plugin_name', 'plugin_class' ) as $setting )
								if ( ! empty ( $data[$setting] ) )
									$option['dropins']['installed'][$file][$setting] = $data[$setting];
						}
					}
				}
				unlink( $zip_path );
				update_option( 'marketers_delight', $option );
			}
		}
		elseif ( $extension == 'json' ) {
			$json = file_get_contents( $_FILES['file']['tmp_name'] );
			$file = json_decode( $json );
			if ( $upload_action == 'md_icons' )
				$this->icons( $file );
		}

		wp_die();
	}

	/**
	 * Run icons processes for icons file upload.
	 *
	 * @since 5.2.3
	 */
	
	public function icons( $file ) {
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
}

new md_files;