<?php

/**
 * Create Drop-ins settings panel.
 *
 * @since 5.2.1
 */

class md_dropins extends md_api {

	/**
	 * Register admin page.
	 *
	 * @since 5.2.1
	 */

	public function register() {
		$name = __( 'Drop-ins', 'md' );
		$fields = array(
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
		$total_updates = md_setting( array( 'license', 'updates', 'dropins' ), 0 );
		$updates_badge = ! empty( $total_updates ) && count( $total_updates ) > 0 ? " <span class=\"update-plugins count-" . count( $total_updates ) . "\"><span class=\"plugin-count\">" . count( $total_updates ) . "</span></span>" : '';

		return array(
			'admin_page' => array(
				'name' => "$name{$updates_badge}",
				'parent_slug' => 'themes.php',
				'icon' => 'dashicons-marketers-delight',
				'position' => 65,
				'admin_header' => true,
				'admin_tab' => $name,
				'tab_name' => $name,
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
		$updates = md_setting( array( 'license', 'updates', 'dropins' ) );

		ksort( $installed );

		include md_template( 'admin/dropins', true );
	}

	/**
	 * This form can install themes from anywhere, but is not
	 * yet live. Refer to current drop-in install method at api/files.php
	 *
	 * @since 5.4
	 */

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