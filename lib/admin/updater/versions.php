<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Run MD5.3 upgrader scripts. Move previously built-in Drop-ins
 * to NEW /wp-content/md-dropings/ folder.
 *
 * @since 5.3
 */

function marketers_delight_53() {
	$files = new md_files;
//	$files->move_dropins();
//	$files->install_dropins();
}

/**
 * MD5.2.1 moves Features Manager to Dropins page.
 *
 * @since 5.2.1
 */

function marketers_delight_521() {
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

function marketers_delight_51() {
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

function marketers_delight_496() {
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

function marketers_delight_49() {

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