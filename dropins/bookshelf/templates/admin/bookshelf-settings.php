<h1 class="md-spacer"><?php echo __('Books Settings', 'md'); ?></h1>
<hr/>
<div class="columns-2 columns-double">
    <div class="col">
        <h2><?php echo __('Books Page', 'md'); ?></h2>
        <hr/>
        <div class="md-sep-small">
            <?php $this->fields->field('archives_title', array(
                'type' => 'text',
                'label' => __('Page Title', 'md'),
                'description' => __('Add an <code>h1</code> title tag to the top of the archives page.', 'md'),
            )); ?>
        </div>
        <div class="md-sep-small">
            <?php $this->fields->field('archives_text', array(
                'type' => 'textarea',
                'label' => __('Page Text', 'md'),
                'description' => __('Add a text description below the archives title.', 'md'),
                'rows' => 4
            )); ?>
        </div>
    </div>
    <div class="col">
        <h2><?php echo __('General Settings', 'md'); ?></h2>
        <hr/>
        <div class="md-sep-small">
            <?php $this->fields->field('archives_slug', array(
                'type' => 'text',
                'label' => __('Page Slug', 'md'),
                'description' => __('Change the URL of the bookshelf archives page.', 'md'),
                'placeholder' => $this->slug
            )); ?>
        </div>
        <div class="md-sep-small">
            <?php $this->fields->field('posts_per_page', array(
                'type' => 'number',
                'label' => __('Books Per Page', 'md'),
                'description' => __('Change how many books per page should show on each archives page.', 'md'),
                'placeholder' => 20
            )); ?>
        </div>
        <div class="md-sep-small">
            <?php $this->fields->field('archives_listing', array(
                'type' => 'select',
                'label' => __('Page Layout', 'md'),
                'options' => array(
                    'grid' => __('Grid (default)', 'md'),
                    'excerpt' => __('Excerpt View', 'md')
                )
            )); ?>
            <?php $this->fields->field('archives_layout', array(
                'type' => 'checkbox',
                'description' => sprintf(__('To add a <b>custom sidebar</b>, first enable it here, then go to the "Edit Sidebars" in the <a href="%s">Widgets</a> panel.', 'md'), admin_url('widgets.php')),
                'options' => array(
                    'categories' => __('Remove <b>Categories</b>', 'md'),
                    'sidebar' => __('Add <b>Sidebar</b>', 'md')
                )
            )); ?>
        </div>
    </div>
</div>
<?php $this->fields->save(); ?>