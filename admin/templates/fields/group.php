<?php

if ( empty( $option ) )
	$option = isset( $args['default'] ) && is_array( $args['default'] ) ? $args['default'] : array();

$field_path = $args['field'];
$path = $args['path'];
$group_id = is_array( $field_path ) ? implode( '_', $field_path ) : $field_path;
$group_key = "{$this->_id}_$group_id";
$clone = "{clone:$group_key}";
$sort = "{$group_id}_sort";
$elements = isset( $args['elements'] ) ? $args['elements'] : array();
$style = isset( $args['style'] ) ? esc_attr( $args['style'] ) : 'list';

if ( isset( $args['sort'] ) ) {
	$classes = array( 'md-sort' );
	$option = $this->get_field( array( $this->_clean_id, $group_id ), $elements );
	$order = $this->get_field( array( $this->_clean_id, $sort ) );

	if ( $order ) {
		$order = explode( ',', $order );
		array_combine( $order, $option );
	}
}
else {
	$classes = array( 'md-groups' );
	$empty[$clone] = array();
	$option = array_merge( $empty, $option );
}

$callback_args = isset( $args['callback_args'] ) ? $args['callback_args'] : null;

$classes[] = "md-group-$style";
$classes = join( ' ', $classes );
?>

<?php if ( $style !== 'simple' ) : ?>

<div class="md-group-head md-clear">

	<?php if ( isset( $args['label'] ) )
		$this->label( $id, $args ); ?>

	<?php if ( ! isset( $args['sort'] ) && ! isset( $args['hide_button'] ) ) {
		$button_label = ! empty( $args['button_text'] ) ? $args['button_text'] : __( 'Add New', 'md' );
		$button_classes = ! empty( $args['classes'] ) ? ' ' . $args['classes'] : '';

		echo '<span class="md-clone-add button' . esc_attr( $button_classes ) . '" data-clone-group="' . esc_attr( "{$this->_id}_{$group_id}" ) . '">' . $button_label . '</span>';
	} ?>

</div>

<?php endif; ?>

<div id="md_group_<?php echo esc_attr( "{$this->_id}_$group_id" ); ?>" class="<?php echo esc_attr( $classes ); ?>">

	<?php foreach ( $option as $group => $fields ) :
		$valid = isset( $args['active_key'] ) && ! empty( $fields[$args['active_key']] ) ? ' valid' : '';
	?>

	<div <?php echo isset( $args['sort'] ) ? ' data-id="' . esc_attr( $group ) . '"' : ''; ?>class="md-group<?php echo $valid . ( "$group" == $clone ? ' empty' : '' ) . ( $style == 'boxes' ? ' md-widget md-toggle' : '' ) . ( isset( $args['secondary'] ) ? ' md-widget-secondary' : '' ); ?>">

		<div class="md-widget-bar<?php echo $style == 'boxes' ? ' md-widget-title' : ''; ?>">

			<?php if ( $style == 'boxes' ) {
				echo '<div class="md-widget-edit">';

				$this->field( array_merge( (array) $path, array( $group, 'name' ) ), array(
					'type' => 'text',
					'placeholder' => isset( $args['new_label'] ) ? $args['new_label'] : __( 'New entry...', 'md' )
				) );

				if ( isset( $args['subtitle'] ) )
					$this->field( array_merge( (array) $path, array( $group, 'subtitle' ) ), array(
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
				<?php if ( $style == 'boxes' ) : ?>
				<span class="md-toggle-arrow" title="<?php echo __( 'Click to toggle', 'md' ); ?>"></span>
				<?php endif; ?>
			</div>

		</div>

		<div class="md-group-content<?php echo ( $style == 'boxes' ? ' md-widget-item' : '' ); ?>">
			<?php call_user_func( $args['callback'], $path, $group, $callback_args ); ?>
		</div>

	</div>

	<?php endforeach; ?>

</div>

<?php if ( $style === 'simple' ) : // Adds a row named from this input, through the same clone as Add New ?>

<div class="md-group-add">
	<input type="text" class="md-group-add-name regular-text" placeholder="<?php echo esc_attr( isset( $args['add_label'] ) ? $args['add_label'] : __( 'New entry name', 'md' ) ); ?>" aria-label="<?php echo esc_attr( isset( $args['add_label'] ) ? $args['add_label'] : __( 'New entry name', 'md' ) ); ?>" />
	<span class="button md-clone-add" data-clone-group="<?php echo esc_attr( "{$this->_id}_{$group_id}" ); ?>"><?php echo __( 'Add', 'md' ); ?></span>
</div>

<?php endif; ?>
