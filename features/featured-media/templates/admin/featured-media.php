<div class="columns-2 columns-single md-full-select">

	<!-- Media -->

	<div class="col col1">

		<?php if ( ! $args['image_only'] ) : ?>
		<div class="md-sep-small">
			<?php if ( ! $can_edit_code && $media_type === 'custom_html' ) : ?>
				<p class="md-label"><?php esc_html_e( 'Custom HTML is managed by an administrator.', 'md' ); ?></p>
			<?php else :
				$fields->field( 'media_type', array(
					'type' => 'select',
					'label' => __( 'Media Type', 'md' ),
					'classes' => 'md-conditional-option',
					'empty_label' => $args['is_post'] ? __( 'Featured Image', 'md' ) : __( 'Image', 'md' ),
					'options' => $can_edit_code ? array(
						'video' => __( 'Video Embed', 'md' ),
						'custom_html' => __( 'Custom HTML', 'md' )
					) : array( 'video' => __( 'Video Embed', 'md' ) )
				) );
			endif; ?>
		</div>
		<?php endif; ?>

		<?php if ( ! $args['is_post'] ) : ?>
		<div id="<?php echo $prefix; ?>_featured_media_image" class="md-conditional-item md-conditional-image md-conditional-default" style="display: <?php echo in_array( $media_type, array( '', 'image' ) ) ? 'block' : 'none'; ?>">
			<?php $fields->field( 'image', array(
				'type' => 'upload',
				'label' => __( 'Image', 'md' ),
				'upload_type' => 'media'
			) ); ?>
		</div>
		<?php endif; ?>

		<?php if ( ! $args['image_only'] ) : ?>
		<div id="<?php echo $prefix; ?>_featured_media_video" class="md-conditional-item md-conditional-video" style="display: <?php echo $media_type == 'video' ? 'block' : 'none'; ?>">
			<?php $fields->field( 'video', array(
				'type' => 'url',
				'label' => __( 'Video URL', 'md' )
			) ); ?>
		</div>

		<?php if ( $can_edit_code ) : ?>
		<div id="<?php echo $prefix; ?>_featured_media_custom_html" class="md-conditional-item md-conditional-custom_html" style="display: <?php echo $media_type == 'custom_html' ? 'block' : 'none'; ?>">
			<?php $fields->field( 'custom_html', array(
				'type' => 'code',
				'label' => __( 'Custom HTML', 'md' )
			) ); ?>
		</div>
		<?php endif; ?>
		<?php endif; ?>

	</div>

	<!-- Layout -->

	<div class="col col2">

		<div class="md-conditional md-sep-micro">

			<div class="md-sep-micro">
				<?php $fields->field( 'position', array(
					'type' => 'select',
					'label' => __( 'Position', 'md' ),
					'classes' => 'md-conditional-option',
					'empty_label' => $args['position_label'],
					'options' => $positions,
					'groups' => $position_groups
				) ); ?>
			</div>

			<div class="md-conditional-item md-conditional-default md-conditional-title_left md-conditional-title_right md-conditional-left md-conditional-right" style="display: <?php echo in_array( $position, $side_positions, true ) ? 'block' : 'none'; ?>">
				<?php $fields->field( 'mobile', array(
					'type' => 'select',
					'label' => __( 'Position on mobile', 'md' ),
					'empty_label' => __( 'Stack (default)', 'md' ),
					'options' => array(
						'beside' => __( 'Keep beside', 'md' ),
						'hide' => __( 'Hide', 'md' )
					)
				) ); ?>
			</div>

		</div>

		<?php $fields->field( 'image_width', array(
			'type' => 'range',
			'label' => __( 'Max width', 'md' ),
			'unit' => 'px',
			'max' => '550'
		) ); ?>

	</div>

</div>
