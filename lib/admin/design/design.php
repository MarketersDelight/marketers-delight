<?php
/**
 * Create Site Design admin page.
 *
 * @since 5.0
 */

class md_site_design extends md_api
{

	/**
	 * Include other design settings pages.
	 *
	 * @since 5.0
	 */

	public function includes()
	{
		if (is_admin()) {
			foreach (array('colors', 'typography', 'icons', 'layout', 'header', 'content', 'loop', 'sidebars') as $file)
				require_once("{$file}/{$file}.php");
		}
	}

	/**
	 * Until Site Design dashboard is made, redirect to Colors.
	 *
	 * @since 5.0
	 */

	public function actions()
	{
		if (isset($_GET['page']) && !isset($_GET['tab']) && $_GET['page'] == $this->_id)
			add_action('admin_init', array($this, 'admin_init'));
	}

	public function admin_init()
	{
		wp_redirect(admin_url("admin.php?page={$this->_id}&tab=md_colors"));
		exit;
	}

	/**
	 * Register admin page.
	 *
	 * @since 5.0
	 */

	public function register()
	{
		return array(
			'admin_page' => array(
				'name' => __('Site Design', 'md'),
				'admin_header' => true
			)
		);
	}

}

new md_site_design;
