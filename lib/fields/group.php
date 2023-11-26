<div class="md-group-head md-clear">

	<?php if ( isset( $args['label'] ) ) : ?>
		<?php $this->label( $id, $args ); ?>
	<?php endif; ?>

	<?php if ( ! isset( $args['hide_button'] ) ) : ?>
		<?php $this->clone_button( $args['field'] ); ?>
	<?php endif; ?>

</div>

<div id="md_group_<?php echo esc_attr( "{$this->_id}_" . $args['field'] ); ?>" class="md-groups md-group-<?php echo $style; ?>">

	<?php foreach ( $option as $group => $fields ) :
		$valid = isset( $args['active_key'] ) && ! empty( $fields[$args['active_key']] ) ? ' valid' : '';
	?>

		<div class="md-group<?php echo ( $valid ) . ( "$group" == $var ? ' empty' : '' ) . ( $style == 'boxes' ? ' md-widget md-toggle' : '' ); ?>">

			<div class="md-group-controls<?php echo ( $style == 'boxes' ? ' md-widget-title' : '' ); ?>">

				<?php if ( $style == 'boxes' ) : ?>
					<?php $this->field( array( $args['field'], $group, 'name' ), array(
						'type' => 'text',
						'placeholder' => isset( $args['new_label'] ) ? $args['new_label'] : __( 'New entry...', 'md' ),
						'classes' => 'md-focus'
					) ); ?>
				<?php endif; ?>

				<span class="md-group-controls-inner">
					<span class="md-delete dashicons dashicons-no" title="<?php echo __( 'Delete', 'md' ); ?>"></span>
					<span class="md-reorder dashicons dashicons-menu" title="<?php echo __( 'Reorder', 'md' ); ?>"></span>
				</span>

			</div>

			<div class="md-group-content<?php echo ( $style == 'boxes' ? ' md-widget-item' : '' ); ?>">
				<?php call_user_func( $args['callback'], $args['field'], $group ); ?>
			</div>

		</div>

	<?php endforeach; ?>

</div>