<div class="md-widget md-toggle md-sep-small">

	<h3 class="md-widget-title"><?php echo __( 'Logo', 'md' ); ?></h3>

	<div class="md-widget-item md-header-logo<?php echo ! empty( $this->values['colors']['logo_html_display']['enable'] ) ? ' md-has-logo-html' : ''; ?>">

		<?php $this->fields->devices(); ?>

		<div class="columns-3 columns-single md-sep-micro">
			<?php foreach ( array( 'site_title', 'site_tagline' ) as $site ) : ?>
				<?php if ( empty( $this->values['header']['display'][$site] ) ) : ?>
					<div class="col md-sep-small">
						<?php $this->fields->field( array( 'header', $site ), array(
							'type' => 'color',
							'label' => $options['header'][$site],
							'default' => $defaults['colors']['header'][$site]
						) ); ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>

		<div class="columns-2 columns-single">
			<div class="col md-sep-small">
				<?php $this->fields->field( 'logo', array(
					'type' => 'upload',
					'upload_type' => 'media',
					'label' => __( 'Logo', 'md' )
				) ); ?>
			</div>
			<div class="col md-sep-small">
				<?php $this->fields->field( 'logo_alt', array(
					'type' => 'upload',
					'upload_type' => 'media',
					'label' => __( 'Logo (dark theme)', 'md' )
				) ); ?>
			</div>
		</div>

		<div class="columns-2 columns-single">
			<div class="col md-sep-small">
				<?php foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) : ?>
					<div class="md-<?php echo $device; ?>">
						<?php $this->fields->field( array( 'logo_width', $device ), array(
							'type' => 'range',
							'label' => sprintf( __( 'Logo Width%s', 'md' ), " ($device)" ),
							'max' => 500
						) ); ?>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="col">
				<?php $this->fields->field( 'logo_html_display', array(
					'type' => 'checkbox',
					'label' => __( 'Custom logo', 'md' ),
					'options' => array(
						'enable' => __( 'Enable custom logo HTML', 'md' )
					)
				) ); ?>
			</div>
		</div>

		<div class="md-header-logo-html">
			<?php $this->fields->field( 'logo_html', array(
				'type' => 'code',
				'rows' => 8,
				'label' => __( 'Logo HTML code', 'md' ),
				'description' => sprintf( __( '<b>Tip:</b> Use custom HTML here, including <a href="%s" target="_blank">MD helper classes</a>.', 'md' ), 'https://marketersdelight.com/style-guide/' )
			) ); ?>
		</div>

		<?php $this->fields->save(); ?>

	</div>
</div>