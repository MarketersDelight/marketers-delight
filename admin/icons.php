<?php
/**
 * Create font icons library.
 *
 * @since 5.2.3
 */

class md_icons extends md_api {

	/**
	 * Handle the Icons screen's upload and reset.
	 *
	 * @since 6.2
	 */

	public function actions() {
		add_action( 'wp_ajax_md_file', array( $this, 'upload' ) );
		add_action( 'wp_ajax_md_action', array( $this, 'reset' ), 5 );
	}

	/**
	 * Import an IcoMoon selection.json file.
	 *
	 * @since 5.2.3
	 */

	public function upload() {
		if ( ! $this->can_change() )
			wp_send_json_error( __( 'Sorry, you are not allowed to change icons.', 'md' ), 403 );

		$file = $_FILES['file'] ?? array();
		$is_json = ! empty( $file['name'] ) && strtolower( pathinfo( sanitize_file_name( $file['name'] ), PATHINFO_EXTENSION ) ) === 'json';

		if ( ! $is_json || ( $file['error'] ?? 1 ) !== UPLOAD_ERR_OK || ( $file['size'] ?? 0 ) > wp_max_upload_size() || empty( $file['tmp_name'] ) )
			wp_send_json_error( __( 'That file couldn\'t be uploaded. Upload the selection.json file from your IcoMoon download.', 'md' ), 400 );

		$data = json_decode( (string) file_get_contents( $file['tmp_name'] ), true );

		if ( empty( $data['icons'] ) || ! is_array( $data['icons'] ) )
			wp_send_json_error( __( 'That file has no icons in it. Upload the selection.json file from your IcoMoon download.', 'md' ), 400 );

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

		wp_send_json_success();
	}

	/**
	 * Go back to the default icons.
	 *
	 * @since 5.4
	 */

	public function reset() {
		if ( ( $_POST['action_type'] ?? '' ) !== 'reset-icons' || ! $this->can_change() )
			return;

		$option = md_setting_part( array( 'icons', 'custom_icons' ) );
		$option['icons'] = $option['custom_icons'] = array();

		md_update_setting_part( $option );
		md_compile_css();

		wp_die();
	}

	/**
	 * Check the request's nonce and the user.
	 *
	 * @since 6.2
	 */

	private function can_change() {
		return wp_verify_nonce( $_POST['nonce'] ?? '', 'marketers_delight_nonce' ) && current_user_can( 'manage_options' );
	}

	/**
	 * Register admin page.
	 *
	 * @since 5.2.3
	 */

	public function register() {
		return array(
			'admin_page' => array(
				'name' => __( 'Icons', 'md' ),
				'tab_name' => __( 'Icons', 'md' ),
				'parent' => 'md_settings',
				'order' => 3,
				'fields' => array(
					'data' => array(
						'type' => 'group',
						'fields' => array(
							'label' => array( 'type' => 'text' ),
							'unicode' => array( 'type' => 'text' )
						)
					)
				)
			)
		);
	}

	/**
	 * Load icons files and styles to settings page.
	 *
	 * @since 5.2.3
	 */

	public function admin_enqueue() { ?>
		<style type="text/css">
			@font-face {
				font-family: md-icon;
				font-display: swap;
				src: url('<?php echo md_font_icons_url(); ?>') format('woff');
				font-style: normal;
				font-weight: 400;
			}
			[class*="md-icon"]:before {
				display: inline-block;
				font-family: md-icon;
				font-size: 30px;
				font-style: normal;
				font-variant: normal;
				font-weight: 400;
				line-height: 1;
				speak: none;
				text-align: center;
				text-decoration: inherit;
				text-transform: none;
			}
			.md-icon.icon-data:before { content: attr(data-md-icon); }
			<?php
				foreach ( md_icons() as $icon => $fields ) {
					if ( ! isset( $fields['unicode'] ) ) continue;
					$selectors = '';
					if ( isset( $fields['classes'] ) )
						foreach ( $fields['classes'] as $selector )
							$selectors .= ",{$selector}:before";
					echo '.md-icon-' . $icon . ":before{$selectors}{content:'\\" . $fields['unicode'] . '\'}';
				}
			?>
		</style>
	<?php }

	/**
	 * Create admin settings fields.
	 *
	 * @since 5.2.3
	 */

	public function admin_page() {
		$icons = md_icons();
		$icons_count = count( $icons );
		$default_icons_ids = md_get_icons( 'ids', true );
		include md_template( 'admin/icons', true );
	}

}

new md_icons;
