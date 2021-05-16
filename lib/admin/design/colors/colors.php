<?php
/**
 * Create Site Design admin page.
 *
 * @since 5.0
 */

class md_colors extends md_api
{

	/**
	 * Actions, filters, and properties.
	 *
	 * @since 5.0
	 */

	public function actions()
	{
		$this->design = new md_design;
		$this->defaults = $this->design->defaults();
		$this->values = $this->design->values();
	}

	/**
	 * Register admin page.
	 *
	 * @since 5.0
	 */

	public function register()
	{
		$fields = array();
		foreach ($this->options() as $group => $group_fields)
			foreach ($group_fields as $color => $label)
				$fields[$group][$color]['type'] = 'color';
		return array(
			'admin_page' => array(
				'name' => __('Colors', 'md'),
				'parent' => 'md_site_design',
				'fields' => array(
					'site' => array(
						'bg_color' => array('type' => 'color'),
						'links' => array('type' => 'color'),
						'primary' => array('type' => 'color'),
						'secondary' => array('type' => 'color'),
						'tertiary' => array('type' => 'color'),
						'action' => array('type' => 'color'),
						'accent' => array('type' => 'color'),
						'text' => array('type' => 'color'),
						'text-sec' => array('type' => 'color'),
						'headline' => array('type' => 'color'),
						'headline-links' => array('type' => 'color'),
						'button' => array('type' => 'color'),
						'button-text' => array('type' => 'color'),
						'button-sec' => array('type' => 'color'),
						'button-sec-text' => array('type' => 'color')
					),
					'header' => array(
						'bg_color' => array('type' => 'color'),
						'site_title' => array('type' => 'color'),
						'site_tagline' => array('type' => 'color'),
						'menu' => array(
							'links' => array('type' => 'color'),
							'hover' => array('type' => 'color'),
							'active' => array('type' => 'color')
						),
						'submenu' => array(
							'bg_color' => array('type' => 'color'),
							'links' => array('type' => 'color'),
							'hover' => array('type' => 'color')
						)
					),
					'main_menu' => array(
						'bg_color' => array('type' => 'color'),
						'links' => array('type' => 'color'),
						'active' => array('type' => 'color'),
						'subtext' => array('type' => 'color'),
						'sub_menu' => array('type' => 'color'),
						'icons' => array('type' => 'color'),
						'social' => array('type' => 'color')
					),
					'content' => array(
						'bg_color' => array('type' => 'color'),
						'border_color' => array('type' => 'color')
					),
					'sidebar' => array(
						'bg_color' => array('type' => 'color'),
						'text' => array('type' => 'color'),
						'title' => array('type' => 'color'),
						'links' => array('type' => 'color')
					),
					'footer' => array(
						'bg_color' => array('type' => 'color'),
						'text' => array('type' => 'color'),
						'title' => array('type' => 'color'),
						'links' => array('type' => 'color')
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

	public function options()
	{
		return array(
			'site' => array(
				'bg_color' => __('Background Color', 'md'),
				'primary' => __('Primary Color', 'md'),
				'secondary' => __('Secondary Color', 'md'),
				'tertiary' => __('Tertiary Color', 'md'),
				'action' => __('Action Color', 'md'),
				'accent' => __('Accent Color', 'md')
			),
			'text' => array(
				'text' => __('Text Color', 'md'),
				'text-sec' => __('Secondary Text Color', 'md'),
				'links' => __('Links Color', 'md'),
				'headline' => __('Headline Color', 'md'),
				'headline-links' => __('Headline Links Color', 'md')
			),
			'button' => array(
				'button' => __('Button Color', 'md'),
				'button-text' => __('Button Text Color', 'md'),
				'button-sec' => __('Secondary Button Color', 'md'),
				'button-sec-text' => __('Secondary Button Text Color', 'md')
			),
			'header' => array(
				'bg_color' => __('Background Color', 'md'),
				'site_title' => __('Site Title Color', 'md'),
				'site_tagline' => __('Site Tagline Color', 'md'),
				'menu' => array(
					'links' => __('Links Color', 'md'),
					'hover' => __('Hover Color', 'md'),
					'active' => __('Links Active Color', 'md')
				),
				'submenu' => array(
					'bg_color' => __('Background Color', 'md'),
					'links' => __('Links Color', 'md'),
					'hover' => __('Links Hover Color', 'md')
				)
			),
			'main_menu' => array(
				'bg_color' => __('Background Color', 'md'),
				'links' => __('Links Color', 'md'),
				'active' => __('Links Active Color', 'md'),
				'subtext' => __('Subtext Color', 'md'),
				'sub_menu' => __('Submenu Background Color', 'md'),
				'icons' => __('Icons Color', 'md'),
				'social' => __('Social Icons Color', 'md')
			),
			'content' => array(
				'bg_color' => __('Background Color', 'md'),
				'border_color' => __('Border Color', 'md')
			),
			'sidebar' => array(
				'bg_color' => __('Background Color', 'md'),
				'text' => __('Text Color', 'md'),
				'title' => __('Title Color', 'md'),
				'links' => __('Links Color', 'md')
			),
			'footer' => array(
				'bg_color' => __('Background Color', 'md'),
				'text' => __('Text Color', 'md'),
				'title' => __('Title Color', 'md'),
				'links' => __('Links Color', 'md')
			)
		);
	}

	/**
	 * Create admin settings fields.
	 *
	 * @since 5.0
	 */

	public function admin_page()
	{
		$options = $this->options();
		$defaults = $this->defaults['colors'];
		include('colors-settings.php');
	}

}

new md_colors;
