<?php
$type_object = get_post_type_object( get_post_type( $fields['post_id'] ?? get_the_ID() ) );

if ( ! $type_object )
	return;

$type_label = ! empty( $fields['name'] ) ? $fields['name'] : $type_object->labels->singular_name;
$type_url = get_post_type_archive_link( $type_object->name );
$type_classes = md_byline_classes( $fields, array( 'byline-post-type', "byline-post-type-{$type_object->name}" ) );

if ( $type_url )
	echo '<a href="' . esc_url( $type_url ) . '" class="' . $type_classes . '">' . md_icon( 'file' ) . esc_html( $type_label ) . '</a>';
else
	echo '<span class="' . $type_classes . '">' . md_icon( 'file' ) . esc_html( $type_label ) . '</span>';
