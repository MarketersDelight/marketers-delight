<?php

if (!defined('ABSPATH')) { exit; }

require_once( trailingslashit( get_template_directory() ) . 'lib/marketers-delight.php' );

/**
 * Outputs inline JavaScript to footer.
 *
 * @since 4.0
 */

if ( ! function_exists( 'md_inline_js' ) ) :
	function md_inline_js() {
		if ( md_has_menu() )
			wp_add_inline_script( 'marketers-delight', "\tMD.headerMenu();" );
		if ( md_has_main_menu() )
			wp_add_inline_script( 'marketers-delight', "\tMD.mainMenu();" );
	}
endif;
add_action( 'wp_footer', 'md_inline_js' );

/**
 * Filter length of excerpts + more text of loops.
 *
 * @since 4.5
 */

function md_excerpt_length() {
	$words = md_get_loop( array( 'loop', 'excerpt_length' ) );
	$words = ! empty( $words ) ? $words : 55;
	return apply_filters( 'md_filter_excerpt_length', esc_attr( $words ) );
}
add_filter( 'excerpt_length', 'md_excerpt_length' );

/**
 * Removes "Protected:" text from the title of password protected posts.
 *
 * @since 4.1
 */

function md_remove_protected_title( $title ) {
	return '%s';
}
add_filter( 'private_title_format', 'md_remove_protected_title' );
add_filter( 'protected_title_format', 'md_remove_protected_title' );