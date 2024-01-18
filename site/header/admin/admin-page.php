<div class="md-header-settings md-content-wrap<?php echo $header_layout == 'flyer' ? ' is-flyer' : ''; ?>">

	<?php $this->fields->devices(); ?>

	<h2 class="md-title"><?php echo __( 'Header', 'md' ); ?></h2>

	<p><?php echo __( 'Customize your website header with specialized navigation elements.', 'md' ); ?></p>

	<hr class="md-sep-small" />

	<?php include( 'layout-fields.php' ); ?>

	<?php include( 'design-fields.php' ); ?>

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Typography', 'md' ); ?></h3>

		<div class="md-widget-item">

			<div class="md-sep-small">
				<?php $this->fields->typography( array(), array(
					'font_size' => array(
						'desktop' => $defaults['header']['font_size']['desktop']
					),
					'line_height' => array(
						'desktop' => $defaults['header']['line_height']['desktop']
					)
				) ); ?>
			</div>

		</div>

	</div>

	<?php $this->fields->field( 'builder', $builder_fields ); ?>

	<?php $this->fields->save(); ?>

</div>
