<div class="columns-2 columns-30-70 columns-single">

	<div class="col col1">
		<?php $this->fields->field( 'image', array(
			'type' => 'upload',
			'upload_type' => 'media'
		) ); ?>
	</div>

	<div class="col col2">

		<?php $this->fields->field( 'position', array(
			'type' => 'select',
			'label' => __( 'Position', 'md' ),
			'empty_label' => __( 'Use default position', 'md' ),
			'wrap_classes' => 'md-sep-small',
			'options' => $this->fields->data->values['featured_image']
		) ); ?>

		<?php $this->fields->field( 'image_width', array(
			'type' => 'range',
			'label' => __( 'Image Width', 'md' ),
			'unit' => 'px',
			'max' => '550'
		) ); ?>

	</div>

</div>