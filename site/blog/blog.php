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
	 * A simple way to override the post count loop for archive.
	 *
	 * @since 5.6
	 */

	public function parse_query( $wp ) {
		if ( ! is_admin() && $wp->is_main_query() && ( $wp->is_home || $wp->is_category ) ) {
			$posts_per_page = get_option( 'posts_per_page' );
			$wp->query_vars['posts_per_page'] = md_post_type_field( array( 'loop', 'posts_per_page' ), $posts_per_page, 'post' );
		}

		return $wp;
	}

	/**
	 * Pull admin settings from various parts of MD for use
	 * on this settings page.
	 *
	 * @since 5.6
	 */

	public function admin_settings( $settings ) {
		$settings[$this->_id] = array( 'page_cover', 'layout', 'featured_image', 'loop', 'byline', 'share', 'optins', 'scripts' );

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
