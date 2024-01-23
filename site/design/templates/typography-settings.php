<div class="md-content-wrap">

	<?php $this->fields->devices(); ?>

	<h2 class="md-title"><?php echo __( 'Fonts & Typography', 'md' ); ?></h2>

	<p><?php echo __( 'Assign custom fonts and fine-tune your website\'s typography.', 'md' ); ?></p>

	<hr class="md-sep" />

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Body', 'md' ); ?></h3>

		<div class="md-widget-item">
			<?php $this->fields->typography( 'body', array(
				'font_size' => array(
					'desktop' => $defaults['body']['font_size']['desktop'],
					'tablet' => $defaults['body']['font_size']['tablet'],
					'mobile' => $defaults['body']['font_size']['mobile']
				),
				'line_height' => array(
					'desktop' => $defaults['body']['line_height']['desktop'],
					'tablet' => $defaults['body']['line_height']['tablet'],
					'mobile' => $defaults['body']['line_height']['mobile']
				),
				'bold' => true
			) ); ?>
		</div>

	</div>

	<hr class="md-sep-small" />

	<?php foreach ( array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) as $h ) :
		$label = $h == 'h1' ? __( 'Inherit from body', 'md' ) : __( 'Inherit from <h1>', 'md' );
	?>
		<div class="md-widget md-toggle md-sep-small">

			<h3 class="md-widget-title"><?php echo sprintf( __( 'Headline (%s)', 'md' ), $h ); ?></h3>

			<div class="md-widget-item">

				<?php $this->fields->typography( $h, array(
					'font_size' => array(
						'desktop' => $defaults[$h]['font_size']['desktop'],
						'tablet' => $defaults[$h]['font_size']['tablet'],
						'mobile' => $defaults[$h]['font_size']['mobile']
					),
					'line_height' => array(
						'desktop' => $defaults[$h]['line_height']['desktop'],
						'tablet' => $defaults[$h]['line_height']['tablet'],
						'mobile' => $defaults[$h]['line_height']['mobile']
					),
					'font_family' => array( 'placeholder' => $label ),
					'font_weight' => array( 'empty_label' => $label )
				) ); ?>

			</div>

		</div>

	<?php endforeach; ?>

	<hr class="md-sep-small" />

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Sidebar', 'md' ); ?></h3>

		<div class="md-widget-item">

			<div class="md-sep-small">
				<?php $this->fields->typography( 'sidebar', array(
					'font_size' => array(
						'desktop' => $defaults['sidebar']['font_size']['desktop'],
						'tablet' => $defaults['sidebar']['font_size']['tablet'],
						'mobile' => $defaults['sidebar']['font_size']['mobile']
					),
					'line_height' => array(
						'desktop' => $defaults['sidebar']['line_height']['desktop'],
						'tablet' => $defaults['sidebar']['line_height']['tablet'],
						'mobile' => $defaults['sidebar']['line_height']['mobile']
					)
				) ); ?>
			</div>

			<hr class="md-sep" />

			<div class="md-sep-small">

				<h4 class="md-title"><?php echo __( 'Sidebar Title', 'md' ); ?></h4>

				<?php $this->fields->typography( 'sidebar_title', array(
					'font_size' => array(
						'desktop' => $defaults['sidebar_title']['font_size']['desktop'],
						'tablet' => $defaults['sidebar_title']['font_size']['tablet'],
						'mobile' => $defaults['sidebar_title']['font_size']['mobile']
					),
					'line_height' => array(
						'desktop' => $defaults['sidebar_title']['line_height']['desktop'],
						'tablet' => $defaults['sidebar_title']['line_height']['tablet'],
						'mobile' => $defaults['sidebar_title']['line_height']['mobile']
					),
					'font_family' => array( 'placeholder' => __( 'Inherit from <h3>', 'md' ) ),
					'font_weight' => array( 'empty_label' => __( 'Inherit from <h3>', 'md' ) )
				) ); ?>

			</div>

		</div>

	</div>

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Footer', 'md' ); ?></h3>

		<div class="md-widget-item">

			<div class="md-sep-small">
				<?php $this->fields->typography( 'footer', array(
					'font_size' => array(
						'desktop' => $defaults['footer']['font_size']['desktop'],
						'tablet' => $defaults['footer']['font_size']['tablet'],
						'mobile' => $defaults['footer']['font_size']['mobile']
					),
					'line_height' => array(
						'desktop' => $defaults['footer']['line_height']['desktop'],
						'tablet' => $defaults['footer']['line_height']['tablet'],
						'mobile' => $defaults['footer']['line_height']['mobile']
					)
				) ); ?>
			</div>

			<hr class="md-sep" />

			<div class="md-sep-small">
				<h4 class="md-title"><?php echo __( 'Footer Title', 'md' ); ?></h4>
				<?php $this->fields->typography( 'footer_title', array(
					'font_size' => array(
						'desktop' => $defaults['footer_title']['font_size']['desktop'],
						'tablet' => $defaults['footer_title']['font_size']['tablet'],
						'mobile' => $defaults['footer_title']['font_size']['mobile']
					),
					'line_height' => array(
						'desktop' => $defaults['footer_title']['line_height']['desktop'],
						'tablet' => $defaults['footer_title']['line_height']['tablet'],
						'mobile' => $defaults['footer_title']['line_height']['mobile']
					),
					'font_family' => array( 'placeholder' => __( 'Inherit from <h3>', 'md' ) ),
					'font_weight' => array( 'empty_label' => __( 'Inherit from <h3>', 'md' ) )
				) ); ?>
			</div>

		</div>

	</div>

	<?php $this->fields->field( 'google_fonts', array(
		'type' => 'text',
		'hidden' => true
	) ); ?>

	<hr class="md-sep-small" />

	<?php $this->fields->save(); ?>

</div>
