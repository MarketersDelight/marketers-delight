<div class="md-sep-small">
	<?php $this->fields->field( 'cover_position', array(
		'type' => 'select',
		'label' => __( 'Cover Photo', 'md' ),
		'empty_label' => __( 'Use default cover image', 'md' ),
		'options' => $sanitize->values['covers']
	) ); ?>
</div>

<div id="md_cover_settings" style="display: <?php echo ! empty( $cover_position ) ? 'block' : 'none'; ?>">

	<div class="md-sep-small">
		<?php $this->fields->field( 'cover_image', array(
			'type' => 'upload',
			'upload_type' => 'media',
			'label' => __( 'Cover Image', 'md' ),
			'description' => __( 'Set a background image for Header and Headline background covers.<br /><b>Recommended photo size: 1280x720px</b>, or smaller for background patterns.', 'md' )
		) ); ?>
	</div>

	<div id="md_cover_overlay" class="md-sep-small" style="display: <?php echo ( empty( $disable_overlay ) && empty( $disable_overlay_single ) ) || ( ! empty( $disable_overlay_single ) && ! empty( $disable_overlay ) ) ? 'block' : 'none'; ?>">
		<?php $this->fields->field( 'bg_color', array(
			'type' => 'color',
			'label' =>  __( 'Cover Overlay', 'md' ),
			'default' => md_setting( array( 'colors', 'page_cover', 'cover_color' ), $values['colors']['header']['cover_color'] )
		) ); ?>
	</div>

	<div class="md-sep-small">
		<?php $this->fields->field( 'text_color', array(
			'type' => 'checkbox',
			'label' => __( 'Cover Settings', 'md' ),
			'options' => array(
				'categories' => __( 'Apply to all Categories', 'md' ),
				'posts' => __( 'Apply to all Posts', 'md' ),
				'alternate' => __( 'Use alternate text color', 'md' ),
				'disable_cover' => $overlay_label
			)
		) ); ?>
	</div>

</div>
