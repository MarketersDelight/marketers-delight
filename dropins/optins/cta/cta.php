<?php
/**
 * Create Call to Action admin panel.
 *
 * @since 4.1
 */

class md_cta extends md_api
{

	public $dir = 'dropins/optins';

	/**
	 * Include files for Floating Bars.
	 *
	 * @since 5.0
	 */

	public function includes()
	{
		require_once('data.php');
	}

	/**
	 * Set properties.
	 *
	 * @since 5.0
	 */

	public function actions()
	{
		$this->data = new md_cta_data;
	}

	/**
	 * Pesuedo constructor, registers the admin page.
	 *
	 * @since 4.1
	 */

	public function register()
	{
		return array(
			'admin_page' => array(
				'name' => __('Call to Action', 'md'),
				'parent' => 'md_optins',
				'fields' => array(
					'email_list' => array(
						'type' => 'select',
						'options' => md_email_data(array('show' => 'ids'))
					),
					'forms' => array(
						'type' => 'group',
						'fields' => $this->data->fields()
					)
				)
			)
		);
	}

	/**
	 * Build out the admin page and include fields.
	 *
	 * @since 4.1
	 */

	public function admin_page()
	{
		include(md_template($this->dir, 'cta/admin/cta-settings', true));
	}

	/**
	 * Individual Email Form admin template.
	 *
	 * @since 5.0
	 */

	public function fields($group, $field)
	{
		$screen = get_current_screen();
		$colors = $this->data->colors();
		$cta_type = md_setting(array('cta', 'forms', $field, 'cta_type'));
		$button_type = md_setting(array('cta', 'forms', $field, 'button_type'));
		$data = md_setting('integrations');
		$email_data = md_email_data();
		include(md_template($this->dir, 'cta/admin/cta-fields', true));
	}

	/**
	 * Email admin page scripts.
	 *
	 * @since 5.0
	 */

	public function admin_scripts()
	{
		$this->data->admin_scripts();
	}

	/**
	 * Load Email Forms across site.
	 *
	 * @since 5.0
	 */

	public function template()
	{
		$hooks = array(
			'before_content_box' => 'md_hook_before_content_box',
			'before_html' => 'md_hook_before_html',
			'before_content' => 'md_hook_before_content',
			'before_sidebar' => 'md_hook_before_sidebar',
			'after_sidebar' => 'md_hook_after_sidebar',
			'content' => 'md_hook_content',
			'before_footer' => 'md_hook_before_footer'
		);
		$cta_forms = $this->cta_forms();
		foreach ($cta_forms as $position => $forms) {
			foreach ($forms as $id => $fields) {
				if ($this->has_cta($fields)) {
					$order = 10;
					if (is_singular() && $position == 'content') {
						$hook = 'md_hook_content_item';
						$order = 45;
					} else
						$hook = $hooks[$position];
					add_action($hook, array($this, 'cta_form'), $order);
				}
			}
		}
		// Insert CTA after X loop item
		if (is_home() || is_category() || is_tax())
			add_action('md_hook_x_loop', array($this, 'cta_x_loop'));
	}

	/**
	 * Get list of email forms by location to load throughout site.
	 * Need to refactor this, insane.
	 *
	 * @since 5.0
	 */

