<div class="columns-3 columns-single md-full-select">

	<div class="col">
		<?php $this->fields->field( 'media_type', array(
			'type' => 'select',
			'label' => __( 'Media Type', 'md' ),
			'classes' => 'md-conditional-option',
			'empty_label' => __( 'Featured Image', 'md' ),
			'options' => array(
				'video' => __( 'Video Embed', 'md' ),
				'custom_html' => __( 'Custom HTML', 'md' )
			)
		) ); ?>
	</div>

	<div class="col">
		<?php $this->fields->field( 'position', array(
			'type' => 'select',
			'label' => __( 'Position', 'md' ),
			'empty_label' => __( 'Use default position', 'md' ),
			'wrap_classes' => 'md-sep-small',
			'options' => $this->fields->data->values['featured_image']
		) ); ?>
	</div>

	<div class="col">
		<?php $this->fields->field( 'image_width', array(
			'type' => 'range',
			'label' => __( 'Width', 'md' ),
			'unit' => 'px',
			'max' => '550'
		) ); ?>
	</div>

</div>

<div id="<?php echo $prefix; ?>_featured_media_image" class="md-conditional-item md-conditional-image mt-half" style="display: <?php echo in_array( $media_type, array( '', 'image' ) ) ? 'block' : 'none'; ?>">
	<?php if ( ! $is_post )
		$this->fields->field( 'image', array(
			'type' => 'upload',
			'label' => __( 'Upload Image', 'md' ),
			'upload_type' => 'media'
		) );
	?>
</div>

<div id="<?php echo $prefix; ?>_featured_media_video" class="md-conditional-item md-conditional-video mt-half" style="display: <?php echo $media_type == 'video' ? 'block' : 'none'; ?>">
	<?php $this->fields->field( 'video', array(
		'type' => 'url',
		'label' => __( 'Video URL', 'md' )
	) ); ?>
</div>

<div id="<?php echo $prefix; ?>_featured_media_custom_html" class="md-conditional-item md-conditional-custom_html mt-half" style="display: <?php echo $media_type == 'custom_html' ? 'block' : 'none'; ?>">
	<?php $this->fields->field( 'custom_html', array(
		'type' => 'code',
		'label' => __( 'Custom HTML', 'md' )
	) ); ?>
</div>
