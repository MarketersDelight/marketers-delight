<p class="description"><?php echo __( 'Add or modify the Page Cover settings of this page below. <strong>Recommended image size: 1280x720px</strong>', 'md' ); ?></p>

<hr class="md-sep-small" />

<div class="columns-3 columns-half">

	<div class="col">
		<?php $this->fields->field( 'image', array(
			'type' => 'upload',
			'upload_type' => 'media',
			'label' => __( 'Cover Photo', 'md' ),
		) ); ?>
	</div>

	<div class="col">

		<?php $this->fields->field( 'position', array(
			'type' => 'select',
			'label' => __( 'Cover Type', 'md' ),
			'empty_label' => __( 'Use default cover image', 'md' ),
			'options' => $sanitize->values['covers'],
			'wrap_classes' => 'md-sep-small'
		) ); ?>

		<?php $this->fields->field( 'bg_color', array(
			'type' => 'color',
			'label' =>  __( 'Overlay Color', 'md' ),
			'default' => md_setting( array( 'colors', 'page_cover', 'cover_color' ), $values['colors']['page_cover']['cover_color'] ),
			'wrap_classes' => 'md-sep-small'
		) ); ?>

	</div>

	<div class="col">
		<?php $this->fields->field( 'display', array(
			'type' => 'checkbox',
			'label' => __( 'Settings', 'md' ),
			'options' => array(
				'alternate' => __( 'Use alternate text color', 'md' ),
				'disable_cover' => $disable_overlay ? __( 'Add overlay', 'md' ) : __( 'Remove overlay', 'md' )
			)
		) ); ?>
	</div>

</div>
