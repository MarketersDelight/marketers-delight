<?php
/**
 * Builds Customizer Panels + Settings for each popup
 * using the option ID created on the Customizer redirect screen.
 *
 * @since 4.5
 */

class md_popups_customize
{

    /**
     * Get Edit options array and set up/load what's needed
     * for Customizer experience.
     *
     * @since 4.5
     */

    public function __construct()
    {
        add_action('customize_register', array($this, 'register'));
        add_action('customize_controls_enqueue_scripts', array($this, 'controls_enqueue'));
        add_action('customize_preview_init', array($this, 'preview_scripts'));
        if (isset($_GET['popups_designer']) && isset($_GET['id'])) {
            add_action('wp_head', array($this, 'custom_css'));
            add_action('template_redirect', array($this, 'template'));
            add_action('wp_footer', array($this, 'script'));
        }
    }

    /**
     * Load custom controls and controls templates.
     *
     * @since 4.5
     */

    public function register($wp_customize)
    {
        // Register Controls
        include_once('controls/controls.php');
        $wp_customize->register_control_type('MD_Customize_Control_Range');

        // Add Popups Designer Panel
        $wp_customize->add_panel('md_popups_designer', array(
            'title' => __('Popups Designer', 'md'),
            'description' => sprintf(__('Edit the Popups you have created from <a href="%s"><b>MD > Optins > Popups</b></a>.', 'md'), admin_url('admin.php?page=md_optins&tab=md_popups')),
            'priority' => 30
        ));

        // Build Settings Sections
        $sanitize = new md_sanitize;
        $popups = md_setting(array('popups', 'popups'));
        $helper_classes = sprintf(__('<a href="%s" target="_blank">Enhance with MD helper classes &rarr;</a>', 'md'), 'https://marketersdelight.com/styles/');
        if (!empty($popups))
            foreach ($popups as $popup_id => $fields) {
                $prefix = "marketers_delight[popups_data][{$popup_id}]";
                include('sections.php');
            }
    }

    /**
     * Load Customizer Preview scripts.
     *
     * @since 4.5
     */

    public function preview_scripts()
    {
        wp_enqueue_script('md-popups-preview', MD_URL . 'dropins/optins/popups/customize/preview.js', array('customize-preview'), md_ver('dropins/optins/popups/customize/preview.js'), true);
        wp_localize_script('md-popups-preview', 'mdPopups', array(
            'popups' => md_get_popups('ids'),
            'popupsDesigner' => get_site_url() . '?popups_designer&popup_id=' . (!empty($_GET['popups_designer']['popup']) ? $_GET['popups_designer']['popup'] : '')
        ));
    }

    /**
     * Load Customizer Controls scripts.
     *
     * @since 5.0
     */

    public function controls_enqueue()
    {
        $css_path = 'dropins/optins/popups/customize/controls/controls.css';
        $js_path = 'dropins/optins/popups/customize/controls/controls.js';
        wp_enqueue_style('md-customize-controls', MD_URL . $css_path, array(), md_ver($css_path));
        wp_enqueue_script('md-popups-controls', MD_URL . $js_path, array('customize-controls'), md_ver($js_path), true);
        wp_localize_script('md-popups-controls', 'mdPopups', array(
            'popups' => md_get_popups('ids'),
            'popupsDesigner' => get_site_url() . '?popups_designer&popup_id=' . (!empty($_GET['popups_designer']['popup']) ? $_GET['popups_designer']['popup'] : ''),
        ));
    }

    /**
     * Manipulate templates in Customizer.
     *
     * @since 4.5
     */

    public function template()
    {
        add_filter('md_filter_has_header', '__return_false');
        add_filter('md_filter_has_template', '__return_false');
        add_filter('md_filter_has_footer', '__return_false');
        md_popup(array('id' => $_GET['id']));
    }

    /**
     * Load Customizer script to show selected popup.
     *
     * @since 5.0
     */

    public function script()
    {
        $id = $_GET['id'];
        $js["md_popup_$id"]['id'] = "md_popup_$id";
        $js["md_popup_$id"]['show'] = 'seconds';
        $js["md_popup_$id"]['delay'] = 0;
        wp_add_inline_script('marketers-delight', "\tMD.popups.init({ " . md_js_object($js) . "});");
    }

    /**
     * Load custom CSS from Custom CSS field.
     *
     * @since 5.0
     */

    public function custom_css()
    { ?>
        <style id="md_popup_custom_css" type="text/css">
            <?php echo md_setting( array( 'popups_data', $_GET['id'], 'custom_template', 'custom_css' ) ); ?>
        </style>
    <?php }

}

new md_popups_customize;