<?php
/**
 * Create Site Design admin page.
 *
 * @since 5.0
 */

class md_colors extends md_api {

	// Setup properties

	private $colors;
	private $defaults;

	/**
	 * Run high level actions, filters, and define dynamic properties.
	 *
	 * @since 5.0
	 */

	public function actions() {
		$this->defaults = $this->design()->defaults();
	}

	/**
	 * Register admin page.
	 *
	 * @since 5.0
	 */

	public function register() {
		$this->build_colors();

		$fields = $this->colors;
		$fields['width']['site'] = array( 'type' => 'range' );
		$fields['width']['content'] = array( 'type' => 'range' );
		$fields['width']['sidebar'] = array( 'type' => 'range' );
		$fields['palette'] = $fields['custom'] = array(
			'type' => 'group',
			'fields' => array(
				'hex' => array( 'type' => 'color', 'hex_only' => true ),
				'name' => array( 'type' => 'text' ),
				'key' => array( 'type' => 'text' )
			)
		);

		return array(
			'admin_page' => array(
				'name' => __( 'Design', 'md' ),
				'parent' => 'md_settings',
				'admin_header' => true,
				'order' => 3,
				'hide_tab' => true,
				'fields' => array_merge( $fields, array(
					'design' => array(
						'type' => 'select',
						'options' => array_keys( md_filter_loop_styles() )
					)
				) )
			)
		);
	}

	/**
	 * Create admin settings fields.
	 *
	 * @since 5.0
	 */

	public function admin_page() {
		$options = $this->options();
		$defaults = $this->defaults;
		$line_height = $this->design()->values()['typography']['body']['line_height']['desktop'];
		$palette = $this->design()->base_palette();
		$palette_defaults = md_design::$palette;
		$design = md_setting( array( 'colors', 'design' ) );

		$post_width = round( 21 * $line_height );
		$layout_spacing = ! $design ? ( $line_height + round( $line_height / 2 ) ) * 2 : 0;
		$sidebar_width = round( 12 * $line_height );
		$gap = round( $line_height * 1.5 );
		$site_width = $post_width + $layout_spacing + $sidebar_width + $gap;

		$content_style = md_filter_loop_styles();
		unset( $content_style['box'] );

		include md_template( 'admin/design', true );
	}

	/**
	 * Format all known colors from the master list into a register[$fields]
	 * format for a safe save.
	 *
	 * @since 6.0
	 */

	private function build_colors() {
		$palette = md_color_palette();

		$this->colors = array();

		foreach ( $this->design()->color_groups() as $group => $fields )
			foreach ( $fields as $field => $options ) {
				$args = array( 'type' => 'color' );

				if ( ! empty( $options['inherit'] ) )
					$args['inherit'] = $options['inherit'];

				$default = isset( $this->defaults['colors'][$group][$field] ) ? $this->defaults['colors'][$group][$field] : '';

				if ( isset( $palette[$default] ) )
					$default = $palette[$default]['hex'];

				if ( ! $default && ! empty( $options['default'] ) )
					$default = $options['default'];

				if ( $default )
					$args['default'] = esc_attr( $default );

				$this->colors[$group][$field] = $args;
			}
	}

	/**
	 * Format options from master color list to render as admin fields.
	 *
	 * @since 5.0
	 */

	public function options() {
		$sections = array();

		foreach ( $this->design()->color_groups() as $group => $fields )
			foreach ( $fields as $field => $options ) {
				$section = ! empty( $options['section'] ) ? $options['section'] : $group;
				$entry = array( 'label' => $options['label'] );

				if ( ! empty( $options['inherit'] ) )
					$entry['inherit'] = $options['inherit'];

				if ( ! empty( $options['default'] ) )
					$entry['default'] = $options['default'];
				elseif ( ! empty( $this->colors[$group][$field]['default'] ) )
					$entry['default'] = $this->colors[$group][$field]['default'];

				$sections[$section][$field] = $entry;
			}

		return $sections;
	}

}

new md_colors;
