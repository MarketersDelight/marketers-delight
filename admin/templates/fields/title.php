<?php

$title_args = array(
	'type' => 'text',
	'label' => __( 'Page Title', 'md' )
);

$size_args = array(
	'type' => 'select',
	'empty_label' => __( 'Default', 'md' ),
	'options' => array(
		'huge' => __( 'Huge', 'md' )
	),
	'attributes' => array(
		'aria-label' => __( 'Title size', 'md' )
	)
);

$classes = ! empty( $args['row'] ) ? 'md-title-field md-field-row mb-single' : 'md-title-field mb-single';

if ( ! empty( $args['placeholder'] ) )
	$title_args['placeholder'] = $args['placeholder'];

if ( ! empty( $args['inherit'] ) ) {
	$title_args['inherit'] = true;
	$size_args['inherit'] = true;
}

echo '<div class="' . esc_attr( $classes ) . '">';

$this->field( $args['title'], $title_args );
$this->field( $args['size'], $size_args );

if ( ! empty( $args['description'] ) )
	$this->description( $args['description'] );

echo '</div>';
