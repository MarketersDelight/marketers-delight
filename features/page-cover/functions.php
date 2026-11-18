<?php

/**
 * Get Cover attributes for any given page.
 *
 * @since 4.1
 * @renamed 6.0 (md_featured_image_style)
 */

function md_cover( $context = null ) {
	if ( ! isset( $context ) )
		$context = is_singular() || is_404() ? 'post' : 'page';

	$cover = array();
	$inherit = false;
	$post_type_cover = md_value( array(
		'key' => 'page_cover',
		'default' => array(),
		'post_type' => md_get_post_type()
	) );

	if ( $context === 'post' ) {
		$inherit_args = array(
			'key' => array( 'loop', 'inherit', 'page_cover' ),
			'post_type' => md_get_post_type()
		);

		if ( is_category() || is_tax() ) {
			$inherit_args['term'] = get_queried_object_id();
			$inherit_args['taxonomy'] = get_queried_object()->taxonomy;
		}

		$inherit = md_value( $inherit_args );
		$single_cover = (array) md_post_meta( 'page_cover', null, array() );

		foreach ( $single_cover as $key => $value )
			if ( $value === '' || $value === false || $value === null || $value === array() )
				unset( $single_cover[$key] );

		if ( ! empty( $post_type_cover['display']['single'] ) )
			$cover = array_replace_recursive( $post_type_cover, $single_cover );
		else
			$cover = $single_cover;
	}
	elseif ( is_category() || is_tax() ) {
		$tax_cover = md_value( array(
			'key' => 'page_cover',
			'default' => array(),
			'taxonomy' => get_queried_object()->taxonomy,
			'post_type' => md_get_post_type(),
			'inherit_post_type' => false
		) );
		$term_cover = (array) md_term_meta( 'page_cover', null, array() );

		foreach ( $term_cover as $key => $value )
			if ( $value === '' || $value === false || $value === null || $value === array() )
				unset( $term_cover[$key] );

		$cover = array_replace_recursive( $post_type_cover, $tax_cover, $term_cover );
	}
	elseif ( is_post_type_archive() || is_home() )
		$cover = $post_type_cover;
	elseif ( is_author() )
		$cover = md_value( array(
			'key' => 'page_cover',
			'default' => array(),
			'post_type' => 'author'
		) );
	else $cover = md_post_meta( 'page_cover', true, array() );

	if (
		( isset( $cover['position'] ) && $cover['position'] === 'remove' ) ||
		( $context === 'post' && ! md_is_queried_post() && ! $inherit )
	)
		$cover = array();

	return $cover;
}

/**
 * Return an array of class names related to a Cover. Pass $cover to use
 * resolved cover data instead of the current page's.
 *
 * @since 6.0
 */

function md_cover_classes( $context = 'post', $cover = null ) {
	$classes = array();
	$cover = $cover ?? md_cover( $context );

	if ( empty( $cover['position'] ) )
		return $classes;

	$classes[] = 'cover';

	$classes[] = ! empty( $cover['display']['alternate'] ) || md_color_is_light( $cover['bg_color'] ?? '' ) ? 'text-dark' : 'text-white';

	if ( ! empty( $cover['photo']['id'] ) )
		$classes[] = 'has-image';

	if ( ! empty( $cover['display']['bg_repeat'] ) )
		$classes[] = 'repeat';

	return join( ' ', $classes );
}

/**
 * Check if Header Cover or Header Cover Full.
 *
 * @since 6.0
 */

function md_has_header_cover( $context = null, $cover = null ) {
	if ( ! isset( $context ) )
		$context = is_singular() || is_404() ? 'post' : 'page';

	$cover = $cover ?? md_cover( $context );

	if (
		( ( $context == 'post' && ( is_singular() || is_404() ) ) || $context == 'page' ) &&
		! empty( $cover['position'] ) && in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ), true )
	)
		return esc_attr( $cover['position'] );
}

/**
 * Print a cover's photo as an image behind its content, so it loads sized
 * files. A repeating photo stays a CSS background. The first hero cover
 * (a page title, or one marked 'hero') loads first; the rest load lazily.
 *
 * @since 6.2.3
 */

function md_cover_image( $context = 'post', $cover = null ) {
	static $has_priority = false;
	$cover = $cover ?? md_cover( $context );
	$is_hero = ! empty( $cover['hero'] ) || $context === 'page' || ( $context === 'post' && md_is_queried_post() );
	$attributes = array(
		'class' => 'cover-image',
		'sizes' => '100vw',
		'loading' => 'lazy'
	);

	if ( empty( $cover['position'] ) || empty( $cover['photo']['id'] ) || ! empty( $cover['display']['bg_repeat'] ) )
		return;

	if ( $is_hero && ! $has_priority ) {
		$attributes['loading'] = false;
		$attributes['fetchpriority'] = 'high';
		$has_priority = true;
	}

	echo wp_get_attachment_image( $cover['photo']['id'], 'full', false, $attributes );
}

/**
 * Add Overlay HTML to covers. Pass $cover to use resolved cover data
 * instead of the current page's, with an optional 'overlay_opacity'.
 *
 * @since 4.8.6
 */

function md_overlay( $context = 'post', $cover = null ) {
	$style = array();
	$cover = $cover ?? md_cover( $context );

	if ( empty( $cover['position'] ) || ! empty( $cover['display']['disable_overlay'] ) )
		return;

	if ( ! empty( $cover['bg_color'] ) )
		$style['vars']['md-overlay'] = md_color_hex( $cover['bg_color'] );

	if ( isset( $cover['overlay_opacity'] ) && $cover['overlay_opacity'] !== '' )
		$style['vars']['md-overlay-opacity'] = $cover['overlay_opacity'];

	echo '<div class="overlay" aria-hidden="true"' . md_style( $style ) . '></div>';
}

/**
 * Page Cover fields and their save rules, shared by the Page Cover
 * settings and Section Titles.
 *
 * @since 6.1
 */

function md_page_cover_fields() {
	return array(
		'photo' => array(
			'type' => 'upload',
			'upload_type' => 'media',
			'label' => __( 'Upload Image', 'md' )
		),
		'position' => array(
			'type' => 'select',
			'label' => __( 'Position', 'md' ),
			'options' => array( 'headline_cover', 'header_cover', 'header_cover_full', 'remove' )
		),
		'bg_color' => array(
			'type' => 'color',
			'label' => __( 'Overlay Color', 'md' )
		),
		'display' => array(
			'type' => 'checkbox',
			'options' => array( 'alternate', 'disable_overlay', 'bg_repeat', 'single', 'show_excerpt' )
		),
		'title_content' => array(
			'type' => 'text',
			'label' => __( 'Description', 'md' )
		)
	);
}
