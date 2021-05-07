<?php
/**
 * Scripts and Styles manager. Add Header/Footer tracking scripts
 * sitewide or to a single post/page/category and disable scripts
 * and styles on the same basis.
 *
 * @since 4.4.2
 */

class md_scripts extends md_api
{

    /**
     * Run actions and filters.
     *
     * @since 4.4.2
     */

    public function actions()
    {
        $this->settings = md_setting(array('settings'));
        $this->google_analytics = md_setting(array('integrations', 'api_keys', 'google_analytics'));
        add_action('wp_head', array($this, 'wp_head'));
        add_action('wp_footer', array($this, 'wp_footer'), 100);
        add_action('wp_enqueue_scripts', array($this, 'dequeue_scripts'));
    }

    /**
     * Pesuedo constructor, adds admin tab to MD page.
     *
     * @since 5.0
     */

    public function register()
    {
        $this->name = __('Scripts Manager', 'md');
        return array(
            'meta_box' => array(
                'name' => $this->name,
                'fields' => $this->fields()
            ),
            'term' => array(
                'name' => $this->name,
                'fields' => $this->fields()
            )
        );
    }

    /**
     * Register settings to save.
     *
     * @since 4.4.2
     */

    public function fields()
    {
        $scripts = $this->dequeue_data('ids');
        $save = array(
            'body_class' => array('type' => 'text'),
            'header_scripts' => array('type' => 'code'),
            'footer_scripts' => array('type' => 'code')
        );
        if (!empty($scripts))
            $save['scripts_manager'] = array(
                'type' => 'checkbox',
                'options' => $scripts
            );
        return $save;
    }

    /**
     * Compile scripts data into different formats from filter.
     *
     * @since 4.4.2
     */

    public function dequeue_data($sort = null)
    {
        $scripts = apply_filters('md_filter_dequeue_scripts', array());
        if (!empty($scripts))
            if ($sort == 'ids') {
                $ids = array();
                foreach ($scripts as $plugin => $assets)
                    $ids[] = $plugin;
                return $ids;
            } elseif ($sort == 'labels') {
                $labels = array();
                foreach ($scripts as $plugin => $assets)
                    $labels[$plugin] = $assets['label'];
                return $labels;
            }
        return $scripts;
    }

    /**
     * Meta box fields.
     *
     * @since 5.0
     */

    public function meta_box()
    {
        $this->admin_template();
    }

    /**
     * Admin page content.
     *
     * @since 4.4.2
     */

    public function admin_template()
    {
        $scripts = $this->dequeue_data('labels');
        $screen = get_current_screen();
        ?>
        <?php if ($screen->base !== 'toplevel_page_md_settings') : ?>
        <div class="md-sep-small">
            <?php $this->fields->field('body_class', array(
                'type' => 'text',
                'label' => __('Body classes', 'md'),
                'description' => __('Add custom CSS classes to the <code>body</code> tag of this page.', 'md')
            )); ?>
        </div>
    <?php endif; ?>
        <div class="md-sep-small">
            <?php $this->fields->field('header_scripts', array(
                'type' => 'code',
                'label' => __('Header Scripts', 'md'),
                'description' => __('Print scripts to the <code>&lt;head></code> section (before opening <code>&lt;body></code> tag).', 'md')
            )); ?>
        </div>
        <div class="md-sep-small">
            <?php $this->fields->field('footer_scripts', array(
                'type' => 'code',
                'label' => __('Footer Scripts', 'md'),
                'description' => __('Print scripts after the <code>&lt;footer></code> section (before closing <code>&lt;/body></code> tag).', 'md')
            )); ?>
        </div>
        <?php if ($screen->base !== 'toplevel_page_md_settings' && !empty($scripts)) : ?>
        <div class="md-sep-small">
            <?php $this->fields->field('scripts_manager', array(
                'type' => 'checkbox',
                'label' => __('Scripts Optimization', 'md'),
                'description' => __('<b>Note:</b> Only remove the scripts and styles you know this page does not use, otherwise you may break some plugin functionality.', 'md'),
                'options' => $scripts
            )); ?>
        </div>
    <?php endif; ?>
    <?php }

    /**
     * Terms fields.
     *
     * @since 5.0
     */

    public function term()
    { ?>
        <div class="md-widget md-toggle md-sep-small">
            <h3 class="md-widget-title"><?php echo $this->name; ?></h3>
            <div class="md-widget-item">
                <?php $this->admin_template(); ?>
            </div>
        </div>
    <?php }

    /**
     * Run dequeue actions on specified pages.
     *
     * @since 4.9.4
     */

    public function dequeue_scripts()
    {
        $scripts = $this->dequeue_data();
        if (!empty($scripts)) {
            if (is_singular()) {
                $meta = md_post_meta(array('scripts', 'scripts_manager'));
                if (!empty($meta))
                    $this->dequeue_action($meta);
            }
            if (is_category() || is_tax()) {
                $tax = md_term_meta(array('scripts', 'scripts_manager'));
                if (!empty($tax))
                    $this->dequeue_action($tax);
            }
        }
    }

    /**
     * Call this action to dequeue specified scripts and styles.
     *
     * @since 4.9.4
     */

    public function dequeue_action($source)
    {
        foreach ($this->dequeue_data() as $plugin => $assets)
            if (!empty($source[$plugin])) {
                if (isset($assets['styles']))
                    foreach ($assets['styles'] as $style)
                        wp_dequeue_style($style);
                if (isset($assets['scripts']))
                    foreach ($assets['scripts'] as $script)
                        wp_dequeue_script($script);
            }
    }

    /**
     * Add scripts to header.
     *
     * @since 4.4.2
     */

    public function wp_head()
    {
        if (!empty($this->settings['header_scripts']))
            echo html_entity_decode($this->settings['header_scripts']) . "\n";
        if (is_category() || is_tax()) {
            $tax_scripts = md_term_meta(array('scripts', 'header_scripts'));
            if (!empty($tax_scripts))
                echo html_entity_decode($tax_scripts) . "\n";
        }
        if (is_singular()) {
            $meta_scripts = md_post_meta(array('scripts', 'header_scripts'));
            if (!empty($meta_scripts))
                echo html_entity_decode($meta_scripts) . "\n";
        }
    }

    /**
     * Add scripts to footer.
     *
     * @since 4.4.2
     */

    public function wp_footer()
    {
        if (!empty($this->google_analytics['key']))
            $this->google_analytics();
        if (!empty($this->settings['footer_scripts']))
            echo html_entity_decode($this->settings['footer_scripts']) . "\n";
        if (is_category() || is_tax()) {
            $tax_scripts = md_term_meta(array('scripts', 'footer_scripts'));
            if (!empty($tax_scripts))
                echo html_entity_decode($tax_scripts) . "\n";
        }
        if (is_singular()) {
            $meta_scripts = md_post_meta(array('scripts', 'footer_scripts'));
            if (!empty($meta_scripts))
                echo html_entity_decode($meta_scripts) . "\n";
        }
    }

    /**
     * Google Analytics Tracking Code.
     *
     * @since 4.9
     */

    public function google_analytics()
    { ?>
        <!-- Global Site Tag (gtag.js) - Google Analytics -->
        <script async
                src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($this->google_analytics['key']); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());
            gtag('config', '<?php echo esc_attr($this->google_analytics['key']); ?>');
        </script>
    <?php }

}

new md_scripts;