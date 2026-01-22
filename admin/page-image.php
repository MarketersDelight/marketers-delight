<?php

class md_page_image extends md_api {

	/**
	 * Register meta box and term.
	 *
	 * @since 4.3.5
	 */

	public function register() {
		$this->name = __( 'Page Image', 'md' );
		return array(
			'admin_page' => array(
				'name' => $this->name,
				'child_of' => array( 'hero', 'page_settings' ),
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
			'image' => array(
				'type' => 'upload',
				'upload_type' => 'media'
			),
			'image_width' => array( 'type' => 'range' ),
			'position' => array(
				'type' => 'select',
				'options' => array_keys( $this->fields->data->values['featured_image'] )
			)
		);

		return $fields;
	}

	/**
	 * General fields admin template.
	 *
	 * @since 4.7
	 */

	public function admin_fields() {
		$screen = get_current_screen();
		$active = ! in_array( $screen->base, array( 'post', 'post-new' ) ) ? ' active' : '';

		echo "<div class=\"md-$this->_clean_id md-tab-content{$active}\">";
		include md_template( 'admin/page-image', true );
		echo '</div>';
	}

}

new md_page_image;