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
 * @since 6.0.4
 */

function md_get_cta( $context = 'post' ) {
	if ( $context == 'page' )
		return md_module( 'page_cta' );

	if ( $context == 'post' && is_singular() )
		return md_post_meta( 'page_cta' );

	return array();
}
