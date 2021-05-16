<?php

class md_text_image extends WP_Widget
{

	public $_allowed_html = array(
		'span' => array(
			'class' => array(),
			'id' => array(),
		),
		'br' => array(),
		'b' => array(),
		'i' => array()
	);

	public function __construct()
	{
		parent::__construct('md_text_image', __('MD &rarr; Text and Image', 'md'), array(
			'description' => __('Create a Text widget with a small right-aligned image as well as a button.', 'md'),
			'customize_selective_refresh' => true
		));
		add_action('admin_enqueue_scripts', array($this, 'scripts'));
	}

	public function scripts($hook)
	{
		if ($hook == 'widgets.php' || is_customize_preview())
			wp_enqueue_media();
	}

	public function widget($args, $val)
	{
		$title = $val['title'];
		$desc = apply_filters('widget_text', $val['desc']);
		$image = $val['image'];
		$button_text = $val['button_text'];
		$button_link = $val['button_link'];
		$button_html = !empty($button_link) ? 'a href="' . esc_url($button_link) . '"' : 'span';
		$button_html_c = !empty($button_link) ? 'a' : 'span';
		include(md_template('widgets/text-image', true));
	}

	public function update($new, $val)
	{
		$val['title'] = wp_kses($new['title'], $this->_allowed_html);
		$val['desc'] = $new['desc'];
		$val['image'] = !empty($new['image']) ? strip_tags($new['image']) : '';
		$val['button_text'] = strip_tags($new['button_text']);
		$val['button_link'] = esc_url($new['button_link']);

		return $val;
	}

	public function form($val)
	{
		$val = wp_parse_args((array)$val, array(
			'title' => '',
			'desc' => '',
			'image' => '',
			'button_text' => '',
			'button_link' => ''
		));

		$display = !empty($val['image']) ? 'block' : 'none';
		include('templates/text-image.php');
	}
}
