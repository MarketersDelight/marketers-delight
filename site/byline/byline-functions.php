<?php

/**
 * Check if current page has byline.
 *
 * @since 4.0
 */

function md_has_byline() {
	$show = true;

	if ( get_post_type() == 'page' )
		$show = false;

	return apply_filters( 'md_filter_has_byline', $show );
}

/**
 * Get byline items based on a specified position.
 *
 * Accepts: before_headline | after_headline | after_post
 *
 * @since 5.6
 */

function md_get_byline( $position ) {
	$byline = array();
	$context = 'single';
	$builder = md_post_type_field( array( 'byline', 'builder' ), array() );

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

	if ( ! md_has_byline() )
		return;

	if ( isset( $args['items'] ) )
		$items = $args['items'];
	else
		$items = md_get_byline( $location );

	if ( empty( $items ) && ! empty( $args['default'] ) )
		$items = $args['default'];
	elseif ( empty( $items ) )
		return;

	$classes = 'byline';

	if ( isset( $args['classes'] ) )
		$classes .= ' ' . $args['classes'];

	echo '<div class="' .  esc_attr( $classes ) . '">';

	foreach ( $items as $item => $fields )
		if ( isset( $fields['dropin'] ) ) {
			$path = $fields['dropin'];
			$items = md_byline_items();

			if ( isset( $items[$path]['template'] ) )
				call_user_func( $items[$path]['template'], $fields );
		}
		else
			include( md_template( "byline/$item", true ) );

	echo '</div>';
}

/**
 * Byline location hooks. A little silly, but keeps flexible.
 *
 * @since 5.6
 */

function md_byline_before_headline() {
	md_byline( 'before_headline', array(
		'default' => array(
			'author' => array(),
			'date' => array(),
			'edit' => array()
		)
	) );
}

function md_byline_after_headline() {
	md_byline( 'after_headline' );
}

function md_byline_after_post() {
	md_byline( 'after_post', array(
		'classes' => 'post-footer'
	) );
}
