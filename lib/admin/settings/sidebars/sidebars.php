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
	 * Register fields for save validation.
	 *
	 * @since 4.6.2
	 */

	public function register() {
		$options = array();
		$types = md_sidebars();
		$sidebars = md_get_sidebars();
		$fields = array(
			'areas' => array(
				'type' => 'group',
				'group_key_lowercase' => true, // widgets must save all lowercase
				'fields' => array(
					'name' => array( 'type' => 'text' )
				)
			)
		);

		foreach ( $sidebars as $id => $name )
			$options[] = $id;

		foreach ( $types as $type => $pages )
			foreach ( $pages as $page => $val )
				$fields["{$type}_$page"] = array(
					'type' => 'select',
					'options' => $options
				);

		return array(
			'admin_page' => array(
				'name' => __( 'Sidebars', 'md' ),
				'parent' => 'md_settings',
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
		$types = md_sidebars();
		$sidebars = md_get_sidebars();
		include( 'sidebars-settings.php' );
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