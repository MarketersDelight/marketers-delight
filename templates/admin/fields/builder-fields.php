<div class="md-builder-group">

	<div class="md-builder-tab md-reorder">
		<p class="md-builder-tab-icon"<?php echo md_style( array( 'color' => $color ) ); ?>>
			<i class="dashicons dashicons-<?php echo esc_attr( $icon ); ?>"></i>
		</p>
		<p class="md-builder-tab-label"><?php echo esc_html( $fields['title'] ); ?></p>
	</div>

	<div class="md-widget md-toggle md-group">

		<div class="md-widget-bar md-widget-title">

			<div class="md-widget-edit">
				<?php
					$this->field( array( $key, $group, 'name' ), array(
						'type' => 'text',
						'placeholder' => isset( $fields['placeholder'] ) ? $fields['placeholder'] : __( 'Enter label...', 'md' )
					) );

					if ( isset( $fields['subtitle'] ) )
						$this->field( array( $key, $group, 'subtitle' ), array(
							'type' => 'text',
							'placeholder' => __( 'Add subtitle (optional)', 'md' ),
							'classes' => 'small-text'
						) );
				?>
			</div>

			<div class="md-widget-handle"><span><?php echo __( 'Click here to reorder this group.', 'md' ); ?></span></div>

			<div class="md-widget-controls">
				<span class="md-delete dashicons dashicons-no" title="<?php echo __( 'Delete', 'md' ); ?>"></span>
				<span class="md-badge"<?php echo md_style( array( 'bg_color' => $color ) ); ?>><i class="dashicons dashicons-<?php echo esc_attr( $icon ); ?>"></i> <?php echo esc_html( $fields['title'] ); ?></span>
				<span class="md-reorder dashicons dashicons-menu" title="<?php echo __( 'Reorder', 'md' ); ?>"></span>
				<span class="md-toggle-arrow" title="<?php echo __( 'Click to toggle', 'md' ); ?>"></span>
			</div>

		</div>

		<div class="md-widget-item">
			<?php
				$this->field( array( $key, $group, 'builder_type' ), array(
					'type' => 'text',
					'hidden' => true,
					'default' => esc_attr( $type )
				) );

				$this->field( array( $key, $group, 'builder_area' ), array(
					'type' => 'text',
					'hidden' => true,
					'classes' => 'canvas-area',
					'default' => $group
				) );

				call_user_func( $fields['callback'], $group, $type );
			?>
		</div>

	</div>

</div>
