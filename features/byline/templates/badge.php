<?php
$post_id = isset( $fields['post_id'] ) ? $fields['post_id'] : get_the_ID();
$time = get_the_time( 'U', $post_id );
$label = ! empty( $fields['name'] ) ? $fields['name'] : __( 'New!', 'md' );
$t = ! empty( $fields['time'] ) ? $fields['time'] : 7;

if ( isset( $fields['set_time'] ) )
	$time = $fields['set_time'];

if ( $time < strtotime( "-$t days" ) )
	return;

echo '<span class="' . md_byline_classes( $fields, 'byline-badge badge' ) . '">' . esc_html( $label ) . '</span>';
