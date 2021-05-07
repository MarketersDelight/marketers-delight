<div class="md-dashboard md-content-wrap-med">
    <div class="columns-70-30 columns-single">
        <div class="col col1 md-sep-small">
            <?php do_action('md_hook_settings_col2_top'); ?>
            <div class="md-widget md-toggle md-sep-small">
                <h3 class="md-widget-title"><?php echo __('Site Tools', 'md'); ?></h3>
                <div class="md-widget-item">
                    <div class="md-sep">
                        <?php $this->fields->field('css', array(
                            'type' => 'checkbox',
                            'label' => __('CSS Manager', 'md'),
                            'description' => sprintf(__('If using a child theme you can save an extra HTTP request by combining your custom stylesheet with MD\'s stylesheet file. For further optimization you can print your stylesheets inline to your site\'s %s.<br /><br />Note: if you combine your child theme styles you will need to resave the MD settings above to see any changes you make to the file afterwards.', 'md'), '<code>&lt;head&gt;</code>'),
                            'options' => array(
                                'child' => sprintf(__('Combine child theme CSS into %s', 'md'), '<code>style.css</code>'),
                                'inline' => sprintf(__('Print %s inline', 'md'), '<code>style.css</code>')
                            )
                        )); ?>
                    </div>
                    <div class="md-sep">
                        <?php $this->fields->field('404_page', array(
                            'type' => 'number',
                            'label' => __('404 Page ID', 'md')
                        )); ?>
                        <?php if ($page404) : ?>
                            <?php $this->fields->description(sprintf(__('Success! You can <a href="%s">edit your 404 page here</a>.', 'md'), admin_url('post.php?post=' . esc_attr($page404) . '&action=edit'))); ?>
                        <?php else : ?>
                            <?php echo $this->fields->description(sprintf(__('Create a custom 404 page by attaching a new <a href="%s">Page ID</a> here.', 'md'), admin_url('edit.php?post_type=page'))); ?>
                        <?php endif; ?>
                    </div>
                    <div class="md-sep">
                        <?php $this->fields->field('webfonts', array(
                            'type' => 'checkbox',
                            'label' => __('Web Fonts', 'md'),
                            'description' => __('By default MD loads all Google and Typekit web fonts through a stylesheet and prefetch method. To <b>attempt</b> to improve font performance and fight off "Flash of invisible text," enable the WebFont loader script here.', 'md'),
                            'options' => array(
                                'loader' => __('Enable WebFont Loader', 'md')
                            )
                        )); ?>
                    </div>
                    <div class="md-sep">
                        <?php $this->fields->field('head', array(
                            'type' => 'checkbox',
                            'label' => __('Clean <code>&lt;head&gt;</code> Tags', 'md'),
                            'description' => sprintf(__('By default, MD removes many unneeded scripts and tags from the %s (like emojis), and disables comment cookies for guests. To restore these features, enable this option.', 'md'), '<code>&lt;head&gt;</code>'),
                            'options' => array(
                                'blocks' => __('<b>Remove</b> <code>block-library.css</code> style from head', 'md'),
                                'wpjson' => __('<b>Remove</b> <code>/wp-json/</code> REST API from head', 'md'),
                                'oembed' => __('<b>Remove</b> <code>wp-embed.js</code> script from footer', 'md'),
                                'optimize' => __('<b>Enable</b> emojis, comment cookie, and other outdated WP tags', 'md')
                            )
                        )); ?>
                    </div>
                </div>
            </div>
            <?php if (md_has('scripts')) :
                $scripts = new md_scripts($this->_id);
                ?>
                <div class="md-widget md-toggle md-sep-small">
                    <h3 class="md-widget-title"><?php echo __('Scripts Manager', 'md'); ?></h3>
                    <div class="md-widget-item">
                        <?php $scripts->admin_template(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="col col2">
            <div class="md-save md-sep md-clear">
                <?php $this->fields->save(); ?>
                <p><a href="https://mdforums.org/" class="md-save-link"
                      target="_blank"><?php echo __('What\'s new at the <b>MD forums</b> &rarr;', 'md'); ?></a></p>
            </div>
        </div>
    </div>
</div>