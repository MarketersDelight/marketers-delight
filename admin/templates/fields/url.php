<?php

$placeholder = isset( $args['placeholder'] ) ? esc_attr( $args['placeholder'] ) : 'https://';
$disabled = isset( $args['disabled'] ) ? ' disabled' : '';

?>

<input type="text" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( esc_url( $option ) ); ?>" class="regular-text" placeholder="<?php echo $placeholder; ?>"<?php echo $disabled; ?> />
