<?php // Run Marketers Delight

require_once trailingslashit( get_template_directory() ) . 'marketers-delight.php';

function vin() {
	md_compile();
}
add_action( 'init', 'vin' );