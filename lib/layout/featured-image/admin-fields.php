<?php if ( ! $is_post ) : ?>
	<?php $this->fields->devices(); ?>
<?php endif; ?>

<?php $this->featured_image_position(); ?>

<?php if ( ! $is_post ) : ?>

	<div class="md-field-row md-sep-small">
		<?php $this->fields->field( 'image', array(
			'type' => 'upload',
			'upload_type' => 'media',
			'label' => __( 'Upload Image', 'md' )
		) ); ?>
	</div>

	<?php foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) : ?>
		<div class="md-<?php echo esc_attr( $device ); ?> md-field-row">
			<?php $this->fields->field( array( 'image_width', $device ), array(
				'type' => 'range',
				'label' => sprintf( __( 'Image Width (%s)', 'md' ), $device ),
				'unit' => 'px',
				'max' => '550'
			) ); ?>
		</div>
	<?php endforeach; ?>

<?php endif; ?>
