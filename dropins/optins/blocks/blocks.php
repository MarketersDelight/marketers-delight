<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Filter Optins Blocks into MD Blocks system.
 *
 * @since 5.3
 */

function md_optins_blocks( $blocks ) {
	$blocks['email'] = array(
		'path' => 'dropins/optins/blocks/email.js',
		'callback' => 'md_email_block',
		'localize' => array( 'colors', 'email' )
	);
	return $blocks;
}

add_filter( 'md_filter_blocks', 'md_optins_blocks' );

/**
 * Add custom parameters to Block localized script.
 *
 * @since 5.3
 */

function md_optins_blocks_scripts( $scripts, $data ) {
	$email = md_email_data( array( 'show' => 'names', 'label' => true, 'empty_label' => true ) );
	$popups = md_setting( array( 'popups', 'popups' ) );

	if ( in_array( 'email', $data ) && ! empty( $email ) )
		foreach ( $email as $list => $name )
			$scripts['email'][] = array( 'label' => $name, 'value' => $list );

	if ( in_array( 'popups', $data ) && ! empty( $popups ) )
		foreach ( $popups as $popup => $fields )
			$scripts['popups'][] = array( 'label' => $fields['name'], 'value' => $popup );

	return $scripts;
}

add_filter( 'md_filter_blocks_scripts', 'md_optins_blocks_scripts', 10, 2 );

/**
 * Email Block template
 *
 * @since 4.9
 */

function md_email_block( $attributes, $content ) {
	ob_start();
	include( md_template( 'dropins/optins', 'blocks/email', true ) );
	return ob_get_clean();
}