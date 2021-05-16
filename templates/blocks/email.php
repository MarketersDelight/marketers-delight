<?php
$atts = $classes = $submit = array();
$email_list = md_block_field($attributes, 'emailList');
$align = md_block_field($attributes, 'align');
$bg_color = md_block_field($attributes, 'bgColor');
$bg_color_class = md_block_field($attributes, 'bgColorClass');
$text_color = md_block_field($attributes, 'textColor');
$text_color_class = md_block_field($attributes, 'textColorClass');
$submit_bg_color = md_block_field($attributes, 'buttonColor');
$submit_bg_color_class = md_block_field($attributes, 'buttonColorClass');
$submit_color = md_block_field($attributes, 'buttonTextColor');
$submit_color_class = md_block_field($attributes, 'buttonTextColorClass');
$marginBottom = md_block_field($attributes, 'marginBottom');
$padding = md_block_field($attributes, 'padding');
$text_align = md_block_field($attributes, 'alignment');
$has_bg_color = $bg_color || $bg_color_class ? true : false;
$has_text_color = $text_color || $text_color_class ? true : false;
$classes[] = 'email-block';
$classes[] = $bg_color_class;
$classes[] = md_block_field($attributes, 'className');
$classes[] = md_block_field($attributes, 'shadow') ? 'shadow' : '';
$classes[] = 'text-' . esc_attr($text_align);
$submit[] = $submit_bg_color_class;
$submit[] = $submit_color_class;
$classes[] = $marginBottom ? $marginBottom : 'mb-mid';
if ($has_bg_color || $has_text_color)
	$classes[] = $padding ? $padding : 'block-single';
if (!empty($text_color) && empty($text_color_class))
	$atts['email_text_color'] = $text_color;
if (!empty($text_color_class))
	$classes[] = $text_color_class;
if (!empty($submit_bg_color) && empty($submit_bg_color_class))
	$atts['email_submit_bg_color'] = $submit_bg_color;
if (!empty($submit_color) && empty($submit_color_class))
	$atts['email_submit_color'] = $submit_color;
if ($align)
	$classes[] = "align{$align}";
md_email_form(array(
	'email_title' => md_block_field($attributes, 'title'),
	'email_desc' => md_block_field($attributes, 'text'),
	'email_list' => !empty($email_list) ? $email_list : md_setting(array('optins', 'cta', 'email_list')),
	'email_input' => array(
		'name' => md_block_field($attributes, 'name')
	),
	'email_name_label' => md_block_field($attributes, 'namePlaceholder'),
	'email_email_label' => md_block_field($attributes, 'emailPlaceholder'),
	'email_submit_text' => md_block_field($attributes, 'submitText'),
	'email_form_footer' => md_block_field($attributes, 'footerText'),
	'email_thank_you' => md_block_field($attributes, 'thankYou'),
	'email_form_style' => array(
		'attached' => md_block_field($attributes, 'attached')
	),
	'email_image' => md_block_field($attributes, 'mediaURL'),
	'email_bg_color' => empty($bg_color_class) ? $bg_color : '',
	'email_text_color' => $text_color,
	'email_submit_classes' => join(' ', $submit),
	'email_classes' => join(' ', $classes)
), $atts);
