<div class="md-content-wrap">

	<h2 class="md-title"><?php echo __( 'Site Design', 'md' ); ?></h2>

	<p><?php echo __( 'Configure your site\'s design and brand settings here.', 'md' ); ?></p>

	<hr class="md-sep" />

	<div class="md-widget md-toggle md-color-widget md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Color Palette', 'md' ); ?></h3>

		<div class="md-widget-item">

			<p class="description"><?php echo __( 'Enter the colors from your brand guide. These colors will be available throughout your website.', 'md' ); ?></p>

			<hr class="md-sep-small" />

			<div class="md-palette-grid md-sep">
				<?php foreach ( $palette as $color_key => $color ) : ?>
				<div class="md-palette-item">
					<?php $this->fields->field( array( 'palette', $color_key, 'hex' ), array(
						'type' => 'color',
						'hex_only' => true,
						'label' => $color['name'],
						'default' => $palette_defaults[$color_key]
					) ); ?>
				</div>
				<?php endforeach; ?>
			</div>

			<?php

			$this->fields->field( 'custom', array(
				'type' => 'group',
				'label' => __( 'Additional Colors', 'md' ),
				'button_text' => __( 'Add Color', 'md' ),
				'wrap_classes' => 'md-custom-colors md-sep',
				'callback' => function( $group, $field ) {
					$color = md_setting( array( 'colors', $group, $field ) );
					$key = ! empty( $color['key'] ) ? $color['key'] : ( ! empty( $color['name'] ) ? $color['name'] : '' );

					echo '<div class="md-custom-color-row columns-3 columns-25-50-25 columns-half">'.
						 '<div class="col col1">';

					$this->fields->field( array( $group, $field, 'hex' ), array(
						'type' => 'color',
						'hex_only' => true,
						'label' => __( 'Color', 'md' )
					) );

					echo '</div>'.
						 '<div class="col col2">';

					$this->fields->field( array( $group, $field, 'name' ), array(
						'type' => 'text',
						'label' => __( 'Name', 'md' )
					) );

					echo '</div>'.
						 '<div class="col col3">';

					$this->fields->field( array( $group, $field, 'key' ), array(
						'type' => 'text',
						'label' => __( 'Key', 'md' ),
						'placeholder' => sanitize_title( $key )
					) );

					echo '</div></div>';
				}
			) );

			$this->fields->save(); ?>

		</div>

	</div>

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Site Layout', 'md' ); ?></h3>

		<div class="md-widget-item">

			<?php $this->fields->field( 'design', array(
				'type' => 'select',
				'label' => __( 'Site style', 'md' ),
				'empty_label' => __( 'Box style', 'md' ),
				'description' => __( 'Change your site from box style to simple, or add your own styles.', 'md' ),
				'options' => $content_style
			) ); ?>

			<hr class="md-sep-small" />

			<div class="columns-2 columns-single md-sep-micro">
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'width', 'content' ), array(
						'type' => 'range',
						'label' => __( 'Post Width', 'md' ),
						'placeholder' => $defaults['colors']['width']['post'],
						'min' => ( $defaults['colors']['width']['post'] - $line_height * 4 ),
						'max' => ( $defaults['colors']['width']['post'] + $line_height * 8 )
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'width', 'sidebar' ), array(
						'type' => 'range',
						'label' => __( 'Sidebar Width', 'md' ),
						'placeholder' => $defaults['colors']['width']['sidebar'],
						'min' => ( $defaults['colors']['width']['sidebar'] - $line_height * 4 ),
						'max' => ( $defaults['colors']['width']['sidebar'] + $line_height * 8 )
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'width', 'site' ), array(
						'type' => 'range',
						'label' => __( 'Site Width', 'md' ),
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

	<hr class="md-sep-small" />

	<div class="md-widget md-toggle md-color-widget md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Site Colors', 'md' ); ?></h3>

		<div class="md-widget-item columns-2 columns-single">
			<?php foreach ( $options['site'] as $field => $opts ) : ?>
			<div class="col md-sep-small">
				<?php $this->color_field( array( 'site', $field ), $opts ); ?>
			</div>
			<?php endforeach; ?>
		</div>

	</div>

	<div class="md-widget md-toggle md-color-widget md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Actions', 'md' ); ?></h3>

		<div class="md-widget-item">

			<?php foreach ( array(
				'primary' => __( 'Primary Button', 'md' ),
				'secondary' => __( 'Secondary Button', 'md' ),
				'status' => __( 'Status', 'md' )
			) as $action => $label ) : ?>
			<h4><?php echo esc_html( $label ); ?></h4>

			<div class="columns-2 columns-single<?php echo $action !== 'status' ? ' md-sep-small' : ''; ?>">
				<?php foreach ( $options['actions'][$action] as $field => $opts ) : ?>
				<div class="col md-sep-small">
					<?php $this->color_field( array( 'actions', $action, $field ), $opts ); ?>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endforeach; ?>

		</div>

	</div>

	<div class="md-widget md-toggle md-color-widget md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Header and Navigation', 'md' ); ?></h3>

		<div class="md-widget-item">

			<div class="columns-2 columns-single md-sep">
				<?php foreach ( array( 'bg_color', 'text_color', 'border_color' ) as $field ) : ?>
				<div class="col md-sep-small">
					<?php $this->color_field( array( 'header', $field ), $options['header'][$field] ); ?>
				</div>
				<?php endforeach; ?>
			</div>

			<h4><?php echo __( 'Menu', 'md' ); ?></h4>

			<div class="columns-2 columns-single md-sep">
				<?php foreach ( $options['header']['menu'] as $field => $opts ) : ?>
				<div class="col md-sep-small">
					<?php $this->color_field( array( 'header', 'menu', $field ), $opts ); ?>
				</div>
				<?php endforeach; ?>
			</div>

			<h4><?php echo __( 'Submenu', 'md' ); ?></h4>

			<div class="columns-2 columns-single">
				<?php foreach ( $options['header']['submenu'] as $field => $opts ) : ?>
				<div class="col md-sep-small">
					<?php $this->color_field( array( 'header', 'submenu', $field ), $opts ); ?>
				</div>
				<?php endforeach; ?>
			</div>

		</div>

	</div>

	<?php foreach ( array( 'content' => __( 'Content', 'md' ), 'sidebar' => __( 'Sidebar', 'md' ), 'panel' => __( 'Panel', 'md' ), 'footer' => __( 'Footer', 'md' ) ) as $group => $label ) : ?>
	<div class="md-widget md-toggle md-color-widget md-sep-small">

		<h3 class="md-widget-title"><?php echo esc_html( $label ); ?></h3>

		<div class="md-widget-item columns-2 columns-single">
			<?php foreach ( $options[$group] as $field => $opts ) : ?>
			<div class="col md-sep-small">
				<?php $this->color_field( array( $group, $field ), $opts ); ?>
			</div>
			<?php endforeach; ?>
		</div>

	</div>
	<?php endforeach; ?>

	<hr class="md-sep-small" />

	<?php $this->fields->save(); ?>

</div>
