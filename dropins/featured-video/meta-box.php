<div class="md-sep-small">
    <?php $this->fields->field('service', array(
        'type' => 'select',
        'label' => __('Video Service', 'md'),
        'empty_label' => __('Select video service&hellip;', 'md'),
        'options' => array(
            'youtube' => __('YouTube', 'md'),
            'vimeo' => __('Vimeo', 'md'),
            'embed' => __('Embed Code', 'md')
        )
    )); ?>
</div>

<div id="featured_video_options" style="display: <?php echo !empty($meta['service']) ? 'block' : 'none'; ?>">

    <div class="md-sep-small">
        <?php $this->fields->field('position', array(
            'type' => 'select',
            'label' => __('Video Position', 'md'),
            'options' => array(
                'before_headline' => __('Before Headline (default)', 'md'),
                'after_headline' => __('After Headline', 'md')
            )
        )); ?>
    </div>

    <?php foreach ($videos as $id => $fields) : ?>
        <div id="featured_video_<?php echo $id; ?>_row" class="md-sep-small"
             style="display: <?php echo !empty($meta['service']) && $meta['service'] == $id ? 'block' : 'none'; ?>">
            <?php $this->fields->field($id, array(
                'type' => 'text',
                'label' => $fields['label'],
                'description' => $fields['desc']
            )); ?>
        </div>
    <?php endforeach; ?>

    <div id="featured_video_embed_row" class="md-sep-small"
         style="display: <?php echo !empty($meta['service']) && $meta['service'] == 'embed' ? 'block' : 'none'; ?>">
        <?php $this->fields->field('embed', array(
            'type' => 'code',
            'label' => __('Video Embed Code', 'md'),
            'description' => __('Paste your custom video embed code here.', 'md'),
            'rows' => 8
        )); ?>
    </div>

    <?php if (get_post_type() == 'videos') : ?>
        <div class="md-sep-small">
            <?php $this->fields->field('thumbnail', array(
                'type' => 'upload',
                'upload_type' => 'media',
                'label' => __('Video Thumbnail', 'md')
            )); ?>
        </div>
    <?php endif; ?>

</div>