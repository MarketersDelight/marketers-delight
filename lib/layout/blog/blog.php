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
		$page_settings = $this->fields->data->page_settings();
		$page_settings['link_primary'] = $this->fields->data->links( array( 'save' => true ) );
		$page_settings['link_secondary'] = $this->fields->data->links( array( 'save' => true ) );

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
		$cta_type = $this->fields->get_field( 'page_cta' );
		$prefix = $this->_prefix();

		include( 'blog-settings.php' );
	}

}

new md_post;
