<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Create Drop-ins settings panel.
 *
 * @since 5.2.1
 */

class md_dropins extends md_api {

	/**
	 * Include additional files.
	 *
	 * @since 5.3
	 */
	
	public function includes() {
		require_once( 'store.php' );
	}

	/**
	 * Register admin page.
	 *
	 * @since 5.2.1
	 */

	public function register() {
		$fields = array(
			'move_dropins' => array( 'type' => 'text' ),
			'migrate_dropins' => array( 'type' => 'text' ),
			'moved_dropins' => array( 'type' => 'text' ),
			'installed' => array(
				'type' => 'group',
				'fields' => array(
					'name' => array( 'type' => 'text' ),
					'version' => array( 'type' => 'text' ),
					'description' => array( 'type' => 'text' ),
					'dropin_url' => array( 'type' => 'url' ),
					'author_url' => array( 'type' => 'url' ),
					'settings_url' => array( 'type' => 'url' ),
					'icon' => array( 'type' => 'text' ),
					'author' => array( 'type' => 'text' ),
					'colors' => array( 'type' => 'text' ),
					'status' => array(
						'type' => 'checkbox',
						'options' => array( 'enable' )
					),
					'plugin_name' => array( 'type' => 'text' ),
					'plugin_class' => array( 'type' => 'text' ),
					'priority' => array( 'type' => 'text' ),
					'active' => array( 'type' => 'text' )
				)
			)
		);
		$fields['features'] = array(
			'type' => 'checkbox',
			'options' => array( 'blocks', 'stream', 'bookshelf', 'optins', 'share', 'main_menu', 'admin_bar', 'footnotes', 'tracking_scripts', 'woocommerce' )
		);
		return array(
			'admin_page' => array(
				'name' => __( 'Drop-ins', 'md' ),
				'admin_header' => true,
				'admin_tab' => __( 'Dropin-ins', 'md' ),
				'admin_tab_parent' => 'md_dropins',
				'fields' => $fields
			)
		);
	}

	/**
	 * Load simple toggle script to <head>.
	 *
	 * @since 5.3
	 */
	
	public function admin_scripts() { ?>
		<script>
			jQuery( document ).ready( function( $ ) {
				$( '#md_upload_dropin_button' ).on( 'click', function( e ) {
					e.preventDefault();
					$( '#md_upload_dropin' ).toggle();
				});
			});
		</script>
	<?php }

	/**
	 * Build admin fields.
	 *
	 * @since 5.2.1
	 */

	public function admin_page() {
		$installed = md_setting( array( 'dropins', 'installed' ), array() );
		ksort( $installed );
		include( 'admin-page.php' );
	}

	public function ____admin_page_before() { ?>
		<div class="upload-dropin">
			<p class="install-help"><?php _e( 'If you have a dropin in a .zip format, you may install or update it by uploading it here.' ); ?></p>
			<form method="post" enctype="multipart/form-data" class="wp-upload-form" action="<?php echo self_admin_url( 'update.php?action=upload-md-dropin' ); ?>">
				<?php wp_nonce_field( 'dropin-upload' ); ?>
				<label class="screen-reader-text" for="dropinzip"><?php _e( 'Drop-in zip file' ); ?></label>
				<input type="file" id="dropinzip" name="dropinzip" accept=".zip" />
				<?php submit_button( __( 'Install Now' ), '', 'install-dropin-submit', false ); ?>
			</form>
		</div>
	<?php }

}

new md_dropins;