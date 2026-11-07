<?php
/**
 * Create Site Design admin page.
 *
 * @since 5.0
 */

class md_colors extends md_api {

	// Setup properties

	private $colors;
	private $palette;
	private $resolved;
	private $menu_slug = 'admin.php?page=md_settings&tab=md_colors';

	/**
	 * Link to the Settings tab from the Theme menu.
	 *
	 * @since 6.2
	 */

	public function actions() {
		add_action( 'admin_menu', array( $this, 'menu_link' ), 20 );
		add_filter( 'submenu_file', array( $this, 'menu_current' ) );
	}

	/**
	 * Add the Design link right after Settings.
	 *
	 * @since 6.2
	 */

	public function menu_link() {
		$name = __( 'Design', 'md' );

		add_submenu_page( 'md_settings', $name, $name, 'manage_options', $this->menu_slug, '', 1 );
	}

	/**
	 * Show the Theme menu link as current on the tab.
	 *
	 * @since 6.2
	 */

	public function menu_current( $submenu_file ) {
		return isset( $_GET['tab'] ) && $_GET['tab'] === $this->_id ? $this->menu_slug : $submenu_file;
	}

	/**
	 * Register admin page.
	 *
	 * @since 5.0
	 */

	public function register() {
		$this->colors = new md_design_colors;
		$this->palette = $this->colors->base_palette();

		$fields = $this->build_color_fields( $this->colors->roles );
		$fields['width']['site'] = array(
			'type' => 'range',
			'label' => __( 'Site Width', 'md' )
		);
		$fields['width']['content'] = array(
			'type' => 'range',
			'label' => __( 'Post Width', 'md' )
		);
		$fields['width']['sidebar'] = array(
			'type' => 'range',
			'label' => __( 'Sidebar Width', 'md' )
		);
		$fields['palette'] = array(
			'type' => 'group',
			'fields' => array(
				'hex' => array( 'type' => 'color', 'hex_only' => true )
			)
		);
		$fields['custom'] = array(
			'type' => 'group',
			'label' => __( 'Additional Colors', 'md' ),
			'fields' => array(
				'hex' => array(
					'type' => 'color',
					'hex_only' => true,
					'label' => __( 'Color', 'md' )
				),
				'name' => array(
					'type' => 'text',
					'label' => __( 'Name', 'md' )
				),
				'key' => array(
					'type' => 'text',
					'label' => __( 'Key', 'md' )
				)
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
						'label' => __( 'Site style', 'md' ),
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
		$this->resolved = $values['colors'];
		$line_height = $values['typography']['body']['line_height']['desktop'];
		$this->palette = $this->colors->base_palette();
		$palette = $this->palette;
		$palette_defaults = $this->colors->palette;
		$design = md_setting( array( 'colors', 'design' ) );

		$widths = $this->design()->widths( $values );
		$post_width = $widths['post_width'];
		$layout_spacing = $widths['content_width'] - $post_width;
		$sidebar_width = $widths['sidebar_width'];
		$gap = $line_height;
		$site_width = $post_width + $layout_spacing + $sidebar_width + $gap;

		$content_style = md_filter_loop_styles();

		unset( $content_style['box'] );

		include md_template( 'admin/design', true );
	}

	/**
	 * Turn the color roles into the field schema that sanitizes saved Design
	 * settings. Palette defaults keep a `palette` marker, so picking that swatch
	 * saves no override.
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
	 * Turn one role into color picker arguments: its palette, literal or
	 * inherited default, and the label of the role it inherits from.
	 *
	 * @since 6.0
	 */

	public function color_field( $path, $options ) {
		$source = $options['default'];
		$palette = isset( $this->palette[$source] ) ? $source : '';

		if ( ! empty( $options['inherit'] ) )
			$default = $this->colors->path_value( $this->resolved, $options['inherit'] );
		elseif ( $palette )
			$default = $this->palette[$palette]['hex'];
		else
			$default = $source;

		$args = array(
			'label' => $this->role_label( $options['label'] ),
			'default' => $default
		);

		if ( $palette )
			$args['palette'] = $palette;

		if ( ! empty( $options['inherit'] ) )
			$args['fallback_label'] = $this->role_label( $this->colors->role_label( $options['inherit'] ) );

		if ( ! empty( $options['auto'] ) && in_array( md_setting( array_merge( array( 'colors' ), $path ) ), array( '', null ), true ) ) {
			$args['default'] = $this->colors->path_value( $this->resolved, $path );
			$args['auto_label'] = __( 'Best contrast on background', 'md' );
		}

		$this->fields->field( $path, $args );
	}

	/**
	 * A role's label, translated. Roles keep plain labels because they load
	 * outside the admin too; listing them here gets them translated.
	 *
	 * @since 6.2
	 */

	private function role_label( $label ) {
		$labels = array(
			'Background' => __( 'Background', 'md' ),
			'Border' => __( 'Border', 'md' ),
			'Box Border' => __( 'Box Border', 'md' ),
			'Content Border' => __( 'Content Border', 'md' ),
			'Content Box' => __( 'Content Box', 'md' ),
			'Contrast Text' => __( 'Contrast Text', 'md' ),
			'Danger' => __( 'Danger', 'md' ),
			'Headline Links' => __( 'Headline Links', 'md' ),
			'Headlines' => __( 'Headlines', 'md' ),
			'Hover Background' => __( 'Hover Background', 'md' ),
			'Links' => __( 'Links', 'md' ),
			'Links Active' => __( 'Links Active', 'md' ),
			'Links Hover' => __( 'Links Hover', 'md' ),
			'Main Background' => __( 'Main Background', 'md' ),
			'Muted Links' => __( 'Muted Links', 'md' ),
			'Muted Text' => __( 'Muted Text', 'md' ),
			'Page Cover Overlay' => __( 'Page Cover Overlay', 'md' ),
			'Text' => __( 'Text', 'md' ),
			'Title' => __( 'Title', 'md' ),
			'Title Link' => __( 'Title Link', 'md' ),
			'Warning' => __( 'Warning', 'md' )
		);

		return $labels[$label] ?? $label;
	}

}

new md_colors;
