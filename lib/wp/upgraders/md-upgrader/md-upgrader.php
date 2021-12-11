<?php
/**
 * A legacy admin upgrade system, for running actions and
 * altering data for various MD upgrades.
 *
 * @since 4.7
 */

class md_upgrader {

	/**
	 * Configure updater, assign properties, fire hooks, and
	 * start the Updater engine into the MDAPI.
	 *
	 * @since 4.7
	 */

	public function __construct() {
		add_action( 'admin_init', array( $this, 'after_update' ) );
		// Drop-ins migrator for updates less than MD5.3 only
		if ( md_setting( array( 'dropins', 'migrate_dropins' ) ) ) {
			add_action( 'admin_notices', array( $this, 'update_notice' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'update_scripts' ) );
		}
	}

	/**
	 * Run after upgrade processes.
	 *
	 * @sincd 4.8.1
	 */

	public function after_update() {
		$version = md_setting( array( 'version' ) );
		if ( empty( $version ) || $version < MD_VERSION ) {
			if ( md_setting( array( 'version' ) ) < '4.9' )
				$this->marketers_delight_49();
			if ( md_setting( array( 'version' ) ) < '4.9.6' )
				$this->marketers_delight_496();
			if ( md_setting( array( 'version' ) ) < '5.1' )
				$this->marketers_delight_51();
			if ( md_setting( array( 'version' ) ) < '5.2.1' )
				$this->marketers_delight_521();
			if ( md_setting( array( 'version' ) ) >= '5.0' ) {
				$option = md_setting();
				if ( empty( $option ) || file_exists( MD_INSTALLED_DROPINS ) )
					$option['dropins']['move_dropins'] = true;
				elseif ( empty( $option['dropins']['moved_dropins'] ) )
					$option['dropins']['migrate_dropins'] = true;
				$option['version'] = MD_VERSION;
				update_option( 'marketers_delight', $option );
			}
			md_compile_css();
		}
	}

	/**
	 * Disable requests to wp.org repository.
	 *
	 * @since 4.7
	 */

