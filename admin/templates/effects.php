<div class="md-widget md-toggle md-dashboard-card">

	<h3 class="md-widget-title"><?php echo md_dropin_icon_tile( 'dashicons-admin-customizer', '', 'small' ); ?><span><?php echo __( 'Effects', 'md' ); ?><span class="md-widget-description"><?php echo __( 'Corners, borders and shadows', 'md' ); ?></span></span></h3>

	<div class="md-widget-item md-widget-rows">

		<div>
			<h4><?php echo __( 'Boxes', 'md' ); ?></h4>

			<div class="mb-single">
				<?php $this->fields->field( 'border_radius', array(
					'placeholder' => 8,
					'min' => 0,
					'max' => 48
				) ); ?>
			</div>

			<div class="mb-single">
				<?php $this->fields->field( 'border_width', array(
					'placeholder' => 1,
					'min' => 0,
					'max' => 6
				) ); ?>
			</div>

			<?php $this->fields->field( 'box_style', array(
				'label' => '',
				'options' => array( 'remove' => __( 'Remove <b>Box Style</b>', 'md' ) )
			) ); ?>

			<?php $this->fields->field( 'dividers', array(
				'label' => '',
				'options' => array( 'divide' => __( '<b>Divide</b> content with lines', 'md' ) )
			) ); ?>
		</div>

		<div>
			<h4><?php echo __( 'Shadows', 'md' ); ?></h4>

			<div class="md-full-select mb-single">
				<?php $this->fields->field( 'shadow_style', array(
					'empty_label' => __( 'Soft', 'md' ),
					'options' => array(
						'hard' => __( 'Hard', 'md' ),
						'none' => __( 'None', 'md' )
					)
				) ); ?>
			</div>

			<?php $this->fields->field( 'shadow_color', array(
				'default' => '#000000'
			) ); ?>
		</div>

	</div>

</div>

<div class="md-widget md-toggle md-dashboard-card">

	<h3 class="md-widget-title"><?php echo md_dropin_icon_tile( 'dashicons-feedback', '', 'small' ); ?><span><?php echo __( 'Forms', 'md' ); ?><span class="md-widget-description"><?php echo __( 'Search, comment and signup fields', 'md' ); ?></span></span></h3>

	<div class="md-widget-item">

		<div class="md-full-select mb-single">
			<?php $this->fields->field( 'field_style', array(
				'empty_label' => __( 'Box', 'md' ),
				'options' => array( 'underline' => __( 'Underline', 'md' ) )
			) ); ?>
		</div>

		<div class="mb-single">
			<?php $this->fields->field( 'field_radius', array(
				'placeholder' => (int) $effects['border_radius'],
				'min' => 0,
				'max' => 48
			) ); ?>
		</div>

		<?php $this->fields->field( 'field_join', array(
			'label' => '',
			'options' => array( 'join' => __( '<b>Join</b> fields and buttons together', 'md' ) )
		) ); ?>

	</div>

</div>
