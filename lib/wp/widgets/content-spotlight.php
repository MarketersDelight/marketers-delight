<?php

class md_content_spotlight extends WP_Widget
{

    public function __construct()
    {
        parent::__construct('md_content_spotlight', __('MD &rarr; Content Spotlight', 'md'), array(
            'description' => __('Create a nicely designed image banner and link to any page on your site.', 'md'),
            'customize_selective_refresh' => true
        ));
    }

    public function widget($args, $val)
    {
        $widget_title = !empty($val['widget_title']) ? $val['widget_title'] : '';
        $title = $val['title'];
        $intro = $val['intro'];
        $link = $val['link'];
        $link_new = !empty($val['link_new']) ? $val['link_new'] : '';
        $image = $val['image'];
        $html = !empty($link) ? 'a href="' . esc_url($link) . '"' . (!empty($link_new) ? ' target="_blank"' : '') : 'div';
        $html_c = !empty($link) ? 'a' : 'div';
        $src = !empty($image) ? " style=\"background-image: url('$image');\"" : '';
        $image_classes = !empty($image) ? 'image-overlay' : '';
        if (!$intro && !$title && !$link && !$image)
            return false;
        include(md_template('widgets/content-spotlight', true));
    }

    public function update($new, $val)
    {
        $sanitize = new md_sanitize;
        $val['title'] = $sanitize->text($new['title']);
        $val['widget_title'] = $sanitize->text($new['widget_title']);
        $val['intro'] = $sanitize->text($new['intro']);
        $val['link'] = $sanitize->url($new['link']);
        $val['image'] = esc_url($new['image']);
        if (isset($new['link_new']))
            $val['link_new'] = $sanitize->checkbox($new['link_new']);
        return $val;
    }

    public function form($val)
    {
        $val = wp_parse_args((array)$val, array(
            'widget_title' => '',
            'intro' => '',
            'title' => '',
            'link' => '',
            'link_new' => '',
            'image' => ''
        ));
        $display = (!empty($val['image']) ? 'block' : 'none');
        include('templates/content-spotlight.php');
    }

}