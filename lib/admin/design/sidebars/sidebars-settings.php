<div class="md-content-wrap-med columns-2 columns-single columns-65-35">
    <div class="col col1 md-sep-small">
        <?php $this->fields->field('areas', array(
            'type' => 'group',
            'label' => __('Custom Sidebars', 'md'),
            'description' => sprintf(__('Create custom sidebars and assign them to custom post types here. Once created you can go to the <a href="%s">Widgets</a> screen to fill the sidebar area with widgets.', 'md'), admin_url('widgets.php')),
            'callback' => array($this, 'widget_areas')
        )); ?>
        <hr class="mt-half md-sep-small"/>
        <?php $this->fields->save(__('Save Sidebars', 'md')); ?>
    </div>
    <div class="col col2">
        <h2 class="md-title"><?php echo __('Assign Sidebars', 'md'); ?></h2>
        <p class="description md-sep-small"><?php echo __('Set custom sidebars across different post type pages on your site.', 'md'); ?></p>
        <?php if (!empty($sidebars)) : ?>
            <?php foreach ($types as $type => $pages) : ?>
                <div class="md-group md-widget md-toggle">
                    <h3 class="md-widget-title"><?php echo ucwords($type); ?></h3>
                    <div class="md-group-content md-widget-item">
                        <?php foreach ($pages as $page => $val) : ?>
                            <div class="md-sep-micro">
                                <?php $this->fields->field("{$type}_{$page}", array(
                                    'type' => 'select',
                                    'label' => ucwords($page),
                                    'empty_label' => __('Use Main sidebar', 'md'),
                                    'options' => $sidebars
                                )); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="md-alert"><?php echo __('<b>Tip:</b> First create a custom sidebar by clicking "Add new" and save your settings to assign your sidebars to post types.', 'md'); ?></p>
        <?php endif; ?>
    </div>
</div>