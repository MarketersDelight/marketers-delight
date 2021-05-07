<?php

/**
 * The one Walker to rule them all. This adds 2 arguments that lets
 * any menu using it determine whether or not to show the title and
 * description of each menu item.
 *
 * @since 4.0
 */
class md_menu_walker extends Walker_Nav_Menu
{
    function __construct($title = true, $desc = false)
    {
        $this->md_title = $title;
        $this->md_desc = $desc;
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        $class_names = '';
        $classes = empty($item->classes) ? array() : (array)$item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        $classes[] = !empty($item->description) ? 'menu-item-has-desc' : '';
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        $output .= $indent . '<li' . $id . $class_names . '>';
        $atts = array();
        $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';
        $atts['href'] = !empty($item->url) ? $item->url : '';
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        $desc = $this->md_desc && !empty($item->description) ? '<span class="menu-item-desc">' . esc_html($item->description) . '</span>' : '';
        $item_output = $args->before;
        $item_output .= '<a' . $attributes . ' itemprop="url">';
        $args->link_before = '<span class="menu-item-title" itemprop="name">';
        $args->link_after = '</span>';
        $item_output .= $this->md_title ? $args->link_before . md_text_field($item->title, $item->ID) . $args->link_after : '';
        $item_output .= $desc . '</a>';
        $item_output .= $args->after;
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

/**
 * Old menu Walker menu class name. Preserved for backwards compatibility.
 *
 * @since 4.5
 */
class md_main_menu_walker extends Walker_Nav_Menu
{
    function __construct($title = true, $desc = false)
    {
        new md_menu_walker($title, $desc);
    }
}