<?php
/**
 * Create Post Settings admin pages.
 *
 * @since 6.0
 */

class md_post extends md_api {

	/**
	 * Create admin page and meta box.
	 *
	 * @since 6.0
	 */

	public function register() {
		$page_settings = $this->fields->data->page_settings();
		$page_settings['link_primary'] = $this->fields->data->links( array( 'sort' => 'save' ) );
		$page_settings['link_secondary'] = $this->fields->data->links( array( 'sort' => 'save' ) );

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
	 * @since 6.0
	 */

	public function parse_query( $wp ) {
		if ( ! is_admin() && $wp->is_main_query() && ( $wp->is_home || $wp->is_category ) ) {
			$per_page = md_post_type_field( array( 'loop', 'posts_per_page' ), get_option( 'posts_per_page' ), 'post' );
			$order = md_post_type_field( array( 'loop', 'order' ), null, 'post' );
			$orderby = md_post_type_field( array( 'loop', 'orderby' ), null, 'post' );

//			$wp->query_vars['posts_per_page'] = esc_attr( $per_page );

			if ( $order )
				$wp->query_vars['order'] = esc_attr( $order );

			if ( $orderby )
				$wp->query_vars['orderby'] = esc_attr( $orderby );
		}

		return $wp;
	}

	/**
	 * Pull admin settings from various parts of MD for use
	 * on this settings page.
	 *
	 * @since 6.0
	 */

	public function admin_settings( $settings ) {
		$settings[$this->_id] = array( 'page_cover', 'layout', 'featured_image', 'loop', 'byline', 'share', 'optins', 'cta', 'floating_bars', 'popups', 'scripts' );

		return $settings;
	}

	/**
	 * Admin page template.
	 *
	 * @since 6.0
	 */

	public function admin_page() {
		include( 'blog-settings.php' );
	}

}

new md_post;
