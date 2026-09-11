<?php

/**
 * Convert a page title to the plaintext format required by the document title.
 *
 * @since 6.0
 */

function md_sanitize_document_title( $title ) {
	return trim( wp_specialchars_decode( wp_strip_all_tags( (string) $title, true ) ) );
}

/**
 * Resolve MD archive titles and guarantee a title for untitled singular posts.
 *
 * Only the page-specific title part is changed. WordPress remains responsible
 * for the site name, pagination, separator, and other document title parts.
 *
 * @since 6.0
 */

function md_document_title_parts( $parts ) {
	$title = '';

	if ( is_home() || is_post_type_archive() || is_tax() || is_category() || is_tag() )
		$title = md_get_title( 'page' );
	elseif ( is_singular() && empty( $parts['title'] ) ) {
		$post = get_queried_object();

		if ( is_object( $post ) && ! empty( $post->post_type ) ) {
			$post_type = get_post_type_object( $post->post_type );
			$stored_title = isset( $post->post_title ) ? trim( (string) $post->post_title ) : '';

			if ( $stored_title )
				$title = get_the_title( $post );
			elseif ( $post_type && ! empty( $post_type->labels->singular_name ) )
				$title = $post_type->labels->singular_name;
			elseif ( $post_type && ! empty( $post_type->labels->name ) )
				$title = $post_type->labels->name;
			else
				$title = __( 'Post', 'md' );
		}
	}

	$title = md_sanitize_document_title( $title );

	if ( $title )
		$parts['title'] = $title;

	return $parts;
}

add_filter( 'document_title_parts', 'md_document_title_parts' );
