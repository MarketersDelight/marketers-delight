<?php
/**
 * Create Site Design admin page.
 *
 * @since 5.0
 */

class md_colors extends md_api {

	// Setup properties

	private $colors;
	private $fallbacks;
	private $palette;

	/**
	 * Register admin page.
	 *
	 * @since 5.0
	 */

	public function register() {
		$this->colors = new md_design_colors;
		$this->palette = $this->colors->base_palette();
		$fields = $this->build_color_fields( $this->colors->roles );
		$fields['width']['site'] = array( 'type' => 'range' );
		$fields['width']['content'] = array( 'type' => 'range' );
		$fields['width']['sidebar'] = array( 'type' => 'range' );
		$fields['palette'] = array(
			'type' => 'group',
			'fields' => array(
				'hex' => array( 'type' => 'color', 'hex_only' => true )
			)
		);
		$fields['custom'] = array(
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
		$this->colors = new md_design_colors;
		$options = $this->colors->roles;
		$defaults = $this->design()->defaults();
		$values = $this->design()->values();
		$this->fallbacks = $this->colors->inheritance( $values['colors'] );
		$line_height = $values['typography']['body']['line_height']['desktop'];
		$this->palette = $this->colors->base_palette();
		$palette = $this->palette;
		$palette_defaults = $this->colors->palette;
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
	 * Project the normalized semantic role tree into the field schema used to
	 * sanitize saved Design settings. An entry containing `default` is a color
	 * field. Palette-key defaults retain a `palette` marker so choosing that
	 * built-in swatch is saved as no override; literal and empty defaults are
	 * registered directly. Group nesting and setting paths remain unchanged.
	 *
	 * @since 6.0
	 */

	private function build_color_fields( $roles ) {
		if ( array_key_exists( 'default', $roles ) ) {
			$field = array( 'type' => 'color' );
			$default = $roles['default'];

			if ( isset( $this->palette[$default] ) )
				$field['palette'] = $default;
			else
				$field['default'] = $default;

			return $field;
		}

		$fields = array();

		foreach ( $roles as $key => $children )
			$fields[$key] = $this->build_color_fields( $children );

		return $fields;
	}

	/**
	 * Translate one semantic role into color-picker arguments. Palette defaults
	 * display the current built-in palette value, literal defaults pass through,
	 * and inherited fields read their fallback value and label from the role's
	 * functional `inherit` path.
	 *
	 * @since 6.0
	 */

	public function color_field( $path, $options ) {
		$source = $options['default'];
		$palette = isset( $this->palette[$source] ) ? $source : '';

		if ( ! empty( $options['inherit'] ) ) {
			$default = $this->fallbacks;

			foreach ( $path as $key ) {
				if ( ! is_array( $default ) || ! array_key_exists( $key, $default ) ) {
					$default = '';
					break;
				}

				$default = $default[$key];
			}
		}
		elseif ( $palette )
			$default = $this->palette[$palette]['hex'];
		else
			$default = $source;

		$args = array(
			'type' => 'color',
			'label' => __( $options['label'], 'md' ),
			'default' => $default
		);

		if ( $palette )
			$args['palette'] = $palette;

		if ( ! empty( $options['inherit'] ) )
			$args['fallback_label'] = __( $this->colors->role_label( $options['inherit'] ), 'md' );

		$this->fields->field( $path, $args );
	}

}

new md_colors;
