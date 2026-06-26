<?php
/**
 * Create Site Design admin page.
 *
 * @todo Rename to design, merge $this->colors and phase out $this->options
 * @since 5.0
 */

class md_colors extends md_api {

	private $colors;
	private $defaults;

	/**
	 * Register admin page.
	 *
	 * @since 5.0
	 */

	public function register() {
		$fields = $this->colors;
		$fields['width']['site'] = array( 'type' => 'range' );
		$fields['width']['content'] = array( 'type' => 'range' );
		$fields['width']['sidebar'] = array( 'type' => 'range' );
		$fields['palette'] = $fields['custom'] = array(
			'type' => 'group',
			'fields' => array(
				'hex'  => array( 'type' => 'color' ),
				'name' => array( 'type' => 'text' )
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
		$palette = apply_filters( 'md_color_palette', array() );

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
	 * Actions, filters, and properties.
	 *
	 * @since 5.0
	 */

	public function actions() {
		$this->defaults = $this->design()->defaults();
		$this->colors = array(
			'site' => array(
				'text-main' => array( 'inherit' => 'text-main' ),
				'text-secondary' => array( 'inherit' => 'text-secondary' ),
				'links' => array( 'inherit' => 'primary' ),
				'links-secondary' => array( 'inherit' => 'text-secondary' ),
				'headline' => array( 'inherit' => 'text-main' ),
				'headline-links' => array( 'inherit' => 'text-main' ),
				'button' => array(),
				'button-text' => array(),
				'button-secondary' => array(),
				'button-secondary-text' => array()
			),
			'header' => array(
				'bg_color' => array(),
				'border_color' => array( 'inherit' => 'border' ),
				'color' => array( 'inherit' => 'text-main' )
			),
			'menu' => array(
				'links' => array( 'inherit' => 'text-main' ),
				'hover' => array( 'inherit' => 'primary' ),
				'active' => array( 'inherit' => 'primary' )
			),
			'submenu' => array(
				'bg_color' => array( 'inherit' => 'background' ),
				'links' => array( 'inherit' => 'text-secondary' ),
				'hover' => array( 'inherit' => 'primary' )
			),
			'content' => array(
				'bg_color' => array( 'inherit' => 'background' ),
				'body_color' => array( 'inherit' => 'surface' ),
				'border_color' => array( 'inherit' => 'border' ),
				'page_cover' => array()
			),
			'sidebar' => array(
				'bg_color' => array(),
				'text' => array( 'inherit' => 'text-secondary' ),
				'title' => array( 'inherit' => 'text-main' ),
				'title_link' => array( 'inherit' => 'text-main' ),
				'links' => array( 'inherit' => 'text-secondary' )
			),
			'footer' => array(
				'bg_color' => array(),
				'border_color' => array( 'inherit' => 'border' ),
				'text' => array( 'inherit' => 'text-main' ),
				'title' => array( 'inherit' => 'text-main' ),
				'title_link' => array( 'inherit' => 'text-main' ),
				'links' => array( 'inherit' => 'muted' )
			)
		);

		foreach ( $this->colors as $group => $fields )
			foreach ( $fields as $field => $args ) {
				$args['type'] = 'color';

				if ( ! empty( $this->defaults['colors'][$group][$field] ) )
					$args['default'] = esc_attr( $this->defaults['colors'][$group][$field] );

				$this->colors[$group][$field] = $args;
			}
	}

	/**
	 * Organize options with labels.
	 *
	 * @since 5.0
	 */

	public function options() {
		return array(
			'text' => array(
				'text-main' => array(
					'label'   => __( 'Text', 'md' ),
					'inherit' => 'text-main'
				),
				'text-secondary' => array(
					'label'   => __( 'Text Secondary', 'md' ),
					'inherit' => 'text-secondary'
				),
				'links' => array(
					'label'   => __( 'Links', 'md' ),
					'inherit' => 'primary'
				),
				'links-secondary' => array(
					'label'   => __( 'Links Secondary', 'md' ),
					'inherit' => 'text-secondary'
				),
				'headline' => array(
					'label'   => __( 'Headline', 'md' ),
					'inherit' => 'text-main'
				),
				'headline-links' => array(
					'label'   => __( 'Headline Links', 'md' ),
					'inherit' => 'text-main'
				)
			),
			'button' => array(
				'button' => array(
					'label'   => __( 'Background', 'md' ),
					'default' => $this->defaults['colors']['site']['button']
				),
				'button-text' => array(
					'label'   => __( 'Text', 'md' ),
					'default' => $this->defaults['colors']['site']['button-text']
				)
			),
			'button-secondary' => array(
				'button-secondary' => array(
					'label'   => __( 'Background', 'md' ),
					'default' => $this->defaults['colors']['site']['button-secondary']
				),
				'button-secondary-text' => array(
					'label'   => __( 'Text', 'md' ),
					'default' => $this->defaults['colors']['site']['button-secondary-text']
				)
			),
			'header' => array(
				'bg_color' => array(
					'label' => __( 'Background', 'md' )
				),
				'border_color' => array(
					'label'   => __( 'Border', 'md' ),
					'inherit' => 'border'
				),
				'color' => array(
					'label'   => __( 'Text', 'md' ),
					'inherit' => 'text-main'
				)
			),
			'menu' => array(
				'links' => array(
					'label'   => __( 'Links', 'md' ),
					'inherit' => 'text-main'
				),
				'hover' => array(
					'label'   => __( 'Links Hover', 'md' ),
					'inherit' => 'primary'
				),
				'active' => array(
					'label'   => __( 'Links Active', 'md' ),
					'inherit' => 'primary'
				)
			),
			'submenu' => array(
				'bg_color' => array(
					'label'   => __( 'Background', 'md' ),
					'inherit' => 'background'
				),
				'links' => array(
					'label'   => __( 'Links', 'md' ),
					'inherit' => 'text-secondary'
				),
				'hover' => array(
					'label'   => __( 'Links Hover', 'md' ),
					'inherit' => 'primary'
				)
			),
			'content' => array(
				'body_color' => array(
					'label'   => __( 'Content Body', 'md' ),
					'inherit' => 'surface'
				),
				'bg_color' => array(
					'label'   => __( 'Content Box', 'md' ),
					'inherit' => 'background'
				),
				'border_color' => array(
					'label'   => __( 'Border', 'md' ),
					'inherit' => 'border'
				),
				'page_cover' => array(
					'label' => __( 'Page Cover', 'md' )
				)
			),
			'sidebar' => array(
				'bg_color' => array(
					'label' => __( 'Background', 'md' )
				),
				'text' => array(
					'label'   => __( 'Text', 'md' ),
					'inherit' => 'text-secondary'
				),
				'title' => array(
					'label'   => __( 'Title', 'md' ),
					'inherit' => 'text-main'
				),
				'title_link' => array(
					'label'   => __( 'Title Link', 'md' ),
					'inherit' => 'text-main'
				),
				'links' => array(
					'label'   => __( 'Links', 'md' ),
					'inherit' => 'text-secondary'
				)
			),
			'footer' => array(
				'bg_color' => array(
					'label' => __( 'Background', 'md' )
				),
				'border_color' => array(
					'label'   => __( 'Border', 'md' ),
					'inherit' => 'border'
				),
				'text' => array(
					'label'   => __( 'Text', 'md' ),
					'inherit' => 'text-main'
				),
				'title' => array(
					'label'   => __( 'Title', 'md' ),
					'inherit' => 'text-main'
				),
				'title_link' => array(
					'label'   => __( 'Title Link', 'md' ),
					'inherit' => 'text-main'
				),
				'links' => array(
					'label'   => __( 'Links', 'md' ),
					'inherit' => 'muted'
				)
			)
		);
	}

}

new md_colors;