	public function cta_forms($group = null)
	{
		$forms = array();
		$cta_forms = md_setting(array('cta', 'forms'));
		$cta_remove = md_meta(array('optins', 'cta_remove'), true);
		$remove = !empty($cta_remove) ? array_keys($cta_remove) : array();
		$cta_add = md_meta(array('optins', 'cta'), true);
		$add = !empty($cta_add) ? $cta_add : array();

		if ($add)
			foreach ($add as $id => $fields) {
				$position = !empty($fields['position']) ? $fields['position'] : 'before_content_box';
				$forms[$position][$id] = array(
					'title' => md_meta(array('optins', 'cta', $id, 'title')),
					'title_html' => md_meta(array('optins', 'cta', $id, 'title_html')),
					'text' => md_meta(array('optins', 'cta', $id, 'text')),
					'image' => md_meta(array('optins', 'cta', $id, 'image')),
					'image_alignment' => md_meta(array('optins', 'cta', $id, 'image_alignment')),
					'image_width' => md_meta(array('optins', 'cta', $id, 'image_width')),
					'image_classes' => md_meta(array('optins', 'cta', $id, 'image_classes')),
					'cta_type' => md_meta(array('optins', 'cta', $id, 'cta_type')),
					'button_type' => md_meta(array('optins', 'cta', $id, 'button_type')),
					'button_text' => md_meta(array('optins', 'cta', $id, 'button_text')),
					'button_url' => md_meta(array('optins', 'cta', $id, 'button_url')),
					'button_popup' => md_meta(array('optins', 'cta', $id, 'button_popup')),
					'email_list' => md_meta(array('optins', 'cta', $id, 'email_list')),
					'email_custom' => md_meta(array('optins', 'cta', $id, 'email_custom')),
					'attached' => md_meta(array('optins', 'cta', $id, 'email_form_style', 'attached')),
					'name' => md_meta(array('optins', 'cta', $id, 'email_input', 'name')),
					'email_name_label' => md_meta(array('optins', 'cta', $id, 'email_name_label')),
					'email_email_label' => md_meta(array('optins', 'cta', $id, 'email_email_label')),
					'email_submit_text' => md_meta(array('optins', 'cta', $id, 'email_submit_text')),
					'email_form_footer' => md_meta(array('optins', 'cta', $id, 'email_form_footer')),
					'email_thank_you' => md_meta(array('optins', 'cta', $id, 'email_thank_you')),
					'position' => md_meta(array('optins', 'cta', $id, 'position')),
					'bg_color' => md_meta(array('optins', 'cta', $id, 'bg_color')),
					'text_color' => md_meta(array('optins', 'cta', $id, 'text_color')),
					'text_sec_color' => md_meta(array('optins', 'cta', $id, 'text_sec_color')),
					'button_color' => md_meta(array('optins', 'cta', $id, 'button_color')),
					'button_text_color' => md_meta(array('optins', 'cta', $id, 'button_text_color')),
					'bg_image' => md_meta(array('optins', 'cta', $id, 'bg_image')),
					'bg_image_style' => md_meta(array('optins', 'cta', $id, 'bg_image_style', 'repeat')),
					'classes' => md_meta(array('optins', 'cta', $id, 'classes')),
					'html' => md_meta(array('optins', 'cta', $id, 'html')),
					'html_format' => md_meta(array('optins', 'cta', $id, 'html_format'))
				);
				$forms[$position][$id]['locations']['current'] = true;
			}

		if (!empty($cta_forms))
			foreach ($cta_forms as $id => $form) {
				if (in_array($id, $remove) || !$this->has_cta($form))
					continue;
				$position = !empty($form['position']) ? $form['position'] : 'before_content_box';
				$forms[$position][$id] = $this->cta_settings($id);
			}

		if (isset($group))
			return $forms[$group];

		return $forms;
	}

	/**
	 * A comprehensive check to see if current page has email form.
	 *
	 * @since 5.0
	 */

	public function has_cta($fields)
	{
		$post_type = get_post_type();
		$locations = !empty($fields['locations']) ? array_keys($fields['locations']) : array();
		$rules = !empty($fields['rules']) ? $fields['rules'] : '';

		if (
			(empty($rules) || ($rules == 'logged_in' && is_user_logged_in()) || ($rules == 'logged_out' && !is_user_logged_in())) &&
			(
				!empty($fields['locations']['sitewide']) ||
				!empty($fields['locations']['current']) ||
				(is_front_page() && !empty($fields['locations']['front'])) ||
				(is_home() && !empty($fields['locations']['home'])) ||
				(is_singular('post') && !empty($fields['locations']['post'])) ||
				(is_category() && !empty($fields['locations']['category'])) ||
				(is_page() && !empty($fields['locations']['page'])) ||
				(is_author() && !empty($fields['locations']['author'])) ||
				(is_search() && !empty($fields['locations']['search'])) ||
				(
					(is_post_type_archive($post_type) && !empty($fields['locations']["{$post_type}_archive"])) ||
					(is_singular($post_type) && !empty($fields['locations']["{$post_type}_single"])) ||
					(is_tax(get_query_var('taxonomy')) && in_array(get_query_var('taxonomy'), $locations))
				)
			)
		)
			return true;
	}

