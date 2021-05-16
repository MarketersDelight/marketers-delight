<div id="md_popup_<?php echo esc_attr($fields['id']); ?>"
	 class="<?php echo esc_attr($fields['classes']); ?>"<?php echo md_style(array('bg_color' => $fields['bg_color'], 'border_color' => $fields['border_color'], 'color' => $fields['text_color'], 'bg_image' => $fields['bg_image'], 'bg_size' => $fields['bg_repeat'])); ?>>
	<?php if (empty($fields['enable_custom_template'])) : ?>
		<?php if (!empty($fields['subtitle']) || is_customize_preview()) : ?>
			<div class="popup-secondary-color popup-secondary-bg-color popup-subtitle block-half text-center micro-text caps"><?php echo esc_html($fields['subtitle']); ?></div>
		<?php endif; ?>
		<div class="popup-inner block-double text-center">
			<?php if (!empty($fields['featured_image']['id'])) : ?>
				<?php echo wp_get_attachment_image($fields['featured_image']['id'], 'full', false, array(
						'class' => join(' ', array('popup-image', $fields['featured_image']['classes'], $fields['featured_image']['alignment']['center'], 'mb-mid')),
						'style' => $fields['featured_image']['style']['width']
				)); ?>
			<?php endif; ?>
			<?php if (!empty($fields['headline']) || is_customize_preview()) : ?>
				<p class="popup-title huge-title mb-single"<?php echo md_style(array('color' => $fields['headline_color'])); ?>><?php echo md_text_field($fields['headline']); ?></p>
			<?php endif; ?>
			<?php if (!empty($fields['description']) || is_customize_preview()) : ?>
				<div class="popup-text small-text block-double-lr mb-mid text-sec"><?php echo wpautop($fields['description']); ?></div>
			<?php endif; ?>
			<?php if (!empty($fields['button']['enable']) || !empty($fields['button_sec']['enable'])) : ?>
				<div class="popup-buttons mb-half clear">
					<?php if (!empty($fields['button']['enable'])) : ?>
						<?php md_button($fields['button']); ?>
					<?php endif; ?>
					<?php if (!empty($fields['button_sec']['enable'])) : ?>
						<?php md_button($fields['button_sec']); ?>
					<?php endif; ?>
				</div>
				<?php if (!empty($fields['button']['footer_text']) || is_customize_preview()) : ?>
					<p class="popup-buttons-text small italic clear"><?php echo esc_html($fields['button']['footer_text']); ?></p>
				<?php endif; ?>
			<?php endif; ?>
			<?php if (!empty($fields['email']['email_list'])) : ?>
				<?php md_email_form($fields['email'], $fields['email']['atts']); ?>
			<?php endif; ?>
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
