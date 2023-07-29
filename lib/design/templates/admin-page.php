<div class="md-content-wrap">

	<h2 class="md-title"><?php echo __( 'Site Design', 'md' ); ?></h2>

	<p><?php echo __( 'Configure your site\'s design and brand settings here.', 'md' ); ?></p>

	<hr class="md-sep" />

	<?php include( 'logo-settings.php' ); ?>

	<div class="md-widget md-toggle md-sep-small">
		<h3 class="md-widget-title"><?php echo __( 'Branding', 'md' ); ?></h3>
		<div class="md-widget-item">
			<p class="description"><?php echo __( '<b>Tip:</b> Save any changes you make to your brand colors to see how they apply across your site.', 'md' ); ?></p>
			<hr class="md-sep-small" />
			<div class="columns-3 columns-single">
				<?php foreach ( $options['site'] as $field => $label ) : ?>
					<div class="col md-sep-small">
						<?php $this->fields->field( array( 'site', $field ), array(
							'type' => 'color',
							'label' => $label,
							'default' => $defaults['colors']['site'][$field]
						) ); ?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php $this->fields->save(); ?>
		</div>
	</div>

	<div class="md-widget md-toggle md-sep-small">
		<h3 class="md-widget-title"><?php echo __( 'Text', 'md' ); ?></h3>
		<div class="md-widget-item columns-3 columns-single">
			<?php foreach ( $options['text'] as $field => $label ) : ?>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'site', $field ), array(
						'type' => 'color',
						'label' => $label,
						'default' => $defaults['colors']['site'][$field]
					) ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="md-widget md-toggle md-sep-small">
		<h3 class="md-widget-title"><?php echo __( 'Buttons', 'md' ); ?></h3>
		<div class="md-widget-item">
			<h4><?php echo __( 'Main Button', 'md' ); ?></h4>
			<div class="columns-3 columns-single">
				<?php foreach ( $options['button'] as $field => $label ) : ?>
					<?php if ( in_array( $field, array( 'button', 'button-text' ) ) ) : ?>
						<div class="col md-sep-small">
							<?php $this->fields->field( array( 'site', $field ), array(
								'type' => 'color',
								'label' => $label,
								'default' => $defaults['colors']['site'][$field]
							) ); ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<hr class="md-sep-small" />
			<h4><?php echo __( 'Secondary Button', 'md' ); ?></h4>
			<div class="columns-3 columns-single">
				<?php foreach ( $options['button'] as $field => $label ) : ?>
					<?php if ( in_array( $field, array( 'button-sec', 'button-sec-text' ) ) ) : ?>
						<div class="col md-sep-small">
							<?php $this->fields->field( array( 'site', $field ), array(
								'type' => 'color',
								'label' => $label,
								'default' => $defaults['colors']['site'][$field]
							) ); ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<hr class="md-sep-small" />

	<?php
		foreach ( array( 'header', 'featured-image', 'content' ) as $name )
			include( "{$name}-settings.php" );
	?>

	<div class="md-widget md-toggle md-sep-small">
		<h3 class="md-widget-title"><?php echo __( 'Sidebar', 'md' ); ?></h3>
		<div class="md-widget-item columns-3 columns-single">
			<?php foreach ( $options['sidebar'] as $field => $label ) : ?>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'sidebar', $field ), array(
						'type' => 'color',
						'label' => $label,
						'default' => ! empty( $defaults['colors']['sidebar'][$field] ) ? $defaults['colors']['sidebar'][$field] : ''
					) ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="md-widget md-toggle md-sep-small">
		<h3 class="md-widget-title"><?php echo __( 'Footer', 'md' ); ?></h3>
		<div class="md-widget-item columns-3 columns-single">
			<?php foreach ( $options['footer'] as $field => $label ) : ?>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'footer', $field ), array(
						'type' => 'color',
						'label' => $label,
						'default' => $defaults['colors']['footer'][$field]
					) ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<hr class="md-sep-small" />

	<?php $this->fields->save(); ?>

</div>
