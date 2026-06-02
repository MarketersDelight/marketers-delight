<?php
/**
 * Register admin options for Page Cover fields.
 *
 * @since 6.0
 */

class md_page_cover extends md_api {

	/**
	 * Register meta box and term.
	 *
	 * @since 4.3.5
	 */

	public function register() {
		$this->name = __( 'Page Cover', 'md' );
		return array(
			'admin_page' => array(
				'name' => $this->name,
				'child_of' => array( 'hero', 'page_settings' ),
				'fields' => $this->fields()
			),
			'meta_box' => array(
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
			'photo' => array(
				'type' => 'upload',
				'upload_type' => 'media'
			),
			'position' => array(
				'type' => 'select',
				'options' => array( 'headline_cover', 'header_cover', 'header_cover_full', 'remove' )
			),
			'bg_color' => array( 'type' => 'color' ),
			'display' => array(
				'type' => 'checkbox',
				'options' => array( 'alternate', 'disable_overlay', 'bg_repeat', 'single', 'show_excerpt' )
			),
			'title_content' => array( 'type' => 'text' )
		);

		return $fields;
	}
	/**
	 * Add settings template and script to various screens.
	 *
	 * @since 4.7
	 */

	public function admin_fields() {
		$screen = get_current_screen();
		$is_admin = ! in_array( $screen->base, array( 'post', 'post-new', 'term' ) ) ? true : false;

		echo "<div class=\"md-$this->_clean_id md-tab-content\">";
		include md_template( 'admin/page-cover', true );
		echo '</div>';
	}

}

new md_page_cover;