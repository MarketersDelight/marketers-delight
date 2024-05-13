<div class="md-builder-group">

	<div class="md-builder-tab md-reorder">
		<p class="md-builder-tab-icon"<?php echo ! empty( $color ) ? ' style="color: ' . esc_attr( $color ) . ';"' : ''; ?>><i class="dashicons dashicons-<?php echo esc_attr( $icon ); ?>"></i></p>
		<p class="md-builder-tab-label"><?php echo esc_html( $fields['title'] ); ?></p>
	</div>

	<div class="md-widget md-toggle md-group">

		<h3 class="md-widget-title md-group-controls">

			<span class="md-badge"<?php echo md_style( array( 'bg_color' => $color ) ); ?>><i class="dashicons dashicons-<?php echo esc_attr( $icon ); ?>"></i> <?php echo esc_html( $fields['title'] ); ?></span>

			<?php if ( ! isset( $fields['hide_title'] ) || $fields['hide_title'] !== false )
				$this->field( array( $key, $group, 'title' ), array(
					'type' => 'text',
					'placeholder' => isset( $fields['placeholder'] ) ? $fields['placeholder'] : __( 'Enter label...', 'md' )
				) ); ?>

			<span class="md-group-controls-inner">
				<span class="md-delete dashicons dashicons-no" title="<?php echo __( 'Delete', 'md' ); ?>"></span>
				<span class="md-reorder dashicons dashicons-menu" title="<?php echo __( 'Reorder', 'md' ); ?>"></span>
			</span>

		</h3>

		<div class="md-widget-item">

			<?php $this->field( array( $key, $group, 'type' ), array(
				'type' => 'text',
				'hidden' => true,
				'default' => esc_attr( $type )
			) ); ?>

			<?php $this->field( array( $key, $group, 'area' ), array(
				'type' => 'text',
				'hidden' => true,
				'classes' => 'canvas-area',
				'default' => $group
			) ); ?>

			<?php call_user_func( $fields['callback'], $group, $type ); ?>

		</div>

	</div>

</div>
