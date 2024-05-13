<div class="md-conditional md-tabs">

	<?php if ( ! $is_post ) : ?>
	<p class="description"><i class="dashicons dashicons-editor-help"></i> <?php echo __( 'The <strong>Page Title area</strong> contains the Main Title and Description, Featured Image, and the main call to action.<br />Set a cover photo or background color to create a <strong>Hero section!</strong>', 'md' ); ?></p>
	<?php endif; ?>

	<div class="nav-tab-wrapper<?php echo $is_post ? ' md-subnav-tab-wrapper' : ''; ?>">
		<?php if ( ! $is_post ) : ?>
		<a href="#" class="md-tab nav-tab nav-tab-active" data-md-tab="md-page-featured-image"><?php echo __( 'Featured Image', 'md' ); ?></a>
		<?php endif; ?>
		<a href="#" class="md-tab nav-tab<?php echo $is_post ? ' nav-tab-active' : ''; ?>" data-md-tab="md-page-cta"><?php echo __( 'Call to Action', 'md' ); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="md-page-cover"><?php echo __( 'Cover', 'md' ); ?></a>
	</div>

	<?php if ( ! $is_post ) : ?>

	<div class="md-page-featured-image md-tab-content active">

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

	<?php endif; ?>

	<div class="md-page-cta md-conditional md-tab-content<?php echo $is_post ? ' active' : ''; ?>">

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
			<?php $this->fields->field( 'links', array(
				'type' => 'group',
				'sort' => true,
				'style' => 'boxes',
				'secondary' => true,
				'callback' => array( $this, 'sort_fields' ),
				'elements' => array(
					'link_primary' => array(
						'label' => __( 'Primary Link', 'md' )
					),
					'link_secondary' => array(
						'label' => __( 'Secondary Link', 'md' )
					)
				)
			) ); ?>
		</div>

		<div id="<?php echo $prefix; ?>_page_cta_custom" class="md-conditional-item md-conditional-custom" style="display: <?php echo $cta_type == 'custom' ? 'block' : 'none'; ?>">
			<?php $this->fields->field( 'custom_html', array(
				'type' => 'code',
				'label' => __( 'Custom HTML', 'md' ),
			) ); ?>
		</div>

	</div>

	<div class="md-page-cover md-tab-content">

		<div class="columns-2 columns-25-75 columns-single">

			<div class="col col1">
				<?php $this->fields->field( 'cover_photo', array(
					'type' => 'upload',
					'upload_type' => 'media'
				) ); ?>
			</div>

			<div class="col">
				<div class="columns-2 columns-half mb-half">
					<div class="col">
						<?php $this->fields->field( 'cover_position', array(
							'type' => 'select',
							'label' => __( 'Position', 'md' ),
							'empty_label' => __( 'Do not show cover', 'md' ),
							'options' => $sanitize->values['covers']
						) ); ?>
					</div>
					<div class="col">
						<?php $this->fields->field( 'cover_bg_color', array(
							'type' => 'color',
							'label' =>  __( 'Overlay Color', 'md' ),
							'default' => 'rgba(0, 0, 0, 0.5)'
						) ); ?>
					</div>
				</div>
				<?php $this->fields->field( 'cover_display', array(
					'type' => 'checkbox',
					'label' => __( 'Settings', 'md' ),
					'inline' => true,
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