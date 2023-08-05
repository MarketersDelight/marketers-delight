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
		$options = array();
		$types = md_sidebars();
		$sidebars = md_get_sidebars();
		$fields = array(
			'display' => array(
				'type' => 'checkbox',
				'options' => array( 'sitewide' )
			),
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
			foreach ( $pages as $page => $val ) {
				$fields["{$type}_$page"] = array(
					'type' => 'select',
					'options' => $options
				);
				$fields["{$type}_{$page}_show"] = array(
					'type' => 'checkbox',
					'options' => array( 'enable', 'disable' )
				);
			}

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
		$classes = '';
		$types = md_sidebars();
		$sidebars = md_get_sidebars();
		$sitewide = $this->fields->module( array( 'display', 'sitewide' ) );

		if ( ! empty( $sitewide ) )
			$classes = ' is-sitewide';

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

	/**
	 * Popups admin scripts.
	 *
	 * @since 5.6
	 */

	public function admin_scripts() { ?>
		<script>
			( function() {
				document.getElementById( '<?php echo $this->_prefix; ?>_display_sitewide' ).onchange = function() {
					jQuery( '#md_sidebars' ).toggleClass( 'is-sitewide' );
				}
			})();
		</script>
	<?php }

}

new md_sidebars;
