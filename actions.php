<?php

/**
 * Fire general layout hooks.
 *
 * @since 4.0
 */

add_action( 'md_hook_content_item', 'md_author', 20 );
add_action( 'md_hook_content_item', 'md_comments', 20 );
add_action( 'md_hook_after_comments_list', 'md_comment_form' );
add_action( 'md_hook_footer', 'md_footer_columns_template' );
add_action( 'md_hook_footer_bottom', 'md_footer_copy' );

/**
 * Add hooks to the template with advanced logic.
 *
 * @since 4.0
 */

function md_templates() {
	$context = is_singular() || is_404() ? 'post' : 'page';
	$title_hook = md_has_header_cover( $context ) ? 'md_hook_content_box_top' : 'md_hook_content';

	add_action( 'md_hook_content', 'md_breadcrumbs' );
	add_action( $title_hook, 'md_page_title' );

	if ( md_has_post_content() )
		add_action( 'md_hook_content', 'md_loop', 20 );

	add_action( 'md_hook_content', 'md_post_nav', 50 );
}

add_action( 'template_redirect', 'md_templates' );

/**
 * Call Post/Page Title dynamically.
 *
 * @since 4.0
 */

function md_page_title() {
	if ( ! is_singular() && ! is_404() )
		md_title( 'page' );
	elseif ( md_has_header_cover( 'post' ) )
		md_title();
}

/**
 * Register MD action hooks.
 *
 * @since 4.0
 */

function md_block_editor_css() {
	do_action( 'md_block_editor_css' );
}

function md_hook_css_data() { // 5.3.1.1
	do_action( 'md_hook_css_data' );
}

function md_hook_before_html() {
	do_action( 'md_hook_before_html' );
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
	do_action( 'md_hook_before_header' );
}

function md_hook_after_header() {
	do_action( 'md_hook_after_header' );
}

function md_hook_before_header_menu() {
	do_action( 'md_hook_before_header_menu' );
}

function md_hook_after_header_menu() {
	do_action( 'md_hook_after_header_menu' );
}

function md_hook_header_details() {
	do_action( 'md_hook_header_details' );
}

function md_hook_header_logo_bottom() {
	do_action( 'md_hook_header_logo_bottom' );
}

function md_hook_after_site_title() {
	do_action( 'md_hook_after_site_title' );
}

function md_hook_before_content_box() {
	do_action( 'md_hook_before_content_box' );
}

function md_hook_content_box_top() {
	do_action( 'md_hook_content_box_top' );
}

function md_hook_content_box_bottom() {
	do_action( 'md_hook_content_box_bottom' );
}

function md_hook_content() {
	$data = apply_filters( 'md_content_data', array() );

	do_action( 'md_hook_content', $data );
}

function md_hook_content_top() {
	do_action( 'md_hook_content_top' );
}

function md_hook_content_bottom() {
	do_action( 'md_hook_content_bottom' );
}

function md_hook_before_content() {
	do_action( 'md_hook_before_content' );
}

function md_hook_after_content() {
	do_action( 'md_hook_after_content' );
}

function md_hook_loop_top() {
	do_action( 'md_hook_loop_top' );
}

function md_hook_loop_before() {
	do_action( 'md_hook_loop_before' );
}

function md_hook_loop_after() {
	do_action( 'md_hook_loop_after' );
}

function md_hook_content_item() {
	do_action( 'md_hook_content_item' );
}

function md_hook_the_content() {
	do_action( 'md_hook_the_content' );
}

function md_hook_before_the_content() {
	do_action( 'md_hook_before_the_content' );
}

function md_hook_after_the_content() {
	do_action( 'md_hook_after_the_content' );
}

function md_hook_the_content_top() {
	do_action( 'md_hook_the_content_top' );
}

function md_hook_the_content_bottom() {
	do_action( 'md_hook_the_content_bottom' );
}

function md_hook_before_title() {
	do_action( 'md_hook_before_title' );
}

function md_hook_after_title() {
	do_action( 'md_hook_after_title' );
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

function md_hook_featured_post_bottom() {
	do_action( 'md_hook_featured_post_bottom' );
}

function md_hook_comments() {
	do_action( 'md_hook_comments' );
}

function md_hook_before_comments_list() {
	do_action( 'md_hook_before_comments_list' );
}

function md_hook_after_comments_list() {
	do_action( 'md_hook_after_comments_list' );
}

function md_hook_before_sidebar() {
	do_action( 'md_hook_before_sidebar' );
}

function md_hook_after_sidebar() {
	do_action( 'md_hook_after_sidebar' );
}

function md_hook_before_footer() {
	do_action( 'md_hook_before_footer' );
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

function md_hook_js_custom_triggers() {
	do_action( 'md_hook_js_custom_triggers' );
}

function md_hook_js_onscroll() {
	do_action( 'md_hook_js_onscroll' );
}