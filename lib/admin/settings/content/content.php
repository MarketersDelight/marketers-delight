<?php
/**
 * Create Layout Options settings page.
 *
 * @since 5.0
 */

class md_content extends md_api {

	/**
	 * Actions, filters, and properties.
	 *
	 * @since 5.0
	 */

	public function actions() {
		$this->sanitize = new md_sanitize;
		$this->design = new md_design;
		$this->defaults = $this->design->defaults();
		$this->values = $this->design->values();
	}

	/**
	 * Create admin page with registered fields.
	 *
	 * @since 5.0
	 */

	public function register() {
		$fields = array(
			'admin_page' => array(
				'name' => __( 'Content', 'md' ),
				'parent' => 'md_settings',
				'fields' => array(
					'width' => array(
						'site' => array( 'type' => 'range' ),
						'content' => array( 'type' => 'range' ),
						'sidebar' => array( 'type' => 'range' )
					),
					'layout' => array(
						'type' => 'select',
						'options' => array_keys( $this->sanitize->values['content_box'] )
					),
					'style' => array(
						'type' => 'select',
						'options' => array( 'minimal' )
					),
					'post' => array(
						'type' => 'checkbox',
						'options' => array( 'breadcrumbs', 'blocks' )
					),
					'sidebar' => array(
						'type' => 'checkbox',
						'options' => array( 'blog_remove', 'single', 'category' )
					),
					'author_box' => array(
						'type' => 'checkbox',
						'options' => array( 'enable', 'all_posts' )
					),
					'featured_image' => array(
						'position' => array(
							'type' => 'select',
							'options' => array_keys( $this->sanitize->values['featured_image'] )
						),
						'cover_color' => array( 'type' => 'color' ),
						'cover' => array(
							'type' => 'upload',
							'upload_type' => 'media'
						),
						'styles' => array(
							'type' => 'checkbox',
							'options' => array( 'repeat', 'text_color', 'remove_caption' )
						)
					),
					'byline' => array(
						'type' => 'checkbox',
						'options' => md_byline_items( 'ids' )
					),
					'byline_position' => array(
						'type' => 'select',
						'options' => array( 'before_headline', 'after_headline' )
					)
				)
			)
		);
		return $fields;
	}

	/**
	 * Build Layout admin page fields.
	 *
	 * @since 5.0
	 */

	public function admin_page() {
		$defaults = $this->defaults['content'];
		$values = $this->values;
		$sanitize = $this->sanitize;
		$position = md_setting( array( 'content', 'featured_image', 'position' ) );
		$cta_options = array();
		$cta = md_setting( array( 'cta', 'forms' ) );
		$archives_loop = md_setting( array( 'loop', 'archives' ) );
		if ( ! empty( $cta ) )
			foreach ( $cta as $cta_id => $cta_fields )
				$cta_options[$cta_id] = $cta_fields['name'];
		include( 'content-settings.php' );
	}

}

new md_content;