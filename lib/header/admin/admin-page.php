<div class="md-header-settings md-content-wrap<?php echo $header_layout == 'flyer' ? ' is-flyer' : ''; ?>">
	<h2 class="md-title"><?php echo __( 'Header', 'md' ); ?></h2>
	<p><?php echo __( 'Create a custom header layout with flexible navigation elements.', 'md' ); ?></p>
	<hr class="md-sep-small" />
	<div class="md-widget md-toggle md-sep-small">
		<h3 class="md-widget-title"><?php echo __( 'Layout Settings', 'md' ); ?></h3>
		<div class="md-widget-item">
			<div class="md-header-layout md-radio-fields md-clear md-sep-micro">
				<?php $this->fields->field( 'layout', array(
					'type' => 'radio',
					'label' => __( 'Header Layout', 'md' ),
					'label_icon' => 'dashicons dashicons-desktop',
					'layout' => 'banner',
					'options' => array(
						'standard' => array(
							'name' => __( 'Header Left', 'md' ),
							'description' => __( 'The default header layout with a left-aligned logo and nav menu to the right.', 'md' ),
							'image' => MD_URL . 'lib/admin/images/header-standard.gif'
						),
						'rtl' => array(
							'name' => __( 'Header Right', 'md' ),
							'description' => __( 'A reversed header layout with a right-aligned logo and nav menu to the left.', 'md' ),
							'image' => MD_URL . 'lib/admin/images/header-rtl.gif'
						),
						'flyer' => array(
							'name' => __( 'Header Center', 'md' ),
							'description' => __( 'A logo aligned to the center between two outer navigation areas.', 'md' ),
							'image' => MD_URL . 'lib/admin/images/header-flyer.gif'
						)
					)
				) ); ?>
			</div>
			<div class="md-radio-fields md-clear md-sep-micro">
				<?php $this->fields->field( 'layout_mobile', array(
					'type' => 'radio',
					'label' => __( 'Mobile Layout', 'md' ),
					'label_icon' => 'dashicons dashicons-smartphone',
					'layout' => 'banner',
					'options' => array(
						'standard' => array(
							'name' => __( 'Standard', 'md' ),
							'description' => __( 'The default layout with the logo on the left and controls to the right.', 'md' ),
							'image' => MD_URL . 'lib/admin/images/header-mobile-standard.gif'
						),
						'expanded' => array(
							'name' => __( 'Expanded', 'md' ),
							'description' => __( 'Moves the main nav control before the Logo, and other controls to the right.', 'md' ),
							'image' => MD_URL . 'lib/admin/images/header-mobile-expanded.gif'
						)
					)
				) ); ?>
			</div>
			<hr class="md-sep-micro" />
			<div class="columns-2 columns-single md-sep-micro">
				<div class="col md-sep-micro">
					<?php $this->fields->field( 'site_title', array(
						'type' => 'text',
						'label' => __( 'Site Title', 'md' ),
						'placeholder' => get_bloginfo( 'name' ),
					) ); ?>
				</div>
				<div class="col">
					<?php $this->fields->field( 'site_tagline', array(
						'type' => 'text',
						'label' => __( 'Tagline', 'md' ),
						'placeholder' => get_bloginfo( 'description' )
					) ); ?>
				</div>
			</div>
			<div class="md-sep-micro">
				<?php $this->fields->field( 'display', array(
					'type' => 'checkbox',
					'label' => __( 'Display settings', 'md' ),
					'multi' => true,
					'options' => array(
						'site_title' => __( 'Remove <b>Site Title</b>', 'md' ),
						'site_tagline' => __( 'Remove <b>Site Tagline</b>', 'md' ),
						'align_tagline' => __( 'Align Title and Tagline in one line', 'md' ),
						'hide_title_mobile' => __( 'Hide <b>Site Title</b> on mobile', 'md' ),
						'hide_tagline_mobile' => __( 'Hide <b>Site Tagline</b> on mobile', 'md' )
					)
				) ); ?>
			</div>
		</div>
	</div>

	<hr class="md-sep-small" />

	<?php $this->fields->field( 'builder', $builder_fields ); ?>

	<?php $this->fields->save(); ?>

</div>
