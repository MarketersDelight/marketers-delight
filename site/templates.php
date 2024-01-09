<?php
/**
 * A collection of action hooks and filters that construct various
 * parts of the website layout.
 *
 * @since 5.6
*/

if ( ! function_exists( 'md_templates' ) ) :

function md_templates() {

	// Breadcrumbs

	if ( md_has_breadcrumbs() )
		add_action( 'md_hook_content', 'md_breadcrumbs' );

	// Page Title

	if ( ! is_singular() ) {
		$page_title = new md_page_title;
		$page_title->templates();
	}

	// Content Box

	add_action( 'md_hook_content', 'md_loop', 30 );
	add_action( 'md_hook_featured_image_bottom', 'md_get_caption' );

	if ( md_has_headline() )
		add_action( 'md_hook_content_item', 'md_headline', 20 );

	add_action( 'md_hook_content_item', 'md_content_text', 40 );

	if ( md_has_author_box() )
		add_action( 'md_hook_content_item', 'md_author', 60 );

	add_action( 'md_hook_content_item', 'md_comments', 60 );
	add_action( 'md_hook_after_comments_list', 'md_comment_form' );

	add_action( 'md_hook_content', 'md_pagination', 40 );

	if ( md_has_post_nav() )
		add_action( 'md_hook_content', 'md_post_nav', 40 );

	// Footer

	add_action( 'md_hook_footer', 'md_footer_columns_template' );
	add_action( 'md_hook_footer_bottom', 'md_footer_copy' );
}

endif;

add_action( 'template_redirect', 'md_templates' );
