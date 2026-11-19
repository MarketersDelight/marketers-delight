<?php

$categories = $terms = array();
$post_id = isset( $fields['post_id'] ) ? $fields['post_id'] : get_the_ID();
$post_type = isset( $fields['post_type'] ) ? $fields['post_type'] : md_get_post_type( $post_id );
$taxonomies = get_object_taxonomies( $post_type );

if ( empty( $fields['term'] ) )
	$categories[] = ! empty( $taxonomies[0] ) ? esc_attr( $taxonomies[0] ) : 'category';
elseif ( $fields['term'] == 'all' )
	$categories = $taxonomies;
else
	$categories[] = esc_attr( $fields['term'] );

foreach ( $categories as $category ) {
	$category_terms = get_the_terms( $post_id, $category );

	if ( $category_terms && ! is_wp_error( $category_terms ) )
		$terms = array_merge( $terms, $category_terms );
}

if ( empty( $terms ) )
	return;

$limit = 0;

if ( ! empty( $fields['settings']['first'] ) )
	$limit = 1;
elseif ( ! empty( $fields['builder_area'] ) && $fields['builder_area'] === 'archives' )
	$limit = isset( $fields['limit'] ) && $fields['limit'] !== '' ? absint( $fields['limit'] ) : 3;

$visible_terms = $limit ? array_slice( $terms, 0, $limit ) : $terms;
$remainder = count( $terms ) - count( $visible_terms );
$style = $fields['style'] ?? '';

if ( is_array( $style ) )
	$style = ! empty( $style['tag'] ) ? 'tag' : '';

$links = array();

foreach ( $visible_terms as $term ) {
	$link_classes = array( 'byline-' . $term->slug, 'byline-tax-' . str_replace( '_', '-', $term->taxonomy ), 'clickout' );
	$term_icon = '';

	if ( $style === 'tag' )
		$link_classes[] = 'tag tag-outline';
	elseif ( $style === 'icon' ) {
		$identity = md_category_identity( $term );

		if ( $identity['icon'] ) {
			$icon_style = $identity['color'] ? md_style( array(
				'vars' => array(
					'md-category-color' => $identity['color'],
					'md-category-text' => $identity['text']
				)
			) ) : '';
			$term_icon = '<span class="category-title-icon square-icon micro" aria-hidden="true"' . $icon_style . '>' . md_icon( $identity['icon'] ) . '</span>';
		}
	}

	$links[] = array(
		'url' => get_term_link( $term->term_id ),
		'classes' => $link_classes,
		'html' => $term_icon . esc_html( $term->name )
	);
}

if ( count( $links ) === 1 && ! $remainder && $style === 'icon' && empty( $fields['name'] ) )
	echo '<a href="' . esc_url( $links[0]['url'] ) . '" class="' . md_byline_classes( $fields, array_merge( array( 'byline-category' ), $links[0]['classes'] ) ) . '">' . $links[0]['html'] . '</a>';
else {
	echo '<span class="' . md_byline_classes( $fields, 'byline-category' ) . '">';

	if ( $style !== 'icon' )
		echo md_icon( 'tags' );

	if ( ! empty( $fields['name'] ) )
		echo '<span class="byline-label">' . esc_html( $fields['name'] ) . '</span> ';

	foreach ( $links as $link )
		echo '<a href="' . esc_url( $link['url'] ) . '" class="' . esc_attr( join( ' ', $link['classes'] ) ) . '">' . $link['html'] . '</a>';

	if ( $remainder )
		echo '<span class="byline-category-more">' . esc_html( sprintf( __( '+%s more', 'md' ), number_format_i18n( $remainder ) ) ) . '</span>';

	echo '</span>';
}
