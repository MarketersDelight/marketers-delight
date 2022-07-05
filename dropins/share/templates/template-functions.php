<?php

/**
 * Like counter on AJAX request.
 *
 * @since 4.9.2
 */

function md_like() {
	if ( wp_verify_nonce( $_POST['nonce'], 'marketers_delight_nonce' ) ) {
		$id = esc_attr( $_POST['post_id'] );
		$post_type = get_post_type( $id );
		$option = md_setting();
		$is_archive = isset( $_POST['archive'] ) && $_POST['archive'] == 'true' ? true : false;
		if ( $is_archive )
			$meta = md_term_meta( null, $id );
		else
			$meta = md_post_meta( null, $id );
		if ( empty( $meta['share']['likes'] ) )
			$meta['share']['likes'] = '';
		$meta['share']['likes']++;
		if ( empty( $option['share']["{$post_type}_likes"] ) )
			$option['share']["{$post_type}_likes"] = '';
		$option['share']["{$post_type}_likes"]++;
		if ( $is_archive )
			update_term_meta( $id, 'marketers_delight', $meta );
		else
			update_post_meta( $id, 'marketers_delight', $meta );
		update_option( 'marketers_delight', $option );
	}
	wp_die();
}

/**
 * Get share icons.
 *
 * @since 5.0
 */

function md_share_icons( $group = null ) {
	$icons = array();
	$option = md_setting( array( 'share', 'icons' ) );
	if ( ! empty( $option ) )
		foreach( $option as $icon => $fields )
			$icons[$fields['status']][] = $icon;
	if ( isset( $group ) )
		$icons = $icons[$group];
	return $icons;
}