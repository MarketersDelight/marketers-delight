<div class="md-widget md-toggle md-sep-small" style="border-left: 0;">
	<h3 class="md-widget-title md-group-controls" style="background-color: <?php echo $args['color']; ?>;<?php echo $share !== 'like' ? ' color: #fff;' : ''; ?>">
		<?php echo $args['label']; ?>
		<span class="md-group-controls-inner">
			<span class="md-reorder dashicons dashicons-menu" title="<?php echo __( 'Reorder', 'md' ); ?>"></span>
		</span>
	</h3>
	<div class="md-widget-item">
		<?php $this->fields->field( array( 'icons', $share, 'status' ), array(
			'type' => 'text',
			'hidden' => true,
			'classes' => 'canvas-status',
			'default' => $args['status']
		) ); ?>
		<?php if ( in_array( 'icon', $fields ) ) : ?>
			<div class="md-spacer-small">
				<?php $this->fields->field( array( 'icons', $share, 'icon' ), array(
					'type' => 'select',
					'label' => __( 'Icon', 'md' ),
					'description' => __( 'Change the icon for this share button.', 'md' ),
					'empty_label' => __( 'Select icon...', 'md' ),
					'options' => $args['icons']
				) ); ?>
			</div>
		<?php endif; ?>
		<?php if ( in_array( 'username', $fields ) ) : ?>
			<div class="md-spacer-small">
				<?php $this->fields->field( array( 'icons', $share, 'username' ), array(
					'type' => 'text',
					'label' => __( 'Username', 'md' ),
					'description' => __( 'Enter your username only, no @ characters.', 'md' )
				) ); ?>
			</div>
		<?php endif; ?>
		<?php if ( md_has( 'popups' ) && in_array( 'popup', $fields ) ) :
			$popups = md_setting( array( 'popups' ) );
		?>
			<?php if ( ! empty( $popups['popups'] ) ) :
				foreach ( $popups['popups'] as $popup_id => $popup )
					$options[$popup_id] = $popup['name'];
			?>
				<div class="md-spacer-small">
					<?php $this->fields->field( array( 'icons', $share, 'popup' ), array(
						'type' => 'select',
						'label' => __( 'Select Popup', 'md' ),
						'empty_label' => __( 'Select a popup&hellip;', 'md' ),
						'options' => $options
					) ); ?>
				</div>
			<?php else : ?>
				<?php md_popup_connect_notice(); ?>
			<?php endif; ?>
		<?php endif; ?>
		<?php if ( in_array( 'url', $fields ) ) : ?>
			<div class="md-spacer-small">
				<?php $this->fields->field( array( 'icons', $share, 'url' ), array(
					'type' => 'text',
					'label' => __( 'Custom URL', 'md' ),
					'description' => __( 'Add direct link to your profile instead of share link.', 'md' )
				) ); ?>
			</div>
		<?php endif; ?>
		<?php if ( in_array( 'text', $fields ) ) : ?>
			<div class="md-spacer-small">
				<?php $this->fields->field( array( 'icons', $share, 'text' ), array(
					'type' => 'text',
					'label' => __( 'Label', 'md' ),
					'description' => __( 'Text only shows on <b>Inline buttons</b>.', 'md' )
				) ); ?>
			</div>
		<?php endif; ?>
		<?php if ( in_array( 'disable', $fields ) ) : ?>
			<div class="md-spacer-small">
				<?php $this->fields->field( array( 'icons', $share, 'disable' ), array(
					'type' => 'checkbox',
					'label' => __( 'Display', 'md' ),
					'options' => array(
						'floating' => __( 'Remove from floating icons', 'md' ),
						'inline' => __( 'Remove from inline icons', 'md' )
					)
				) ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>