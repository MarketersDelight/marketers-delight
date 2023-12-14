<?php
/**
 * MD Simple Sidebars adds custom sidebar controls to the Widgets
 * interface and extends into MD Layout options to make it easy
 * to change sidebars across your site in different tax's + post types.
 *
 * @since 4.6.2
 */

class md_sidebars extends md_api {

	/**
	 * Include related files.
	 *
	 * @since 4.6.2
	 */

	public function includes() {
		include_once( 'sidebar-functions.php' );
	}

	/**
	 * Register fields for save validation.
	 *
	 * @since 4.6.2
	 */

	public function register() {
		$typography = $this->fields->data->typography();
		$fields = array(
			'areas' => array(
				'type' => 'group',
				'group_key_lowercase' => true, // widgets must save all lowercase
				'fields' => array(
					'name' => array( 'type' => 'text' )
				)
			),
			'bg_color' => array( 'type' => 'color' ),
			'text' => array( 'type' => 'color' ),
			'title' => array( 'type' => 'color' ),
			'links' => array( 'type' => 'color' )
		);

		$fields = array_merge( $fields, $typography );

		$fields['sidebar_title'] = $typography;

		return array(
			'admin_page' => array(
				'name' => __( 'Sidebars', 'md' ),
				'parent' => 'md_settings',
				'order' => 40,
				'fields' => $fields
			)
		);
	}

	/**
	 * Create toggle options box to show on Widgets panel.
	 *
	 * @since 4.6.2
	 */

	public function admin_page() {
		$defaults = $this->_data( 'defaults' );
		include( 'admin-page.php' );
	}

	/**
	 * Create the admin field that will be repeated in $this->fields().
	 *
	 * @since 4.6.2
	 */

	public function widget_areas( $group, $field ) {
		$this->fields->field( array( $group, $field, 'name' ), array(
			'type' => 'text',
			'placeholder' => __( 'Enter sidebar name...', 'md' ),
			'classes' => 'md-focus'
		) );
	}

}

new md_sidebars;
