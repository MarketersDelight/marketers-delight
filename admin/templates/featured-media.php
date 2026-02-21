<div class="columns-2 columns-single md-full-select<?php echo $is_post ? ' columns-30-70' : ''; ?>">

	<div class="col col1">

		<div class="columns-2 columns-single">

			<div class="col md-sep-small<?php echo $is_post ? ' col-full' : ''; ?>">
				<?php $this->fields->field( 'media_type', array(
					'type' => 'select',
					'label' => __( 'Media Type', 'md' ),
					'classes' => 'md-conditional-option',
					'options' => array(
						'image' => __( 'Featured Image', 'md' ),
						'video' => __( 'Video Embed', 'md' ),
						'custom_html' => __( 'Custom HTML', 'md' )
					)
				) ); ?>
			</div>

			<?php if ( ! $is_post ) : ?>

			<div class="col md-sep-small">
				<?php $this->fields->field( 'position', array(
					'type' => 'select',
					'label' => __( 'Position', 'md' ),
					'empty_label' => __( 'Use default position', 'md' ),
					'wrap_classes' => 'md-sep-small',
					'options' => $this->fields->data->values['featured_image']
				) ); ?>
			</div>

			<div class="col col3 col-full">
				<?php $this->fields->field( 'image_width', array(
					'type' => 'range',
					'label' => __( 'Width', 'md' ),
					'unit' => 'px',
					'max' => '550'
				) ); ?>
			</div>

			<?php endif; ?>

		</div>

	</div>

	<div class="col col2">

		<div id="<?php echo $prefix; ?>_featured_media_image" class="md-conditional-item md-conditional-image" style="display: <?php echo in_array( $media_type, array( '', 'image' ) ) ? 'block' : 'none'; ?>">
			<?php if ( ! $is_post )
				$this->fields->field( 'image', array(
					'type' => 'upload',
					'label' => __( 'Upload Image', 'md' ),
					'upload_type' => 'media'
				) );
			?>
		</div>

		<div id="<?php echo $prefix; ?>_featured_media_video" class="md-conditional-item md-conditional-video" style="display: <?php echo $media_type == 'video' ? 'block' : 'none'; ?>">
			<?php $this->fields->field( 'video', array(
				'type' => 'url',
				'label' => __( 'Video URL', 'md' )
			) ); ?>
		</div>

		<div id="<?php echo $prefix; ?>_featured_media_custom_html" class="md-conditional-item md-conditional-custom_html" style="display: <?php echo $media_type == 'custom_html' ? 'block' : 'none'; ?>">
			<?php $this->fields->field( 'custom_html', array(
				'type' => 'code',
				'label' => __( 'Custom HTML', 'md' )
			) ); ?>
		</div>

	</div>

</div>