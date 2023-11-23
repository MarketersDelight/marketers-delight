<div class="md-featured-image md-field-row md-sep-small">

	<label class="md-label-wrap"><label class="md-label"><?php echo __( 'Featured Image', 'md' ); ?></label></label>

	<div class="md-field">
		<div class="columns-2 columns-30-70 columns-single">

			<div class="col col1 md-sep-small">
				<?php $this->fields->field( 'image', array(
					'type' => 'upload',
					'upload_type' => 'media',
					'wrap_classes' => 'md-upload-thumbnail'
				) ); ?>
			</div>

			<div class="col col2">

				<?php $this->fields->devices(); ?>

				<?php $this->featured_image_position(); ?>

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

</div>
