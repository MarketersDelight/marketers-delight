<?php // Run Marketers Delight

require_once trailingslashit( get_template_directory() ) . 'marketers-delight.php';

function md_test_compile() { md_compile(); }
add_action( 'init', 'md_test_compile' );
