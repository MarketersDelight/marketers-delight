<div class="md-tabs md-conditional">
	<div class="nav-tab-wrapper">
		<a href="#" class="md-tab nav-tab nav-tab-active" data-md-tab="cta-content"><?php echo __('Text', 'md'); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="cta-image"><?php echo __('Image', 'md'); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="cta-form"><?php echo __('CTA', 'md'); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="cta-display"><?php echo __('Display', 'md'); ?></a>
	</div>
	<div class="cta-content md-tab-content active">
		<div class="columns-10-90 columns-half">
			<div class="col col1 md-sep-micro">
				<?php $this->fields->field(array($group, $field, 'title_html'), array(
						'label' => __('HTML', 'md'),
						'type' => 'select',
						'options' => array(
								'' => '<p>',
								'h1' => '<h1>',
								'h2' => '<h2>',
								'h3' => '<h3>',
								'h4' => '<h4>',
						)
				)); ?>
			</div>
			<div class="col col2 md-sep-micro">
				<?php $this->fields->field(array($group, $field, 'title'), array(
						'type' => 'text',
						'label' => __('Title', 'md')
				)); ?>
			</div>
		</div>
		<div class="md-sep-micro">
			<?php $this->fields->field(array($group, $field, 'text'), array(
					'type' => 'textarea',
					'label' => __('Text', 'md'),
					'rows' => 3
			)); ?>
		</div>
	</div>
	<div class="cta-image md-tab-content">
		<div class="md-sep-small">
			<?php $this->fields->field(array($group, $field, 'image'), array(
					'type' => 'upload',
					'upload_type' => 'media',
					'label' => __('Image', 'md')
			)); ?>
		</div>
		<div class="columns-2 columns-single">
			<div class="col">
				<?php $this->fields->field(array($group, $field, 'image_width'), array(
						'type' => 'range',
						'label' => __('Image Width', 'md'),
						'unit' => '%',
						'min' => 10,
						'max' => 100
				)); ?>
			</div>
			<div class="col">
				<?php $this->fields->field(array($group, $field, 'image_alignment'), array(
						'type' => 'select',
						'label' => __('Image Alignment', 'md'),
						'options' => array(
								'' => __('Select alignment...', 'md'),
								'alignleft' => __('Align to left', 'md'),
								'alignright' => __('Align to right', 'md'),
								'aligncenter' => __('Align to center', 'md')
						)
				)); ?>
			</div>
		</div>
		<div class="md-sep-micro">
			<?php $this->fields->field(array($group, $field, 'image_classes'), array(
					'type' => 'text',
					'label' => sprintf(__('%s Classes', 'md'), 'Image <acronym title="Cascading Style Sheets">CSS</acronym>'),
					'description' => sprintf(__('Commonly used classes: <code>avatar</code>, <code>shadow</code>, <code>circle</code> | <a href="%s" target="_blank">Enhance with MD helper classes &rarr;</a>', 'md'), 'https://marketersdelight.com/styles/')
			)); ?>
		</div>
	</div>
	<div class="cta-form md-tab-content">
		<div class="md-sep-small">
			<?php $this->fields->field(array($group, $field, 'cta_type'), array(
					'type' => 'select',
					'label' => __('CTA Type', 'md'),
					'empty_label' => __('Select CTA type...', 'md'),
					'classes' => 'md-conditional-option',
					'options' => array(
							'button' => __('Button', 'md'),
							'email' => __('Email Form', 'md'),
							'html' => __('Custom HTML', 'md')
					)
			)); ?>
		</div>
		<div class="cta-button md-conditional-item md-conditional-button md-sep-small"
			 style="display: <?php echo $cta_type == 'button' ? 'block' : 'none'; ?>">
			<hr/>
			<h4><?php echo __('Button', 'md'); ?></h4>
			<div class="md-sep-small">
				<?php $this->fields->field(array($group, $field, 'button_type'), array(
						'type' => 'select',
						'label' => __('Button Type', 'md'),
						'empty_label' => __('Select button type...', 'md'),
						'classes' => 'cta-button-type',
						'options' => array(
								'url' => __('Link', 'md'),
								'popup' => __('Open Popup', 'md'),
//						'close' => __( 'Close Floating Bar', 'md' )
						)
				)); ?>
			</div>
			<div class="cta-button-group columns-2 columns-single md-sep-small"
				 style="display: <?php echo in_array($button_type, array('url', 'popup', 'close')) ? 'block' : 'none'; ?>">
				<div class="col">
					<?php $this->fields->field(array($group, $field, 'button_text'), array(
							'type' => 'text',
							'label' => __('Button Text', 'md'),
							'placeholder' => __('Get Access Now', 'md')
					)); ?>
				</div>
				<div class="col">
					<div class="cta-button-url md-sep-small"
						 style="display: <?php echo $button_type == 'url' ? 'block' : 'none'; ?>">
						<?php $this->fields->field(array($group, $field, 'button_url'), array(
								'type' => 'text',
								'label' => __('Button URL', 'md'),
								'placeholder' => __('https://', 'md')
						)); ?>
					</div>
					<div class="cta-button-popup md-sep-small"
						 style="display: <?php echo $button_type == 'popup' ? 'block' : 'none'; ?>">
						<?php $this->fields->field(array($group, $field, 'button_popup'), array(
								'type' => 'select',
								'label' => __('Button Popup', 'md'),
								'empty_label' => __('Select popup...', 'md'),
								'options' => md_get_popups('options')
						)); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="cta-form md-conditional-item md-conditional-email"
			 style="display: <?php echo $cta_type == 'email' ? 'block' : 'none'; ?>">
			<hr/>
			<h4><?php echo __('Email Form', 'md'); ?></h4>
			<div class="columns-35-65 columns-single">
				<div class="col col1 md-sep-small">
					<?php if (!empty($email_data)) : ?>
						<?php $this->fields->field(array($group, $field, 'email_list'), array(
								'type' => 'select',
								'label' => __('Email List', 'md'),
								'empty_label' => __('Use default email list', 'md'),
								'optgroup' => true,
								'options' => $email_data
						)); ?>
					<?php else : ?>
						<?php md_email_connect_notice(); ?>
					<?php endif; ?>
				</div>
				<div class="col col2">
					<div class="md-sep-micro">
						<?php $this->fields->field(array($group, $field, 'email_form_style'), array(
								'type' => 'checkbox',
								'label' => __('Form Fields', 'md'),
								'options' => array(
										'attached' => __('Attach input fields to each other in a single line', 'md')
								)
						)); ?>
						<?php $this->fields->field(array($group, $field, 'email_input'), array(
								'type' => 'checkbox',
								'options' => array(
										'name' => __('Ask for subscribers name in signup form', 'md'),
								)
						)); ?>
					</div>
					<div id="<?php echo $this->_prefix; ?>_name_label_field" class="md-sep-micro">
						<?php $this->fields->field(array($group, $field, 'email_name_label'), array(
								'type' => 'text',
								'label' => __('Name Field Label', 'md'),
								'placeholder' => __('Enter your name&hellip;', 'md')
						)); ?>
					</div>
					<div class="md-sep-micro">
						<?php $this->fields->field(array($group, $field, 'email_email_label'), array(
								'type' => 'text',
								'label' => __('Email Field Label', 'md'),
								'placeholder' => __('Enter your email&hellip;', 'md')
						)); ?>
					</div>
					<div class="md-sep-micro">
						<?php $this->fields->field(array($group, $field, 'email_submit_text'), array(
								'type' => 'text',
								'label' => __('Submit Button Text', 'md'),
								'placeholder' => __('Join Now!', 'md')
						)); ?>
					</div>
					<div class="md-sep-micro">
						<?php $this->fields->field(array($group, $field, 'email_form_footer'), array(
								'type' => 'text',
								'label' => __('Email Footer Text', 'md')
						)); ?>
					</div>
					<?php if (!empty($data['enabled']['aweber']) || !empty($data['enabled']['mailerlite'])) : ?>
						<div class="md-sep-micro">
							<?php $this->fields->field(array($group, $field, 'email_thank_you'), array(
									'type' => 'text',
									'label' => __('Thank You Page URL', 'md'),
									'description' => sprintf(__('Subscribers will be redirected to this URL after signup. Leave blank to use settings from your email provider. <br /><small>%s only available for AWeber & MailerLite forms.</small>', 'md'), '<span class="required">*</span>')
							)); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="md-conditional-item md-conditional-html"
			 style="display: <?php echo $cta_type == 'html' ? 'block' : 'none'; ?>">
			<hr/>
			<h4><?php echo __('Custom HTML', 'md'); ?></h4>
			<div class="md-sep-micro">
				<?php $this->fields->field(array($group, $field, 'html'), array(
						'type' => 'code',
						'description' => sprintf(__('Design your own call-to-action with custom HTML, scripts, and %s.', 'md'), '<a href="https://marketersdelight.com/style-guide/" target="_blank">Helper Classes</a>')
				)); ?>
			</div>
			<div class="md-sep-micro">
				<?php $this->fields->field(array($group, $field, 'html_format'), array(
						'type' => 'checkbox',
						'options' => array(
								'wp' => __('Enable WordPress formatting', 'md')
						)
				)); ?>
			</div>
		</div>
	</div>
	<div class="cta-display md-tab-content">
		<?php if (!in_array($screen->base, array('post', 'post-new'))) : ?>
			<div class="md-sep-micro">
				<?php $this->fields->field(array($group, $field, 'locations'), array(
						'type' => 'checkbox',
						'multi' => true,
						'label' => __('Show on...', 'md'),
						'options' => md_optins_locations('options')
				)); ?>
			</div>
		<?php endif; ?>
		<div class="columns-2 columns-single">
			<div class="col md-sep-small">
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
			<div class="col md-sep-small">
				<?php $this->fields->field(array($group, $field, 'position'), array(
						'type' => 'select',
						'label' => __('Position on page', 'md'),
						'empty_label' => __('After Header (default)', 'md'),
						'classes' => 'cta-position',
						'options' => array(
								'before_html' => __('Before Header', 'md'),
								'before_content' => __('Before Content', 'md'),
								'content' => __('After Post', 'md'),
								'before_sidebar' => __('Before Sidebar', 'md'),
								'after_sidebar' => __('After Sidebar', 'md'),
								'before_footer' => __('Before Footer', 'md')
						)
				)); ?>
			</div>
		</div>
		<hr class="md-sep-small"/>
		<div class="columns-4 columns-single">
			<?php foreach ($colors as $color_field => $color_fields) : ?>
				<div class="col md-sep-micro">
					<?php $this->fields->field(array($group, $field, $color_field), array(
							'type' => 'color',
							'group' => true,
							'label' => $color_fields['label'],
							'default' => $color_fields['color']
					)); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="md-sep-micro">
			<?php $this->fields->field(array($group, $field, 'bg_image'), array(
					'type' => 'upload',
					'upload_type' => 'media',
					'label' => __('Background Image', 'md')
			)); ?>
		</div>
		<div class="md-sep-small">
			<?php $this->fields->field(array($group, $field, 'bg_image_style'), array(
					'type' => 'checkbox',
					'options' => array(
							'repeat' => __('Use background repeat', 'md'),
					)
			)); ?>
		</div>
		<hr class="md-sep-small"/>
		<div class="md-sep-micro">
			<?php $this->fields->field(array($group, $field, 'classes'), array(
					'type' => 'text',
					'label' => sprintf(__('%s Classes', 'md'), '<acronym title="Cascading Style Sheets">CSS</acronym>'),
					'description' => sprintf(__('Commonly used classes: <code>shadow</code>, <code>text-center</code> | <a href="%s" target="_blank">Enhance with MD helper classes &rarr;</a>', 'md'), 'https://marketersdelight.com/styles/')
			)); ?>
		</div>
	</div>
</div>
