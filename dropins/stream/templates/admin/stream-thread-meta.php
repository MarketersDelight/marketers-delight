<div class="md-sep-small">
    <?php $this->fields->field(array($group, $field, 'text'), array(
        'type' => 'textarea',
        'label' => __('Text', 'md'),
    )); ?>
</div>
<div class="md-sep-small">
    <?php $this->fields->field(array($group, $field, 'post_id'), array(
        'type' => 'number',
        'label' => __('Post ID', 'md')
    )); ?>
</div>
<div class="md-sep-small">
    <?php $this->fields->field(array($group, $field, 'image'), array(
        'type' => 'upload',
        'upload_type' => 'media',
        'label' => __('Image', 'md')
    )); ?>
</div>
<?php $this->fields->field(array($group, $field, 'date'), array(
    'type' => 'text',
    'hidden' => true,
    'populate' => 'date'
)); ?>
<?php $this->fields->field(array($group, $field, 'user_id'), array(
    'type' => 'text',
    'hidden' => true,
    'populate' => 'user-id'
)); ?>