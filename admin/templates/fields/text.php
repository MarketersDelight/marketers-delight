<?php

$type = ! empty( $args['hidden'] ) ? 'hidden' : 'text';
$option = ! empty( $args['option'] ) ? $args['option'] : $option;
$value = isset( $args['default'] ) && $option == '' ? $args['default'] : $option;
$placeholder = isset( $args['placeholder'] ) ? ' placeholder="' . esc_attr( $args['placeholder'] ) . '"' : '';
$readonly = ! empty( $args['readonly_after_save'] ) && ! empty( $option ) ? ' readonly' : '';
$style = isset( $args['style'] ) ? ' style="' . esc_attr( $args['style'] ) . '"' : '';
$populate = isset( $args['populate'] ) ? ' md-populate-' . $args['populate'] : '';
$classes = isset( $args['classes'] ) ? ' ' . $args['classes'] : '';
$disabled = ! empty( $args['disabled'] ) ? ' disabled' : '';
$unit = isset( $args['unit'] ) ? ' <label for="' . $id . '" class="md-prefix description">' . $args['unit'] . '</label> ' : '';

?>

<?php echo $unit; ?><input type="<?php echo esc_attr( $type ); ?>" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( stripslashes( $value ) ); ?>"<?php echo $placeholder; ?> class="regular-text<?php echo esc_attr( $classes ); ?><?php echo esc_attr( $populate ); ?>"<?php echo $readonly; ?><?php echo $style; ?><?php echo $disabled; ?> />