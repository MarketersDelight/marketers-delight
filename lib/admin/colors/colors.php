<?php
/**
 * Create Site Design admin page.
 *
 * @since 5.0
 */

class md_colors extends md_api {

	/**
	 * Actions, filters, and properties.
	 *
	 * @since 5.0
	 */

	public function actions() {
		$this->design = new md_design;
		$this->defaults = $this->design->defaults();
		$this->values = $this->design->values();
	}

	/**
	 * Register admin page.
	 *
	 * @since 5.0
	 */

	public function register() {
		$fields = array();
		foreach ( $this->options() as $group => $group_fields )
			foreach ( $group_fields as $color => $label )
				$fields[$group][$color]['type'] = 'color';
		return array(
			'admin_page' => array(
				'name' => __( 'Colors', 'md' ),
				'parent' => 'md_settings',
				'fields' => array(
					'site' => array(
						'bg_color' => array( 'type' => 'color' ),
						'links' => array( 'type' => 'color' ),
						'primary' => array( 'type' => 'color' ),
						'secondary' => array( 'type' => 'color' ),
						'tertiary' => array( 'type' => 'color' ),
						'action' => array( 'type' => 'color' ),
						'accent' => array( 'type' => 'color' ),
						'text' => array( 'type' => 'color' ),
						'text-sec' => array( 'type' => 'color' ),
						'headline' => array( 'type' => 'color' ),
						'headline-links' => array( 'type' => 'color' ),
						'button' => array( 'type' => 'color' ),
						'button-text' => array( 'type' => 'color' ),
						'button-sec' => array( 'type' => 'color' ),
						'button-sec-text' => array( 'type' => 'color' )
					),
					'header' => array(
						'bg_color' => array( 'type' => 'color' ),
						'color' => array( 'type' => 'color' ),
						'site_title' => array( 'type' => 'color' ),
						'site_tagline' => array( 'type' => 'color' ),
						'menu' => array(
							'links' => array( 'type' => 'color' ),
							'hover' => array( 'type' => 'color' ),
							'active' => array( 'type' => 'color' )
						),
						'submenu' => array(
							'bg_color' => array( 'type' => 'color' ),
							'links' => array( 'type' => 'color' ),
							'hover' => array( 'type' => 'color' )
						)
					),
					'main_menu' => array(
						'bg_color' => array( 'type' => 'color' ),
						'links' => array( 'type' => 'color' ),
						'links_hover' => array( 'type' => 'color' ),
						'active' => array( 'type' => 'color' ),
						'subtext' => array( 'type' => 'color' ),
						'sub_menu' => array( 'type' => 'color' ),
						'icons' => array( 'type' => 'color' ),
						'social' => array( 'type' => 'color' )
					),
					'content' => array(
						'bg_color' => array( 'type' => 'color' ),
						'border_color' => array( 'type' => 'color' )
					),
					'sidebar' => array(
						'bg_color' => array( 'type' => 'color' ),
						'text' => array( 'type' => 'color' ),
						'title' => array( 'type' => 'color' ),
						'links' => array( 'type' => 'color' )
					),
					'footer' => array(
						'bg_color' => array( 'type' => 'color' ),
						'text' => array( 'type' => 'color' ),
						'title' => array( 'type' => 'color' ),
						'links' => array( 'type' => 'color' )
					)
				)
			)
		);
	}

	/**
	 * Organize options with labels.
	 *
	 * @since 5.0
	 */

	public function options() {
		return array(
			'site' => array(
				'bg_color' => __( 'Background', 'md' ),
				'primary' => __( 'Primary', 'md' ),
				'secondary' => __( 'Secondary', 'md' ),
				'tertiary' => __( 'Tertiary', 'md' ),
				'action' => __( 'Action', 'md' ),
				'accent' => __( 'Accent', 'md' )
			),
			'text' => array(
				'text' => __( 'Text', 'md' ),
				'text-sec' => __( 'Secondary Text', 'md' ),
				'links' => __( 'Links', 'md' ),
				'headline' => __( 'Headline', 'md' ),
				'headline-links' => __( 'Headline Links', 'md' )
			),
			'button' => array(
				'button' => __( 'Button', 'md' ),
				'button-text' => __( 'Button Text', 'md' ),
				'button-sec' => __( 'Secondary Button', 'md' ),
				'button-sec-text' => __( 'Secondary Button Text', 'md' )
			),
			'header' => array(
				'bg_color' => __( 'Background', 'md' ),
				'color' => __( 'Text Color', 'md' ),
				'site_title' => __( 'Site Title', 'md' ),
				'site_tagline' => __( 'Site Tagline', 'md' ),
				'menu' => array(
					'links' => __( 'Links', 'md' ),
					'hover' => __( 'Hover', 'md' ),
					'active' => __( 'Links Active', 'md' )
				),
				'submenu' => array(
					'bg_color' => __( 'Background', 'md' ),
					'links' => __( 'Links', 'md' ),
					'hover' => __( 'Links Hover', 'md' )
				)
			),
			'main_menu' => array(
				'bg_color' => __( 'Background', 'md' ),
				'subtext' => __( 'Text', 'md' ),
				'links' => __( 'Links', 'md' ),
				'links_hover' => __( 'Links Hover', 'md' ),
				'active' => __( 'Links Active', 'md' ),
				'sub_menu' => __( 'Sub menu', 'md' ),
				'icons' => __( 'Icons color', 'md' ),
				'social' => __( 'Social Icons', 'md' )
			),
			'content' => array(
				'bg_color' => __( 'Background', 'md' ),
				'border_color' => __( 'Border', 'md' )
			),
			'sidebar' => array(
				'bg_color' => __( 'Background', 'md' ),
				'text' => __( 'Text', 'md' ),
				'title' => __( 'Title', 'md' ),
				'links' => __( 'Links', 'md' )
			),
			'footer' => array(
				'bg_color' => __( 'Background', 'md' ),
				'text' => __( 'Text', 'md' ),
				'title' => __( 'Title', 'md' ),
				'links' => __( 'Links', 'md' )
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
		$defaults = $this->defaults['colors'];
		include( 'colors-settings.php' );
	}

}

new md_colors;