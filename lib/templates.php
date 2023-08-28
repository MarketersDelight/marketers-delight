<?php
/**
 * A collection of action hooks and filters that construct various
 * parts of the website layout.
 *
 * @since 5.6
*/

if ( ! function_exists( 'md_templates' ) ) :

function md_templates() {
	$breadcrumbs = md_has_breadcrumbs();
	if ( $breadcrumbs ) {
		$hook = 'md_hook_content';
		if ( $breadcrumbs == 'before_page_title' ) {
			if ( is_singular() ) {
				$cover = md_cover();
				if ( ! empty( $cover['position'] ) )
					$hook = 'md_hook_before_headline';
			}
			else
				$hook = 'md_hook_page_title';
		}
		add_action( $hook, 'md_breadcrumbs' );
	}

	if ( ! is_singular() ) {
		$page_title = new md_page_title;
		$page_title->templates();
	}

	add_action( 'md_hook_content', 'md_loop' );
	add_action( 'md_hook_content', 'md_pagination', 30 );

	if ( md_has_headline() && ! md_has_headline_cover() )
		add_action( 'md_hook_content_item', 'md_headline', 20 );

	add_action( 'md_hook_content_item', 'md_content_text', 40 );
	add_action( 'md_hook_content_item', 'md_comments', 60 );
	add_action( 'md_hook_after_comments_list', 'md_comment_form' );

	if ( ! is_404() && md_has_byline() ) {
		$hook_byline = 'md_hook_before_headline';
		$byline_position = md_post_type_field( array( 'loop', 'byline_position' ) );

		if ( is_singular() ) {
			$single_byline_position = md_post_type_field( array( 'single', 'byline_position' ) );

			if ( ! empty( $single_byline_position ) )
				$byline_position = $single_byline_position;
		}

		if ( $byline_position == 'after_headline' )
			$hook_byline = 'md_hook_after_headline';

		add_action( $hook_byline, 'md_byline' );
	}

	add_action( 'md_hook_before_headline', 'md_cover_caption', 3 );

	if ( md_has_author_box() )
		add_action( 'md_hook_content_item', 'md_author', 50 );

	if ( is_singular( 'post' ) )
		add_action( 'md_hook_content', 'md_post_nav', 70 );

	add_action( 'md_hook_footer', 'md_footer_columns_template' );
	add_action( 'md_hook_footer_bottom', 'md_footer_copy' );
}

endif;

add_action( 'template_redirect', 'md_templates' );
