<?php
/**
 * Create Post Settings admin pages.
 *
 * @since 5.6
 */

class md_post extends md_api {

	/**
	 * Create admin page and meta box.
	 *
	 * @since 5.6
	 */

	public function register() {
		$page_settings = md_page_settings_fields();

		return array(
			'admin_page' => array(
				'name' => __( 'Settings', 'md' ),
				'parent_slug' => 'edit.php',
				'fields' => $page_settings
			)
		);
	}

	/**
	 * Pull admin settings from various parts of MD for use
	 * on this settings page.
	 *
	 * @since 5.6
	 */

	public function admin_settings( $settings ) {
		$settings[$this->_id] = array( 'page_cover', 'featured_image', 'layout', 'loop', 'single', 'share', 'optins', 'scripts' );

		return $settings;
	}

	/**
	 * Admin page template.
	 *
	 * @since 5.6
	 */

	public function admin_page() {
		include( 'blog-settings.php' );
	}

}

new md_post;
