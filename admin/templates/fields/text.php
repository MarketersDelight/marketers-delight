<?php

$type = ! empty( $args['hidden'] ) ? 'hidden' : 'text';
$option = ! empty( $args['option'] ) ? $args['option'] : $option;
$value = isset( $args['default'] ) && $option == '' ? $args['default'] : $option;
$value = isset( $args['map'] ) && is_array( $option ) ? $option['value'] : $value;
$readonly = isset( $args['readonly'] ) || ( ! empty( $args['readonly_after_save'] ) && ! empty( $option ) );
$classes = array( 'regular-text' );

if ( ! empty( $args['classes'] ) )
    $classes[] = $args['classes'];

if ( ! empty( $args['populate'] ) )
    $classes[] = 'md-populate-' . $args['populate'];

$attrs = array(
	'type' => $type,
	'name' => $name,
	'id' => $id,
	'value' => esc_textarea( $value ),
	'class' => implode( ' ', $classes )
);

if ( ! empty( $args['placeholder'] ) )
    $attrs['placeholder'] = $args['placeholder'];

if ( ! empty( $args['style'] ) )
    $attrs['style'] = $args['style'];

if ( ! empty( $args['disabled'] ) )
    $attrs['disabled'] = true;

if ( $readonly ) {
    $attrs['readonly'] = true;
    $attrs['title'] = __( 'Field cannot be edited.', 'md' );
}

$attr = '';

foreach ( $attrs as $key => $val )
	$attr .= $val === true ? " $key" : ' ' . $key . '="' . esc_attr( $val ) . '"';

$icon = $readonly && ! isset( $args['hide_icon'] ) ? '<label for="' . esc_attr( $id ) . '" class="dashicons dashicons-lock"></label>' : '';
$unit = ! empty( $args['unit'] ) ? ' <label for="' . esc_attr( $id ) . '" class="md-prefix description">' . $args['unit'] . '</label> ' : '';

echo "$icon $unit <input $attr />";
