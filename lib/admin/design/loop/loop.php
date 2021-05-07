<?php
/**
 * Create and store loops settings data.
 *
 * @since 5.1
 */

class md_loop extends md_api
{

    /**
     * Fire actions and filters.
     *
     * @since 5.1
     */

    public function actions()
    {
        add_action('md_site_design_content_archives', array($this, 'admin_template'));
    }

    /**
     * Create meta box and terms.
     *
     * @since 5.0
     */

    public function register()
    {
        $this->name = __('Loop', 'md');
        return array(
            'term' => array(
                'name' => $this->name,
                'fields' => $this->register_fields()
            ),
            'admin_page' => array(
                'name' => $this->name,
                'parent' => 'design',
                'fields' => $this->register_fields()
            )
        );
    }

    /**
     * Register fields for save.
     *
     * @since 5.1
     */

    public function register_fields()
    {
        $cta_ids = array();
        $cta = md_setting(array('cta', 'forms'));
        if (!empty($cta))
            foreach ($cta as $cta_id => $cta_fields)
                $cta_ids[] = $cta_id;
        return array(
            'archives' => array(
                'type' => 'select',
                'options' => md_loops('ids')
            ),
            'featured' => array('type' => 'number'),
            'columns' => array('type' => 'number'),
            'byline' => array(
                'type' => 'checkbox',
                'options' => md_byline_items('ids')
            ),
            'byline_position' => array(
                'type' => 'select',
                'options' => array('before_headline', 'after_headline')
            ),
            'content' => array(
                'type' => 'select',
                'options' => array('excerpt', 'hide')
            ),
            'excerpt_length' => array('type' => 'number'),
            'read_more' => array('type' => 'text'),
            'cta_x_loop' => array('type' => 'number'),
            'x_cta' => array(
                'type' => 'select',
                'options' => $cta_ids
            )
        );
    }

    /**
     * Add fields to terms interface.
     *
     * @since 5.1
     */

    public function term()
    {
        $this->admin_template();
    }

    /**
     * Call template with required data passed down.
     *
     * @since 5.1
     */

    public function admin_template()
    {
        $cta_options = array();
        $cta = md_setting(array('cta', 'forms'));
        $archives_loop = $this->fields->get_field(array('loop', 'archives'));
        if (!empty($cta))
            foreach ($cta as $cta_id => $cta_fields)
                $cta_options[$cta_id] = $cta_fields['name'];
        include('loop-settings.php');
        $this->admin_script();
    }

    /**
     * Admin scripts for Content settings.
     *
     * @since 5.1
     */

    public function admin_script()
    { ?>
        <script>
            document.getElementById('<?php echo "{$this->_option}_{$this->_clean_id}_archives"; ?>').onchange = function (e) {
                document.getElementById('content_loop_teasers').style.display = this.value == 'teasers' ? 'block' : 'none';
            }
        </script>
    <?php }

}

new md_loop;