<div id="md_popup_<?php echo esc_attr($fields['id']); ?>"
     class="<?php echo esc_attr($fields['classes']) . ' image-' . $fields['featured_image']['alignment']['left']; ?>"<?php echo md_style(array('bg_color' => $fields['bg_color'], 'border_color' => $fields['border_color'], 'color' => $fields['text_color'])); ?>>
    <?php if (empty($fields['enable_custom_template'])) : ?>
        <div class="popup-inner">
            <div class="popup-image-wrap popup-secondary-bg-color"<?php echo md_style(array('bg_color' => $fields['secondary_color'], 'bg_image' => $fields['bg_image'], 'bg_size' => $fields['bg_repeat'])); ?>>
                <?php if (!empty($fields['featured_image']['id'])) : ?>
                    <?php echo wp_get_attachment_image($fields['featured_image']['id'], 'full', false, array(
                        'class' => join(' ', array('popup-image', $fields['featured_image']['classes'])),
                        'style' => $fields['featured_image']['style']['width'],
                        'data-default-align' => 'alignleft'
                    )); ?>
                <?php endif; ?>
            </div>
            <div class="popup-content block-double">
                <?php if (!empty($fields['subtitle']) || is_customize_preview()) : ?>
                    <p class="popup-subtitle mb-half"><?php echo esc_html($fields['subtitle']); ?></p>
                <?php endif; ?>
                <?php if (!empty($fields['headline']) || is_customize_preview()) : ?>
                    <p class="popup-title large-title mb-half"<?php echo md_style(array('color' => $fields['headline_color'])); ?>><?php echo md_text_field($fields['headline']); ?></p>
                <?php endif; ?>
                <?php if (!empty($fields['description']) || is_customize_preview()) : ?>
                    <div class="popup-text micro-text mb-single"><?php echo wpautop($fields['description']); ?></div>
                <?php endif; ?>
                <?php if (!empty($fields['button']['enable']) || !empty($fields['button_sec']['enable'])) : ?>
                    <div class="popup-buttons mb-mid clear">
                        <?php if (!empty($fields['button']['enable'])) : ?>
                            <?php md_button($fields['button']); ?>
                        <?php endif; ?>
                        <?php if (!empty($fields['button_sec']['enable'])) : ?>
                            <?php md_button($fields['button_sec']); ?>
                        <?php endif; ?>
                        <?php if (!empty($fields['button']['footer_text']) || is_customize_preview()) : ?>
                            <p class="popup-buttons-text small italic"><?php echo esc_html($fields['button']['footer_text']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($fields['email']['email_list'])) : ?>
                    <?php md_email_form($fields['email'], $fields['email']['atts']); ?>
                <?php endif; ?>
            </div>
        </div>
    <?php else : ?>
        <div class="popup-inner">
            <?php echo !empty($fields['custom_template_filter']) ? apply_filters('the_content', $fields['custom_template']) : $fields['custom_template']; ?>
        </div>
    <?php endif; ?>
    <div class="md-popup-close md-popup-close-corner"<?php echo md_style(array('color' => $fields['close_color'])); ?>>
        &times;
    </div>
</div>