<?php

$classes = isset( $args['classes'] ) ? ' ' . $args['classes'] : '';
$class_size  = isset( $args['size'] ) ? 'size="' . $args['size'] . '"' : '';
$placeholder = isset( $args['placeholder'] ) ? ' placeholder="' . sanitize_text_field( $args['placeholder'] ) . '"' : '';
$style  = isset( $args['style'] ) ? ' style="' . sanitize_text_field( $args['style'] ) . '"' : '';
$max = isset( $args['max'] ) ? ' max="' . esc_attr( $args['max'] ) . '"' : '';
$unit = isset( $args['unit'] ) ? ' <label for="' . $id . '" class="md-unit description">' . sanitize_text_field( $args['unit'] ) . '</label>' : '';
$prefix = isset( $args['prefix'] ) ? ' <label for="' . $id . '" class="md-prefix description">' . sanitize_text_field( $args['prefix'] ) . '</label>' : '';

?>

<?php echo $prefix; ?><input type="number" class="regular-text<?php echo esc_attr( $classes ); ?>" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $option ); ?>"<?php echo $placeholder . $style . $max; ?> /><?php echo $unit; ?>