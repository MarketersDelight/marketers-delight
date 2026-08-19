<?php

$classes = array( 'large-text' );
if ( isset( $args['classes'] ) )
	$classes[] = $args['classes'];
$classes = join( ' ', $classes );
$rows = ! empty( $args['rows'] ) ? intval( $args['rows'] ) : 6;
$attrs = array(
	'name' => $name,
	'id' => $id,
	'class' => $classes,
	'rows' => $rows
);

foreach ( $args['attributes'] ?? array() as $key => $val )
	$attrs[$key] = $val;

$attr = '';

foreach ( $attrs as $key => $val )
	$attr .= $val === true ? " $key" : ' ' . $key . '="' . esc_attr( $val ) . '"';
?>

<textarea<?php echo $attr; ?>><?php echo esc_textarea( $option ); ?></textarea>
