<?php

/**
 * Get list of items that can be used in Archive Meta, optionally
 * limited to items available for a post type.
 *
 * @since 6.0
 */

function md_archive_meta_items( $post_type = null ) {
	$items = apply_filters( 'md_archive_meta_items', array(), $post_type );

	if ( $post_type === null )
		return $items;

	foreach ( $items as $id => $fields )
		if ( ! empty( $fields['post_types'] ) && ( ! $post_type || ! in_array( $post_type, (array) $fields['post_types'], true ) ) )
			unset( $items[$id] );

	return $items;
}

/**
 * Emphasize the numbers in an Archive Meta value, leaving any
 * markup an item returns untouched.
 *
 * @since 6.0
 */

function md_archive_meta_value( $value ) {
	$parts = preg_split( '/(<[^>]*>)/', wp_kses_post( $value ), -1, PREG_SPLIT_DELIM_CAPTURE );
	$depth = 0;

	foreach ( $parts as $i => $part ) {
		if ( $part === '' )
			continue;

		if ( $part[0] === '<' ) {
			if ( strpos( $part, '</' ) === 0 )
				$depth--;
			elseif ( substr( $part, -2 ) !== '/>' )
				$depth++;

			continue;
		}

		if ( ! $depth )
			$parts[$i] = preg_replace( '/\d[\d,.]*/', '<strong>$0</strong>', $part );
	}

	return join( '', $parts );
}

/**
 * Render the Archive Meta items configured for the current archive.
 *
 * @since 6.0
 */

function md_archive_meta() {
	if ( ! is_home() && ! is_archive() )
		return;

	$post_type = md_get_post_type();
	$builder = md_get_post_type_builder( 'archive_meta', $post_type );
	$items = md_archive_meta_items( $post_type );
	$output = '';

	foreach ( $builder as $fields ) {
		if ( ( $fields['builder_area'] ?? '' ) !== 'archives' )
			continue;

		$type = $fields['builder_type'] ?? '';
		$callback = $items[$type]['callback'] ?? null;

		if ( ! is_callable( $callback ) )
			continue;

		$value = call_user_func( $callback, $fields, $post_type );

		if ( $value )
			$output .= '<span class="byline-item middot">' . md_icon( $items[$type]['icon'] ) . '<span class="byline-label">' . md_archive_meta_value( $value ) . '</span></span>';
	}

	if ( $output )
		echo '<div class="archive-meta byline">' . $output . '</div>';
}
