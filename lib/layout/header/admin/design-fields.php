<div class="md-widget md-toggle md-sep-small">

	<h3 class="md-widget-title"><?php echo __( 'Design', 'md' ); ?></h3>

	<div class="md-widget-item">

		<div class="columns-3 columns-single">
			<div class="col md-sep-small">
				<?php $this->fields->field( 'bg_color', array(
					'type' => 'color',
					'label' => __( 'Background', 'md' ),
					'default' => $defaults['header']['bg_color']
				) ); ?>
			</div>
			<div class="col md-sep-small">
				<?php $this->fields->field( 'color', array(
					'type' => 'color',
					'label' => __( 'Text', 'md' ),
					'default' => $defaults['header']['color']
				) ); ?>
			</div>
			<div class="col md-sep-small">
				<?php $this->fields->field( 'border_color', array(
					'type' => 'color',
					'label' => __( 'Border', 'md' ),
					'default' => $defaults['header']['border_color']
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
					'default' => $defaults['header']['menu']['links']
				) ); ?>
			</div>
			<div class="col md-sep-small">
				<?php $this->fields->field( array( 'menu', 'hover' ), array(
					'type' => 'color',
					'label' => __( 'Links Hover', 'md' ),
					'default' => $defaults['header']['menu']['hover']
				) ); ?>
			</div>
			<div class="col md-sep-small">
				<?php $this->fields->field( array( 'menu', 'active' ), array(
					'type' => 'color',
					'label' => __( 'Links Active', 'md' ),
					'default' => $defaults['header']['menu']['active']
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
					'default' => $defaults['header']['submenu']['bg_color']
				) ); ?>
			</div>
			<div class="col md-sep-small">
				<?php $this->fields->field( array( 'submenu', 'links' ), array(
					'type' => 'color',
					'label' => __( 'Links', 'md' ),
					'default' => $defaults['header']['submenu']['links']
				) ); ?>
			</div>
			<div class="col md-sep-small">
				<?php $this->fields->field( array( 'submenu', 'hover' ), array(
					'type' => 'color',
					'label' => __( 'Links Hover', 'md' ),
					'default' => $defaults['header']['submenu']['hover']
				) ); ?>
			</div>
		</div>

		<?php do_action( 'md_design_header_settings' ); ?>

	</div>
</div>
