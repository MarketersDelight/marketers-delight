<h1><?php echo __('Stream Settings', 'md'); ?></h1>
<hr/>
<div class="md-content-wrap-wide">
    <div class="columns-2 columns-double md-sep-small">
        <div class="col">
            <h2><?php echo __('Stream Title', 'md'); ?></h2>
            <hr/>
            <div class="md-sep-small">
                <?php $this->fields->field('archives_title', array(
                    'type' => 'text',
                    'label' => __('Page Title', 'md'),
                    'description' => __('Add an <code>h1</code> title tag to the top of the Stream page.', 'md')
                )); ?>
            </div>
            <div class="md-sep-small">
                <?php $this->fields->field('archives_text', array(
                    'type' => 'textarea',
                    'label' => __('Page Text', 'md'),
                    'description' => __('Write a description below the title.', 'md'),
                    'rows' => 4
                )); ?>
            </div>
            <div class="md-sep-small">
                <?php $this->fields->field('archives_photo', array(
                    'type' => 'upload',
                    'label' => __('Profile Photo', 'md'),
                    'description' => __('<b>Recommended size: 250x250px (or smaller)</b><br />Add a face to your Stream by uploading your photo or logo.', 'md')
                )); ?>
            </div>
        </div>
        <div class="col">
            <h2><?php echo __('General Settings', 'md'); ?></h2>
            <hr/>
            <div class="md-sep-small">
                <?php $this->fields->field('settings', array(
                    'type' => 'checkbox',
                    'options' => array(
                        'disable_activity' => __('<b>Disable</b> latest activity', 'md')
                    )
                )); ?>
            </div>
            <div class="md-sep-small">
                <?php $this->fields->field('slug', array(
                    'type' => 'text',
                    'label' => __('Page Slug', 'md'),
                    'description' => __('Change the URL of your Stream pages.', 'md'),
                    'placeholder' => $this->slug,
                )); ?>
            </div>
            <div class="md-sep-small">
                <?php $this->fields->field('posts_per_page', array(
                    'type' => 'number',
                    'label' => __('Posts Per Page', 'md'),
                    'description' => __('Change how many posts to show on Stream pages.', 'md'),
                    'placeholder' => 10
                )); ?>
            </div>
            <div class="md-sep-small">
                <?php $this->fields->field('layout', array(
                    'type' => 'checkbox',
                    'label' => __('Layout', 'md'),
                    'description' => sprintf(__('<b>Tip:</b> Assign custom sidebars to your stream archive and single pages at the <a href="%s">Widgets > Edit Sidebars</a> screen.', 'md'), admin_url('widgets.php')),
                    'options' => array(
                        'add_archives_sidebar' => __('<b>Add sidebar</b> to Stream page', 'md'),
                        'add_single_sidebar' => __('<b>Add sidebar</b> to Stream posts', 'md'),
                        'add_stream_title' => __('<b>Add stream title</b> to Stream posts', 'md'),
                        'remove_post_titles' => __('<b>Remove post title</b> from Stream posts', 'md'),
                        'remove_breadcrumbs' => __('<b>Remove breadcrumbs</b> from Stream page', 'md'),
                        'disable_comments' => __('<b>Disable</b> comments', 'md')
                    )
                )); ?>
            </div>
        </div>
    </div>
    <?php $this->fields->save(); ?>
</div>