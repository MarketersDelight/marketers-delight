<div class="md-field-row md-sep-small">
	<?php $this->field( 'archives_title', array(
		'type' => 'text',
		'label' => __( 'Page Title', 'md' ),
		'description' => __( 'Add an <code>h1</code> title tag to the top of the page.', 'md' )
	) ); ?>
</div>

<div class="md-field-row md-sep-small">
	<?php $this->field( 'archives_text', array(
		'type' => 'editor',
		'init' => true,
		'label' => __( 'Description', 'md' ),
		'description' => __( 'Write a short description to show below the page title.', 'md' ),
		'rows' => 4
	) ); ?>
</div>

<div class="md-field-row md-conditional">

	<p class="md-label-wrap">
		<label class="md-label"><?php echo __( 'Call to Action', 'md' ); ?></label>
	</p>

	<div class="md-field">

		<?php $this->field( 'page_cta', array(
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
			<?php $this->field( 'links', array(
				'type' => 'group',
				'sort' => true,
				'style' => 'boxes',
				'secondary' => true,
				'subtitle' => true,
				'callback' => array( $this, 'link_fields' ),
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
			<?php $this->field( 'custom_html', array(
				'type' => 'code',
				'label' => __( 'Custom HTML', 'md' ),
			) ); ?>
		</div>

	</div>

</div>

<div class="md-field-row md-tab-content md-sep-small active">

	<p class="md-label-wrap">
		<label class="md-label"><?php echo __( 'Featured Image', 'md' ); ?></label>
	</p>

	<div class="md-field columns-2 columns-40-60 columns-single">

		<div class="col col1">
			<?php $this->field( 'featured_image', array(
				'type' => 'upload',
				'upload_type' => 'media'
			) ); ?>
		</div>

		<div class="col col2">

			<?php $this->field( 'featured_image_position', array(
				'type' => 'select',
				'label' => __( 'Position', 'md' ),
				'empty_label' => __( 'Use default', 'md' ),
				'wrap_classes' => 'md-sep-small',
				'options' => $sanitize->values['featured_image']
			) ); ?>

			<?php foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) : ?>
				<div class="md-<?php echo esc_attr( $device ); ?>">
					<?php $this->field( array( 'featured_image_width', $device ), array(
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

<div class="md-field-row md-sep-small">

	<p class="md-label-wrap">
		<label class="md-label"><?php echo __( 'Page Cover', 'md' ); ?></label>
	</p>

	<div class="md-field columns-2 columns-40-60 columns-single">

		<div class="col col1">
			<?php $this->field( 'cover_photo', array(
				'type' => 'upload',
				'upload_type' => 'media'
			) ); ?>
		</div>

		<div class="col col2">
			<div class="columns-2 columns-single mb-half">
				<div class="col">
					<?php $this->field( 'cover_position', array(
						'type' => 'select',
						'label' => __( 'Position', 'md' ),
						'empty_label' => __( 'No cover', 'md' ),
						'options' => $sanitize->values['covers']
					) ); ?>
				</div>
				<div class="col">
					<?php $this->field( 'cover_bg_color', array(
						'type' => 'color',
						'label' =>  __( 'Overlay Color', 'md' ),
						'default' => 'rgba(0, 0, 0, 0.5)'
					) ); ?>
				</div>
			</div>
			<?php $this->field( 'cover_display', array(
				'type' => 'checkbox',
				'label' => __( 'Settings', 'md' ),
				'inline' => true,
				'options' => array(
					'alternate' => __( 'Invert text color', 'md' ),
					'bg_repeat' => __( 'Background repeat', 'md' ),
					'disable_cover' => __( 'Remove overlay', 'md' )
				)
			) ); ?>
		</div>

	</div>

</div>
