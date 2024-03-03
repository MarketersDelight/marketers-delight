<div class="<?php echo $has_tabs ? 'md-tabs' : 'md-test'; ?> md-conditional">

	<?php if ( $has_tabs ) : ?>

	<p class="description"><i class="dashicons dashicons-editor-help"></i> <?php echo __( 'The <strong>Hero area</strong> contains the Page Title & Description and is located above the fold.<br />Upload a cover photo and CTA for maximum impact.', 'md' ); ?></p>

	<div class="nav-tab-wrapper">
		<a href="#" class="md-tab nav-tab nav-tab-active" data-md-tab="md-hero-featured-image"><?php echo __( 'Featured Image', 'md' ); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="md-hero-cta"><?php echo __( 'Call to Action', 'md' ); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="md-hero-cover"><?php echo __( 'Cover', 'md' ); ?></a>
	</div>

	<div class="md-hero-featured-image md-tab-content active">

		<div class="columns-2 columns-30-70 columns-double">

			<div class="col col1">
				<?php $this->fields->field( 'image', array(
					'type' => 'upload',
					'upload_type' => 'media'
				) ); ?>
			</div>

			<div class="col col2">

				<?php $this->fields->devices(); ?>

				<?php $this->fields->field( 'image_position', array(
					'type' => 'select',
					'label' => __( 'Position', 'md' ),
					'empty_label' => __( 'Use default position', 'md' ),
					'wrap_classes' => 'md-sep-small',
					'options' => $sanitize->values['featured_image']
				) ); ?>

				<?php foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) : ?>

				<div class="md-<?php echo esc_attr( $device ); ?>">
					<?php $this->fields->field( array( 'image_width', $device ), array(
						'type' => 'range',
						'label' => sprintf( __( 'Width (%s)', 'md' ), $device ),
						'unit' => 'px',
						'max' => '550'
					) ); ?>
				</div>

				<?php endforeach; ?>

			</div>
		</div>

	</div>

	<?php else : ?>

	<h3 class="mt-none"><?php echo __( 'Call to Action', 'md' ); ?></h3>

	<?php endif; ?>

	<div class="md-hero-cta md-conditional <?php echo $has_tabs ? 'md-tab-content' : 'md-sep-small'; ?>">

		<?php $this->fields->field( 'page_cta', array(
			'type' => 'select',
			'empty_label' => __( 'Select CTA type...', 'md' ),
			'classes' => 'md-conditional-option',
			'wrap_classes' => 'md-sep-small',
			'options' => array(
				'links' => __( 'Links', 'md' ),
				'custom' => __( 'Custom HTML', 'md' )
			)
		) ); ?>

		<div id="<?php echo $prefix; ?>_page_cta_links" class="md-conditional-item md-conditional-links" style="display: <?php echo $cta_type == 'links' ? 'block' : 'none'; ?>">

			<div class="md-widget md-widget-secondary md-toggle">

				<div class="md-widget-title">
					<?php echo __( 'Secondary Link', 'md' ); ?>
				</div>

				<div class="md-widget-item">
					<?php $this->fields->link_fields( array(
						'group' => array( 'link_secondary' )
					) ); ?>
				</div>

			</div>

			<div class="md-widget md-widget-secondary md-toggle">

				<div class="md-widget-title">
					<?php echo __( 'Primary Link', 'md' ); ?>
				</div>

				<div class="md-widget-item">
					<?php $this->fields->link_fields( array(
						'group' => array( 'link_primary' )
					) ); ?>
				</div>

			</div>

		</div>

		<div id="<?php echo $prefix; ?>_page_cta_custom" class="md-conditional-item md-conditional-custom" style="display: <?php echo $cta_type == 'custom' ? 'block' : 'none'; ?>">
			<?php $this->fields->field( 'custom_html', array(
				'type' => 'code',
				'label' => __( 'Custom HTML', 'md' ),
			) ); ?>
		</div>

	</div>

	<?php if ( ! $has_tabs ) : ?>

	<hr />

	<h3><?php echo __( 'Cover Photo', 'md' ); ?></h3>

	<?php endif; ?>

	<div class="md-hero-cover<?php echo $has_tabs ? ' md-tab-content' : ''; ?>">

		<div class="columns-3 columns-double">

			<div class="col">
				<?php $this->fields->field( 'cover_photo', array(
					'type' => 'upload',
					'upload_type' => 'media'
				) ); ?>
			</div>

			<div class="col">
				<?php $this->fields->field( 'cover_position', array(
					'type' => 'select',
					'label' => __( 'Position', 'md' ),
					'empty_label' => __( 'Do not show cover', 'md' ),
					'options' => $sanitize->values['covers'],
					'wrap_classes' => 'md-sep-micro'
				) ); ?>
				<?php $this->fields->field( 'cover_bg_color', array(
					'type' => 'color',
					'label' =>  __( 'Overlay Color', 'md' ),
					'default' => 'rgba(0, 0, 0, 0.5)',
					'wrap_classes' => 'md-sep-micro'
				) ); ?>
			</div>

			<div class="col">
				<?php $this->fields->field( 'cover_display', array(
					'type' => 'checkbox',
					'label' => __( 'Settings', 'md' ),
					'options' => array(
						'alternate' => __( 'Use alternate text color', 'md' ),
						'bg_repeat' => __( 'Background repeat', 'md' ),
						'disable_cover' => __( 'Remove overlay', 'md' )
					)
				) ); ?>
			</div>

		</div>

	</div>

</div>