<div class="md-content-wrap">

	<?php $this->fields->devices(); ?>

	<h2 class="md-title"><?php echo __( 'Fonts & Typography', 'md' ); ?></h2>

	<p><?php echo __( 'Assign custom fonts and fine-tune your website\'s typography.', 'md' ); ?></p>

	<hr class="mb-mid" />

	<div class="md-widget md-toggle mb-single">

		<h3 class="md-widget-title"><span><?php echo __( 'Body', 'md' ); ?><span class="md-widget-description"><?php echo __( 'The main text across your site', 'md' ); ?></span></span></h3>

		<div class="md-widget-item">
			<?php $this->fields->typography( 'body', array(
				'font_size' => array(
					'desktop' => $defaults['body']['font_size']['desktop']
				),
				'line_height' => array(
					'desktop' => $defaults['body']['line_height']['desktop']
				),
				'bold' => true
			) ); ?>
		</div>

	</div>

	<hr class="mb-single" />

	<?php foreach ( array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) as $h ) :
		$label = $h == 'h1' ? __( 'Inherit from body', 'md' ) : __( 'Inherit from <h1>', 'md' );
	?>

		<div class="md-widget md-toggle mb-single">

			<h3 class="md-widget-title"><span><?php echo sprintf( __( 'Headline (%s)', 'md' ), $h ); ?><?php if ( $h === 'h1' ) : ?><span class="md-widget-description"><?php echo __( 'H2 to H6 inherit this font and weight', 'md' ); ?></span><?php endif; ?></span></h3>

			<div class="md-widget-item">

				<?php $this->fields->typography( $h, array(
					'font_size' => array(
						'desktop' => $defaults[$h]['font_size']['desktop']
					),
					'line_height' => array(
						'desktop' => $defaults[$h]['line_height']['desktop']
					),
					'font_family' => array( 'placeholder' => $label ),
					'font_weight' => array( 'empty_label' => $label )
				) ); ?>

			</div>

		</div>

	<?php endforeach; ?>

	<hr class="mb-single" />

	<div class="md-widget md-toggle mb-single">

		<h3 class="md-widget-title"><span><?php echo __( 'Header', 'md' ); ?><span class="md-widget-description"><?php echo __( 'Text in your site header', 'md' ); ?></span></span></h3>

		<div class="md-widget-item">
			<?php $this->fields->typography( 'header', array(
				'font_size' => array(
					'desktop' => $defaults['header']['font_size']['desktop']
				),
				'line_height' => array(
					'desktop' => $defaults['header']['line_height']['desktop']
				)
			) ); ?>
		</div>

	</div>

	<div class="md-widget md-toggle mb-single">

		<h3 class="md-widget-title"><span><?php echo __( 'Sidebar', 'md' ); ?><span class="md-widget-description"><?php echo __( 'Text in your sidebars', 'md' ); ?></span></span></h3>

		<div class="md-widget-item">
			<?php $this->fields->typography( 'sidebar', array(
				'font_size' => array(
					'desktop' => $defaults['sidebar']['font_size']['desktop']
				),
				'line_height' => array(
					'desktop' => $defaults['sidebar']['line_height']['desktop']
				)
			) ); ?>
		</div>

	</div>

	<div class="md-widget md-toggle mb-single">

		<h3 class="md-widget-title"><span><?php echo __( 'Footer', 'md' ); ?><span class="md-widget-description"><?php echo __( 'Text in your site footer', 'md' ); ?></span></span></h3>

		<div class="md-widget-item">
			<?php $this->fields->typography( 'footer', array(
				'font_size' => array(
					'desktop' => $defaults['footer']['font_size']['desktop']
				),
				'line_height' => array(
					'desktop' => $defaults['footer']['line_height']['desktop']
				)
			) ); ?>
		</div>

	</div>


</div>
