<div class="md-widget md-toggle md-sep-small">

	<h3 class="md-widget-title"><?php echo __( 'Page Cover', 'md' ); ?></h3>

	<div class="md-widget-item">

		<div class="md-sep-micro">
			<?php $this->fields->field( array( 'page_cover', 'cover_position' ), array(
				'type' => 'select',
				'label' => __( 'Cover Photo', 'md' ),
				'empty_label' => __( 'Set cover photo...', 'md' ),
				'description' => __( 'A cover photo is a decorative image that displays behind the main title of any page.', 'md' ),
				'options' => $this->sanitize->values['covers']
			) ); ?>
		</div>

		<div id="md_cover_settings" class="md-display-none" style="display: <?php echo ! empty( $cover ) ? 'block' : 'none'; ?>">

			<div class="md-sep-micro">
				<?php $this->fields->field( array( 'page_cover', 'cover_image' ), array(
					'type' => 'upload',
					'upload_type' => 'media',
					'label' => __( 'Cover Image', 'md' ),
					'description' => __( 'Set a default background image for all Header/Headline cover posts. You can customize this on each page from the editor screen.', 'md' )
				) ); ?>
			</div>

			<div id="md_cover_overlay" class="md-sep-small" style="display: <?php echo empty( $disable_overlay ) ? 'block' : 'none'; ?>">
				<?php $this->fields->field( array( 'page_cover', 'cover_color' ), array(
					'type' => 'color',
					'label' => __( 'Cover Overlay', 'md' ),
					'default' => $defaults['colors']['header']['cover_color']
				) ); ?>
			</div>

			<div class="md-sep-micro">
				<?php $this->fields->field( array( 'page_cover', 'cover_styles' ), array(
					'type' => 'checkbox',
					'label' => __( 'Cover Settings', 'md' ),
					'options' => array(
						'text_color' => __( 'Use dark text', 'md' ),
						'disable_cover' => __( 'Remove overlay', 'md' )
					)
				) ); ?>
			</div>

		</div>

	</div>

</div>
