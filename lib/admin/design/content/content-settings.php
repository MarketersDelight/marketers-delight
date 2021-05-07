<div class="md-content-wrap">
    <?php $this->fields->devices(); ?>
    <h2 class="md-title"><?php echo __('Content Box', 'md'); ?></h2>
    <p><?php echo __('Adjust the width, design, and other parts of your website\'s content box.', 'md'); ?></p>
    <hr class="md-sep"/>
    <div class="md-widget md-toggle md-sep">
        <h3 class="md-widget-title"><?php echo __('Layout', 'md'); ?></h3>
        <div class="md-widget-item">
            <div class="columns-2 columns-single">
                <div class="col md-desktop">
                    <div class="md-sep-small">
                        <?php $this->fields->field(array('width', 'site'), array(
                            'type' => 'range',
                            'label' => __('Site Width', 'md'),
                            'placeholder' => $defaults['width']['site']
                        )); ?>
                    </div>
                    <div class="md-sep-small">
                        <?php $this->fields->field(array('width', 'content'), array(
                            'type' => 'range',
                            'label' => __('Post Width', 'md'),
                            'placeholder' => $defaults['width']['post']
                        )); ?>
                    </div>
                    <div class="md-sep-small">
                        <?php $this->fields->field(array('width', 'sidebar'), array(
                            'type' => 'range',
                            'label' => __('Sidebar Width', 'md'),
                            'placeholder' => $defaults['width']['sidebar']
                        )); ?>
                    </div>
                    <p class="description"><?php echo __('<b>Tip:</b> Set the <b>Post Width</b> to the exact length your text will read in the content box.', 'md'); ?>
                    <p class="description"><?php echo sprintf(__('<b>Tip:</b> For the most accurate results you must add any extra spacing within the content box and sidebar to find the true width of your site.<br /><br /><code><b>%s</b> + <b>%2s</b>%3s = <b>%4s</b></code>', 'md'), $values['content']['width']['post'], $values['content']['width']['sidebar'], (md_setting(array('content', 'style')) == '' ? ' + <b>' . ($values['typography']['body']['line_height']['desktop'] * 4) . '</b>' : ''), $values['content']['width']['site']); ?></p>
                </div>
                <div class="col">
                    <div class="md-sep-micro md-desktop">
                        <?php $this->fields->field('layout', array(
                            'type' => 'select',
                            'empty_label' => __('Select layout...', 'md'),
                            'label' => __('Content box', 'md'),
                            'options' => $sanitize->values['content_box']
                        )); ?>
                    </div>
                    <div class="md-sep-small md-desktop">
                        <?php $this->fields->field('style', array(
                            'type' => 'select',
                            'empty_label' => __('Default style', 'md'),
                            'options' => array(
                                'minimal' => __('Minimal style', 'md')
                            )
                        )); ?>
                    </div>
                    <div class="md-sep-small">
                        <?php $this->fields->field('post', array(
                            'type' => 'checkbox',
                            'label' => __('Content', 'md'),
                            'options' => array(
                                'breadcrumbs' => __('Enable breadcrumbs', 'md'),
                                'subtitle' => __('Enable subtitle', 'md'),
                                'footnotes' => __('Enable footnotes', 'md'),
                                'blocks' => __('Disable MD editor blocks', 'md'),
                            )
                        )); ?>
                    </div>
                    <div class="md-sep-small">
                        <?php $this->fields->field('sidebar', array(
                            'type' => 'checkbox',
                            'label' => __('Sidebar', 'md'),
                            'description' => sprintf(__('Edit and create sidebars in <a href="%s">Appearance > Widgets</a>.'), admin_url('widgets.php')),
                            'options' => array(
                                'blog_remove' => __('Remove from blog page', 'md'),
                                'category' => __('Enable on all category pages', 'md'),
                                'single' => __('Enable on all blog posts', 'md')
                            )
                        )); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h3 class="md-title"><?php echo __('Archives', 'md'); ?></h3>
    <p><?php echo __('Change the display of archives pages like your blog and categories.', 'md'); ?></p>
    <hr class="md-sep-small"/>
    <?php do_action('md_site_design_content_archives'); ?>
    <h3 class="md-title"><?php echo __('Single', 'md'); ?></h3>
    <p><?php echo __('Change the display settings of single posts and pages.', 'md'); ?></p>
    <hr class="md-sep-small"/>
    <div class="md-widget md-toggle md-sep-small">
        <h3 class="md-widget-title"><?php echo __('Byline', 'md'); ?></h3>
        <div class="md-widget-item">
            <div class="md-sep-small">
                <?php $this->fields->field('byline_position', array(
                    'type' => 'select',
                    'empty_label' => __('Select byline position...', 'md'),
                    'options' => array(
                        'before_headline' => __('Show before headline', 'md'),
                        'after_headline' => __('Show after headline', 'md')
                    )
                )); ?>
            </div>
            <div class="md-sep-small">
                <?php $this->fields->field('byline', array(
                    'type' => 'checkbox',
                    'multi' => true,
                    'options' => md_byline_items()
                )); ?>
            </div>
        </div>
    </div>
    <div class="md-widget md-toggle md-sep-small">
        <h3 class="md-widget-title"><?php echo __('Featured Image', 'md'); ?></h3>
        <div class="md-widget-item">
            <p class="description"><?php echo __('Here you can set the default featured image settings across your entire site. Every post and page will use these settings, and you can also override them on any page within the post editor!', 'md'); ?></p>
            <hr class="md-sep-small"/>
            <div class="md-sep-small">
                <?php $this->fields->field(array('featured_image', 'position'), array(
                    'type' => 'select',
                    'label' => __('Featured Image Position', 'md'),
                    'empty_label' => __('Select featured image position&hellip;', 'md'),
                    'options' => $sanitize->values['featured_image']
                )); ?>
            </div>
            <div class="md-sep-small">
                <?php $this->fields->field(array('featured_image', 'cover_color'), array(
                    'type' => 'color',
                    'label' => __('Featured Image Cover Color', 'md'),
                    'default' => $defaults['featured_image']['cover_color']
                )); ?>
            </div>
            <div class="md-sep-small">
                <?php $this->fields->field(array('featured_image', 'cover'), array(
                    'type' => 'upload',
                    'upload_type' => 'media',
                    'label' => __('Default Featured Image Cover', 'md'),
                    'description' => __('Set a default background image for all Header/Headline cover posts, or set them individually from the Edit Post screen.', 'md')
                )); ?>
            </div>
            <div class="md-sep-small">
                <?php $this->fields->field(array('featured_image', 'styles'), array(
                    'type' => 'checkbox',
                    'options' => array(
                        'repeat' => __('Use background repeat', 'md'),
                        'text_color' => __('Show dark text', 'md')
                    )
                )); ?>
            </div>
        </div>
    </div>
    <div class="md-widget md-toggle md-sep-small">
        <h3 class="md-widget-title"><?php echo __('Author Box', 'md'); ?></h3>
        <div class="md-widget-item">
            <div class="md-sep-small">
                <?php $this->fields->field('author_box', array(
                    'type' => 'checkbox',
                    'options' => array(
                        'enable' => __('Enable author box after blog posts', 'md'),
                        'all_posts' => __('Link to author archives page', 'md')
                    )
                )); ?>
            </div>
        </div>
    </div>
    <hr class="md-sep-small"/>
    <?php $this->fields->save(); ?>
</div>