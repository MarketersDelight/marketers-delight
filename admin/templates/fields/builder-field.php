<div class="md-builder-group"<?php echo $group === '{clone}' && ! empty( $fields['context'] ) ? ' data-context="' . esc_attr( $fields['context'] ) . '"' : ''; ?>>

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
						'value' => $row['name'] ?? '',
						'placeholder' => isset( $fields['placeholder'] ) ? $fields['placeholder'] : __( 'Enter label...', 'md' )
					) );

					if ( isset( $fields['subtitle'] ) )
						$this->field( array( $key, $group, 'subtitle' ), array(
							'type' => 'text',
							'value' => $row['subtitle'] ?? '',
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
					'value' => $row['builder_type'] ?? $type
				) );

				$this->field( array( $key, $group, 'builder_area' ), array(
					'type' => 'text',
					'hidden' => true,
					'classes' => 'canvas-area',
					'value' => $row['builder_area'] ?? $group
				) );

				if ( $scope ) : ?>
				<div class="md-checkbox-description">
					<?php
					$this->field( array( $key, $group, 'scope' ), array(
						'type' => 'checkbox',
						'options' => array( 'post_type_only' => __( 'Don’t show on categories', 'md' ) )
					) );
					?>
					<p class="description"><?php echo esc_html__( 'Also shown on category pages unless disabled here.', 'md' ); ?></p>
				</div>
				<hr class="md-sep-micro" />
				<?php endif;

				call_user_func( $fields['admin_callback'], $group, $type, $this, $row );
			?>
		</div>

	</div>

</div>
