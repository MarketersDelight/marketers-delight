<?php

/**
 * Get Hero/inline CTA of any given page. A CTA can be a
 * link group, email form, custom HTML, or more.
 *
 * @since 6.0
 */

function md_cta( $context = 'post', $cta = array() ) {
	if ( ! apply_filters( "md_has_{$context}_cta", true ) )
		return;

	$html = '';

	if ( empty( $cta ) )
		$cta = md_get_cta( $context );

	$type = ! empty( $cta['page_cta'] ) ? $cta['page_cta'] : '';

	if ( $type == 'remove' )
		return;

	if ( $type == 'links' && ! empty( $cta['links'] ) ) {
		foreach ( $cta['links'] as $group => $fields )
			if ( ! empty( $cta['links'][$group] ) ) {
				$cta['links'][$group]['classes'][] = 'cta-link';
				$html .= md_get_link( $cta['links'][$group] );
			}
	}
	elseif ( $type == 'custom' && ! empty( $cta['custom_html'] ) )
		$html = $cta['custom_html'];
	elseif ( ! empty( $type ) )
		$html = apply_filters( "md_cta_{$type}", $context, $cta );

	if ( empty( $html ) )
		return;

	include md_template( 'features', 'page-cta/cta', true );
}

/**
 * Get the saved CTA for the current page title: the archive's Page CTA
 * settings, or a single post's own CTA. Other contexts pass their CTA
 * to md_cta() directly.
 *
 * @since 6.1
 */

function md_get_cta( $context = 'post' ) {
	if ( $context == 'page' ) {
		$cta = md_module( 'page_cta' );

		// Fields saved without a type don't stop a category inheriting its CTA

		if ( empty( $cta['page_cta'] ) && ( is_category() || is_tax() ) ) {
			$cta = md_taxonomy_field( 'page_cta' );

			if ( empty( $cta['page_cta'] ) )
				$cta = md_post_type_field( 'page_cta' );
		}

		return $cta;
	}

	if ( $context == 'post' && md_is_queried_post() )
		return md_post_meta( 'page_cta' );

	return array();
}

/**
 * CTA types with their labels, save rules, and admin panels. Drop-ins add
 * their own types through the md_page_cta_types filter. A type's
 * admin_callback gets ( $fields, $prefix ) and renders its fields with
 * $fields, which saves wherever the CTA settings live (Page CTA, or a
 * Section Title row).
 *
 * @since 6.1
 */

function md_page_cta_types() {
	$data = new md_fields_data;

	return apply_filters( 'md_page_cta_types', array(
		'links' => array(
			'label' => __( 'Links', 'md' ),
			'fields' => array(
				'links_sort' => array( 'type' => 'text' ),
				'links' => array(
					'type' => 'group',
					'fields' => $data->links( array( 'sort' => 'save' ) )
				)
			),
			'admin_callback' => 'md_page_cta_links_admin'
		),
		'custom' => array(
			'label' => __( 'Custom HTML', 'md' ),
			'fields' => array(
				'custom_html' => array( 'type' => 'code' )
			),
			'admin_callback' => 'md_page_cta_custom_admin'
		)
	) );
}

/**
 * Page CTA fields and their save rules: the CTA type plus every type's
 * own fields. Shared by the Page CTA settings and Section Titles.
 *
 * @since 6.1
 */

function md_page_cta_fields() {
	$types = md_page_cta_types();
	$save = array(
		'page_cta' => array(
			'type' => 'select',
			'options' => array_merge( array_keys( $types ), array( 'remove' ) )
		),
		'title_position' => array(
			'type' => 'select',
			'options' => array( 'before_title', 'byline_aside' )
		)
	);

	foreach ( $types as $type )
		if ( ! empty( $type['fields'] ) && is_array( $type['fields'] ) )
			$save = array_merge( $save, $type['fields'] );

	return $save;
}

/**
 * Render the CTA type select and each type's panel. With $inherit, an
 * empty select names the CTA it inherits, like "Use default (Email
 * Form)"; settings that don't inherit, like a Section Title row, pass
 * false.
 *
 * @since 6.1
 */

function md_page_cta_admin( $fields, $prefix, $inherit = true ) {
	$cta_type = $fields->saved( 'page_cta' );
	$cta_types = md_page_cta_types();
	$cta_options = array();

	foreach ( $cta_types as $type_id => $type )
		$cta_options[$type_id] = ! empty( $type['label'] ) ? $type['label'] : $type_id;

	if ( $inherit )
		$cta_options['remove'] = __( 'Don\'t show', 'md' );

	include md_template( 'features', 'page-cta/admin/page-cta', true );
}

/**
 * Links CTA panel.
 *
 * @since 6.1
 */

function md_page_cta_links_admin( $fields ) {
	$fields->links_group();
}

/**
 * Custom HTML CTA panel.
 *
 * @since 6.1
 */

function md_page_cta_custom_admin( $fields ) {
	$fields->field( 'custom_html', array(
		'type' => 'code',
		'label' => __( 'Custom HTML', 'md' )
	) );
}
