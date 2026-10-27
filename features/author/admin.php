<?php
/**
 * The Author Photo tab of a post type's Page Title settings: show the
 * post author's photo beside single post titles.
 *
 * @since 6.0.4
 */

class md_author_photo extends md_api {

	/**
	 * Hooks.
	 *
	 * @since 6.0.4
	 */

	public function actions() {
		add_filter( 'md_admin_page_hero_fields', array( $this, 'tabs' ), 11 );
	}

	/**
	 * Only singles show the photo, so skip the tab on taxonomy and
	 * author archive settings.
	 *
	 * @since 6.0.4
	 */

	public function tabs( $fields ) {
		if ( ! empty( $this->_get_screen['is_taxonomy'] ) || ( $this->_get_screen['page'] ?? '' ) === 'md_author' )
			unset( $fields[$this->_clean_id] );

		return $fields;
	}

	/**
	 * Register the tab on post type settings screens.
	 *
	 * @since 6.0.4
	 */

	public function register() {
		$this->name = __( 'Author Photo', 'md' );

		return array(
			'admin_page' => array(
				'name' => $this->name,
				'child_of' => array( 'hero', 'page_settings' ),
				'fields' => $this->fields()
			)
		);
	}

	/**
	 * Set options for save.
	 *
	 * @since 6.0.4
	 */

	public function fields() {
		return array(
			'display' => array(
				'type' => 'checkbox',
				'options' => array( 'show' )
			),
			'position' => array(
				'type' => 'select',
				'options' => array( 'title_right', 'title_center' )
			),
			'mobile' => array(
				'type' => 'select',
				'options' => array( 'beside', 'hide' )
			),
			'image_width' => array( 'type' => 'number' )
		);
	}

	/**
	 * Render the tab.
	 *
	 * @since 6.0.4
	 */

	public function admin_fields() {
		$show = $this->fields->saved( array( 'display', 'show' ) );
		$positions = $this->fields->data->values['featured_image'];

		include md_template( 'features', 'author/admin/author-photo', true );
	}

}

new md_author_photo( 'md_author_photo' );
