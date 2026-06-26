<div class="md-content-wrap">

	<h2 class="md-title"><?php echo __( 'Site Design', 'md' ); ?></h2>

	<p><?php echo __( 'Configure your site\'s design and brand settings here.', 'md' ); ?></p>

	<hr class="md-sep" />

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Branding', 'md' ); ?></h3>

		<div class="md-widget-item">

			<p class="description"><?php echo __( '<b>Tip:</b> Save any changes you make to your brand colors to see how they apply across your site.', 'md' ); ?></p>

			<hr class="md-sep-small" />

			<div class="columns-2 columns-30-70 columns-half md-sep-micro">
				<b class="col col1"><?php echo __( 'Color', 'md' ); ?></b>
				<b class="col col2"><?php echo __( 'Name', 'md' ); ?></b>
			</div>

			<?php foreach ( $palette as $color_key => $color ) : ?>
			<div class="columns-2 columns-30-70 columns-half md-sep">
				<div class="col col1">
					<?php $this->fields->field( array( 'palette', $color_key, 'hex' ), array(
						'type' => 'color',
						'default' => $color
					) ); ?>
				</div>
				<div class="col col2">
					<?php $this->fields->field( array( 'palette', $color_key, 'name' ), array(
						'type' => 'text',
						'readonly' => true,
						'placeholder' => ucwords( str_replace( '-', ' ', $color_key ) )
					) ); ?>
				</div>
			</div>
			<?php endforeach;

			$this->fields->field( 'custom', array(
				'type' => 'group',
				'label' => __( 'Custom Colors', 'md' ),
				'wrap_classes' => 'md-sep',
				'callback' => function( $group, $field ) {
					echo '<div class="columns-2 columns-half columns-30-70">'.
						 '<div class="col col1">';

					$this->fields->field( array( $group, $field, 'hex' ), array(
						'type' => 'color',
					) );

					echo '</div>'.
						 '<div class="col col2">';

					$this->fields->field( array( $group, $field, 'name' ), array(
						'type' => 'text',
					) );

					echo '</div>'.
						 '</div>';
				}
			) );

			$this->fields->save(); ?>

		</div>

	</div>

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Text', 'md' ); ?></h3>

		<div class="md-widget-item columns-2 columns-single">
			<?php foreach ( $options['text'] as $field => $opts ) : ?>
			<div class="col md-sep-small">
				<?php $this->fields->field( array( 'site', $field ), array(
					'type' => 'color',
					'label' => $opts['label'],
					'inherit' => isset( $opts['inherit'] ) ? $opts['inherit'] : null
				) ); ?>
			</div>
			<?php endforeach; ?>
		</div>

	</div>

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Buttons', 'md' ); ?></h3>

		<div class="md-widget-item">

			<h4><?php echo __( 'Main Button', 'md' ); ?></h4>

			<div class="columns-2 columns-single">
				<?php foreach ( $options['button'] as $field => $opts ) : ?>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'site', $field ), array(
						'type' => 'color',
						'label' => $opts['label'],
						'default' => $opts['default']
					) ); ?>
				</div>
				<?php endforeach; ?>
			</div>

			<hr class="md-sep-small" />

			<h4><?php echo __( 'Secondary Button', 'md' ); ?></h4>

			<div class="columns-2 columns-single">
				<?php foreach ( $options['button-secondary'] as $field => $opts ) : ?>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'site', $field ), array(
						'type' => 'color',
						'label' => $opts['label'],
						'default' => $opts['default']
					) ); ?>
				</div>
				<?php endforeach; ?>
			</div>

		</div>

	</div>

	<hr class="md-sep-small" />

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Site', 'md' ); ?></h3>

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

			<p class="description"><?php echo __( '<b>Tip:</b> Set the <b>Post Width</b> to the exact length your text will read in the content box.', 'md' ); ?>

			<p class="description"><?php echo __( '<b>Tip:</b> To calculate your own site width you must add any extra spacing within the content and sidebar, which can change based on the <b>Site style</b>.', 'md' ); ?></p>

			<?php echo '<p class="description"><code>';

			if ( $design )
				echo "<b>$post_width</b> + <b>$sidebar_width</b> + <b>$gap</b> = <b>$site_width</b>";
			else
				echo "<b>( $post_width + $layout_spacing )</b> + <b>$sidebar_width</b> + <b>$gap</b> = <b>$site_width</b>";

			echo '</code></p>'; ?>

		</div>

	</div>

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Header', 'md' ); ?></h3>

		<div class="md-widget-item">

			<div class="columns-2 columns-single md-sep-small">
				<?php foreach ( $options['header'] as $field => $opts ) : ?>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'header', $field ), array(
						'type' => 'color',
						'label' => $opts['label'],
						'inherit' => isset( $opts['inherit'] ) ? $opts['inherit'] : null,
						'default' => isset( $opts['default'] ) ? $opts['default'] : ''
					) ); ?>
				</div>
				<?php endforeach; ?>
			</div>

			<hr class="md-sep-small" />

			<h4><?php echo __( 'Menu', 'md' ); ?></h4>

			<div class="columns-2 columns-single md-sep-small">
				<?php foreach ( $options['menu'] as $field => $opts ) : ?>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'menu', $field ), array(
						'type' => 'color',
						'label' => $opts['label'],
						'inherit' => isset( $opts['inherit'] ) ? $opts['inherit'] : null,
						'default' => isset( $opts['default'] ) ? $opts['default'] : ''
					) ); ?>
				</div>
				<?php endforeach; ?>
			</div>

			<hr class="md-sep-small" />

			<h4><?php echo __( 'Sub Menu', 'md' ); ?></h4>

			<div class="columns-2 columns-single md-sep-small">
				<?php foreach ( $options['submenu'] as $field => $opts ) : ?>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'submenu', $field ), array(
						'type' => 'color',
						'label' => $opts['label'],
						'inherit' => isset( $opts['inherit'] ) ? $opts['inherit'] : null,
						'default' => isset( $opts['default'] ) ? $opts['default'] : ''
					) ); ?>
				</div>
				<?php endforeach; ?>
			</div>

		</div>

	</div>

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Content', 'md' ); ?></h3>

		<div class="md-widget-item columns-2 columns-single">
			<?php foreach ( $options['content'] as $field => $opts ) : ?>
			<div class="col md-sep-small">
				<?php $this->fields->field( array( 'content', $field ), array(
					'type' => 'color',
					'label' => $opts['label'],
					'inherit' => isset( $opts['inherit'] ) ? $opts['inherit'] : null,
					'default' => isset( $opts['default'] ) ? $opts['default'] : ''
				) ); ?>
			</div>
			<?php endforeach; ?>
		</div>

	</div>

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Sidebar', 'md' ); ?></h3>

		<div class="md-widget-item columns-2 columns-single">
			<?php foreach ( $options['sidebar'] as $field => $opts ) : ?>
			<div class="col md-sep-small">
				<?php $this->fields->field( array( 'sidebar', $field ), array(
					'type' => 'color',
					'label' => $opts['label'],
					'inherit' => isset( $opts['inherit'] ) ? $opts['inherit'] : null,
					'default' => isset( $opts['default'] ) ? $opts['default'] : ''
				) ); ?>
			</div>
			<?php endforeach; ?>
		</div>

	</div>

	<div class="md-widget md-toggle md-sep-small">

		<h3 class="md-widget-title"><?php echo __( 'Footer', 'md' ); ?></h3>

		<div class="md-widget-item columns-2 columns-single">
			<?php foreach ( $options['footer'] as $field => $opts ) : ?>
			<div class="col md-sep-small">
				<?php $this->fields->field( array( 'footer', $field ), array(
					'type' => 'color',
					'label' => $opts['label'],
					'inherit' => isset( $opts['inherit'] ) ? $opts['inherit'] : null,
					'default' => isset( $opts['default'] ) ? $opts['default'] : ''
				) ); ?>
			</div>
			<?php endforeach; ?>
		</div>

	</div>

	<hr class="md-sep-small" />

	<?php $this->fields->save(); ?>

</div>