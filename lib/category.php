<?php
/**
 * Register category-wide settings.
 *
 * @since 6.0
 */

class md_category extends md_api {

	/**
	 * Create admin page and meta box.
	 *
	 * @since 6.0
	 */

	public function register() {
		return array(
			'admin_page' => array(
				'name' => __( 'Settings', 'md' ),
				'parent' => 'md_post',
				'fields' => md_page_settings_fields()
			)
		);
	}

	/**
	 * Pull admin settings from various parts of MD for use
	 * on this settings page.
	 *
	 * @since 6.0
	 */

	public function admin_settings( $settings ) {
		$settings[$this->_id] = array( 'page_title', 'layout', 'loop', 'byline', 'share', 'optins', 'scripts' );

		return $settings;
	}

	/**
	 * Admin page template.
	 *
	 * @since 6.0
	 */

	public function admin_page() {
		echo '<h1>' . __( 'Category Settings', 'md' ) . '</h1>'.
			 '<hr class="md-sep-small" />'.
			 '<div class="md-content-wrap-med">';

		do_action( "{$this->_id}_admin_fields" );

		$this->fields->save();

		echo '</div>';
	}

}

new md_category;
