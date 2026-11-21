<div class="md-dashboard md-design-page md-content-wrap-wide">

	<h2 class="md-dashboard-title"><?php echo __( 'Colors', 'md' ); ?></h2>

	<div class="md-widget md-toggle md-color-widget md-dashboard-card">

		<h3 class="md-widget-title"><?php echo md_dropin_icon_tile( 'dashicons-art', '', 'small' ); ?><span><?php echo __( 'Color Palette', 'md' ); ?><span class="md-widget-description"><?php echo __( 'Your brand colors, used across your site', 'md' ); ?></span></span></h3>

		<div class="md-widget-item md-widget-rows">

			<div class="md-palette-grid">

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

			<?php $this->fields->field( 'custom', array(
				'button_text' => __( 'Add Color', 'md' ),
				'wrap_classes' => 'md-custom-colors',
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

	<div class="md-widget md-toggle md-color-widget md-dashboard-card">

		<h3 class="md-widget-title"><?php echo md_dropin_icon_tile( 'dashicons-admin-appearance', '', 'small' ); ?><span><?php echo __( 'Site Colors', 'md' ); ?><span class="md-widget-description"><?php echo __( 'Default colors across your site', 'md' ); ?></span></span></h3>

		<div class="md-widget-item columns-4 columns-single">

			<?php foreach ( $options['site'] as $field => $opts ) : ?>
			<div class="col mb-single">
				<?php $this->color_field( array( 'site', $field ), $opts ); ?>
			</div>
			<?php endforeach; ?>
			<?php foreach ( $options['actions']['status'] as $field => $opts ) : ?>
			<div class="col mb-single">
				<?php $this->color_field( array( 'actions', 'status', $field ), $opts ); ?>
			</div>
			<?php endforeach; ?>

		</div>

	</div>

	<div class="md-card-grid">

		<div class="md-card-stack">

			<h2 class="md-dashboard-title"><?php echo __( 'Elements', 'md' ); ?></h2>

			<div class="md-widget md-toggle md-color-widget md-dashboard-card">

				<h3 class="md-widget-title"><?php echo md_dropin_icon_tile( 'dashicons-button', '', 'small' ); ?><span><?php echo __( 'Buttons', 'md' ); ?><span class="md-widget-description"><?php echo __( 'Colors and shape for every button', 'md' ); ?></span></span></h3>

				<div class="md-widget-item md-widget-rows">

					<div>
						<h4><?php echo __( 'Colors', 'md' ); ?></h4>

						<div class="columns-2 columns-single gap-single">

							<?php foreach ( array( 'primary' => __( 'Primary', 'md' ), 'secondary' => __( 'Secondary', 'md' ) ) as $action => $label ) : ?>
							<?php foreach ( $options['actions'][$action] as $field => $opts ) : ?>
							<div class="col">
								<?php $opts['label'] = $field === 'bg_color' ? $label : sprintf( __( '%s Text', 'md' ), $label );
								$this->color_field( array( 'actions', $action, $field ), $opts ); ?>
							</div>
							<?php endforeach; ?>
							<?php endforeach; ?>

						</div>
					</div>

					<div>
						<h4><?php echo __( 'Shape', 'md' ); ?></h4>

						<div class="mb-single">
							<?php $this->fields->field( 'button_radius', array(
								'id' => 'effects',
								'type' => 'range',
								'label' => __( 'Corner Radius', 'md' ),
								'placeholder' => (int) $effects['border_radius'],
								'min' => 0,
								'max' => 48
							) ); ?>
						</div>

						<div class="columns-2 columns-single gap-single mb-single">
							<div class="col md-full-select">
								<?php $this->fields->field( 'button_weight', array(
									'id' => 'effects',
									'type' => 'select',
									'label' => __( 'Font Weight', 'md' ),
									'empty_label' => sprintf( __( 'Bold Text (%s)', 'md' ), $bold ),
									'options' => $this->fields->data->font_weights()
								) ); ?>
							</div>
							<div class="col md-full-select">
								<?php $this->fields->field( 'button_case', array(
									'id' => 'effects',
									'type' => 'select',
									'label' => __( 'Letter Case', 'md' ),
									'empty_label' => __( 'Normal', 'md' ),
									'options' => array(
										'uppercase' => __( 'Uppercase', 'md' ),
										'lowercase' => __( 'Lowercase', 'md' ),
										'capitalize' => __( 'Capitalize', 'md' )
									)
								) ); ?>
							</div>
						</div>

						<?php $this->fields->field( 'button_shadow', array(
							'id' => 'effects',
							'type' => 'checkbox',
							'label' => '',
							'options' => array( 'remove' => __( 'Remove <b>Shadow</b>', 'md' ) )
						) ); ?>
					</div>

				</div>

			</div>

			<div class="md-widget md-toggle md-color-widget md-dashboard-card">

				<h3 class="md-widget-title"><?php echo md_dropin_icon_tile( 'dashicons-table-row-before', '', 'small' ); ?><span><?php echo __( 'Header', 'md' ); ?><span class="md-widget-description"><?php echo __( 'Header bar colors', 'md' ); ?></span></span></h3>

				<div class="md-widget-item columns-2 columns-single">

					<?php foreach ( array( 'bg_color', 'text_color', 'border_color' ) as $field ) : ?>
					<div class="col mb-single">
						<?php $this->color_field( array( 'header', $field ), $options['header'][$field] ); ?>
					</div>
					<?php endforeach; ?>

				</div>

			</div>

			<div class="md-widget md-toggle md-color-widget md-dashboard-card">

				<h3 class="md-widget-title"><?php echo md_dropin_icon_tile( 'dashicons-menu', '', 'small' ); ?><span><?php echo __( 'Header Menu', 'md' ); ?><span class="md-widget-description"><?php echo __( 'Menu and submenu colors in your header', 'md' ); ?></span></span></h3>

				<div class="md-widget-item md-widget-rows">

					<div>
						<h4><?php echo __( 'Menu', 'md' ); ?></h4>

						<div class="columns-2 columns-single gap-single">

							<?php foreach ( $options['header']['menu'] as $field => $opts ) : ?>
							<div class="col">
								<?php $this->color_field( array( 'header', 'menu', $field ), $opts ); ?>
							</div>
							<?php endforeach; ?>

						</div>
					</div>

					<div>
						<h4><?php echo __( 'Submenu', 'md' ); ?></h4>

						<div class="columns-2 columns-single gap-single">

							<?php foreach ( $options['header']['submenu'] as $field => $opts ) : ?>
							<div class="col">
								<?php $this->color_field( array( 'header', 'submenu', $field ), $opts ); ?>
							</div>
							<?php endforeach; ?>

						</div>
					</div>

				</div>

			</div>

		</div>

		<div class="md-card-stack">

			<h2 class="md-dashboard-title"><?php echo __( 'Layout', 'md' ); ?></h2>

			<?php foreach ( $areas as $group => $area ) : ?>

			<div class="md-widget md-toggle md-color-widget md-dashboard-card">

				<h3 class="md-widget-title"><?php echo md_dropin_icon_tile( $area['icon'], '', 'small' ); ?><span><?php echo esc_html( $area['label'] ); ?><span class="md-widget-description"><?php echo esc_html( $area['description'] ); ?></span></span></h3>

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

	</div>

	<h2 class="md-dashboard-title"><?php echo __( 'Shapes', 'md' ); ?></h2>

	<div class="md-card-grid">
		<?php do_action( 'md_hook_admin_design' ); ?>
	</div>

</div>
