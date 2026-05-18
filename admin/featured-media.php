<?php

class md_featured_media extends md_api {

	/**
	 * Register meta box and term.
	 *
	 * @since 4.3.5
	 */

	public function register() {
		$this->name = __( 'Featured Media', 'md' );
		return array(
			'admin_page' => array(
				'name' => $this->name,
				'child_of' => array( 'hero', 'page_settings' ),
				'fields' => $this->fields()
			),
			'meta_box' => array(
				'name' => $this->name,
				'child_of' => 'page_settings',
				'fields' => $this->fields()
			),
			'term' => array(
				'name' => $this->name,
				'child_of' => array( 'hero', 'page_settings' ),
				'fields' => $this->fields()
			)
		);
	}

	/**
	 * Set options for save.
	 *
	 * @since 4.3.5
	 */

	public function fields() {
		$fields = array(
			'media_type' => array(
				'type' => 'select',
				'options' => array( 'image', 'video', 'custom_html' )
			),
			'position' => array(
				'type' => 'select',
				'options' => array_keys( $this->fields->data->values['featured_image'] )
			),
			'image' => array(
				'type' => 'upload',
				'upload_type' => 'media'
			),
			'image_width' => array( 'type' => 'number' ),
			'video' => array( 'type' => 'url' ),
			'custom_html' => array( 'type' => 'code' )
		);

		return $fields;
	}

	/**
	 * General fields admin template.
	 *
	 * @since 4.7
	 */

	public function admin_fields() {
		$prefix = $this->_prefix;
		$screen = get_current_screen();
		$is_post = in_array( $screen->base, array( 'post', 'post-new' ) ) ? true : false;
		$media_type = $this->fields->module( 'media_type' );
		$classes = array( "md-$this->_clean_id", 'md-tab-content', 'md-conditional' );

		if ( ! $is_post )
			$classes[] = 'active';

		$classes = join( ' ', $classes );

		echo '<div class="' . esc_attr( $classes ) . '">';
		include md_template( 'admin/featured-media', true );
		echo '</div>';
	}

}

new md_featured_media;