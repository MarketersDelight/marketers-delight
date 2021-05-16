<div class="md-popup-content">
	<div class="md-sep-micro">
		<a href="<?php echo admin_url('customize.php?autofocus[section]=marketers_delight[popups_data][' . esc_attr($field) . ']'); ?>"
		   target="_blank" class="button button-primary"><i
					class="dashicons dashicons-edit"></i> <?php echo __('Edit in Customizer', 'md'); ?></a>&nbsp;
		<a class="button md-popup-button-shortcode"
		   onClick="window.prompt( 'Copy (ctrl + c + enter) and paste shortcode anywhere on your site.', '[md_popup id=&quot;<?php echo $field; ?>&quot; type=&quot;button&quot; text=&quot;Open popup&quot;]' )"><i
					class="dashicons dashicons-editor-code"></i> <?php echo __('Copy Shortcode', 'md'); ?></a>
	</div>
	<hr class="md-sep-small"/>
	<div class="md-sep-small">
		<?php $this->fields->field(array($group, $field, 'locations'), array(
				'type' => 'checkbox',
				'multi' => true,
				'label' => __('Show on...', 'md'),
				'options' => md_optins_locations('options')
		)); ?>
	</div>
	<div class="md-optins-show-fields md-sep-small">
		<div class="md-sep-micro">
			<?php $this->fields->field(array($group, $field, 'rules'), array(
					'type' => 'select',
					'label' => __('Show to...', 'md'),
					'empty_label' => __('All visitors', 'md'),
					'options' => array(
							'logged_in' => __('Logged in users only', 'md'),
							'logged_out' => __('Logged out users only', 'md'),
					)
			)); ?>
		</div>
		<div class="columns-2 columns-single">
			<div class="col">
				<?php $this->fields->field(array($group, $field, 'show'), array(
						'type' => 'select',
						'label' => __('Show Method', 'md'),
						'empty_label' => __('Show after X seconds (default)', 'md'),
						'classes' => 'md-optins-show-field',
						'description' => __('Configure timing with "Show Delay" setting.', 'md'),
						'options' => array(
								'exit' => __('Show on exit intent', 'md'),
								'percent' => __('Show after scroll percentage', 'md')
						)
				)); ?>
			</div>
			<div class="col md-optins-delay">
				<?php $this->fields->field(array($group, $field, 'delay'), array(
						'type' => 'number',
						'label' => __('Show Delay', 'md'),
						'unit' => $show == 'percent' ? 'percent' : __('seconds', 'md'),
						'placeholder' => '5',
						'description' => __('How long before showing this popup.', 'md')
				)); ?>
			</div>
		</div>
	</div>
	<div class="md-sep-small">
		<?php $this->fields->field(array($group, $field, 'cookie'), array(
				'type' => 'number',
				'label' => __('Cookie Duration', 'md'),
				'unit' => 'days',
				'description' => __('Hide popup for X days after close.<br />Enter 0 to always show.', 'md'),
				'placeholder' => 0
		)); ?>
	</div>
</div>
<div class="md-popup-message">
	<p class="description"><?php echo __('To edit this popup, first save the Popup Settings and return to this panel.', 'md'); ?></p>
</div>
