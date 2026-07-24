<?php

$post_type = isset( $args['post_type'] ) ? $args['post_type'] : 'post';
$taxonomy = isset( $args['taxonomy'] ) ? $args['taxonomy'] : 'category';
$defaults = array(
	'description' => '',
	'post_type' => $post_type,
	'taxonomy' => $taxonomy,
	'depth' => 0,
	'hide_empty' => false,
	'hierarchical' => true,
	'order' => 'ASC',
	'orderby' => 'name',
	'style' => 'list',
	'use_desc_for_title' => true
);

if ( isset( $args['order'] ) )
	$defaults['order'] = esc_attr( $args['order'] );

$parsed_args = wp_parse_args( $args, $defaults );

if ( ! isset( $parsed_args['class'] ) )
	$parsed_args['class'] = 'category' === $parsed_args['taxonomy'] ? 'categories' : $parsed_args['taxonomy'];

$parsed_args['walker'] = new md_category_options_walker( $args['field'], $this, $parsed_args );

if ( ! taxonomy_exists( $parsed_args['taxonomy'] ) )
	return false;

$output = '';
$categories = get_categories( $parsed_args );

if ( isset( $args['select_type'] ) && $args['select_type'] = 'select' ) {
	$category_options = array();
	foreach ( $categories as $category_order => $category_field ) {
		$category_id = esc_attr( $category_field->term_id );
		$args['options'][$category_id] = esc_html( $category_field->name );
	}
	$output .= $this->select( $name, $id, $option, $args );
}
else {
	$output .= '<ul class="md-terms-list">';
	if ( empty( $categories ) )
		$output .= '<li class="cat-item-none">' . __( 'No categories', 'md' ) . '</li>';
	$output .= walk_category_tree( $categories, $parsed_args['depth'], $parsed_args );
	$output .= '</ul>';
}

if ( ! empty( $parsed_args['description'] ) )
	$output .= '<p class="description">' . $parsed_args['description'] . '</p>';

echo $output;
