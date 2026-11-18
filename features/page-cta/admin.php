<?php
/**
 * Renders a call to action select field with filterable
 * admin controls.
 *
 * @since 6.0
 */

class md_page_cta extends md_api {

	/**
	 * Register meta box and term.
	 *
	 * @since 4.3.5
	 */

	public function register() {
		$this->name = __( 'Call to Action', 'md' );

		$args = array(
			'name' => $this->name,
			'child_of' => array( 'hero', 'page_settings', 'author' ),
			'fields' => $this->fields()
		);

		return array(
			'admin_page' => $args,
			'term' => $args,
			'meta_box' => array_merge( $args, array( 'child_of' => 'hero' ) )
		);
	}

	/**
	 * Set options for save.
	 *
	 * @since 4.3.5
	 */

	public function fields() {
		return md_page_cta_fields();
	}

	/**
	 * General fields admin template. A post's CTA never inherits, so its
	 * type select has no default to show.
	 *
	 * @since 4.7
	 */

	public function admin_fields() {
		echo "<div class=\"md-$this->_clean_id md-tab-content\">";

		md_page_cta_admin( $this->fields, $this->_prefix, ! $this->_get_screen['is_post'] );

		echo '</div>';
	}
}

new md_page_cta;
