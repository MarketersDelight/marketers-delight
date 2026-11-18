<?php

/**
 * The value that applies for a setting. md_value( 'key' ) or
 * md_value( array( 'a', 'b' ), $default ) read the site; args with a 'key' entry
 * add 'default' and where to read: 'page' => true, 'post', 'term', 'taxonomy', 'post_type'.
 *
 * @since 6.2.2
 */

function md_value( $key, $default = null ) {
	static $values = null;

	if ( ! $values )
		$values = new md_values;

	return $values->get( $key, $default );
}

/**
 * What a post stores at $keys: the post in the loop, the page being viewed
 * ($id true), or the post $id names.
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
			$meta = is_array( $meta ) && array_key_exists( $key, $meta ) ? $meta[$key] : $default;
	}

	return $meta;
}

/**
 * What a term stores at $keys: the term being viewed, or the one $id names.
 *
 * @since 4.7
 */

function md_term_meta( $keys = null, $id = null, $default = null ) {
	if ( ! isset( $id ) )
		$id = is_admin() ? ( isset( $_GET['tag_ID'] ) ? intval( $_GET['tag_ID'] ) : '' ) : get_queried_object_id();

	$id = apply_filters( 'md_setting_id', $id );
	$meta = get_term_meta( $id, 'marketers_delight', true );

	if ( empty( $meta ) )
		$meta = array();

	if ( isset( $keys ) ) {
		$keys = (array) $keys;

		foreach ( $keys as $key )
			$meta = is_array( $meta ) && array_key_exists( $key, $meta ) ? $meta[$key] : $default;
	}

	return $meta;
}

/**
 * What a user stores at $keys: the current user, or the one $id names.
 *
 * @since 5.3.1
 */

function md_user_meta( $keys = null, $id = null, $default = null ) {
	if ( empty( $id ) )
		$id = is_admin() && isset( $_GET['user_id'] ) ? intval( $_GET['user_id'] ) : get_current_user_id();

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
 * What the page being viewed stores itself: its term's meta on term pages,
 * its post's otherwise.
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
 * Get the current post type of a page. This function exists to cover
 * up a bug that changes the global Loop ID of the first post in the Loop
 * when a Loop Query is modified to combine two post types.
 *
 * @since 6.0
 */

function md_get_post_type( $post_id = null ) {
	$post_type = get_post_type( $post_id );
	$term_page = $post_id === null && ( is_category() || is_tag() || is_tax() );
	$taxonomy = $term_page ? ( get_queried_object()->taxonomy ?? '' ) : '';

	if ( $term_page && ( ! $post_type || ( ! in_the_loop() && md_is_shared_taxonomy( $taxonomy ) ) ) )
		$post_type = md_taxonomy_post_type( $taxonomy );
	elseif ( ! $post_type && $post_id === null ) {
		if ( is_post_type_archive() ) {
			$post_type = get_query_var( 'post_type' );
			$post_type = is_array( $post_type ) ? reset( $post_type ) : $post_type;
		}
		elseif ( is_home() )
			$post_type = 'post';
	}

	if ( $post_type == 'stream_activity' )
		$post_type = 'stream';
	elseif ( is_404() )
		$post_type = 'error';

	return $post_type;
}

/**
 * A taxonomy's home post type: the first one it was registered to. Its
 * settings live there, even when other post types share the taxonomy.
 *
 * @since 6.2.4
 */

function md_taxonomy_post_type( $taxonomy ) {
	$object = $taxonomy ? get_taxonomy( $taxonomy ) : null;

	return ! empty( $object->object_type ) ? reset( $object->object_type ) : false;
}

/**
 * MD's post types that use a taxonomy, in the order it was registered to them.
 *
 * @since 6.2.4
 */

function md_taxonomy_post_types( $taxonomy ) {
	$object = $taxonomy ? get_taxonomy( $taxonomy ) : null;
	$post_types = array();

	if ( ! empty( $object->object_type ) ) {
		$options = md_post_type_options();

		foreach ( $object->object_type as $post_type )
			if ( isset( $options[$post_type] ) )
				$post_types[] = $post_type;
	}

	return $post_types;
}

/**
 * Whether two or more of MD's post types use a taxonomy, so its term pages
 * can list several post types.
 *
 * @since 6.2.4
 */

function md_is_shared_taxonomy( $taxonomy ) {
	return count( md_taxonomy_post_types( $taxonomy ) ) > 1;
}

/**
 * The post type whose settings another post type inherits.
 *
 * @since 6.0
 */

function md_post_type_settings_parent( $post_type ) {
	$object = get_post_type_object( $post_type );

	return $object && ! empty( $object->md_settings_parent ) ? $object->md_settings_parent : null;
}

/**
 * Check whether the current post is the single being viewed, not a post
 * that a Query Loop lists on its page.
 *
 * @since 6.1
 */

function md_is_queried_post() {
	return is_singular() && get_the_ID() === get_queried_object_id();
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
			$callback = $elements[$row_type]['callback'] ?? 'md_' . sanitize_key( $row_type );

			if ( ! is_callable( $callback ) )
				continue;

			$item = array( 'type' => $row_type, 'id' => $row_id, 'callback' => $callback );

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
 * Resolve builder areas from inherited post types, taxonomies, terms, and
 * the single being viewed. Each configured area replaces the same area
 * from the preceding layer.
 *
 * @since 6.0
 */

function md_get_post_type_builder( $key, $post_type = null, $preserve_empty = false ) {
	$post_type = $post_type ?: md_get_post_type();
	$args = array(
		'key' => array( $key, 'builder' ),
		'default' => array(),
		'post_type' => $post_type,
		'merge' => 'areas',
		'reset' => $preserve_empty
	);

	if ( is_singular() && $post_type === get_post_type( get_queried_object_id() ) )
		$args['post'] = get_queried_object_id();
	elseif ( is_category() || is_tax() ) {
		$queried = get_queried_object();

		if ( ! empty( $queried->taxonomy ) && is_object_in_taxonomy( $post_type, $queried->taxonomy ) ) {
			$args['term'] = get_queried_object_id();
			$args['taxonomy'] = $queried->taxonomy;
		}
	}

	return md_value( $args );
}

/**
 * Drop builder items made for other post types. Items without
 * post_types are available everywhere.
 *
 * @since 6.1
 */

function md_builder_items_for( $items, $post_type ) {
	foreach ( $items as $id => $fields )
		if ( ! empty( $fields['post_types'] ) && ( ! $post_type || ! in_array( $post_type, (array) $fields['post_types'], true ) ) )
			unset( $items[$id] );

	return $items;
}

/**
 * Check whether a builder row's Don't show options hide it on the current
 * page: on category archives, or after page 1.
 *
 * @since 6.0.3
 */

function md_builder_row_hidden( $row ) {
	$scope = $row['scope'] ?? array();

	if ( ! empty( $scope['post_type_only'] ) && ( is_category() || is_tag() || is_tax() ) )
		return true;

	return ! empty( $scope['paged'] ) && is_paged();
}
