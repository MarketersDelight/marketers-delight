<?php

class md_email_form extends WP_Widget
{

	public function __construct()
	{
		parent::__construct('md_email', __('MD &rarr; Email Form', 'md'), array(
			'description' => __('Easily place email signup forms with AWeber, MailChimp, or your own custom code.', 'md'),
			'customize_selective_refresh' => true
		));
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue'));
	}

	public function admin_enqueue($hook)
	{
		if ($hook == 'widgets.php' || is_customize_preview()) {
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('wp-color-picker');
		}
	}

	public function widget($args, $val)
	{
		$fields = null;
		$fields['email_title'] = $val['title'];
		$fields['email_desc'] = $val['desc'];
		$fields['email_list'] = !empty($val['list']) ? $val['list'] : md_setting(array('optins', 'cta', 'email_list'));
		$fields['email_input']['name'] = $val['form_fields_name'];
		$fields['email_name_label'] = $val['name_label'];
		$fields['email_email_label'] = $val['email_label'];
		$fields['email_submit_text'] = $val['submit_text'];
		$fields['email_form_title'] = $val['email_form_title'];
		$fields['email_form_footer'] = $val['email_form_footer'];
		$fields['email_thank_you'] = !empty($val['email_thank_you']) ? $val['email_thank_you'] : '';
		$fields['email_classes'] = $val['classes'];
		$fields['email_image'] = $val['image'];
		$fields['email_bg_color'] = $val['bg_color'];
		$fields['email_text_color'] = !empty($val['text_color']) ? $val['text_color'] : '';
		$fields['email_form_style']['attached'] = $val['form_style_attached'];
		$fields['email_code'] = $val['custom_code'];
		$atts = array(
			'before_title' => $args['before_title'],
			'after_title' => $args['after_title']
		);
		echo $args['before_widget'];
		md_email_form($fields, $atts);
		echo $args['after_widget'];
	}

	public function update($new, $val)
	{
		$sanitize = new md_sanitize;
		foreach (array('title', 'desc', 'name_label', 'email_label', 'submit_text', 'email_form_title', 'email_form_footer') as $text)
			$val[$text] = $sanitize->text($new[$text]);
		$val['list'] = $sanitize->select($new['list'], md_email_data(array('show' => 'ids')));
		foreach (array('form_fields_name', 'form_style_attached') as $check)
			$val[$check] = !empty($new[$check]) ? true : false;
		$val['email_thank_you'] = !empty($new['email_thank_you']) ? $sanitize->url($new['email_thank_you']) : '';
		$val['image'] = !empty($new['image']) ? esc_url($new['image']) : '';
		$val['bg_color'] = $sanitize->color($new['bg_color']);
		$val['text_color'] = $sanitize->color($new['text_color']);
		$val['classes'] = strip_tags($new['classes']);
		$val['custom_code'] = !empty($new['custom_code']) ? $new['custom_code'] : '';
		return $val;
	}

	public function form($val)
	{
		$val = wp_parse_args((array)$val, array(
			'title' => '',
			'desc' => '',
			'list' => '',
			'form_fields_name' => '',
			'name_label' => '',
			'email_label' => '',
			'submit_text' => '',
			'form_style_attached' => '',
			'email_form_title' => '',
			'email_form_footer' => '',
			'email_thank_you' => '',
			'custom_code' => '',
			'image' => '',
			'bg_color' => '',
			'text_color' => '',
			'classes' => ''
		));
		$data = md_setting(array('integrations'));
		$email_data = md_email_data();
		echo '<div class="md">';
		include('templates/email-form.php');
		echo '</div>';
		wp_enqueue_media();
	}
}
