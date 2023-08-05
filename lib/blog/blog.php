<?php
/**
 * Create Post Settings admin pages.
 *
 * @since 5.6
 */

class md_post extends md_api {

	public function actions() {
		add_action( "md_layout_{$this->_id}_after_settings", array( $this, 'post_settings' ) );
	}

	/**
	 * Create admin page and meta box.
	 *
	 * @since 5.6
	 */

	public function register() {
		$fields = array();
		$fields['single'] = array(
			'author_box' => array(
				'type' => 'checkbox',
				'options' => array( 'enable', 'all_posts' )
			),
			'byline' => array(
				'type' => 'checkbox',
				'options' => md_byline_items( 'ids' )
			),
			'byline_position' => array(
				'type' => 'select',
				'options' => array( 'before_headline', 'after_headline' )
			)
		);
		$page_settings = md_page_settings_fields();
		return array(
			'admin_page' => array(
				'name' => __( 'Settings', 'md' ),
				'parent_slug' => 'edit.php',
				'fields' => array_merge( $fields, $page_settings )
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
		$settings[$this->_id] = array( 'page_cover', 'featured_image', 'layout', 'loop', 'share', 'optins', 'scripts' );
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

	/**
	 * Single Post Settings fields template.
	 *
	 * @since 5.6
	 */

	public function post_settings() {
		include( 'post-settings.php' );
	}

}

new md_post;
