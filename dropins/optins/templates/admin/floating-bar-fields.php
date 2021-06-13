<div class="md-tabs md-conditional">
	<div class="nav-tab-wrapper">
		<a href="#" class="md-tab nav-tab nav-tab-active" data-md-tab="floating-bar-content"><?php echo __( 'Content', 'md' ); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="floating-bar-cta"><?php echo __( 'CTA', 'md' ); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="floating-bar-display"><?php echo __( 'Display', 'md' ); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="floating-bar-design"><?php echo __( 'Design', 'md' ); ?></a>
	</div>
	<div class="floating-bar-content md-tab-content active">
		<div class="md-sep-small">
			<?php $this->fields->field( array( $group, $field, 'title' ), array(
				'type' => 'text',
				'label' => __( 'Title', 'md' )
			) ); ?>
		</div>
		<div class="md-sep-small">
			<?php $this->fields->field( array( $group, $field, 'text' ), array(
				'type' => 'textarea',
				'label' => __( 'Text', 'md' ),
				'rows' => 3
			) ); ?>
		</div>
		<div class="md-sep-small">
			<?php $this->fields->field( array( $group, $field, 'media_type' ), array(
				'type' => 'select',
				'label' => __( 'Media Type', 'md' ),
				'empty_label' => __( 'Select media type...', 'md' ),
				'classes' => 'md-conditional-option',
				'options' => array(
					'image' => __( 'Image', 'md' ),
					'icon' => __( 'Icon', 'md' )
				)
			) ); ?>
		</div>
		<div class="md-conditional-item md-conditional-image md-sep-small" style="display: <?php echo $media_type == 'image' ? 'block' : 'none'; ?>">
			<?php $this->fields->field( array( $group, $field, 'image' ), array(
				'type' => 'upload',
				'upload_type' => 'media',
				'label' => __( 'Image', 'md' )
			) ); ?>
		</div>
		<div class="md-conditional-item md-conditional-icon md-sep-small" style="display: <?php echo $media_type == 'icon' ? 'block' : 'none'; ?>">
			<?php $this->fields->field( array( $group, $field, 'icon' ), array(
				'type' => 'select',
				'label' => __( 'Icon', 'md' ),
				'empty_label' => __( 'Select icon...', 'md' ),
				'options' => $icons
			) ); ?>
		</div>
	</div>
	<div class="floating-bar-cta md-tab-content">
		<div class="md-sep-small">
			<?php $this->fields->field( array( $group, $field, 'cta_type' ), array(
				'type' => 'select',
				'label' => __( 'CTA Type', 'md' ),
				'empty_label' => __( 'Select CTA type...', 'md' ),
				'classes' => 'md-conditional-option',
				'options' => array(
					'button' => __( 'Button', 'md' ),
					'email' => __( 'Email Form', 'md' ),
					'html' => __( 'Custom HTML', 'md' )
				)
			) ); ?>
		</div>
		<div class="floating-button md-conditional-item md-conditional-button md-sep-small" style="display: <?php echo $cta_type == 'button' ? 'block' : 'none'; ?>">
			<hr />
			<h4><?php echo __( 'Button', 'md' ); ?></h4>
			<div class="md-sep-small">
				<?php $this->fields->field( array( $group, $field, 'button_type' ), array(
					'type' => 'select',
					'label' => __( 'Button Type', 'md' ),
					'empty_label' => __( 'Select button type...', 'md' ),
					'classes' => 'floating-button-type',
					'options' => array(
						'url' => __( 'Link', 'md' ),
						'popup' => __( 'Open Popup', 'md' ),
						'close' => __( 'Close Floating Bar', 'md' )
					)
				) ); ?>
			</div>
			<div class="floating-button-url columns-2 columns-single md-sep-small" style="display: <?php echo in_array( $button_type, array( 'url', 'popup', 'close' ) ) ? 'block' : 'none'; ?>">
				<div class="col">
					<?php $this->fields->field( array( $group, $field, 'button_text' ), array(
						'type' => 'text',
						'label' => __( 'Button Text', 'md' ),
						'placeholder' => __( 'Get Access Now', 'md' )
					) ); ?>
				</div>
				<div class="col">
					<?php $this->fields->field( array( $group, $field, 'button_url' ), array(
						'type' => 'text',
						'label' => __( 'Button URL', 'md' ),
						'placeholder' => __( 'https://', 'md' )
					) ); ?>
				</div>
			</div>
			<div class="floating-button-popup md-sep-small" style="display: <?php echo $button_type == 'popup' ? 'block' : 'none'; ?>">
				<?php $this->fields->field( array( $group, $field, 'button_popup' ), array(
					'type' => 'select',
					'label' => __( 'Button Popup', 'md' ),
					'empty_label' => __( 'Select popup...', 'md' ),
					'options' => md_get_popups( 'options' )
				) ); ?>
			</div>
		</div>
		<div class="md-conditional-item md-conditional-email" style="display: <?php echo $cta_type == 'email' ? 'block' : 'none'; ?>">
			<hr />
			<h4><?php echo __( 'Email Form', 'md' ); ?></h4>
			<div class="columns-35-65 columns-single">
				<div class="col col1 md-sep-small">
					<?php $this->fields->field( array( $group, $field, 'email_list' ), array(
						'type' => 'select',
						'label' => __( 'Email List', 'md' ),
						'empty_label' => __( 'Use default email list', 'md' ),
						'optgroup' => true,
						'options' => md_email_data()
					) ); ?>
				</div>
				<div class="col col2">
					<div class="md-sep-micro">
						<?php $this->fields->field( array( $group, $field, 'email_form_style' ), array(
							'type' => 'checkbox',
							'label' => __( 'Form Fields', 'md' ),
							'options' => array(
								'attached' => __( 'Attach input fields to each other in a single line', 'md' )
							)
						) ); ?>
						<?php $this->fields->field( array( $group, $field, 'email_input' ), array(
							'type' => 'checkbox',
							'options' => array(
								'name' => __( 'Ask for subscribers name in signup form', 'md' ),
							)
						) ); ?>
					</div>
					<div id="<?php echo $this->_prefix; ?>_name_label_field" class="md-sep-micro">
						<?php $this->fields->field( array( $group, $field, 'email_name_label' ), array(
							'type' => 'text',
							'label' => __( 'Name Field Label', 'md' ),
							'placeholder' => __( 'Enter your name&hellip;', 'md' )
						) ); ?>
					</div>
					<div class="md-sep-micro">
						<?php $this->fields->field( array( $group, $field, 'email_email_label' ), array(
							'type' => 'text',
							'label' => __( 'Email Field Label', 'md' ),
							'placeholder' => __( 'Enter your email&hellip;', 'md' )
						) ); ?>
					</div>
					<div class="md-sep-micro">
						<?php $this->fields->field( array( $group, $field, 'email_submit_text' ), array(
							'type' => 'text',
							'label' => __( 'Submit Button Text', 'md' ),
							'placeholder' => __( 'Join Now!', 'md' )
						) ); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="md-conditional-html" style="display: <?php echo $cta_type == 'html' ? 'block' : 'none'; ?>">
			<hr />
			<h4><?php echo __( 'Custom HTML', 'md' ); ?></h4>
			<div class="md-sep-micro">
				<?php $this->fields->field( array( $group, $field, 'html' ), array(
					'type' => 'code',
					'description' => sprintf( __( 'Design your own call-to-action with custom HTML, scripts, and %s.', 'md' ), '<a href="https://marketersdelight.com/style-guide/" target="_blank">Helper Classes</a>' )
				) ); ?>
			</div>
			<div class="md-sep-micro">
				<?php $this->fields->field( array( $group, $field, 'html_format' ), array(
					'type' => 'checkbox',
					'options' => array(
						'wp' => __( 'Enable WordPress formatting', 'md' )
					)
				) ); ?>
			</div>
		</div>
	</div>
	<div class="floating-bar-display md-tab-content">
		<?php if ( ! in_array( $screen->base, array( 'post', 'post-new' ) ) ) : ?>
			<div class="md-sep-small">
				<?php $this->fields->field( array( $group, $field, 'locations' ), array(
					'type' => 'checkbox',
					'multi' => true,
					'label' => __( 'Show on...', 'md' ),
					'options' => md_optins_locations( 'options' )
				) ); ?>
			</div>
		<?php endif; ?>
		<div class="columns-2 columns-single">
			<div class="col md-sep-small">
				<?php $this->fields->field( array( $group, $field, 'rules' ), array(
					'type' => 'select',
					'label' => __( 'Show to...', 'md' ),
					'empty_label' => __( 'All visitors', 'md' ),
					'options' => array(
						'logged_in' => __( 'Logged in users only', 'md' ),
						'logged_out' => __( 'Logged out users only', 'md' ),
					)
				) ); ?>
			</div>
			<div class="col md-sep-small">
				<?php $this->fields->field( array( $group, $field, 'position' ), array(
					'type' => 'select',
					'label' => __( 'Position on page', 'md' ),
					'empty_label' => __( 'Bottom of page (floating)', 'md' ),
					'classes' => 'floating-bar-position',
					'options' => array(
						'before_footer' => __( 'Before Footer (static)', 'md' ),
						'top_floating' => __( 'Top of page (floating)', 'md' ),
						'top_static' => __( 'Top of page (static)', 'md' )
					)
				) ); ?>
			</div>
		</div>
		<div class="floating-bar-display-options md-optins-show-fields columns-2 columns-single md-sep-small" style="display: <?php echo $position != 'before_footer' ? 'block' : 'none'; ?>">
			<div class="col md-sep-small">
				<?php $this->fields->field( array( $group, $field, 'show' ), array(
					'type' => 'select',
					'label' => __( 'Show Method', 'md' ),
					'classes' => 'md-optins-show-field',
					'empty_label' => __( 'Show after X seconds (default)', 'md' ),
					'description' => __( 'Configure timing with "Show Delay" setting.', 'md' ),
					'options' => array(
						'percent' => __( 'Show after scroll percentage', 'md' )
					)
				) ); ?>
			</div>
			<div class="md-optins-delay col md-sep-small">
				<?php $this->fields->field( array( $group, $field, 'delay' ), array(
					'type' => 'number',
					'label' => __( 'Show Delay', 'md' ),
					'unit' => $show == 'percent' ? 'percent' : 'seconds',
					'placeholder' => '5',
					'description' => __( 'How long before showing this floating bar.', 'md' )
				) ); ?>
			</div>
			<div class="col">
				<?php $this->fields->field( array( $group, $field, 'display' ), array(
					'type' => 'checkbox',
					'label' => __( 'Close Button', 'md' ),
					'options' => array(
						'close' => __( 'Allow this floating bar to be closed', 'md' )
					)
				) ); ?>
			</div>
			<div class="col">
				<?php $this->fields->field( array( $group, $field, 'cookie' ), array(
					'type' => 'number',
					'label' => __( 'Cookie Duration', 'md' ),
					'unit' => 'days',
					'description' => __( 'Hide floating bar for X days after close.<br />Enter 0 to always show.', 'md' ),
					'placeholder' => 0
				) ); ?>
			</div>
		</div>
	</div>
	<div class="floating-bar-design md-tab-content">
		<h4><?php echo __( 'Layout', 'md' ); ?></h4>
		<div class="md-sep-small">
			<?php $this->fields->field( array( $group, $field, 'layout' ), array(
				'type' => 'checkbox',
				'options' => array(
					'full_width' => __( 'Use entire width of browser', 'md' ),
				)
			) ); ?>
		</div>
		<div class="columns-2 columns-single md-sep-small">
			<div class="col">
				<?php $this->fields->field( array( $group, $field, 'content_width' ), array(
					'type' => 'range',
					'label' => __( 'Content Width', 'md' ),
					'placeholder' => 60,
					'min' => 20,
					'max' => 80,
					'unit' => '%'
				) ); ?>
			</div>
			<div class="col">
				<?php $this->fields->field( array( $group, $field, 'cta_width' ), array(
					'type' => 'range',
					'label' => __( 'CTA Width', 'md' ),
					'placeholder' => 40,
					'min' => 20,
					'max' => 80,
					'unit' => '%'
				) ); ?>
			</div>
		</div>
		<div class="md-conditional-item md-conditional-image columns-2 columns-single md-sep-small" style="display: <?php echo $media_type == 'image' ? 'block' : 'none'; ?>">
			<div class="col">
				<?php $this->fields->field( array( $group, $field, 'image_width' ), array(
					'type' => 'range',
					'label' => __( 'Image Width', 'md' ),
					'placeholder' => 100,
					'min' => 90,
					'max' => 250,
					'unit' => 'px'
				) ); ?>
			</div>
			<div class="col">
				<?php $this->fields->field( array( $group, $field, 'image_style' ), array(
					'type' => 'checkbox',
					'label' => __( 'Image Style', 'md' ),
					'options' => array(
						'shadow' => __( 'Add shadow effect', 'md' ),
						'breakout' => __( 'Add breakout effect', 'md' ),
					)
				) ); ?>
			</div>
		</div>
		<hr class="md-sep-small" />
		<h4><?php echo __( 'Colors', 'md' ); ?></h4>
		<div class="columns-4 columns-single">
			<?php foreach ( $colors as $color_field => $color_fields ) : ?>
				<div class="col md-sep-micro">
					<?php $this->fields->field( array( $group, $field, $color_field ), array(
						'type' => 'color',
						'group' => true,
						'label' => $color_fields['label'],
						'default' => $color_fields['color']
					) ); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<hr class="md-sep-small" />
		<div class="md-sep-small">
			<?php $this->fields->field( array( $group, $field, 'classes' ), array(
				'type' => 'text',
				'label' => __( 'Classes', 'md' ),
				'style' => 'max-width: 400px;',
				'description' => sprintf( __( 'Add additional %s to further style this floating bar.', 'md' ), '<a href="https://marketersdelight.com/style-guide/" target="_blank">CSS classes</a>' )
			) ); ?>
		</div>
	</div>
</div>