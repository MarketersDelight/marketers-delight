<div id="cta_bar_<?php echo esc_attr($id); ?>"
	 class="<?php echo esc_attr($classes); ?>"<?php echo md_style(array('bg_color' => $colors['bg_color'], 'color' => $colors['text_color'])); ?>>
	<div class="inner">
		<div class="cta-bar-inner">
			<div class="cta-bar-content"<?php echo md_style(array('width' => $content_width, 'width_unit' => '%')); ?>>
				<?php if (!empty($media_type)) : ?>
					<div class="cta-bar-media<?php echo esc_attr($image_classes); ?>">
						<?php if ($media_type == 'icon' && !empty($fields['icon'])) : ?>
							<i class="cta-bar-icon <?php echo esc_attr($fields['icon']); ?>"<?php echo md_style(array('bg_color' => $colors['icon_color'], 'color' => $colors['icon_text_color'])); ?>></i>
						<?php elseif ($media_type == 'image' && !empty($fields['image'])) : ?>
							<div class="cta-bar-image"<?php echo md_style(array('width' => $image_width)); ?>>
								<?php echo wp_get_attachment_image($fields['image']['id'], 'full'); ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<div class="cta-bar-text">
					<?php if (!empty($fields['title'])) : ?>
						<p class="cta-bar-title small-title"><?php echo md_text_field($fields['title']); ?></p>
					<?php endif; ?>
					<?php if (!empty($fields['text'])) : ?>
						<div class="cta-bar-desc"<?php echo md_style(array('color' => $colors['text_sec_color'])); ?>>
							<?php echo wpautop($fields['text']); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php if (!empty($cta_type)) : ?>
				<div class="cta-bar-action"<?php echo md_style(array('width' => $cta_width, 'width_unit' => '%')); ?>>
					<?php if ($cta_type == 'button') : ?>
						<?php echo md_button(array(
								'action' => !empty($fields['button_type']) ? $fields['button_type'] : '',
								'link' => !empty($fields['button_url']) ? $fields['button_url'] : '',
								'popup' => !empty($fields['button_popup']) ? $fields['button_popup'] : '',
								'bg_color' => $colors['button_color'],
								'color' => $colors['button_text_color'],
								'text' => $button_text,
								'close' => true,
								'close_class' => 'cta-bar-close',
								'classes' => 'button-arrow'
						)); ?>
					<?php elseif ($cta_type == 'email') : ?>
						<?php md_email_form(
								array(
										'email_list' => !empty($fields['email_list']) ? $fields['email_list'] : '',
										'email_form_style' => !empty($fields['email_form_style']) ? $fields['email_form_style'] : '',
										'email_input' => !empty($fields['email_input']) ? $fields['email_input'] : '',
										'email_name_label' => !empty($fields['email_name_label']) ? $fields['email_name_label'] : '',
										'email_email_label' => !empty($fields['email_email_label']) ? $fields['email_email_label'] : '',
										'email_submit_text' => !empty($fields['email_submit_text']) ? $fields['email_submit_text'] : ''
								),
								array(
										'email_submit_bg_color' => $colors['button_color'],
										'email_submit_color' => $colors['button_text_color']
								)
						); ?>
					<?php elseif ($cta_type == 'html' && !empty($fields['html'])) : ?>
						<?php if (!empty($fields['html_format']['wp'])) : ?>
							<?php echo apply_filters('the_content', $fields['html']); ?>
						<?php else : ?>
							<?php echo $fields['html']; ?>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php if (!empty($fields['display']['close'])) : ?>
		<div class="cta-bar-corner cta-bar-close"><?php echo md_icon('cancel'); ?></div>
	<?php endif; ?>
</div>
<?php if (!empty($fields['links_color'])) : ?>
	<style type="text/css">
		#cta_bar_<?php echo esc_attr( $id ); ?> .cta-bar-content a {
			border-bottom-color: <?php echo esc_attr( $fields['links_color'] ); ?>;
			color: <?php echo esc_attr( $fields['links_color'] ); ?>;
		}
	</style>
<?php endif; ?>
