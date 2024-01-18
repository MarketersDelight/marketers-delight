<?php

/**
 * Get byline items based on a specified position.
 *
 * Accepts: before_headline | after_headline | after_post
 *
 * @since 5.6
 */

function md_get_byline( $position, $loop = array() ) {
	$byline = array();
	$context = 'single';
	$builder = md_post_type_field( array( 'byline', 'builder' ), array() );
	$remove = '';
	$remove_footer = false;

	if ( isset( $loop['is_featured'] ) ) {
		if ( isset( $loop['featured_remove_byline'] ) )
			$remove = $loop['featured_remove_byline'];
		if ( ! empty( $loop['featured_post_footer']['remove'] ) )
			$remove_footer = true;
	}
	else {
		if ( isset( $loop['remove_byline'] ) )
			$remove = $loop['remove_byline'];
		if ( ! empty( $loop['post_footer']['remove'] ) )
			$remove_footer = true;
	}

	if ( $position == $remove || $remove == 'remove' || ( $position == 'after_post' && $remove_footer ) )
		return;

	if ( is_home() || is_archive() )
		$context = 'archives';

	foreach ( $builder as $id => $fields )
		if ( $context == $fields['area'] && $position == $fields['position'] ) {
			$type = $fields['type'];
			$byline[$type] = $fields;
			$byline[$type]['id'] = $id;
		}

	return $byline;
}

/**
 * Render the byline template with designated items.
 *
 * @since 4.0
 */

function md_byline( $location = 'before_headline', $args = array() ) {
	if ( has_action( 'md_hook_byline_' . get_post_type() ) )
		return do_action( 'md_hook_byline_' . get_post_type(), true );

	if ( isset( $args['items'] ) )
		$items = $args['items'];
	else {
		$loop = isset( $args['loop'] ) ? $args['loop'] : array();
		$items = md_get_byline( $location, $loop );
	}

	if ( empty( $items ) && ! empty( $args['default'] ) )
		$items = $args['default'];
	elseif ( empty( $items ) )
		return;

	$c = 1;
	$total = count( $items );
	$classes = array( 'byline' );

	if ( isset( $args['classes'] ) )
		$classes[] = $args['classes'];

	$classes[] = 'items-' . $total;

	$classes = join( ' ', $classes );

	echo '<div class="' .  esc_attr( $classes ) . '">';

	foreach ( $items as $item => $fields ) {
		$fields['c'] = $c;

		if ( isset( $fields['dropin'] ) ) {
			$path = $fields['dropin'];
			$items = md_byline_items();

			if ( isset( $items[$path]['template'] ) )
				call_user_func( $items[$path]['template'], $fields );
		}
		else
			include( md_template( "byline/$item", true ) );

		$c++;
	}

	echo '</div>';
}