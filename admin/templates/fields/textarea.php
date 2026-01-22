<?php

$classes = array( 'large-text' );
if ( isset( $args['classes'] ) )
	$classes[] = $args['classes'];
$classes = join( ' ', $classes );
$rows = ! empty( $args['rows'] ) ? intval( $args['rows'] ) : 6;
?>

<textarea name="<?php echo $name; ?>" id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $classes ); ?>" rows="<?php echo esc_attr( $rows ); ?>"><?php echo esc_attr( stripslashes( $option ) ); ?></textarea>