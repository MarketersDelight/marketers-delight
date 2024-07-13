<div class="md-header-settings md-content-wrap md-tabs<?php echo $header_layout == 'flyer' ? ' is-flyer' : ''; ?>">

	<?php $this->fields->devices(); ?>

	<h2 class="md-title"><?php echo __( 'Edit Header', 'md' ); ?></h2>

	<p><?php echo __( 'Create a custom Header layout and adjust colors and fonts.', 'md' ); ?></p>

	<div class="nav-tab-wrapper">
		<a href="#" class="md-tab nav-tab nav-tab-active" data-md-tab="md-header-settings"><?php echo __( 'Settings', 'md' ); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="md-header-builder"><?php echo __( 'Builder', 'md' ); ?></a>
	</div>

	<div class="md-header-settings md-tab-content active md-sep-small">

		<?php include( md_template( 'admin/header/layout-fields', true ) ); ?>

		<?php include( md_template( 'admin/header/design-fields', true ) ); ?>

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

	</div>

	<div class="md-header-builder md-tab-content">
		<?php $this->fields->field( 'builder', $builder_fields ); ?>
	</div>

	<?php $this->fields->save(); ?>

</div>
