<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function md_hook_css_data() { // 5.3.1.1
	do_action( 'md_hook_css_data' );
}

function md_hook_before_html() {
	do_action( 'md_hook_before_html', 'before_html' );
}

function md_hook_header() {
	do_action( 'md_hook_header' );
}

function md_hook_header_top() {
	do_action( 'md_hook_header_top' );
}

function md_hook_header_bottom() {
	do_action( 'md_hook_header_bottom' );
}

function md_hook_before_header() {
	do_action( 'md_hook_before_header', 'before_header' );
}

function md_hook_after_header() {
	do_action( 'md_hook_after_header', 'after_header' );
}

function md_hook_header_aside() {
	do_action( 'md_hook_header_aside' );
}

function md_hook_header_triggers() {
	do_action( 'md_hook_header_triggers' );
}

function md_hook_before_header_menu() {
	do_action( 'md_hook_before_header_menu' );
}

function md_hook_after_header_menu() {
	do_action( 'md_hook_after_header_menu' );
}

function md_hook_header_logo_bottom() {
	do_action( 'md_hook_header_logo_bottom' );
}

function md_hook_before_content_box() {
	do_action( 'md_hook_before_content_box', 'before_content_box' );
}

function md_hook_content_box_top() {
	do_action( 'md_hook_content_box_top' );
}

function md_hook_content_box_bottom() {
	do_action( 'md_hook_content_box_bottom' );
}

function md_hook_content() {
	do_action( 'md_hook_content', 'content' );
}

function md_hook_content_top() {
	do_action( 'md_hook_content_top' );
}

function md_hook_content_bottom() {
	do_action( 'md_hook_content_bottom' );
}

function md_hook_before_content() {
	do_action( 'md_hook_before_content', 'before_content' );
}

function md_hook_after_content() {
	do_action( 'md_hook_after_content', 'after_content' );
}

function md_hook_content_item() {
	do_action( 'md_hook_content_item', 'content' );
}

function md_hook_before_the_content() {
	do_action( 'md_hook_before_the_content', '' );
}

function md_hook_headline_top() {
	do_action( 'md_hook_headline_top' );
}

function md_hook_headline_bottom() {
	do_action( 'md_hook_headline_bottom' );
}

function md_hook_before_headline() {
	do_action( 'md_hook_before_headline' );
}

function md_hook_after_headline() {
	do_action( 'md_hook_after_headline' );
}

function md_hook_byline_top() {
	do_action( 'md_hook_byline_top' );
}

function md_hook_byline_bottom() {
	do_action( 'md_hook_byline_bottom' );
}

function md_hook_after_featured_image() {
	do_action( 'md_hook_after_featured_image' );
}

function md_hook_featured_image_bottom() {
	do_action( 'md_hook_featured_image_bottom' );
}

function md_hook_content_item_text_top() {
	do_action( 'md_hook_content_item_text_top' );
}

function md_hook_teaser_top() {
	do_action( 'md_hook_teaser_top' );
}

function md_hook_teaser_bottom() {
	do_action( 'md_hook_teaser_bottom' );
}

function md_hook_x_loop( $c ) {
	$x_loop = md_get_loop( array( 'loop', 'cta_x_loop' ) );
	$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	if ( $c == $x_loop && $paged == 1 )
		do_action( 'md_hook_x_loop' );
}

function md_hook_comments() {
	do_action( 'md_hook_comments' );
}

function md_hook_before_sidebar() {
	do_action( 'md_hook_before_sidebar', 'before_sidebar' );
}

function md_hook_after_sidebar() {
	do_action( 'md_hook_after_sidebar', 'after_sidebar' );
}

function md_hook_before_footer() {
	do_action( 'md_hook_before_footer', 'before_footer' );
}

function md_hook_footer() {
	do_action( 'md_hook_footer' );
}

function md_hook_footer_top() {
	do_action( 'md_hook_footer_top' );
}

function md_hook_footer_bottom() {
	do_action( 'md_hook_footer_bottom' );
}

function md_hook_after_footer() {
	do_action( 'md_hook_after_footer' );
}

function md_hook_before_footer_copy() {
	do_action( 'md_hook_before_footer_copy' );
}

function md_hook_after_footer_copy() {
	do_action( 'md_hook_after_footer_copy' );
}

function md_hook_js() {
	do_action( 'md_hook_js' );
}