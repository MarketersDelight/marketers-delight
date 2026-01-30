<div class="md-content-wrap">

	<h2 class="md-title"><?php echo __( 'Site Design', 'md' ); ?></h2>

	<p><?php echo __( 'Configure your site\'s design and brand settings here.', 'md' ); ?></p>

	<hr class="md-sep" />

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

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Site', 'md' ); ?></h3>

		<div class="md-widget-item">

			<?php
			$content_style = md_filter_loop_styles();
			unset( $content_style['box_style'] );
			$this->fields->field( 'design', array(
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

			<p class="description"><?php echo __( '<b>Tip:</b> Set the <b>Post Width</b> to the exact length your text will read in the content box.', 'md' ); ?>

			<p class="description"><?php echo __( '<b>Tip:</b> To calculate your own site width you must add any extra spacing within the content and sidebar, which can change based on the <b>Site style</b>.', 'md' ); ?></p>

			<?php

		$design = md_setting( array( 'colors', 'design' ) );

		$post_width = round( 21 * $line_height );
		$layout_spacing = ! $design ? ( $line_height + round( $line_height / 2 ) ) * 2 : 0;
		$content_width = $post_width + $layout_spacing;
		$content_width = apply_filters( 'md_filter_css_content_width', $content_width, $post_width, $line_height );
		$sidebar_width = round( 12 * $line_height );

		$site_width = $content_width + $sidebar_width + $line_height; #add $line_height to account for gap

		echo '<p class="description"><code>';

		if ( $design )
			echo "<b>$post_width</b> + <b>$sidebar_width</b> + <b>$line_height</b> = <b>$site_width</b>";
		else
			echo "<b>( $post_width + $layout_spacing )</b> + <b>$sidebar_width</b> + <b>$line_height</b> = <b>$site_width</b>";

		echo '</code></p>';

		// 651 + 372 + 94 = 1054

		// <br /><br /><code><b>%s</b> + <b>%2s</b>%3s = <b>%4s</b></code>', 'md' ), $values['colors']['width']['post'], $values['colors']['width']['sidebar'], ( md_setting( array( 'content', 'style' ) ) == '' ? ' + <b>' . ( $layout_spacing * 2 ) . '</b>' : '' ), $values['colors']['width']['site']
			?>

		</div>
	</div>

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Header', 'md' ); ?></h3>

		<div class="md-widget-item">

			<div class="columns-3 columns-single">
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'header', 'bg_color' ), array(
						'type' => 'color',
						'label' => __( 'Background', 'md' ),
						'default' => $defaults['colors']['header']['bg_color']
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'header', 'color' ), array(
						'type' => 'color',
						'label' => __( 'Text', 'md' ),
						'default' => $defaults['colors']['header']['color']
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'header', 'border_color' ), array(
						'type' => 'color',
						'label' => __( 'Border', 'md' ),
						'default' => $defaults['colors']['header']['border_color']
					) ); ?>
				</div>
			</div>

			<hr class="md-sep-small" />

			<h4><?php echo __( 'Menu', 'md' ); ?></h4>

			<div class="columns-3 columns-single">
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'menu', 'links' ), array(
						'type' => 'color',
						'label' => __( 'Links', 'md' ),
						'default' => $defaults['colors']['menu']['links']
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'menu', 'hover' ), array(
						'type' => 'color',
						'label' => __( 'Links Hover', 'md' ),
						'default' => $defaults['colors']['menu']['hover']
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'menu', 'active' ), array(
						'type' => 'color',
						'label' => __( 'Links Active', 'md' ),
						'default' => $defaults['colors']['menu']['active']
					) ); ?>
				</div>
			</div>

			<hr class="md-sep-small" />

			<h4><?php echo __( 'Sub Menu', 'md' ); ?></h4>

			<div class="columns-3 columns-single">
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'submenu', 'bg_color' ), array(
						'type' => 'color',
						'label' => __( 'Background', 'md' ),
						'default' => $defaults['colors']['submenu']['bg_color']
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'submenu', 'links' ), array(
						'type' => 'color',
						'label' => __( 'Links', 'md' ),
						'default' => $defaults['colors']['submenu']['links']
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'submenu', 'hover' ), array(
						'type' => 'color',
						'label' => __( 'Links Hover', 'md' )
					) ); ?>
				</div>
			</div>

			<?php do_action( 'md_design_header_settings' ); ?>

		</div>
	</div>

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Content', 'md' ); ?></h3>

		<div class="md-widget-item">

			<div class="columns-3 columns-single">
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'content', 'body_color' ), array(
						'type' => 'color',
						'label' => __( 'Content Body', 'md' ),
						'default' => $defaults['colors']['content']['body_color']
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'content', 'bg_color' ), array(
						'type' => 'color',
						'label' => __( 'Content Box', 'md' ),
						'default' => $defaults['colors']['content']['bg_color']
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'content', 'border_color' ), array(
						'type' => 'color',
						'label' => __( 'Border', 'md' ),
						'default' => $defaults['colors']['content']['border_color']
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'content', 'page_cover' ), array(
						'type' => 'color',
						'label' => __( 'Page Cover', 'md' ),
						'default' => $defaults['colors']['content']['page_cover']
					) ); ?>
				</div>
			</div>

		</div>
	</div>

	<div class="md-widget md-toggle md-sep-small">
		<h3 class="md-widget-title"><?php echo __( 'Sidebar', 'md' ); ?></h3>
		<div class="md-widget-item columns-3 columns-single">
			<?php foreach ( $options['sidebar'] as $field => $label ) :
				$default = $field !== 'bg_color' ? $defaults['colors']['sidebar'][$field] : '';
			?>
			<div class="col md-sep-small">
				<?php $this->fields->field( array( 'sidebar', $field ), array(
					'type' => 'color',
					'label' => $label,
					'default' => $default
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