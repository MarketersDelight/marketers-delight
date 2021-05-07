<p>
    <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title', 'md'); ?>:</label>

    <input type="text" id="<?php echo $this->get_field_id('title'); ?>"
           name="<?php echo $this->get_field_name('title'); ?>" value="<?php esc_attr_e($val['title']); ?>"
           class="widefat"/>
</p>

<p>
    <label for="<?php echo $this->get_field_id('desc'); ?>"><?php echo __('Description', 'md'); ?>:</label>

    <textarea id="<?php echo $this->get_field_id('desc'); ?>" name="<?php echo $this->get_field_name('desc'); ?>"
              class="widefat" rows="4"><?php printf('%s', esc_textarea($val['desc'])); ?></textarea>
</p>

<?php if (!empty($email_data)) : ?>
    <p>
        <label for="<?php echo $this->get_field_id('list'); ?>"><?php echo __('Email List', 'md'); ?>:</label><br/>

        <select id="<?php echo $this->get_field_id('list'); ?>" name="<?php echo $this->get_field_name('list'); ?>"
                style="max-width: 100%;">
            <option value=""><?php echo __('Use default email list&hellip;', 'md'); ?></option>
            <?php foreach ($email_data

            as $group => $items) : ?>
            <optgroup label="<?php echo esc_html(ucwords($group)); ?>">
                <?php foreach ($items as $list => $fields) : ?>
                    <option value="<?php esc_attr_e($list); ?>"<?php echo selected($val['list'], $list, false); ?>><?php esc_html_e($fields['name']); ?></option>
                <?php endforeach; ?>
                <?php endforeach; ?>
        </select>
    </p>
<?php else : ?>
    <?php md_email_connect_notice(); ?>
<?php endif; ?>

<div class="md">
    <div class="md-widget md-toggle md-sep-small">
        <h3 class="md-widget-title"><?php echo sprintf(__('%s Form Code', 'md'), '<acronym title="HyperText Markup Language">HTML</acronym>'); ?></h3>
        <div class="md-widget-item">
            <p>
                <label for="<?php echo $this->get_field_id('custom_code'); ?>"><?php echo __('Custom HTML Code', 'md'); ?>
                    :</label>
                <textarea id="<?php echo $this->get_field_id('custom_code'); ?>"
                          name="<?php echo $this->get_field_name('custom_code'); ?>" class="widefat"
                          rows="7"><?php printf('%s', esc_textarea($val['custom_code'])); ?></textarea>
            </p>
        </div>
    </div>
</div>

<h4><?php echo __('Input Fields', 'md'); ?></h4>

<p>
    <input id="<?php echo $this->get_field_id('form_fields_name'); ?>" type="checkbox"
           name="<?php echo $this->get_field_name('form_fields_name'); ?>"
           value="1" <?php checked($val['form_fields_name']); ?> />

    <label for="<?php echo $this->get_field_id('form_fields_name'); ?>"><?php echo __('Ask for subscribers name in signup form', 'md'); ?></label>
</p>

<div id="<?php echo $this->get_field_id('name_label'); ?>-field"
     style="display: <?php echo $val['form_fields_name'] ? 'block' : 'none'; ?>">

    <label for="<?php echo $this->get_field_id('name_label'); ?>"><?php echo __('Name Field Label', 'md'); ?>:</label>

    <input type="text" id="<?php echo $this->get_field_id('name_label'); ?>"
           name="<?php echo $this->get_field_name('name_label'); ?>" value="<?php esc_attr_e($val['name_label']); ?>"
           placeholder="<?php echo __('Enter your name&hellip;', 'md'); ?>" class="widefat"/>

</div>

<p>
    <label for="<?php echo $this->get_field_id('email_label'); ?>"><?php echo __('Email Field Label', 'md'); ?>:</label>

    <input type="text" id="<?php echo $this->get_field_id('email_label'); ?>"
           name="<?php echo $this->get_field_name('email_label'); ?>" value="<?php esc_attr_e($val['email_label']); ?>"
           placeholder="<?php echo __('Enter your email&hellip;', 'md'); ?>" class="widefat"/>
</p>

<p>
    <label for="<?php echo $this->get_field_id('submit_text'); ?>"><?php echo __('Submit Button Text', 'md'); ?>
        :</label>

    <input type="text" id="<?php echo $this->get_field_id('submit_text'); ?>"
           name="<?php echo $this->get_field_name('submit_text'); ?>" value="<?php esc_attr_e($val['submit_text']); ?>"
           placeholder="<?php echo _e('Join Now!', 'md'); ?>" class="widefat"/>
</p>

<p>
    <input id="<?php echo $this->get_field_id('form_style_attached'); ?>" type="checkbox"
           name="<?php echo $this->get_field_name('form_style_attached'); ?>"
           value="1" <?php checked($val['form_style_attached']); ?> />

    <label for="<?php echo $this->get_field_id('form_style_attached'); ?>"><?php echo __('Attach input fields to each other', 'md'); ?></label>
