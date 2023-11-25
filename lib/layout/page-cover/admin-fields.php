<p class="description"><?php echo __( 'Add or modify the Page Cover settings of this page below. <strong>Recommended image size: 1280x720px</strong>', 'md' ); ?></p>

<hr class="md-sep-small" />

<div class="columns-2 columns-30-70 columns-single md-sep-small">

	<div class="col col1 md-sep-small">
		<?php $this->fields->field( 'cover_position', array(
			'type' => 'select',
			'label' => __( 'Cover Photo', 'md' ),
			'empty_label' => __( 'Use default cover image', 'md' ),
			'options' => $sanitize->values['covers']
		) ); ?>
	</div>

	<div class="col col2">
		<?php $this->fields->field( 'bg_color', array(
			'type' => 'color',
			'label' =>  __( 'Cover Overlay', 'md' ),
			'default' => md_setting( array( 'colors', 'page_cover', 'cover_color' ), $values['colors']['page_cover']['cover_color'] ),
			'wrap_classes' => 'md-sep-small'
		) ); ?>
	</div>

</div>

<div class="columns-2 columns-30-70 columns-single md-sep-small">

	<div class="col col1">
		<?php $this->fields->field( 'cover_image', array(
			'type' => 'upload',
			'upload_type' => 'media',
			'label' => __( 'Cover Image', 'md' ),
		) ); ?>
	</div>

	<div class="col col2">
		<?php $this->fields->field( 'text_color', array(
			'type' => 'checkbox',
			'label' => __( 'Cover Settings', 'md' ),
			'options' => $cover_settings
		) ); ?>
	</div>

</div>