	/**
	 * Return list of fields for global CTA settings by ID.
	 *
	 * @since 5.1
	 */
	public function cta_settings($id)
	{
		return array(
			'title' => md_setting(array('cta', 'forms', $id, 'title')),
			'title_html' => md_setting(array('cta', 'forms', $id, 'title_html')),
			'text' => md_setting(array('cta', 'forms', $id, 'text')),
			'image' => md_setting(array('cta', 'forms', $id, 'image')),
			'image_alignment' => md_setting(array('cta', 'forms', $id, 'image_alignment')),
			'image_width' => md_setting(array('cta', 'forms', $id, 'image_width')),
			'image_classes' => md_setting(array('cta', 'forms', $id, 'image_classes')),
			'cta_type' => md_setting(array('cta', 'forms', $id, 'cta_type')),
			'button_type' => md_setting(array('cta', 'forms', $id, 'button_type')),
			'button_text' => md_setting(array('cta', 'forms', $id, 'button_text')),
			'button_url' => md_setting(array('cta', 'forms', $id, 'button_url')),
			'button_popup' => md_setting(array('cta', 'forms', $id, 'button_popup')),
			'email_list' => md_setting(array('cta', 'forms', $id, 'email_list')),
			'email_custom' => md_setting(array('cta', 'forms', $id, 'email_custom')),
			'attached' => md_setting(array('cta', 'forms', $id, 'email_form_style', 'attached')),
			'name' => md_setting(array('cta', 'forms', $id, 'email_input', 'name')),
			'email_name_label' => md_setting(array('cta', 'forms', $id, 'email_name_label')),
			'email_email_label' => md_setting(array('cta', 'forms', $id, 'email_email_label')),
			'email_submit_text' => md_setting(array('cta', 'forms', $id, 'email_submit_text')),
			'email_form_footer' => md_setting(array('cta', 'forms', $id, 'email_form_footer')),
			'email_thank_you' => md_setting(array('cta', 'forms', $id, 'email_thank_you')),
			'locations' => md_setting(array('cta', 'forms', $id, 'locations')),
			'position' => md_setting(array('cta', 'forms', $id, 'position')),
			'rules' => md_setting(array('cta', 'forms', $id, 'rules')),
			'bg_color' => md_setting(array('cta', 'forms', $id, 'bg_color')),
			'text_color' => md_setting(array('cta', 'forms', $id, 'text_color')),
			'text_sec_color' => md_setting(array('cta', 'forms', $id, 'text_sec_color')),
			'button_color' => md_setting(array('cta', 'forms', $id, 'button_color')),
			'button_text_color' => md_setting(array('cta', 'forms', $id, 'button_text_color')),
			'bg_image' => md_setting(array('cta', 'forms', $id, 'bg_image')),
			'bg_image_style' => md_setting(array('cta', 'forms', $id, 'bg_image_style', 'repeat')),
			'classes' => md_setting(array('cta', 'forms', $id, 'classes')),
			'html' => md_setting(array('cta', 'forms', $id, 'html')),
			'html_format' => md_setting(array('cta', 'forms', $id, 'html_format'))
		);
	}

	/**
	 * Fire CTAs to various parts of a page.
	 *
	 * @since 5.0
	 */

	public function cta_form($position)
	{
		$forms = $this->cta_forms($position);
		foreach ($forms as $id => $fields)
			$this->cta_template($fields, $position);
	}

	/**
	 * Build CTA output with proper data passed to template.
	 *
	 * @since 5.1
	 */

	public function cta_template($fields, $position)
	{
		$classes = array();
		$text_classes = '';
		$colors = $this->data->colors();
		if (in_array($position, array('before_content', 'content'))) {
			$layout = 'slim';
			$title_classes = 'med-title';
			if (!is_singular())
				$classes[] = 'post-box';
		} elseif (in_array($position, array('before_sidebar', 'after_sidebar'))) {
			$classes[] = 'mb-single';
			$layout = 'small';
			$title_classes = 'small-title';
		} else {
			$layout = 'full';
			$title_classes = 'large-title';
			$text_classes = 'micro-text';
		}
		$classes[] = "cta-$layout";
		if (!empty($fields['classes']))
			$classes[] = $fields['classes'];
		$classes = join(' ', $classes);
		$image_classes = !empty($fields['image_classes']) ? $fields['image_classes'] : '';
		$title_html = !empty($fields['title_html']) ? $fields['title_html'] : 'p';
		$style = array(
			'bg_color' => !empty($fields['bg_color']) ? $fields['bg_color'] : $colors['bg_color']['color'],
			'bg_image' => !empty($fields['bg_image']['url']) ? $fields['bg_image']['url'] : '',
			'bg_size' => !empty($fields['bg_image_style']) ? 'auto' : '',
			'color' => !empty($fields['text_color']) ? $fields['text_color'] : $colors['text_color']['color']
		);
		include(md_template($this->dir, 'cta/cta', true));
	}

	/**
	 * Call template for CTA to show after X loop item.
	 *
	 * @since 5.1
	 */

	public function cta_x_loop()
	{
		$id = md_get_loop(array('loop', 'x_cta'));
		$fields = $this->cta_settings($id);
		$this->cta_template($fields, 'content');
	}

}

new md_cta;
