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

		$dir_name = str_replace( ".$extension", '', $_FILES['file']['name'] );

		$upload_action = ! empty( $_POST['upload_action'] ) ? $_POST['upload_action'] : '';

		if ( $extension == 'zip' ) {
			$url = wp_nonce_url( 'admin.php?page=md_dropins', 'marketers-delight' );

			if ( false === ( $creds = request_filesystem_credentials ($url, '', false, false, null ) ) )
				return;

			if ( ! WP_Filesystem( $creds ) ) {
				request_filesystem_credentials( $url, '', true, false, null );
				return;
			}
			
			global $wp_filesystem;		
			$uploads_dir = MD_INSTALLED_DROPINS;
			$zip_file = basename( $_FILES['file']['name'] );
			$zip_path = "$uploads_dir/$zip_file";

			if ( ! $wp_filesystem->exists( $uploads_dir ) )
				$wp_filesystem->mkdir( $uploads_dir );

			if ( $wp_filesystem->exists( "$uploads_dir/$dir_name" ) )
				$wp_filesystem->delete( "$uploads_dir/$dir_name" , true );

			if ( unzip_file( $_FILES['file']['tmp_name'], $uploads_dir ) ) {
				$files = $wp_filesystem->dirlist( $uploads_dir );
				$option = md_setting();
				foreach ( $files as $file => $fields ) {
					$upload_file = "$uploads_dir/$file/$file.php";
					if ( $wp_filesystem->exists( $upload_file ) ) {
						$config = "$uploads_dir/$file/config.json";
						$option['installed_dropins'][] = esc_attr( $file );
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