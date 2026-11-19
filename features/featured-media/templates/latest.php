<?php

$latest = get_post( $media['latest'] );
$taxonomies = wp_list_filter( get_object_taxonomies( $latest, 'objects' ), array( 'hierarchical' => true, 'public' => true ) );
$terms = $taxonomies ? get_the_terms( $latest, key( $taxonomies ) ) : array();
$posted = sprintf( __( '%s ago', 'md' ), human_time_diff( get_post_time( 'U', true, $latest ), time() ) );

if ( has_post_thumbnail( $latest ) )
	echo get_the_post_thumbnail( $latest, $size === 'full' ? 'medium_large' : $size );

echo '<time class="tag" datetime="' . esc_attr( get_the_date( 'c', $latest ) ) . '" title="' . esc_attr( get_the_date( '', $latest ) ) . '">' . esc_html( apply_filters( 'md_latest_media_label', $posted, $latest ) ) . '</time>';

if ( $terms && ! is_wp_error( $terms ) )
	echo '<p class="lede">' . esc_html( $terms[0]->name ) . '</p>';

echo '<p class="title h4"><a class="clickable" href="' . esc_url( get_permalink( $latest ) ) . '">' . esc_html( get_the_title( $latest ) ) . '</a></p>';