</p>

<p>
    <label for="<?php echo $this->get_field_id('email_form_title'); ?>"><?php echo __('Form Title', 'md'); ?>:</label>

    <input type="text" id="<?php echo $this->get_field_id('email_form_title'); ?>"
           name="<?php echo $this->get_field_name('email_form_title'); ?>"
           value="<?php esc_attr_e($val['email_form_title']); ?>" class="widefat"/>
</p>

<p>
    <label for="<?php echo $this->get_field_id('email_form_footer'); ?>"><?php echo __('Form Footer', 'md'); ?>:</label>

    <textarea id="<?php echo $this->get_field_id('email_form_footer'); ?>"
              name="<?php echo $this->get_field_name('email_form_footer'); ?>" class="widefat"
              rows="4"><?php printf('%s', esc_textarea($val['email_form_footer'])); ?></textarea>
</p>

<?php if (!empty($data['enabled']['aweber']) || !empty($data['enabled']['mailerlite'])) : ?>
    <p>
        <label for="<?php echo $this->get_field_id('email_thank_you'); ?>"><?php echo sprintf(__('Thank You Page URL %s', 'md'), '<span class="required">*</span>'); ?>
            :</label>

        <input type="text" id="<?php echo $this->get_field_id('email_thank_you'); ?>"
               name="<?php echo $this->get_field_name('email_thank_you'); ?>"
               value="<?php esc_attr_e($val['email_thank_you']); ?>" class="widefat"
               placeholder="<?php echo get_site_url(); ?>/thank-you/"/>
        <br/><span
                class="description"><?php echo sprintf(__('Subscribers will be redirected to this URL after signup. Leave blank to use settings from your email provider. <br /><small>%s only available for AWeber & MailerLite forms.</small>', 'md'), '<span class="required">*</span>'); ?></span>
    </p>
<?php endif; ?>

<h4><?php echo __('Form Design', 'md'); ?></h4>

<div class="md-upload md-upload-media<?php echo !empty($val['image']) ? ' has-upload' : ''; ?>">
    <div class="md-uploader">
        <div class="md-upload-preview md-upload-add">
            <div class="md-upload-previewer">
                <span class="dashicons dashicons-upload"></span>
                <p class="md-upload-preview-text"><?php echo __('Click to upload', 'md'); ?></p>
            </div>
            <div class="md-upload-preview-image">
                <img src="<?php echo $val['image']; ?>" alt="<?php echo __('Preview Image', 'md'); ?>"/>
            </div>
        </div>
        <div class="md-upload-controls">
            <label class="md-label"
                   for="<?php echo $this->get_field_id('image'); ?>"><?php echo __('Image URL', 'md'); ?></label>
            <input type="url" class="md-upload-url regular-text" name="<?php echo $this->get_field_name('image'); ?>"
                   id="<?php echo $this->get_field_id('image'); ?>" value="<?php echo $val['image']; ?>"
                   placeholder="https://"/>
        </div>
    </div>
    <div class="md-upload-buttons">
        <input type="button" class="md-upload-add button" value="<?php echo __('Add Image', 'md'); ?>"/>
        <input type="button" class="md-upload-remove button" value="<?php echo __('Remove Image', 'md'); ?>"/>
    </div>
</div>

<?php wp_enqueue_media(); ?>

<p>
    <label for="<?php echo $this->get_field_id('bg_color'); ?>"><?php echo __('Background Color', 'md'); ?></label><br/>
    <input class="md-color-picker" type="text" id="<?php echo $this->get_field_id('bg_color'); ?>"
           name="<?php echo $this->get_field_name('bg_color'); ?>" value="<?php echo esc_attr($val['bg_color']); ?>"
           data-alpha="true"/>
</p>

<p>
    <label for="<?php echo $this->get_field_id('text_color'); ?>"><?php echo __('Text Color', 'md'); ?></label><br/>
    <input class="md-color-picker" type="text" id="<?php echo $this->get_field_id('text_color'); ?>"
           name="<?php echo $this->get_field_name('text_color'); ?>" value="<?php echo esc_attr($val['text_color']); ?>"
           data-alpha="true"/>
</p>

<p>
    <label for="<?php echo $this->get_field_id('classes'); ?>"><?php echo __('HTML Classes', 'md'); ?>:</label>

    <input type="text" id="<?php echo $this->get_field_id('classes'); ?>"
           name="<?php echo $this->get_field_name('classes'); ?>" value="<?php esc_attr_e($val['classes']); ?>"
           placeholder="form-full" class="widefat"/>
</p>

<script>
    <?php if ( wp_doing_ajax() ) : ?>
    MD.media();
    MD.color();
    <?php endif; ?>
    (function () {
        document.getElementById('<?php echo $this->get_field_id('form_fields_name'); ?>').onchange = function () {
            document.getElementById('<?php echo $this->get_field_id('name_label'); ?>-field').style.display = this.checked ? 'block' : 'none';
        }
    })();
</script>