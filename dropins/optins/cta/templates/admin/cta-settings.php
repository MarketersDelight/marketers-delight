<div class="md-content-wrap-med">
    <div class="md-sep-small">
        <?php $this->fields->field('email_list', array(
            'type' => 'select',
            'optgroup' => true,
            'label' => __('Default Email List', 'md'),
            'description' => sprintf(__('To make adding email forms around your website easier you can set a default email list here. Add/edit connected email services through the <a href="%s">Integrations</a> panel.', 'md'), admin_url('admin.php?page=md_integrations')),
            'empty_label' => __('Select an email list...', 'md'),
            'options' => md_email_data()
        )); ?>
    </div>
    <hr class="md-sep-small"/>
    <div class="md-sep-small">
        <?php $this->fields->field('forms', array(
            'type' => 'group',
            'label' => __('Call to Actions', 'md'),
            'style' => 'boxes',
            'active_key' => 'locations',
            'callback' => array($this, 'fields')
        )); ?>
    </div>
    <hr class="md-sep-small"/>
    <?php $this->fields->save(__('Save Call to Actions', 'md')); ?>
</div>