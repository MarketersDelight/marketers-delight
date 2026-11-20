<?php

/**
 * Get Hero/inline CTA of any given page. A CTA can be a link group, email
 * form, custom HTML, or more; HTML already built in $cta['html'] is used as is.
 *
 * @since 6.0
 */

function md_cta( $context = 'post', $cta = array() ) {
	if ( ! apply_filters( "md_has_{$context}_cta", true ) )
		return;

	if ( empty( $cta ) )
		$cta = md_get_cta( $context );

	$type = ! empty( $cta['page_cta'] ) ? $cta['page_cta'] : '';
	$html = ! empty( $cta['html'] ) ? $cta['html'] : md_get_cta_html( $context, $cta );

	if ( empty( $html ) )
		return;

	include md_template( 'features', 'page-cta/cta', true );
}

/**
 * Build a CTA's inner HTML: its links, custom HTML, or whatever a Drop-in's
 * md_cta_{type} filter returns. Empty when there's nothing to show.
 *
 * @since 6.2.2
 */

function md_get_cta_html( $context, $cta ) {
	$html = '';
	$type = ! empty( $cta['page_cta'] ) ? $cta['page_cta'] : '';

	if ( $type == 'links' ) {
		$links = ! empty( $cta['links'] ) ? $cta['links'] : array();

		foreach ( $links as $group => $fields )
			if ( ! empty( $fields ) ) {
				$fields['classes'][] = 'cta-link';
				$html .= md_get_link( $fields );
			}
	}
	elseif ( $type == 'custom' && ! empty( $cta['custom_html'] ) )
		$html = $cta['custom_html'];
	elseif ( $type && $type != 'remove' && has_filter( "md_cta_{$type}" ) )
		$html = apply_filters( "md_cta_{$type}", $context, $cta );

	return $html;
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
		$cta = md_value( array( 'key' => 'page_cta', 'page' => true ) );

		// Fields saved without a type don't stop a category inheriting its CTA

		if ( empty( $cta['page_cta'] ) && ( is_category() || is_tax() ) ) {
			$cta = md_value( array(
				'key' => 'page_cta',
				'taxonomy' => get_queried_object()->taxonomy,
				'post_type' => md_get_post_type(),
				'inherit_post_type' => false
			) );

			if ( empty( $cta['page_cta'] ) )
				$cta = md_value( array( 'key' => 'page_cta', 'post_type' => md_get_post_type() ) );
		}

		return $cta;
	}

	if ( $context == 'post' && md_is_queried_post() )
		return md_post_meta( 'page_cta' );

	return array();
}

/**
 * CTA types with their labels, save rules and admin panels. Drop-ins add types
 * through md_page_cta_types; admin_callback gets ( $fields, $prefix ), and
 * 'heading' => false skips the panel heading and description for its own.
 *
 * @since 6.1
 */

function md_page_cta_types() {
	$data = new md_fields_data;

	return apply_filters( 'md_page_cta_types', array(
		'links' => array(
			'label' => __( 'Links', 'md' ),
			'fields' => array(
				'links' => array(
					'type' => 'group',
					'fields' => $data->links( array( 'sort' => 'save' ) )
				)
			),
			'description' => __( 'Buttons or text links.', 'md' ),
			'admin_callback' => 'md_page_cta_links_admin',
			'heading' => false
		),
		'custom' => array(
			'label' => __( 'Custom HTML', 'md' ),
			'fields' => array(
				'custom_html' => array(
					'type' => 'code',
					'label' => __( 'Custom HTML', 'md' )
				)
			),
			'description' => __( 'Your own HTML, like a form embed code.', 'md' ),
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
			'label' => __( 'CTA Type', 'md' ),
			'options' => array_merge( array_keys( $types ), array( 'remove' ) )
		),
		'title_position' => array(
			'type' => 'select',
			'label' => __( 'Position', 'md' ),
			'options' => array(
				'before_title' => __( 'Before Title', 'md' ),
				'byline_aside' => __( 'Title Aside', 'md' )
			)
		),
		'cta_lede' => array(
			'type' => 'text',
			'label' => __( 'Lede', 'md' )
		),
		'cta_title' => array(
			'type' => 'text',
			'label' => __( 'Heading', 'md' )
		),
		'cta_text' => array(
			'type' => 'textarea',
			'label' => __( 'Text', 'md' )
		),
		'cta_foot' => array(
			'type' => 'text',
			'label' => __( 'Note below', 'md' )
		)
	);

	foreach ( $types as $type )
		if ( ! empty( $type['fields'] ) && is_array( $type['fields'] ) )
			$save = array_merge( $save, $type['fields'] );

	return $save;
}

/**
 * Render the CTA type select and each type's panel. With $inherit, an empty
 * select names the CTA it inherits, and Don't show turns that CTA off.
 *
 * @since 6.1
 */

function md_page_cta_admin( $fields, $prefix, $inherit = true ) {
	$cta_type = $fields->saved( 'page_cta' );
	$cta_types = md_page_cta_types();
	$cta_options = array();

	foreach ( $cta_types as $type_id => $type )
		$cta_options[$type_id] = ! empty( $type['label'] ) ? $type['label'] : $type_id;

	$text_classes = 'md-conditional-item md-conditional-' . join( ' md-conditional-', array_keys( $cta_types ) );
	$text_display = $cta_type && $cta_type !== 'remove' ? 'block' : 'none';

	$inherited = $inherit ? $fields->inherited( 'page_cta' ) : '';

	if ( $cta_type === 'remove' || ( $inherited && $inherited !== 'remove' ) )
		$cta_options['remove'] = __( 'Don\'t show', 'md' );

	include md_template( 'features', 'page-cta/admin/page-cta', true );
}

/**
 * Links CTA panel.
 *
 * @since 6.1
 */

function md_page_cta_links_admin( $fields ) {
	$fields->links_group( 'links', array(
		'label' => __( 'Links', 'md' ),
		'description' => __( 'Buttons or text links.', 'md' )
	) );
}

/**
 * Custom HTML CTA panel.
 *
 * @since 6.1
 */

function md_page_cta_custom_admin( $fields ) {
	$fields->field( 'custom_html', array( 'label' => null ) );
}
