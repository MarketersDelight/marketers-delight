<?php

$classes = isset( $args['classes'] ) ? ' ' . $args['classes'] : '';
$class_size  = isset( $args['size'] ) ? 'size="' . $args['size'] . '"' : '';
$placeholder = isset( $args['placeholder'] ) ? ' placeholder="' . sanitize_text_field( $args['placeholder'] ) . '"' : '';
$style  = isset( $args['style'] ) ? ' style="' . sanitize_text_field( $args['style'] ) . '"' : '';
$min = isset( $args['min'] ) ? ' min="' . esc_attr( $args['min'] ) . '"' : '';
$max = isset( $args['max'] ) ? ' max="' . esc_attr( $args['max'] ) . '"' : '';
$step = isset( $args['step'] ) ? ' step="' . esc_attr( $args['step'] ) . '"' : '';
$unit = isset( $args['unit'] ) ? ' <label for="' . $id . '" class="md-unit description">' . sanitize_text_field( $args['unit'] ) . '</label>' : '';
$disabled = ! empty( $args['disabled'] ) ? ' disabled' : '';
$prefix = isset( $args['prefix'] ) ? ' <label for="' . $id . '" class="md-prefix description">' . sanitize_text_field( $args['prefix'] ) . '</label>' : '';
$attributes = '';

foreach ( $args['attributes'] ?? array() as $key => $value )
	$attributes .= $value === true ? ' ' . esc_attr( $key ) : ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';

?>

<?php echo $prefix; ?><input type="number" class="regular-text<?php echo esc_attr( $classes ); ?>" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $option ); ?>"<?php echo $placeholder . $style . $min . $max . $step . $disabled . $attributes; ?> /><?php echo $unit; ?>
