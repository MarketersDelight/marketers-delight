<div class="md-header-settings md-content-wrap<?php echo $header_layout == 'flyer' ? ' is-flyer' : ''; ?>">

	<h2 class="md-title"><?php echo __( 'Header', 'md' ); ?></h2>

	<p><?php echo __( 'Customize your website header with specialized navigation elements.', 'md' ); ?></p>

	<hr class="md-sep-small" />

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Layout', 'md' ); ?></h3>

		<div class="md-widget-item">

			<div class="md-sep-small">
				<?php $this->fields->field( 'display', array(
					'type' => 'checkbox',
					'label' => __( 'Display', 'md' ),
					'multi' => true,
					'options' => array(
						'site_title' => __( 'Remove <strong>Site Title</strong>', 'md' ),
						'site_tagline' => __( 'Remove <strong>Site Tagline</strong>', 'md' ),
						'align_tagline' => __( 'Align Title and Tagline in one line', 'md' ),
						'hide_title_mobile' => __( 'Hide <strong>Site Title</strong> on mobile', 'md' ),
						'hide_tagline_mobile' => __( 'Hide <strong>Site Tagline</strong> on mobile', 'md' )
					)
				) ); ?>
			</div>

			<div class="md-header-layout md-radio-fields md-clear md-sep-micro">
				<?php $this->fields->field( 'layout', array(
					'type' => 'radio',
					'label' => __( 'Layout', 'md' ),
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

		</div>

	</div>

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Typography', 'md' ); ?></h3>

		<div class="md-widget-item">

			<div class="md-sep-small">
				<?php $this->fields->typography( array(), array(
					'font_size' => array(
						'desktop' => $defaults['header']['font_size']['desktop']
					),
					'line_height' => array(
						'desktop' => $defaults['header']['line_height']['desktop']
					)
				) ); ?>
			</div>

		</div>

	</div>

	<hr class="md-sep-small" />

	<?php $this->fields->field( 'builder', $builder_fields ); ?>

	<?php $this->fields->save(); ?>

</div>
