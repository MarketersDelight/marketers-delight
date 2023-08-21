<div class="md-widget md-toggle md-sep-small">

	<h3 class="md-widget-title"><?php echo __( 'Header', 'md' ); ?></h3>

	<div class="md-widget-item md-tabs">

		<?php if ( has_action( 'md_design_header_tabs' ) ) : ?>
			<div class="nav-tab-wrapper">
				<a href="#" class="md-tab nav-tab nav-tab-active" data-md-tab="header-colors"><?php echo __( 'Colors', 'md' ); ?></a>
				<?php do_action( 'md_design_header_tabs' ); ?>
			</div>
		<?php endif; ?>

		<div class="header-colors<?php echo has_action( 'md_design_header_tabs' ) ? ' md-tab-content active' : ''; ?>">

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

		<?php do_action( 'md_design_header_settings' ); ?>

	</div>
</div>
