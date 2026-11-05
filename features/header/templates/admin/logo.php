<div class="md-content-wrap md-header-logo<?php echo ! empty( $values['logo']['logo_html_display']['enable'] ) ? ' md-has-logo-html' : ''; ?>">

	<?php $this->fields->devices(); ?>

	<h2 class="md-title"><?php echo __( 'Logo', 'md' ); ?></h2>

	<p><?php echo __( 'Upload a custom logo image and customize the Site Title and Tagline text.', 'md' ); ?></p>

	<hr class="mb-single" />

	<div class="columns-2 columns-single mb-single">
		<div class="col">
			<?php $this->fields->field( 'logo', array(
				'height' => '200px'
			) ); ?>
		</div>
	</div>

	<div class="columns-2 columns-single mb-single">
		<div class="col">
			<?php foreach ( array( 'desktop', 'mobile' ) as $device ) : ?>
				<div class="md-<?php echo $device; ?>">
					<?php $this->fields->field( array( 'logo_width', $device ) ); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="col">
			<?php $this->fields->field( 'logo_html_display' ); ?>
		</div>
	</div>

	<div class="md-header-logo-html mb-single">
		<?php $this->fields->field( 'logo_html', array(
			'rows' => 8,
			'description' => sprintf( __( '<b>Tip:</b> Use custom HTML here, including <a href="%s" target="_blank">MD helper classes</a>.', 'md' ), 'https://marketersdelight.com/style-guide/' )
		) ); ?>
	</div>

	<div class="md-widget md-toggle mb-single">

		<h3 class="md-widget-title"><?php echo __( 'Site Title', 'md' ); ?></h3>

		<div class="md-widget-item">

			<div class="columns-2 columns-25-75 columns-half mb-third">
				<div class="col col1 mb-single">
					<?php $this->fields->field( array( 'site_title', 'color' ), array(
						'default' => $values['colors']['header']['text_color'],
						'fallback_label' => __( 'Header Text', 'md' )
					) ); ?>
				</div>
				<div class="col col2 mb-third">
					<?php $this->fields->field( array( 'site_title', 'text' ), array(
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

	<div class="md-widget md-toggle mb-single">

		<h3 class="md-widget-title"><?php echo __( 'Site Tagline', 'md' ); ?></h3>

		<div class="md-widget-item">

			<div class="columns-2 columns-25-75 columns-half mb-third">
				<div class="col col1 mb-single">
					<?php $this->fields->field( array( 'site_tagline', 'color' ), array(
						'default' => $values['colors']['site']['muted_text_color'],
						'fallback_label' => __( 'Site Muted Text', 'md' )
					) ); ?>
				</div>
				<div class="col col2 mb-third">
					<?php $this->fields->field( array( 'site_tagline', 'text' ), array(
						'placeholder' => get_bloginfo( 'description' )
					) ); ?>
				</div>
			</div>

			<?php $this->fields->typography( 'site_tagline' ); ?>

		</div>

	</div>


</div>
