<?php
/**
 * Create Post Settings admin pages.
 *
 * @since 5.6
 */

class md_post extends md_api {

	/**
	 * Run actions and filters for the Blog.
	 *
	 * @since 5.6
	 */

	public function actions() {
		add_action( 'init', array( $this, 'wp_init' ) );
	}

	/**
	 * Create admin page and meta box.
	 *
	 * @since 5.6
	 */

	public function register() {
		return array(
			'admin_page' => array(
				'name' => __( 'Settings', 'md' ),
				'parent_slug' => 'edit.php',
				'fields' => md_page_settings_fields()
			)
		);
	}

	/**
	 * Change labels from Posts menu to Blog in WP admin.
	 *
	 * @since 5.6
	 */

	public function wp_init() {
    	$post_type = get_post_type_object( 'post' );
		$post_type->labels->name = $post_type->labels->menu_name = __( 'Blog', 'md' );
	}

	/**
	 * Pull admin settings from various parts of MD for use
	 * on this settings page.
	 *
	 * @since 5.6
	 */

	public function admin_settings( $settings ) {
		$settings[$this->_id] = array( 'featured_image', 'layout', 'loop', 'share', 'optins', 'scripts' );
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
