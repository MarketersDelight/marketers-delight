<?php
/**
 * Register the meta box that holds post meta controls in a
 * tab interface by groups.
 *
 * @since 6.0
 */

class md_page_settings extends md_api {

	/**
	 * Register custom meta box and terms.
	 *
	 * @since 6.0
	 */

	public function register() {
		$this->name = __( 'Page Settings', 'md' );

		return array( 'meta_box' => array( 'name' => $this->name ) );
	}

	/**
	 * Render Post meta box template.
	 *
	 * @since 6.0
	 */

	public function meta_box() {
		$this->fields->settings_group( 'post_meta' );
	}

}

new md_page_settings;
