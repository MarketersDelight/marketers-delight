<?php

/**
 * A simple way to get various levels of post meta.
 *
 * @since 4.7
 */

function md_post_meta( $keys = null, $id = null, $default = null ) {
	if ( $id === true )
		$id = get_queried_object_id();
	elseif ( ! is_string( $id ) && ! is_int( $id ) )
		$id = get_the_ID();

	$id = apply_filters( 'md_setting_id', $id );
	$meta = get_post_meta( $id, 'marketers_delight', true );

	if ( empty( $meta ) )
		$meta = array();

	if ( isset( $keys ) ) {
		$keys = (array) $keys;
		foreach ( $keys as $key )
			$meta = ! empty( $meta[$key] ) ? $meta[$key] : $default;
	}

	return $meta;
}

/**
 * Quickly access taxonomy field data (deprecates md_tax_data()).
 *
 * @since 4.7
 */

function md_term_meta( $keys = null, $id = null, $default = null ) {
	if ( is_admin() )
		$id = isset( $_GET['tag_ID'] ) ? intval( $_GET['tag_ID'] ) : '';
	else
		$id = isset( $id ) ? $id : get_queried_object_id();

	$id = apply_filters( 'md_setting_id', $id );
	$meta = get_term_meta( $id, 'marketers_delight', true );

	if ( empty( $meta ) )
		$meta = array();

	if ( isset( $keys ) ) {
		$keys = (array) $keys;
		foreach ( $keys as $key )
			$meta = ! empty( $meta[$key] ) ? $meta[$key] : $default;
	}

	return $meta;
}

/**
 * Get the post type whose settings are inherited by another post type.
 *
 * @since 6.0
 */

function md_post_type_settings_parent( $post_type ) {
	$object = get_post_type_object( $post_type );

	return ! empty( $object->md_settings_parent ) ? $object->md_settings_parent : null;
}

/**
 * Get Post Type specific admin fields.
 *
 * @since 6.0
 */

function md_post_type_field( $keys = null, $default = null, $post_type = null ) {
	if ( ! isset( $post_type ) )
		$post_type = md_get_post_type();

	$chain = array();

	// Collect each level once, stopping safely at circular parents

	while ( $post_type && ! array_key_exists( $post_type, $chain ) ) {
		$chain[$post_type] = md_setting( $post_type, array() );
		$post_type = md_post_type_settings_parent( $post_type );
	}

	// Merge from the oldest parent down so each child overrides its defaults

	$settings = array();

	foreach ( array_reverse( $chain ) as $current )
		if ( is_array( $current ) )
			$settings = array_replace_recursive( $settings, $current );

	if ( ! isset( $keys ) )
		return $settings;

	// Follow the requested field path through the resolved settings

	foreach ( (array) $keys as $key ) {
		if ( ! is_array( $settings ) || ! array_key_exists( $key, $settings ) )
			return $default;

		$settings = $settings[$key];
	}

	return $settings;
}

/**
 * Get taxonomy-level global settings (middle tier between post type archive and individual term).
 *
 * @since 6.0
 */

function md_taxonomy_field( $keys = null, $default = null, $post_type = null, $taxonomy = null ) {
	if ( ! $post_type )
		$post_type = md_get_post_type();

	if ( ! $taxonomy ) {
		$queried = get_queried_object();
		$taxonomy = isset( $queried->taxonomy ) ? $queried->taxonomy : null;
	}

	if ( ! $taxonomy )
		return $default;

	$keys = (array) $keys;

	return md_setting( array_merge( (array) $post_type, (array) $taxonomy, $keys ), $default );
}

/**
 * Access user meta.
 *
 * @since 5.3.1
 */

function md_user_meta( $keys = null, $id = null, $default = null ) {
	if ( empty( $id ) )
		if ( is_admin() )
			$id = isset( $_GET['user_id'] ) ? intval( $_GET['user_id'] ) : '';
		else
			$id = get_current_user_id();

	$meta = get_user_meta( $id, 'marketers_delight', true );

	if ( empty( $meta ) )
		$meta = array();

	if ( isset( $keys ) ) {
		$keys = (array) $keys;
		foreach ( $keys as $key )
			$meta = ! empty( $meta[$key] ) ? $meta[$key] : $default;
	}

	return $meta;
}

/**
 * Get meta field from either single or term pages.
 *
 * @since 4.7
 */

