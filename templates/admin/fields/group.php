<?php if ( isset( $args['label'] ) ) : ?>

<div class="md-group-head md-clear">

	<?php $this->label( $id, $args ); ?>

	<?php if ( ! isset( $args['sort'] ) && ! isset( $args['hide_button'] ) )
		$this->clone_button( $group_id ); ?>

</div>

<?php endif; ?>

<div id="md_group_<?php echo esc_attr( "{$this->_id}_$group_id" ); ?>" class="<?php echo esc_attr( $classes ); ?>">

	<?php if ( isset( $args['sort'] ) ) : ?>
	<div data-id="hide">
		<?php $this->field( $sort, array(
			'type' => 'text',
			'hidden' => true,
			'classes' => 'md-sort-order'
		) ); ?>
	</div>
	<?php endif; ?>

	<?php foreach ( $option as $group => $fields ) :
		$valid = isset( $args['active_key'] ) && ! empty( $fields[$args['active_key']] ) ? ' valid' : '';
	?>

	<div <?php echo isset( $args['sort'] ) ? ' data-id="' . esc_attr( $group ) . '"' : ''; ?>class="md-group<?php echo $valid . ( "$group" == $clone ? ' empty' : '' ) . ( $style == 'boxes' ? ' md-widget md-toggle' : '' ) . ( isset( $args['secondary'] ) ? ' md-widget-secondary' : '' ); ?>">

		<div class="md-widget-bar<?php echo $style == 'boxes' ? ' md-widget-title' : ''; ?>">

			<?php if ( $style == 'boxes' ) {
				echo '<div class="md-widget-edit">';

				$this->field( array( $group_id, $group, 'name' ), array(
					'type' => 'text',
					'placeholder' => isset( $args['new_label'] ) ? $args['new_label'] : __( 'New entry...', 'md' )
				) );

				if ( isset( $args['subtitle'] ) )
					$this->field( array( $group_id, $group, 'subtitle' ), array(
						'type' => 'text',
						'placeholder' => __( 'Add subtitle (optional)', 'md' ),
						'classes' => 'small-text'
					) );

				echo '</div>';
			} ?>

			<div class="md-widget-handle"><span><?php echo __( 'Click here to reorder this group.', 'md' ); ?></span></div>

			<div class="md-widget-controls">
				<?php if ( ! isset( $args['sort'] ) ) : ?>
				<span class="md-delete dashicons dashicons-no" title="<?php echo __( 'Delete', 'md' ); ?>"></span>
				<?php endif; ?>
				<span class="md-reorder dashicons dashicons-menu" title="<?php echo __( 'Reorder', 'md' ); ?>"></span>
				<span class="md-toggle-arrow" title="<?php echo __( 'Click to toggle', 'md' ); ?>"></span>
			</div>

		</div>

		<div class="md-group-content<?php echo ( $style == 'boxes' ? ' md-widget-item' : '' ); ?>">
			<?php call_user_func( $args['callback'], $group_id, $group, $callback_args ); ?>
		</div>

	</div>

	<?php endforeach; ?>

</div>