<?php
/**
 * Create Floating Bars admin page.
 *
 * @since 5.0
 */

class md_floating_bars extends md_api
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
        $this->data = new md_floating_bars_data;
        add_action('md_hook_before_footer', array($this, 'load_template'));
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
                'name' => __('Floating Bars', 'md'),
                'parent' => 'md_optins',
                'fields' => array(
                    'bars' => array(
                        'type' => 'group',
                        'fields' => $this->data->fields()
                    )
                )
            )
        );
    }

    /**
     * Admin page template.
     *
     * @since 5.0
     */

    public function admin_page()
    {
        include(md_template($this->dir, 'floating-bars/admin/floating-bars-settings', true));
    }

    /**
     * Individual Floating Bar fields template.
     *
     * @since 5.0
     */

    public function fields($group, $field)
    {
        $screen = get_current_screen();
        $media_type = md_setting(array('floating_bars', 'bars', $field, 'media_type'));
        $cta_type = md_setting(array('floating_bars', 'bars', $field, 'cta_type'));
        $button_type = md_setting(array('floating_bars', 'bars', $field, 'button_type'));
        $position = md_setting(array('floating_bars', 'bars', $field, 'position'));
        $show = md_setting(array('floating_bars', 'bars', $field, 'show'));
        $colors = $this->data->colors();
        $icons = md_get_icons('options', null, 'md-icon-');
        include(md_template($this->dir, 'floating-bars/admin/floating-bar-fields', true));
    }

    /**
     * Floating Bar admin page scripts.
     *
     * @since 5.0
     */

    public function admin_scripts()
    {
        $this->data->admin_scripts();
    }

    /**
     * Load Floating Bar template across site.
     *
     * @since 5.0
     */

    public function load_template()
    {
        $js = array();
        $post_type = get_post_type();
        $floating_bars = md_setting(array('floating_bars', 'bars'));
        $floating_bars = !empty($floating_bars) ? $floating_bars : array();
        $floating_bars_remove = md_meta(array('optins', 'floating_bars_remove'), true);
        $remove = !empty($floating_bars_remove) ? array_keys($floating_bars_remove) : array();
        $floating_bars_add = md_meta(array('optins', 'floating_bars'), true);
        $add = !empty($floating_bars_add) ? $floating_bars_add : array();

        if ($remove)
            foreach ($remove as $id)
                unset($floating_bars[$id]);

        if ($add)
            foreach ($add as $group => $fields)
                $add[$group]['locations']['current'] = true;

        $floating_bars = array_merge($floating_bars, $add);

        foreach ($floating_bars as $id => $fields) {
            $locations = !empty($fields['locations']) ? array_keys($fields['locations']) : array();
            $fields['position'] = !empty($fields['position']) ? $fields['position'] : '';
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
            ) {
                if ((!empty($fields['display']['close']) && empty($_COOKIE["cta_bar_$id"])) || empty($fields['display']['close'])) {
                    $this->floating_bar($id, $fields);
                    if ($fields['position'] !== 'before_footer') {
                        $js["cta_bar_$id"]['id'] = "cta_bar_$id";
                        $js["cta_bar_$id"]['show'] = !empty($fields['show']) ? $fields['show'] : 'seconds';
                        $js["cta_bar_$id"]['delay'] = isset($fields['delay']) ? $fields['delay'] : 5;
                        if (in_array($fields['position'], array('top_static', 'top_floating')))
                            $js["cta_bar_$id"]['position'] = 'top';
                        if (!empty($fields['display']['close']))
                            $js["cta_bar_$id"]['cookieExp'] = isset($fields['cookie']) ? $fields['cookie'] : '0';
                    }
                }
            }
        }
        if (!empty($js))
            wp_add_inline_script('marketers-delight', "\tMD.floatingBars.init({" . md_js_object($js) . "});");
    }

    /**
     * Floating Bar frontend template.
     *
     * @since 5.0
     */

    public function floating_bar($id, $fields)
    {
        $colors = array(
            'bg_color' => !empty($fields['bg_color']) ? $fields['bg_color'] : '',
            'text_color' => !empty($fields['text_color']) ? $fields['text_color'] : '',
            'text_sec_color' => !empty($fields['text_sec_color']) ? $fields['text_sec_color'] : '',
            'icon_color' => !empty($fields['icon_color']) ? $fields['icon_color'] : '',
            'icon_text_color' => !empty($fields['icon_text_color']) ? $fields['icon_text_color'] : '',
            'button_color' => !empty($fields['button_color']) ? $fields['button_color'] : '',
            'button_text_color' => !empty($fields['button_text_color']) ? $fields['button_text_color'] : ''
        );
        $cta_type = !empty($fields['cta_type']) ? $fields['cta_type'] : '';
        $media_type = !empty($fields['media_type']) ? $fields['media_type'] : '';
        $position = !empty($fields['position']) ? $fields['position'] : '';
        $button_text = !empty($fields['button_text']) ? $fields['button_text'] : __('Get Access Now', 'md');
        $classes[] = 'cta-bar';
        if (!empty($fields['classes']))
            $classes[] = $fields['classes'];
        if (!empty($fields['layout']['full_width']))
            $classes[] = 'cta-bar-full';
        $classes[] = !empty($cta_type) ? 'cta-bar-columns' : 'cta-bar-single';
        if (in_array($fields['position'], array('', 'top_floating')))
            $classes[] = 'floating';
        if (in_array($fields['position'], array('top_floating', 'top_static')))
            $classes[] = 'top';
        if ($fields['position'] == 'top_static')
            $classes[] = 'static';
        $classes = join(' ', $classes);
        $image_classes = '';
        if ($media_type == 'image') {
            if (!empty($fields['image_style']['breakout']))
                $image_classes .= ' breakout';
            if (!empty($fields['image_style']['shadow']))
                $image_classes .= ' has-shadow';
        }
        $content_width = !empty($fields['content_width']) ? $fields['content_width'] : '';
        $cta_width = !empty($fields['cta_width']) ? $fields['cta_width'] : '';
        $image_width = !empty($fields['image_width']) ? $fields['image_width'] : '';
        include(md_template($this->dir, 'floating-bars/floating-bar', true));
    }

}

new md_floating_bars;