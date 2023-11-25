<div class="md-widget md-toggle md-sep-small">

	<h3 class="md-widget-title"><?php echo __( 'Page Cover', 'md' ); ?></h3>

	<div class="md-widget-item">

		<p class="description"><?php echo __( 'Set a full-width background color and/or image to your site\'s page header. Apply sitewide and customize on a post and term basis. <strong>Recommended image size: 1280x720px</strong>', 'md' ); ?></p>

		<hr class="md-sep-small" />

		<div class="columns-2 columns-30-70 columns-single md-sep-small">

			<div class="col col1">
				<?php $this->fields->field( array( 'page_cover', 'cover_position' ), array(
					'type' => 'select',
					'label' => __( 'Cover Photo', 'md' ),
					'empty_label' => __( 'Set cover photo...', 'md' ),
					'options' => $this->sanitize->values['covers']
				) ); ?>
			</div>

			<div class="col col2">
				<?php $this->fields->field( array( 'page_cover', 'cover_color' ), array(
					'type' => 'color',
					'label' => __( 'Cover Overlay', 'md' ),
					'default' => $defaults['colors']['page_cover']['cover_color']
				) ); ?>
			</div>

		</div>

		<div class="columns-2 columns-30-70 columns-single">

			<div class="col col1 md-sep-micro">
				<?php $this->fields->field( array( 'page_cover', 'cover_image' ), array(
					'type' => 'upload',
					'upload_type' => 'media',
					'label' => __( 'Cover Image', 'md' ),
				) ); ?>
			</div>

			<div class="col col2">

				<?php $this->fields->field( array( 'page_cover', 'cover_styles' ), array(
					'type' => 'checkbox',
					'label' => __( 'Cover Settings', 'md' ),
					'options' => array(
						'text_color' => __( 'Use alternate text color', 'md' ),
						'disable_cover' => __( 'Remove overlay', 'md' )
					)
				) ); ?>

			</div>

		</div>

	</div>

</div>
