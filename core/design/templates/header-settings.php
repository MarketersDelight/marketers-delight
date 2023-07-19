<div class="md-widget md-toggle md-sep-small">

	<h3 class="md-widget-title"><?php echo __( 'Header', 'md' ); ?></h3>

	<div class="md-widget-item md-tabs">

		<div class="nav-tab-wrapper">
			<a href="#" class="md-tab nav-tab nav-tab-active" data-md-tab="header-colors"><?php echo __( 'Colors', 'md' ); ?></a>
			<?php if ( has_nav_menu( 'main' ) ) : ?>
			<a href="#" class="md-tab nav-tab" data-md-tab="main-menu"><?php echo __( 'Main Menu', 'md' ); ?></a>
			<?php endif; ?>
		</div>

		<div class="md-tab-content active header-colors">

			<div class="columns-3 columns-single">
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'header', 'bg_color' ), array(
						'type' => 'color',
						'label' => $options['header']['bg_color'],
						'default' => $defaults['colors']['header']['bg_color']
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'header', 'color' ), array(
						'type' => 'color',
						'label' => $options['header']['color'],
						'default' => $defaults['colors']['header']['color']
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'header', 'border_color' ), array(
						'type' => 'color',
						'label' => $options['header']['border_color'],
						'default' => $defaults['colors']['header']['border_color']
					) ); ?>
				</div>
			</div>

			<hr class="md-sep-small" />

			<h4><?php echo __( 'Menu', 'md' ); ?></h4>

			<div class="columns-3 columns-single">
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'header', 'menu', 'links' ), array(
						'type' => 'color',
						'label' => $options['header']['menu']['links'],
						'default' => $defaults['colors']['header']['menu']['links']
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'header', 'menu', 'hover' ), array(
						'type' => 'color',
						'label' => $options['header']['menu']['hover'],
						'default' => $defaults['colors']['header']['menu']['hover']
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'header', 'menu', 'active' ), array(
						'type' => 'color',
						'label' => $options['header']['menu']['active'],
						'default' => $defaults['colors']['header']['menu']['active']
					) ); ?>
				</div>
			</div>

			<hr class="md-sep-small" />

			<h4><?php echo __( 'Sub Menu', 'md' ); ?></h4>

			<div class="columns-3 columns-single">
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'header', 'submenu', 'bg_color' ), array(
						'type' => 'color',
						'label' => $options['header']['submenu']['bg_color'],
						'default' => $defaults['colors']['header']['submenu']['bg_color']
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'header', 'submenu', 'links' ), array(
						'type' => 'color',
						'label' => $options['header']['submenu']['links'],
						'default' => $defaults['colors']['header']['submenu']['links']
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'header', 'submenu', 'hover' ), array(
						'type' => 'color',
						'label' => $options['header']['submenu']['hover'],
						'default' => $defaults['colors']['header']['submenu']['hover']
					) ); ?>
				</div>
			</div>

		</div>

		<?php if ( has_nav_menu( 'main' ) ) : ?>
			<div class="md-tab-content main-menu">

				<div class="columns-3 columns-half">
					<?php foreach ( $options['main_menu'] as $field => $label ) : ?>
						<?php if ( in_array( $field, array( 'bg_color', 'subtext' ) ) ) : ?>
							<div class="col md-sep-small">
								<?php $this->fields->field( array( 'main_menu', $field ), array(
									'type' => 'color',
									'label' => $label,
									'default' => ! empty( $defaults['colors']['main_menu'][$field] ) ? $defaults['colors']['main_menu'][$field] : ''
								) ); ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>

				<hr class="md-sep-small" />

				<h4><?php echo __( 'Menu', 'md' ); ?></h4>

				<div class="columns-3 columns-half">
					<?php foreach ( $options['main_menu'] as $field => $label ) : ?>
						<?php if ( in_array( $field, array( 'links', 'active' ) ) ) : ?>
							<div class="col md-sep-small">
								<?php $this->fields->field( array( 'main_menu', $field ), array(
									'type' => 'color',
									'label' => $label,
									'default' => ! empty( $defaults['colors']['main_menu'][$field] ) ? $defaults['colors']['main_menu'][$field] : ''
								) ); ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>

				<hr class="md-sep-small" />

				<h4><?php echo __( 'Sub Menu', 'md' ); ?></h4>

				<div class="columns-3 columns-half">
					<?php foreach ( $options['main_menu'] as $field => $label ) : ?>
						<?php if ( in_array( $field, array( 'sub_menu', 'submenu_links' ) ) ) : ?>
							<div class="col md-sep-small">
								<?php $this->fields->field( array( 'main_menu', $field ), array(
									'type' => 'color',
									'label' => $label,
									'default' => ! empty( $defaults['colors']['main_menu'][$field] ) ? $defaults['colors']['main_menu'][$field] : ''
								) ); ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>

				<hr class="md-sep-small" />

				<h4><?php echo __( 'Icons', 'md' ); ?></h4>

				<div class="columns-3 columns-half">
					<?php foreach ( $options['main_menu'] as $field => $label ) : ?>
						<?php if ( in_array( $field, array( 'icons', 'social' ) ) ) : ?>
							<div class="col md-sep-small">
								<?php $this->fields->field( array( 'main_menu', $field ), array(
									'type' => 'color',
									'label' => $label,
									'default' => ! empty( $defaults['colors']['main_menu'][$field] ) ? $defaults['colors']['main_menu'][$field] : ''
								) ); ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>

			</div>
		<?php endif; ?>

	</div>
</div>