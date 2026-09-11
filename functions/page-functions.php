<?php

/**
 * Determine whether singular posts of a registered post type are indexable.
 *
 * Post types are indexable unless they explicitly register the
 * md_singular_indexable argument as false.
 *
 * @since 6.0
 */

function md_is_singular_indexable( $post_type ) {
	$post_type = get_post_type_object( $post_type );

	return ! $post_type || ! isset( $post_type->md_singular_indexable ) || false !== $post_type->md_singular_indexable;
}

/**
 * Keep declared content fragments out of search engine indexes.
 *
 * @since 6.0
 */

function md_robots_noindex_singular( $robots ) {
	if ( ! is_singular() )
		return $robots;

	$post = get_queried_object();

	if ( is_object( $post ) && ! empty( $post->post_type ) && ! md_is_singular_indexable( $post->post_type ) ) {
		unset( $robots['index'] );
		$robots['noindex'] = true;
	}

	return $robots;
}

add_filter( 'wp_robots', 'md_robots_noindex_singular' );

/**
 * Remove non-indexable singular post types from WordPress core sitemaps.
 *
 * @since 6.0
 */

function md_sitemaps_post_types( $post_types ) {
	foreach ( $post_types as $post_type => $object )
		if ( ! md_is_singular_indexable( $post_type ) )
			unset( $post_types[$post_type] );

	return $post_types;
}

add_filter( 'wp_sitemaps_post_types', 'md_sitemaps_post_types' );

/**
 * Callback function for get_searchform().
 *
 * @since 6.0
 */

function md_search( $args = array() ) {
	include locate_template( 'searchform.php' );
}

/**
 * Outputs the standard WordPress password form with the
 * .form-attached class added to it.
 *
 * @since 4.0
 * @revised 4.3.5
 */

if ( ! function_exists( 'md_password_form' ) ) :

function md_password_form() {
    global $post;
    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
    $o =
		'<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post" class="form-attached">'.
			'<p>' . __( 'To view this protected post, enter the password below:', 'md' ) . '</p>'.
			'<input name="post_password" id="' . $label . '" class="form-input" type="password" placeholder="' . __( 'Enter the password&hellip;', 'md' ) . '" size="20" maxlength="20" />'.
			'<input type="submit" name="submit" class="form-submit" value="' . esc_attr__( 'Get Access', 'md' ) . '" />'.
		'</form>';

	return $o;
}

endif;

add_filter( 'the_password_form', 'md_password_form' );