function md_meta( $keys = null, $id = null, $default = null ) {
	if ( is_string( $id ) || is_int( $id ) )
		$id = esc_attr( $id );

	$id = apply_filters( 'md_setting_id', $id );

	if ( is_category() || is_tax() )
		return md_term_meta( $keys, $id, $default );
	else
		return md_post_meta( $keys, $id, $default );
}

/**
 * Safely get Block fields.
 *
 * @since 4.9
 */

function md_block_field( $attributes, $field ) {
	return ! empty( $attributes[$field] ) ? $attributes[$field] : '';
}

/**
 * Get module field that is either on single term or post
 * pages, or return global setting as fallback.
 *
 * @since 4.7
 */

function md_module( $keys = null, $default = null, $args = array() ) {
	$args = wp_parse_args( $args, array(
		'id' => null,
		'inherit_post_type' => true
	) );

	$id = apply_filters( 'md_setting_id', $args['id'] );

	if ( is_home() || is_post_type_archive() || is_author() )
		$option = md_post_type_field( $keys, $default );
	elseif ( is_category() || is_tax() ) {
		$option = md_term_meta( $keys, $id, null );

		if ( is_null( $option ) )
			$option = md_taxonomy_field( $keys, null );

		if ( is_null( $option ) )
			$option = $args['inherit_post_type'] ? md_post_type_field( $keys, $default ) : $default;
	}
	elseif ( is_singular() || is_404() ) {
		$option = md_post_meta( $keys, $id, null );

		if ( is_null( $option ) )
			$option = $args['inherit_post_type'] ? md_post_type_field( $keys, $default ) : $default;
	}
	else
		$option = md_setting( $keys, $default );

	return $option;
}

/**
 * Get the current post type of a page. This function exists to cover
 * up a bug that changes the global Loop ID of the first post in the Loop
 * when a Loop Query is modified to combine two post types.
 *
 * @since 6.0
 */

function md_get_post_type( $post_id = null ) {
	$post_type = get_post_type( $post_id );

	if ( $post_type == 'stream_activity' )
		$post_type = 'stream';
	elseif ( is_404() )
		$post_type = 'error';

	return $post_type;
}

/**
 * Builder fields are flexible, repeatable settings groups with
 * extra layout details attached to the builder groups.
 * See Header or Byline for examples of how to construct Builder layouts.
 *
 * @since 6.0
 */

function md_get_builder( $id, $type = null, $key = null ) {
	$rows = md_setting( array( $id, 'builder' ) );
	$builder = array(
		'data' => array(),
		'elements' => array(),
		'locations' => array(),
		'fields' => array()
	);

	if ( $rows ) {
		$builder['fields'] = $rows;
		$elements = apply_filters( "md_{$id}_builder_elements", array() );

		foreach ( $rows as $row_id => $fields ) {
			if ( empty( $fields['builder_type'] ) || ! isset( $fields['builder_area'] ) )
				continue;

			$row_type = $fields['builder_type'];
			$row_area = $fields['builder_area'];
			$render = $elements[$row_type]['render'] ?? 'md_' . sanitize_key( $row_type );

			if ( ! is_callable( $render ) )
				continue;

			$item = array( 'type' => $row_type, 'id' => $row_id, 'render' => $render );

			$builder['data'][$row_area][] = $item;
			$builder['elements'][$row_type][] = $row_id;
			$builder['locations'][$row_id] = $row_area;
		}
	}

	if ( $type )
		$builder = isset( $builder[$type] ) ? $builder[$type] : array();

	if ( ! empty( $key ) )
		$builder = ! empty( $builder[$key] ) ? $builder[$key] : array();

	return $builder;
}

/**
 * Resolve a post type builder whose saved rows replace inherited defaults.
 *
 * @since 6.0
 */

function md_get_post_type_builder( $key, $post_type = null ) {
	$post_type = $post_type ?: md_get_post_type();
	$builder = md_post_type_field( array( $key, 'builder' ), array(), $post_type );
	$defaults = md_setting_defaults();
	$current = $defaults[$post_type][$key]['builder'] ?? array();
	$saved = md_setting_part( $post_type );
	$saved = $saved[$post_type][$key]['builder'] ?? null;

	if ( is_array( $saved ) )
		$current = $saved;

	if ( ! $builder || ! $current )
		return $builder;

	$areas = array();

	foreach ( $current as $fields )
		if ( ! empty( $fields['builder_area'] ) )
			$areas[$fields['builder_area']] = true;

	foreach ( $builder as $id => $fields )
		if ( ! array_key_exists( $id, $current ) && ! empty( $fields['builder_area'] ) && isset( $areas[$fields['builder_area']] ) )
			unset( $builder[$id] );

	return $builder;
}
