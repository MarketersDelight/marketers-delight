<div class="md-optins-show-fields columns-4 columns-single md-sep-micro">
    <div class="col col1">
        <?php $this->fields->field(array($group, $field, 'popup'), array(
            'type' => 'select',
            'label' => __('Show Popup', 'md'),
            'empty_label' => __('Select a popup&hellip;', 'md'),
            'options' => md_get_popups('options')
        )); ?>
    </div>
    <div class="col col2">
        <?php $this->fields->field(array($group, $field, 'show'), array(
            'type' => 'select',
            'label' => __('Show Method', 'md'),
            'classes' => 'md-optins-show-field',
            'empty_label' => __('Show after X seconds (default)', 'md'),
            'options' => array(
                'exit' => __('Show on exit intent', 'md'),
                'percent' => __('Show after scroll percentage', 'md')
            )
        )); ?>
    </div>
    <div class="md-optins-delay col col3">
        <?php $this->fields->field(array($group, $field, 'delay'), array(
            'type' => 'number',
            'label' => __('Show Delay', 'md'),
            'unit' => $show == 'percent' ? $show : 'seconds',
            'placeholder' => '5'
        )); ?>
    </div>
    <div class="col col4">
        <?php $this->fields->field(array($group, $field, 'cookie'), array(
            'type' => 'number',
            'label' => __('Cookie Duration', 'md'),
            'unit' => 'days',
            'placeholder' => 30
        )); ?>
    </div>
</div>