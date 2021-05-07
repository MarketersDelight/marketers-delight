<?php
/**
 * This class generates MD CSS files and other actions.
 *
 * @since 4.9.4
 */

class md_css
{

    /**
     * Set properties.
     *
     * @since 4.9.4
     */

    public function __construct()
    {
        $this->files = $this->files();
    }

    /**
     * A list of CSS files to generate from templates.
     *
     * @since 4.9
     */

    public function files()
    {
        return array_merge(array(
            'style' => array(
                'templates' => $this->style_css(),
                'path' => MD_DIR . 'style.css'
            )
        ), apply_filters('md_css_files', array()));
    }

    /**
     * Core style.css template files to load in order
     * with Dropins filter.
     *
     * @since 4.9.4
     */

    public function style_css()
    {
        $templates = array(
            'attributes' => MD_CSS_DIR . 'attributes.php',
            'forms' => MD_CSS_DIR . 'forms.php',
            'blocks' => MD_CSS_DIR . 'blocks.php',
            'spacers' => MD_CSS_DIR . 'spacers.php',
            'columns' => MD_CSS_DIR . 'columns.php',
            'buttons' => MD_CSS_DIR . 'buttons.php',
            'format' => MD_CSS_DIR . 'format.php',
            'layout' => MD_CSS_DIR . 'layout.php',
            'sidebar' => MD_CSS_DIR . 'sidebar.php',
            'menus' => MD_CSS_DIR . 'menus.php',
            'header' => MD_CSS_DIR . 'header.php',
            'post' => MD_CSS_DIR . 'post.php',
            'comments' => MD_CSS_DIR . 'comments.php',
            'widgets' => MD_CSS_DIR . 'widgets.php',
            'footer' => MD_CSS_DIR . 'footer.php'
        );

        $dropins = apply_filters('md_dropins_css_templates', array());

        $templates = array_merge($templates, $dropins);

        $templates['effects'] = MD_CSS_DIR . 'effects.php';

        $child = get_stylesheet_directory() . '/style.css';
        if (is_child_theme() && file_exists($child) && md_setting(array('settings', 'css', 'child'))) {
            $child_dynamic = get_stylesheet_directory() . '/style.php';
            $templates['child'] = $child;
            if (file_exists($child_dynamic))
                $templates['child_dynamic'] = $child_dynamic;
        }

        $templates = apply_filters('md_style_css_templates', $templates);

        return $templates;
    }

    /**
     * Compile CSS in the user designated manner on call.
     *
     * @since 4.8
     */

    public function compile($delete = null)
    {
        $inline = md_setting(array('settings', 'css', 'inline'));
        foreach ($this->files as $file => $fields) {
            if (empty($inline)) {
                if (isset($delete))
                    delete_option("marketers_delight_{$file}_css");
                $this->generate($file);
            } else
                $this->save($file);
        }
        wp_cache_flush();
    }

    /**
     * Compile Customizer settings into the static CSS file.
     *
     * @since 4.8
     */

    public function generate($file)
    {
        $path = $this->files[$file]['path'];
        if (file_exists($path)) {
            ob_start();
            $this->templates($file);
            $css = ob_get_clean();
            $css = $this->clean($css);
            file_put_contents($path, $css);
        }
    }

    /**
     * Get CSS template.
     *
     * @since 4.9
     */

    public function templates($file)
    {
        $g = 1.618;
        $design = new md_design;
        $values = $design->values();
        $theme_url = get_stylesheet_directory_uri();

        $site_width = $values['content']['width']['site'];
        $content_width = $values['content']['width']['content_width'];
        $post_width = $values['content']['width']['post'];
        $sidebar_width = $values['content']['width']['sidebar'];

        $content_style = md_setting(array('content', 'style'));

        $colors = $values['colors'];
        $typography = $values['typography'];
        $header = $values['header'];
        $content = $values['content'];

        $font_size = $values['typography']['body']['font_size'];
        $font_family = $values['typography']['body']['font_family'];
        $line_height = $values['typography']['body']['line_height'];
        $font_weight = !empty($typography['body']['font_weight']) ? $typography['body']['font_weight'] : 'normal';
        $bold = !empty($typography['body']['bold']) ? $typography['body']['bold'] : 'bold';

        $h1 = $values['typography']['h1'];
        $h2 = $values['typography']['h2'];
        $h3 = $values['typography']['h3'];
        $h4 = $values['typography']['h4'];
        $h5 = $values['typography']['h5'];
        $h6 = $values['typography']['h6'];

        $single = $lh = $line_height['desktop'];
        $small = $lhsm = round($single / 6);
        $third = $lhth = round($single / 3);
        $half = $lhh = round($single / 2);
        $mid = $lhm = $single + $half;
        $double = $lhd = round($single * 2);
        $triple = $lht = round($single * 3);
        $quad = $lhq = round($single * 4);

        $gutter_width = round(($site_width - $post_width) / 2);
        $breakout = ($gutter_width / $post_width) * 100;
        $breakout_full = ($gutter_width / $site_width) * 100;

        foreach ($this->files[$file]['templates'] as $template => $path) {
            include($path);
            echo "\n\n";
        }
    }

    /**
     * Clean up CSS before saving.
     *
     * @since 4.9.4
     */

    public function clean($css)
    {
        $css = str_replace(array('<style type="text/css">', '<style type=\'text/css\'>', '<style>', '</style>'), '', $css);
        $css = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $css);
        return trim($css);
    }

    /**
     * Save CSS as option to database (only when needed).
     *
     * @since 4.8
     */

    public function save($file)
    {
        $css = $this->minify($file);
        update_option("marketers_delight_{$file}_css", $css);
    }

    /**
     * Remove all whitespace, line breaks, unwanted characters,
     * and other requirements for file minification.
     *
     * @since 4.9.1
     */

    public function minify($file)
    {
        ob_start();
        $this->templates($file);
        $css = ob_get_clean();
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        $s = array("\r", "\n", "\t", ' }', '{ ', ' {', '; ', ': ', ', ', '   ');
        $r = array('', '', '', '}', '{', '{', ';', ':', ',', '');
        $css = str_replace($s, $r, $css);
        $css = $this->clean($css);
        return $css;
    }

}