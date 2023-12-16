<?php

class md_single extends md_api {

	/**
	 * Create admin page and meta box.
	 *
	 * @since 5.6
	 */

	public function fields() {
		return array(
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
			),
			'byline_settings' => array(
				'type' => 'checkbox',
				'options' => array( 'relative_date', 'author_first_name' )
			),
			'post_nav' => array(
				'type' => 'checkbox',
				'options' => array( 'disable' )
			)
		);
	}

	/**
	 * Single Post Settings fields template.
	 *
	 * @since 5.6
	 */

	public function admin_fields() {
		include( 'single-settings.php' );
	}

}

new md_single;
