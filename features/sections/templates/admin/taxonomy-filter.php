<?php

if ( $options ) {
	$fields->field( array( 'builder', $group, 'taxonomy' ), array(
		'type' => 'select',
		'label' => __( 'Taxonomy', 'md' ),
		'empty_label' => __( 'Auto-detect taxonomy', 'md' ),
		'options' => $options
	) );

	$fields->field( array( 'builder', $group, 'taxonomy_settings' ), array(
		'type' => 'checkbox',
		'label' => __( 'Settings', 'md' ),
		'options' => array(
			'hide_empty' => __( 'Hide empty terms', 'md' )
		)
	) );

	$fields->field( array( 'builder', $group, 'all_label' ), array(
		'type' => 'text',
		'label' => __( 'All label', 'md' ),
		'placeholder' => __( 'All', 'md' )
	) );
}

else echo '<p>' . __( 'No public taxonomies are registered for this post type.', 'md' ) . '</p>';