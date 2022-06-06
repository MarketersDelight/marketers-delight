<div class="md-content-wrap">
	<h2 class="md-title"><?php echo __( 'Site Colors', 'md' ); ?></h2>
	<p><?php echo __( 'Set the primary colors to be used throughout your site design and default admin controls.', 'md' ); ?></p>
	<hr class="md-sep" />
	<div class="md-widget md-toggle md-sep-small">
		<h3 class="md-widget-title"><?php echo __( 'Brand Colors', 'md' ); ?></h3>
		<div class="md-widget-item columns-3 columns-single">
			<?php foreach ( $options['site'] as $field => $label ) : ?>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'site', $field ), array(
						'type' => 'color',
						'group' => true,
						'label' => $label,
						'default' => $defaults['site'][$field]
					) ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="md-widget md-toggle md-sep-small">
		<h3 class="md-widget-title"><?php echo __( 'Text Colors', 'md' ); ?></h3>
		<div class="md-widget-item columns-3 columns-single">
			<?php foreach ( $options['text'] as $field => $label ) : ?>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'site', $field ), array(
						'type' => 'color',
						'group' => true,
						'label' => $label,
						'default' => $defaults['site'][$field]
					) ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="md-widget md-toggle md-sep-small">
		<h3 class="md-widget-title"><?php echo __( 'Button Colors', 'md' ); ?></h3>
		<div class="md-widget-item columns-3 columns-single">
			<?php foreach ( $options['button'] as $field => $label ) : ?>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'site', $field ), array(
						'type' => 'color',
						'group' => true,
						'label' => $label,
						'default' => $defaults['site'][$field]
					) ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<hr class="md-sep-small" />
	<div class="md-widget md-toggle md-sep-small">
		<h3 class="md-widget-title"><?php echo __( 'Header', 'md' ); ?></h3>
		<div class="md-widget-item">
			<div class="md-sep-small">
				<?php $this->fields->field( array( 'header', 'bg_color' ), array(
					'type' => 'color',
					'group' => true,
					'label' => $options['header']['bg_color'],
					'default' => $defaults['header']['bg_color']
				) ); ?>
			</div>
			<hr class="md-sep-small" />
			<?php if ( empty( $this->values['header']['display']['site_title'] ) || empty( $this->values['header']['display']['site_tagline'] ) ) : ?>
				<h4><?php echo __( 'Site Title & Tagline', 'md' ); ?></h4>
				<div class="columns-3 columns-single">
					<?php foreach ( array( 'site_title', 'site_tagline' ) as $site ) : ?>
						<?php if ( empty( $this->values['header']['display'][$site] ) ) : ?>
							<div class="col md-sep-small">
								<?php $this->fields->field( array( 'header', $site ), array(
									'type' => 'color',
									'group' => true,
									'label' => $options['header'][$site],
									'default' => $defaults['header'][$site]
								) ); ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<hr class="md-sep-small" />
			<?php endif; ?>
			<h4><?php echo __( 'Menu Links', 'md' ); ?></h4>
			<div class="columns-3 columns-single">
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'header', 'menu', 'links' ), array(
						'type' => 'color',
						'group' => true,
						'label' => $options['header']['menu']['links'],
						'default' => $defaults['header']['menu']['links']
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'header', 'menu', 'hover' ), array(
						'type' => 'color',
						'group' => true,
						'label' => $options['header']['menu']['hover'],
						'default' => $defaults['header']['menu']['hover']
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'header', 'menu', 'active' ), array(
						'type' => 'color',
						'group' => true,
						'label' => $options['header']['menu']['active'],
						'default' => $defaults['header']['menu']['active']
					) ); ?>
				</div>
			</div>
			<hr class="md-sep-small" />
			<h4><?php echo __( 'Sub Menu Links', 'md' ); ?></h4>
			<div class="columns-3 columns-single">
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'header', 'submenu', 'bg_color' ), array(
						'type' => 'color',
						'group' => true,
						'label' => $options['header']['submenu']['bg_color'],
						'default' => $defaults['header']['submenu']['bg_color']
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'header', 'submenu', 'links' ), array(
						'type' => 'color',
						'group' => true,
						'label' => $options['header']['submenu']['links'],
						'default' => $defaults['header']['submenu']['links']
					) ); ?>
				</div>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'header', 'submenu', 'hover' ), array(
						'type' => 'color',
						'group' => true,
						'label' => $options['header']['submenu']['hover'],
						'default' => $defaults['header']['submenu']['hover']
					) ); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="md-widget md-toggle md-sep-small">
		<h3 class="md-widget-title"><?php echo __( 'Main Menu', 'md' ); ?></h3>
		<div class="md-widget-item columns-3">
			<?php foreach ( $options['main_menu'] as $field => $label ) : ?>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'main_menu', $field ), array(
						'type' => 'color',
						'group' => true,
						'label' => $label,
						'default' => ! empty( $defaults['main_menu'][$field] ) ? $defaults['main_menu'][$field] : ''
					) ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="md-widget md-toggle md-sep-small">
		<h3 class="md-widget-title"><?php echo __( 'Content Box', 'md' ); ?></h3>
		<div class="md-widget-item">
			<div class="columns-3 columns-single">
				<?php foreach ( $options['content'] as $field => $label ) : ?>
					<div class="col md-sep-small">
						<?php $this->fields->field( array( 'content', $field ), array(
							'type' => 'color',
							'group' => true,
							'label' => $label,
							'default' => $defaults['content'][$field]
						) ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<div class="md-widget md-toggle md-sep-small">
		<h3 class="md-widget-title"><?php echo __( 'Sidebar', 'md' ); ?></h3>
		<div class="md-widget-item columns-3 columns-single">
			<?php foreach ( $options['sidebar'] as $field => $label ) : ?>
				<div class="col md-sep-small">
					<?php $this->fields->field( array( 'sidebar', $field ), array(
						'type' => 'color',
						'group' => true,
						'label' => $label,
						'default' => ! empty( $defaults['sidebar'][$field] ) ? $defaults['sidebar'][$field] : ''
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
						'group' => true,
						'label' => $label,
						'default' => $defaults['footer'][$field]
					) ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<hr class="md-sep-small" />
	<?php $this->fields->save(); ?>
</div>