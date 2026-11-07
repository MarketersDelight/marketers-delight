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
 * A post's first words, for a title when it has none. Empty for protected
 * posts, and for posts with no words of their own, like a pasted link.
 *
 * @since 6.2
 */

function md_first_words( $post, $words = 9 ) {
	if ( post_password_required( $post ) )
		return '';

	$text = strip_shortcodes( excerpt_remove_blocks( (string) $post->post_content ) );
	$text = preg_replace( '#https?://\S+#i', '', wp_strip_all_tags( $text, true ) );

	return md_sanitize_document_title( wp_trim_words( $text, $words, '…' ) );
}

/**
 * Resolve MD archive titles and give untitled posts a title: their first words,
 * else their type's name. WordPress keeps the site name and other title parts.
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
			$title = md_first_words( $post );

			if ( ! $title && $post_type && ! empty( $post_type->labels->singular_name ) )
				$title = $post_type->labels->singular_name;
			elseif ( ! $title && $post_type && ! empty( $post_type->labels->name ) )
				$title = $post_type->labels->name;
			elseif ( ! $title )
				$title = __( 'Post', 'md' );
		}
	}

	$title = md_sanitize_document_title( $title );

	if ( $title )
		$parts['title'] = $title;

	return $parts;
}

add_filter( 'document_title_parts', 'md_document_title_parts' );
