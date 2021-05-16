<?php
/**
 * Create Header Options settings page.
 *
 * @since 5.0
 */

class md_header extends md_api
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
	}

	/**
	 * Create admin page with registered fields.
	 *
	 * @since 5.0
	 */

	public function register()
	{
		return array(
			'admin_page' => array(
				'name' => __('Header', 'md'),
				'parent' => 'md_site_design',
				'fields' => array(
					'logo' => array(
						'type' => 'upload',
						'upload_type' => 'media'
					),
					'logo_alt' => array(
						'type' => 'upload',
						'upload_type' => 'media'
					),
					'logo_width' => array(
						'desktop' => array('type' => 'range'),
						'tablet' => array('type' => 'range'),
						'mobile' => array('type' => 'range')
					),
					'display' => array(
						'type' => 'checkbox',
						'options' => array('site_title', 'site_tagline')
					),
					'spacing_top' => array(
						'desktop' => array('type' => 'range'),
						'tablet' => array('type' => 'range'),
						'mobile' => array('type' => 'range')
					),
					'spacing_bottom' => array(
						'desktop' => array('type' => 'range'),
						'tablet' => array('type' => 'range'),
						'mobile' => array('type' => 'range')
					),
					'menu' => array(
						'spacing_lr' => array('type' => 'range'),
					),
					'main_menu' => array(
						'spacing_tb' => array('type' => 'range'),
						'spacing_lr' => array('type' => 'range'),
						'disable' => array(
							'type' => 'checkbox',
							'options' => array('search')
						)
					)
				)
			)
		);
	}

	/**
	 * Build Layout admin page fields.
	 *
	 * @since 5.0
	 */

	public function admin_page()
	{
		$defaults = $this->defaults['header'];
		include('header-settings.php');
	}

}

new md_header;
