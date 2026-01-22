<?php
$default = isset( $args['default'] ) ? $args['default'] : '';
$placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : $default;
$option = ! empty( $option ) ? $option : '';
?>

<div class="md-color-picker-wrap">
	<input type="text" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $id ); ?>" class="md-color-picker<?php echo ! empty( $option ) ? ' md-has-color-value' : ''; ?>" placeholder="<?php echo esc_html( $placeholder ); ?>" value="<?php echo esc_attr( $option ); ?>" data-jscolor="{ value: '<?php echo esc_attr( $option ); ?>' }" />
	<div class="md-color-picker-controls">
		<span class="md-color-picker-fill"<?php echo md_style( array( 'bg_color' => $default ) ); ?>></span>
		<span class="md-color-picker-reset" title="<?php echo __( 'Restore default color', 'md' ); ?>"><i class="dashicons dashicons-undo"></i></span>
	</div>
</div>