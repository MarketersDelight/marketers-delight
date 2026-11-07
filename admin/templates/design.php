<div class="md-content-wrap">

	<h2 class="md-title"><?php echo __( 'Site Design', 'md' ); ?></h2>

	<p><?php echo __( 'Configure your site\'s design and brand settings here.', 'md' ); ?></p>

	<hr class="mb-mid" />

	<div class="md-widget md-toggle md-color-widget mb-single">

		<h3 class="md-widget-title"><span><?php echo __( 'Color Palette', 'md' ); ?><span class="md-widget-description"><?php echo __( 'Your brand colors, used across your site', 'md' ); ?></span></span></h3>

		<div class="md-widget-item">

			<p class="description"><?php echo __( 'Enter the colors from your brand guide. These colors will be available throughout your website.', 'md' ); ?></p>

			<hr class="mb-single" />

			<div class="md-palette-grid mb-mid">
				<?php foreach ( $palette as $color_key => $color ) : ?>
				<div class="md-palette-item">
					<?php $this->fields->field( array( 'palette', $color_key, 'hex' ), array(
						'hex_only' => true,
						'label' => $color['name'],
						'default' => $palette_defaults[$color_key]
					) ); ?>
				</div>
				<?php endforeach; ?>
			</div>

			<?php

			$this->fields->field( 'custom', array(
				'button_text' => __( 'Add Color', 'md' ),
				'wrap_classes' => 'md-custom-colors mb-mid',
				'callback' => function( $group, $field ) {
					$color = md_setting( array( 'colors', $group, $field ) );
					$key = ! empty( $color['key'] ) ? $color['key'] : ( ! empty( $color['name'] ) ? $color['name'] : '' );

					echo '<div class="md-custom-color-row columns-3 columns-25-50-25 columns-half">'.
						 '<div class="col col1">';

					$this->fields->field( array( $group, $field, 'hex' ), array(
						'hex_only' => true
					) );

					echo '</div>'.
						 '<div class="col col2">';

					$this->fields->field( array( $group, $field, 'name' ) );

					echo '</div>'.
						 '<div class="col col3">';

					$this->fields->field( array( $group, $field, 'key' ), array(
						'placeholder' => sanitize_title( $key )
					) );

					echo '</div></div>';
				}
			) ); ?>

		</div>

	</div>

	<div class="md-widget md-toggle mb-single">

		<h3 class="md-widget-title"><span><?php echo __( 'Site Layout', 'md' ); ?><span class="md-widget-description"><?php echo __( 'Content, sidebar and site widths', 'md' ); ?></span></span></h3>

		<div class="md-widget-item">

			<?php $this->fields->field( 'design', array(
				'empty_label' => __( 'Box style', 'md' ),
				'description' => __( 'Change your site from box style to simple, or add your own styles.', 'md' ),
				'options' => $content_style
			) ); ?>

			<hr class="mb-single" />

			<div class="columns-2 columns-single mb-third gap-single">
				<div class="col">
					<?php $this->fields->field( array( 'width', 'content' ), array(
						'placeholder' => $defaults['colors']['width']['post'],
						'min' => ( $defaults['colors']['width']['post'] - $line_height * 4 ),
						'max' => ( $defaults['colors']['width']['post'] + $line_height * 8 )
					) ); ?>
				</div>
				<div class="col">
					<?php $this->fields->field( array( 'width', 'sidebar' ), array(
						'placeholder' => $defaults['colors']['width']['sidebar'],
						'min' => ( $defaults['colors']['width']['sidebar'] - $line_height * 4 ),
						'max' => ( $defaults['colors']['width']['sidebar'] + $line_height * 8 )
					) ); ?>
				</div>
				<div class="col">
					<?php $this->fields->field( array( 'width', 'site' ), array(
						'placeholder' => $defaults['colors']['width']['site'],
						'min' => ( $defaults['colors']['width']['site'] - $line_height * 4 ),
						'max' => ( $defaults['colors']['width']['site'] + $line_height * 8 )
					) ); ?>
				</div>
			</div>

			<p class="description"><?php echo __( '<b>Tip:</b> Set the <b>Post Width</b> to the exact length your text will read in the content box.', 'md' ); ?></p>

			<p class="description"><?php echo __( '<b>Tip:</b> To calculate your own site width you must add any extra spacing within the content and sidebar, which can change based on the <b>Site style</b>.', 'md' ); ?></p>

			<?php echo '<p class="description"><code>';

			if ( $design )
				echo "<b>$post_width</b> + <b>$sidebar_width</b> + <b>$gap</b> = <b>$site_width</b>";
			else
				echo "<b>( $post_width + $layout_spacing )</b> + <b>$sidebar_width</b> + <b>$gap</b> = <b>$site_width</b>";

			echo '</code></p>'; ?>

		</div>

	</div>

	<hr class="mb-single" />

	<div class="md-widget md-toggle md-color-widget mb-single">

		<h3 class="md-widget-title"><span><?php echo __( 'Site Colors', 'md' ); ?><span class="md-widget-description"><?php echo __( 'Default colors across your site', 'md' ); ?></span></span></h3>

		<div class="md-widget-item columns-2 columns-single">
			<?php foreach ( $options['site'] as $field => $opts ) : ?>
			<div class="col mb-single">
				<?php $this->color_field( array( 'site', $field ), $opts ); ?>
			</div>
			<?php endforeach; ?>
		</div>

	</div>

	<div class="md-widget md-toggle md-color-widget mb-single">

		<h3 class="md-widget-title"><span><?php echo __( 'Actions', 'md' ); ?><span class="md-widget-description"><?php echo __( 'Button and status colors', 'md' ); ?></span></span></h3>

		<div class="md-widget-item">

			<?php foreach ( array(
				'primary' => __( 'Primary Button', 'md' ),
				'secondary' => __( 'Secondary Button', 'md' ),
				'status' => __( 'Status', 'md' )
			) as $action => $label ) : ?>
			<h4><?php echo esc_html( $label ); ?></h4>

			<div class="columns-2 columns-single<?php echo $action !== 'status' ? ' mb-single' : ''; ?>">
				<?php foreach ( $options['actions'][$action] as $field => $opts ) : ?>
				<div class="col mb-single">
					<?php $this->color_field( array( 'actions', $action, $field ), $opts ); ?>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endforeach; ?>

		</div>

	</div>

	<div class="md-widget md-toggle md-color-widget mb-single">

		<h3 class="md-widget-title"><span><?php echo __( 'Header and Navigation', 'md' ); ?><span class="md-widget-description"><?php echo __( 'Header, menu and submenu colors', 'md' ); ?></span></span></h3>

		<div class="md-widget-item">

			<div class="columns-2 columns-single mb-mid">
				<?php foreach ( array( 'bg_color', 'text_color', 'border_color' ) as $field ) : ?>
				<div class="col mb-single">
					<?php $this->color_field( array( 'header', $field ), $options['header'][$field] ); ?>
				</div>
				<?php endforeach; ?>
			</div>

			<h4><?php echo __( 'Menu', 'md' ); ?></h4>

			<div class="columns-2 columns-single mb-mid">
				<?php foreach ( $options['header']['menu'] as $field => $opts ) : ?>
				<div class="col mb-single">
					<?php $this->color_field( array( 'header', 'menu', $field ), $opts ); ?>
				</div>
				<?php endforeach; ?>
			</div>

			<h4><?php echo __( 'Submenu', 'md' ); ?></h4>

			<div class="columns-2 columns-single">
				<?php foreach ( $options['header']['submenu'] as $field => $opts ) : ?>
				<div class="col mb-single">
					<?php $this->color_field( array( 'header', 'submenu', $field ), $opts ); ?>
				</div>
				<?php endforeach; ?>
			</div>

		</div>

	</div>

	<?php $areas = array(
		'content' => array( 'label' => __( 'Content', 'md' ), 'description' => __( 'Colors for your posts and pages', 'md' ) ),
		'sidebar' => array( 'label' => __( 'Sidebar', 'md' ), 'description' => __( 'Colors for your sidebars', 'md' ) ),
		'panel' => array( 'label' => __( 'Panel', 'md' ), 'description' => __( 'Colors for your panels', 'md' ) ),
		'footer' => array( 'label' => __( 'Footer', 'md' ), 'description' => __( 'Colors for your site footer', 'md' ) )
	);

	foreach ( $areas as $group => $area ) : ?>

	<div class="md-widget md-toggle md-color-widget mb-single">

		<h3 class="md-widget-title"><span><?php echo esc_html( $area['label'] ); ?><span class="md-widget-description"><?php echo esc_html( $area['description'] ); ?></span></span></h3>

		<div class="md-widget-item columns-2 columns-single">
			<?php foreach ( $options[$group] as $field => $opts ) : ?>
			<div class="col mb-single">
				<?php $this->color_field( array( $group, $field ), $opts ); ?>
			</div>
			<?php endforeach; ?>
		</div>

	</div>

	<?php endforeach; ?>


</div>
