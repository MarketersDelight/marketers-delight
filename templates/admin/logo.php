<div class="md-content-wrap md-header-logo<?php echo ! empty( $values['logo']['logo_html_display']['enable'] ) ? ' md-has-logo-html' : ''; ?>">

	<?php $this->fields->devices(); ?>

	<h2 class="md-title"><?php echo __( 'Logo', 'md' ); ?></h2>

	<p><?php echo __( 'Upload a custom logo image and customize the Site Title and Tagline text.', 'md' ); ?></p>

	<hr class="md-sep-small" />

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

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Site Title', 'md' ); ?></h3>

		<div class="md-widget-item">

			<div class="columns-2 columns-25-75 columns-half md-sep-micro">
				<div class="col col1 md-sep-small">
					<?php $this->fields->field( array( 'site_title', 'color' ), array(
						'type' => 'color',
						'label' => __( 'Color', 'md' ),
						'default' => $defaults['logo']['site_title']['color']
					) ); ?>
				</div>
				<div class="col col2 md-sep-micro">
					<?php $this->fields->field( array( 'site_title', 'text' ), array(
						'type' => 'text',
						'label' => __( 'Site Title', 'md' ),
						'placeholder' => get_bloginfo( 'name' )
					) ); ?>
				</div>
			</div>

			<?php $this->fields->typography( 'site_title', array(
				'font_size' => array(
					'desktop' => $defaults['logo']['site_title']['font_size']['desktop']
				),
				'line_height' => array(
					'desktop' => $defaults['logo']['site_title']['line_height']['desktop']
				)
			) ); ?>

		</div>

	</div>

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Site Tagline', 'md' ); ?></h3>

		<div class="md-widget-item">

			<div class="columns-2 columns-25-75 columns-half md-sep-micro">
				<div class="col col1 md-sep-small">
					<?php $this->fields->field( array( 'site_tagline', 'color' ), array(
						'type' => 'color',
						'label' => __( 'Color', 'md' ),
						'default' => $defaults['logo']['site_tagline']['color']
					) ); ?>
				</div>
				<div class="col col2 md-sep-micro">
					<?php $this->fields->field( array( 'site_tagline', 'text' ), array(
						'type' => 'text',
						'label' => __( 'Site Tagline', 'md' ),
						'placeholder' => get_bloginfo( 'description' )
					) ); ?>
				</div>
			</div>

			<?php $this->fields->typography( 'site_tagline' ); ?>

		</div>

	</div>

	<hr class="md-sep-small" />

	<?php $this->fields->save(); ?>

</div>
