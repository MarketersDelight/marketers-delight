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
	
		$max_uplod_size = wp_max_upload_size();
	
		if ( ( empty( $_FILES['file']['name'] ) ) && ( $_FILES['file']['error'] > 0 || $_FILES['file']['size'] >= $max_uplod_size ) )
			return;
	
		// Todo: expand list of file extensions
		$parts = explode( '.', $_FILES['file']['name'] );
		$extension = end( $parts );
		if ( ! in_array( $extension, array( 'json' ) ) )
			return;
	
		$upload_action = ! empty( $_POST['upload_action'] ) ? $_POST['upload_action'] : '';
	
		if ( $extension == 'json' ) {
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