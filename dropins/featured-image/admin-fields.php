<div class="md md-featured-image-meta">
	<?php if ( $screen->base == 'term' ) : ?>
		<div class="md-sep-small">
			<?php $this->fields->field( 'image', array(
				'type' => 'upload',
				'upload_type' => 'media',
				'label' => __( 'Set featured image', 'md' )
			) ); ?>
		</div>
	<?php endif; ?>
	<div class="md-sep-small">
		<?php $this->fields->field( 'position', array(
			'type' => 'select',
			'label' => __( 'Image Position', 'md' ),
			'empty_label' => __( 'Select image position...', 'md' ),
			'options' => $this->sanitize->values['featured_image']
		) ); ?>
	</div>
	<div id="featured_image_cover_field" style="display: <?php echo $cover_display; ?>">
		<div class="md-sep-small">
			<?php $this->fields->field( 'bg_color', array(
				'type' => 'color',
				'label' =>  __( 'Background Color', 'md' ),
				'default' => $this->values['content']['featured_image']['cover_color']
			) ); ?>
		</div>
		<div class="md-sep-small">
			<?php $this->fields->field( 'text_color', array(
				'type' => 'checkbox',
				'options' => array(
					'alternate' => __( 'Show alternate text color', 'md' )
				)
			) ); ?>
		</div>
	</div>
	<?php $this->fields->field( 'caption', array(
		'type' => 'checkbox',
		'options' => array(
			'add' => __( 'Add caption over image', 'md' )
		)
	) ); ?>
</div>