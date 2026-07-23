<?php
/**
 * Create Site Design admin page.
 *
 * @since 5.0
 */

class md_typography extends md_api {

	/**
	 * Register admin page.
	 *
	 * @since 5.0
	 */

	public function register() {
		$fields = array();
		$groups = array( 'body', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'header', 'sidebar', 'footer' );

		foreach ( $groups as $group ) {
			$fields[$group] = $this->fields->data->typography();

			if ( $group == 'body' ) {
				$fields[$group]['bold'] = array(
					'type' => 'select',
					'options' => array_keys( $this->fields->data->font_weights() )
				);
			}
		}

		return array(
			'admin_page' => array(
				'name' => __( 'Typography', 'md' ),
				'parent' => 'md_settings',
				'order' => 5,
				'fields' => $fields
			)
		);
	}

	/**
	 * Create admin settings fields.
	 *
	 * @since 5.0
	 */

	public function admin_page() {
		$design = new md_design;
		$values = $design->values();
		$defaults = $design->defaults();
		$defaults = $defaults['typography'];

		include md_template( 'admin/typography', true );
	}

}

new md_typography;