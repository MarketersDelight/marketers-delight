<?php

class md_page_cta extends md_api {

	/**
	 * Register meta box and term.
	 *
	 * @since 4.3.5
	 */

	public function register() {
		$this->name = __( 'Call to Action', 'md' );
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
			),
			'meta_box' => array(
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
		$sanitize = $this->_data( 'sanitize' );
		$fields = array(
			'page_cta' => array(
				'type' => 'select',
				'options' => array( 'links', 'custom' )
			),
			'custom_html' => array( 'type' => 'code' ),
			'links_sort' => array( 'type' => 'text' ),
			'links' => array(
				'type' => 'group',
				'fields' => $this->fields->data->links( array( 'sort' => 'save' ) )
			)
		);

		return $fields;
	}

	/**
	 * Wrap admin link fields in callback function to include
	 * as repeatable group fields template.
	 *
	 * @since 6.0
	 */

	public function link_fields( $group, $field ) {
		$this->fields->link_fields( array( 'group' => array( $group, $field ) ) );
	}

	/**
	 * General fields admin template.
	 *
	 * @since 4.7
	 */

	public function admin_fields() {
		$prefix = $this->_prefix;
		$cta_type = $this->fields->module( 'page_cta' );

		echo "<div class=\"md-$this->_clean_id md-tab-content\">";
		include md_template( 'admin/page-cta', true );
		echo '</div>';
	}
}

new md_page_cta;