	public function disable_wp_requests( $r, $url ) {
		if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) )
			return $r;

		$themes = json_decode( $r['body']['themes'] );
		$parent = get_option( 'template' );
		$child = get_option( 'stylesheet' );

		unset( $themes->themes->$parent );
		unset( $themes->themes->$child );

		$r['body']['themes'] = json_encode( $themes );

		return $r;
	}

	/**
	 * Run the MD5.3 updater nag and scripts if applicable.
	 *
	 * @since 5.3
	 */

	public function update_scripts() {
		$dropins_url = admin_url( 'admin.php?page=md_dropins' );
		wp_add_inline_script( 'marketers-delight', "MD.migrateDropins('$dropins_url');" );
	}

	/**
	 * Display Drop-ins migrate notice.
	 *
	 * @since 5.3
	 */

	public function update_notice() { ?>
		<div id="md_updater_notice" class="md notice notice-error">
			<div class="md-before-update">
				<p><?php echo __( 'For full compatibility with the new MD5.3 Drop-ins Manager please run the following update process now.<br /><b>Note:</b> It is strongly recommended you make a complete site and database backup before running this process.', 'md' ); ?></p>
				<p><button id="md_updater_button" class="button"><?php echo __( 'Update now', 'md' ); ?> <i class="dashicons dashicons-update-alt md-loading"></i></button></p>
			</div>
			<p class="md-after-update"><i class="dashicons dashicons-yes"></i> <?php echo __( 'Upgrade complete! Redirecting you to the <strong>Drop-ins Manager</strong>...', 'md' ); ?></p>
		</div>
	<?php }

	/**
	 * MD5.2.1 moves Features Manager to Dropins page.
	 *
	 * @since 5.2.1
	 */
	
	public function marketers_delight_521() {
		$options = md_setting();
		if ( ! empty( $options['settings']['features'] ) ) {
			$options['dropins']['features'] = $options['settings']['features'];
			unset( $options['settings']['features'] );
			update_option( 'marketers_delight', $options );
		}
		return false;
	}
	
	/**
	 * Port some data for MD5.1.
	 *
	 * @since 5.1
	 */
	
	public function marketers_delight_51() {
		$option = md_setting();
		if ( ! empty( $option['content']['loop'] ) ) {
			$option['content']['loop']['archives'] = 'teasers';
			unset( $option['content']['loop'] );
			update_option( 'marketers_delight', $option );
		}
		return false;
	}
	
	/**
	 * Run update processes for MD4.9.6.
	 *
	 * @since 4.9.6
	 */
	
	public function marketers_delight_496() {
		$keys = array();
		$option = md_setting();
	
		if ( ! empty( $option['share']['order'] ) ) {
			foreach ( $option['share']['order'] as $order => $name )
				if ( $name !== 'google' )
					$keys[$order] = $name;
			$option['share']['order'] = $keys;
			update_option( 'marketers_delight', $option );
		}
	
		return false;
	}
	
	/**
	 * Run update processes for MD4.9.
	 *
	 * @since 4.9
	 */
	
	public function marketers_delight_49() {
	
		// 1. Update Option
	
		$option = md_setting();
		$integrations = array();
	
		if ( ! empty( $option['settings']['typekit'] ) ) {
			$integrations['api_keys']['typekit']['key'] = esc_attr( $option['settings']['typekit'] );
			$integrations['enabled']['typekit'] = true;
			unset( $option['settings']['typekit'] );
		}
	
		if ( ! empty( $option['email_data'] ) ) {
			if ( ! in_array( 'custom_code', $option['email_data'] ) ) {
				foreach ( $option['email_data'] as $service => $fields )
					$integrations['enabled'][$service] = true;
				$integrations['services'] = $option['email_data'];
				if ( ! empty( $integrations['services'] ) ) {
					foreach ( $integrations['services'] as $service => $lists ) {
						foreach ( $lists as $list => $fields ) {
							$integrations['services'][$service][$list]['id'] = esc_attr( $list );
							if ( $service == 'mailchimp' ) {
								$url = $integrations['services'][$service][$list]['url'];
								$parse = parse_url( $url );
								parse_str( $parse['query'], $form );
								$host = str_replace( array( 'manage1', 'manage2' ), 'manage', $parse['host'] );
								$integrations['services'][$service][$list]['url'] = esc_url_raw( '//' . $host . '/subscribe/post/' );
								$integrations['services'][$service][$list]['uid'] = esc_attr( $form['u'] );
							}
						}
					}
				}
			}
			unset( $option['email_data'] );
		}
	
		if ( empty( $option['settings']['license_key'] ) && ! empty( $option['dashboard']['license_key'] ) ) # 4.8.4
			$option['settings']['license_key'] = $option['dashboard']['license_key'];
	
		if ( isset( $option['dashboard'] ) )
			unset( $option['dashboard'] );
	
		$option['integrations'] = $integrations;
	
		update_option( 'md_integrations', $integrations );
		update_option( 'marketers_delight', $option );
	
		// 2. Update Theme Mod
	
		$new   = array();
		$theme = get_theme_mod( 'marketers_delight' );
	
		if ( ! isset( $theme['post'] ) )
			$theme['post'] = array();
	
		// Text
		if ( isset( $theme['content']['color'] ) ) {
			$new['site']['text'] = $theme['content']['color'];
			unset( $theme['content']['color'] );
		}
	
		// Headline
		if ( isset( $theme['content']['headline']['color'] ) )
			$new['site']['headline'] = $theme['content']['headline']['color'];
	
		if ( isset( $theme['content']['headline']['links']['color'] ) )
			$new['site']['headline-links'] = $theme['content']['headline']['links']['color'];
	
		if ( isset( $theme['content']['headline'] ) )
			unset( $theme['content']['headline'] );
	
		// Links
		if ( isset( $theme['content']['links']['color'] ) )
			$new['site']['links'] = $theme['content']['links']['color'];
	
		if ( ! empty( $theme['content']['links'] ) )
			unset( $theme['content']['links'] );
	
		// Buttons
		if ( isset( $theme['site']['button']['main']['bg_color'] ) )
			$new['site']['button'] = $theme['site']['button']['main']['bg_color'];
	
		if ( isset( $theme['site']['button']['main']['text']['color'] ) )
			$new['site']['button-text'] = $theme['site']['button']['main']['text']['color'];
	
		if ( isset( $theme['site']['button']['secondary']['bg_color'] ) )
			$new['site']['button-sec'] = $theme['site']['button']['secondary']['bg_color'];
	
		if ( isset( $theme['site']['button']['secondary']['text']['color'] ) )
			$new['site']['button-sec-text'] = $theme['site']['button']['secondary']['text']['color'];
	
		if ( ! empty( $theme['site']['button'] ) )
			unset( $theme['site']['button'] );
	
		$theme = array_merge( $theme, $new );
	
		set_theme_mod( 'marketers_delight', $theme );
	
		// 3. Move Inline CSS
	
		$css = get_option( 'marketers_delight_css' );
		if ( ! empty( $inline ) ) {
			update_option( 'marketers_delight_design_css', $css );
			delete_option( 'marketers_delight_css' );
		}
	
	}

}

new md_upgrader;