<?php

class md_featured_media extends md_api {

	/**
	 * Register meta box and term.
	 *
	 * @since 4.3.5
	 */

	public function register() {
		$this->name = __( 'Media', 'md' );
		return array(
			'admin_page' => array(
				'name' => $this->name,
				'child_of' => array( 'hero', 'page_settings', 'author' ),
				'fields' => $this->fields()
			),
			'meta_box' => array(
				'name' => $this->name,
				'edit_post' => true,
				'child_of' => 'hero',
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
		return md_featured_media_fields();
	}

	/**
	 * General fields admin template.
	 *
	 * @since 4.7
	 */

	public function admin_fields() {
		$is_post = $this->_get_screen['is_post'];
		$classes = array( "md-$this->_clean_id", 'md-tab-content', 'md-conditional' );

		if ( ! $is_post )
			$classes[] = 'active';

		$classes = join( ' ', $classes );

		echo '<div class="' . esc_attr( $classes ) . '">';
		md_featured_media_admin( $this->fields, $this->_prefix, array( 'is_post' => $is_post ) );
		echo '</div>';
	}

}

new md_featured_media;
