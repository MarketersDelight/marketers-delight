<?php

$default = ! empty( $args['default'] ) ? $args['default'] : '';
$placeholder = ! empty( $args['placeholder'] ) ? $args['placeholder'] : $default;
$unit = ! empty( $args['unit'] ) ? $args['unit'] : 'px';
$min = ! empty( $args['min'] ) ? $args['min'] : 0;
$max = ! empty( $args['max'] ) ? $args['max'] : 100;

?>

<p class="md-range">
	<input type="range" class="md-range-field" value="<?php echo esc_attr( $option ); ?>" min="<?php echo $min; ?>" max="<?php echo $max; ?>" />
	<span class="md-range-wrap">
		<input name="<?php echo esc_attr( $name ); ?>" id="<?php echo $id; ?>" class="md-range-number" type="number" value="<?php echo esc_attr( $option ); ?>" placeholder="<?php echo $placeholder; ?>" /> <?php echo esc_html( $unit ); ?>
		<span class="md-range-reset dashicons dashicons-image-rotate" data-default="<?php echo $default; ?>"></span>
	</span>
</p>