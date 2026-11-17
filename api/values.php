<?php
/**
 * Resolves the value that applies for a setting by checking MD's levels in
 * order: post or term, taxonomy, post type (with its settings parents), site.
 *
 * md_value() is the public way in. md_setting() and the meta readers return
 * what's stored at one place.
 *
 * @since 6.2.2
 */

class md_values {

	/**
	 * The first level that stores the key wins, else the default. $key is a key,
	 * a list of keys, or args with a 'key' entry (see md_value()).
	 *
	 * @since 6.2.2
	 */

	public function get( $key, $default = null ) {

		// A plain key or list of keys reads the site; an array with 'key' is args

		$args = array( 'key' => $key, 'default' => $default );
		$args = is_array( $key ) && array_key_exists( 'key', $key ) ? $key : $args;
		$args = array_merge( array(
			'key' => null,
			'default' => null,
			'page' => false,
			'id' => null,
			'post' => null,
			'term' => null,
			'taxonomy' => null,
			'post_type' => null,
			'inherit_post_type' => true,
			'merge' => '',
			'reset' => false,
			'source' => false
		), $args );

		$keys = isset( $args['key'] ) ? (array) $args['key'] : array();
		$levels = array();

		// Drop the post type below a post, term or taxonomy when it shouldn't inherit

		foreach ( $this->levels( $args ) as $level )
			if ( $args['inherit_post_type'] || empty( $level['inherited'] ) )
				$levels[] = $level;

		// Builders merge every level by area instead of stopping at the first

		if ( $args['merge'] === 'areas' )
			return $this->areas( $keys, $args['default'], $levels, $args['reset'] );

		// The first level that stores the key wins, a stored 0 or '' included

		foreach ( $levels as $level ) {
			$value = $this->read( $level, $keys );

			if ( $value !== null )
				return $args['source'] ? array( 'value' => $value, 'source' => $level['type'] ) : $value;
		}

		// Nothing stored anywhere: the default

		return $args['source'] ? array( 'value' => $args['default'], 'source' => 'default' ) : $args['default'];
	}

	/**
	 * The levels to check, in order: the page being viewed, a post, a term, a
	 * taxonomy, a post type, or the site. 'inherited' marks a post type below.
	 *
	 * @since 6.2.2
	 */

	private function levels( $args ) {
		if ( $args['page'] )
			return $this->page_levels( $args['id'] );

		// A post, then its post type (given, or its own)

		if ( $args['post'] )
			return array(
				array( 'type' => 'post', 'id' => $args['post'] ),
				array( 'type' => 'post_type', 'post_type' => $args['post_type'] ? $args['post_type'] : md_get_post_type( $args['post'] ), 'inherited' => true )
			);

		if ( $args['term'] )
			return $this->term_levels( $args['term'], $args['taxonomy'], $args['post_type'] );

		// A taxonomy's settings for a post type, then that post type

		if ( $args['taxonomy'] )
			return array(
				array( 'type' => 'taxonomy', 'taxonomy' => $args['taxonomy'], 'post_type' => $args['post_type'] ),
				array( 'type' => 'post_type', 'post_type' => $args['post_type'], 'inherited' => true )
			);

		if ( isset( $args['post_type'] ) )
			return array( array( 'type' => 'post_type', 'post_type' => $args['post_type'] ) );

		// No place given: the site

		return array( array( 'type' => 'site' ) );
	}

	/**
	 * Levels for the page being viewed. Singles use the post in the loop, unless
	 * $id names another. Tag and date pages read the post type, then the site;
	 * search, which mixes post types, reads the site.
	 *
	 * @since 6.2.2
	 */

	private function page_levels( $id ) {
		$id = apply_filters( 'md_setting_id', $id );

		// Author archives have their own settings, saved like a post type's

		if ( is_author() )
			return array( array( 'type' => 'post_type', 'post_type' => 'author' ) );

		$post_type = md_get_post_type();

		// The blog and post type archives are their post type's own level

		if ( is_home() || is_post_type_archive() )
			return array( array( 'type' => 'post_type', 'post_type' => $post_type ) );

		// Category and custom taxonomy terms: the term, its taxonomy, then the post type

		if ( is_category() || is_tax() ) {
			$queried = get_queried_object();

			return array(
				array( 'type' => 'term', 'id' => $id ),
				array( 'type' => 'taxonomy', 'taxonomy' => ! empty( $queried->taxonomy ) ? $queried->taxonomy : '', 'post_type' => $post_type ),
				array( 'type' => 'post_type', 'post_type' => $post_type, 'inherited' => true )
			);
		}

		// Singles and 404: the post (in the loop, or $id), then its post type

		if ( is_singular() || is_404() )
			return array(
				array( 'type' => 'post', 'id' => $id ),
				array( 'type' => 'post_type', 'post_type' => $post_type, 'inherited' => true )
			);

		if ( is_search() )
			return array( array( 'type' => 'site' ) );

		// Tag and date archives

		return array(
			array( 'type' => 'post_type', 'post_type' => $post_type ),
			array( 'type' => 'site' )
		);
	}

	/**
	 * Levels for one term: its own settings, its taxonomy's (given, or looked up),
	 * then the post type given, or the first one the taxonomy belongs to.
	 *
	 * @since 6.2.2
	 */

	private function term_levels( $term_id, $taxonomy, $post_type ) {

		// Look up what wasn't given: the term's taxonomy, and the taxonomy's post type

		if ( ! $taxonomy ) {
			$term = get_term( $term_id );
			$taxonomy = $term && ! is_wp_error( $term ) ? $term->taxonomy : '';
		}

		if ( ! $post_type && $taxonomy ) {
			$object = get_taxonomy( $taxonomy );
			$post_type = ! empty( $object->object_type ) ? reset( $object->object_type ) : '';
		}

		return array(
			array( 'type' => 'term', 'id' => $term_id ),
			array( 'type' => 'taxonomy', 'taxonomy' => $taxonomy, 'post_type' => $post_type ),
			array( 'type' => 'post_type', 'post_type' => $post_type, 'inherited' => true )
		);
	}

	/**
	 * What one level stores at $keys, or null when it stores nothing there.
	 * A post type's settings sit over its settings parents', defaults included.
	 *
	 * @since 6.2.2
	 */

	private function read( $level, $keys ) {
		if ( $level['type'] === 'post' )
			return md_post_meta( $keys, $level['id'], null );

		if ( $level['type'] === 'term' )
			return md_term_meta( $keys, $level['id'], null );

		if ( $level['type'] === 'taxonomy' )
			return $level['taxonomy'] ? md_setting( array_merge( array( $level['post_type'], $level['taxonomy'] ), $keys ), null ) : null;

		if ( $level['type'] === 'site' )
			return md_setting( $keys, null );

		// A post type: each settings parent first, so the post type's own values win

		$settings = array();

		foreach ( $this->post_types( $level['post_type'] ) as $post_type ) {
			$saved = md_setting( $post_type, array() );

			if ( is_array( $saved ) )
				$settings = array_replace_recursive( $settings, $saved );
		}

		return $this->walk( $settings, $keys );
	}

	/**
	 * Builder areas, merged from the lowest level up. Each post type and taxonomy
	 * adds its defaults, then its saved rows, and a post or term its rows; each
	 * layer replaces the areas it has. With $reset, a saved empty builder clears.
	 *
	 * @since 6.2.2
	 */

	private function areas( $keys, $default, $levels, $reset ) {
		$builder = is_array( $default ) ? $default : array();
		$defaults = md_setting_defaults();
		$stored = get_option( 'marketers_delight', array() );

		// Lowest level first, so each higher level replaces the areas it sets

		foreach ( array_reverse( $levels ) as $level ) {
			if ( $level['type'] === 'post' || $level['type'] === 'term' ) {
				$builder = $this->replace_areas( $builder, $this->read( $level, $keys ) );
				continue;
			}

			// Where this level lives in the option: a taxonomy's branch, or each post type's

			$branches = array();

			if ( $level['type'] === 'taxonomy' )
				$branches[] = array( $level['post_type'], $level['taxonomy'] );
			elseif ( $level['type'] === 'post_type' )
				foreach ( $this->post_types( $level['post_type'] ) as $post_type )
					$branches[] = array( $post_type );

			// Registered defaults, then saved rows; a saved empty builder can reset

			foreach ( $branches as $branch ) {
				$builder = $this->replace_areas( $builder, $this->walk( $defaults, array_merge( $branch, $keys ) ) );
				$saved = $this->walk( $stored, array_merge( $branch, $keys ) );
				$builder = $reset && is_array( $saved ) && ! $saved ? array() : $this->replace_areas( $builder, $saved );
			}
		}

		return $builder;
	}

	/**
	 * Replace the areas a layer of builder rows has; an empty layer changes nothing.
	 *
	 * @since 6.0
	 * @moved 6.2.2 From md_replace_builder_areas().
	 */

	private function replace_areas( $builder, $rows ) {
		if ( ! is_array( $rows ) || ! $rows )
			return $builder;

		// Note the areas this layer sets, drop those areas below, then add its rows

		$areas = array();

		foreach ( $rows as $fields )
			if ( ! empty( $fields['builder_area'] ) )
				$areas[$fields['builder_area']] = true;

		foreach ( $builder as $id => $fields )
			if ( ! empty( $fields['builder_area'] ) && isset( $areas[$fields['builder_area']] ) )
				unset( $builder[$id] );

		return array_replace( $builder, $rows );
	}

	/**
	 * A post type and its settings parents, oldest first, stopping at any loop.
	 *
	 * @since 6.2.2
	 */

	private function post_types( $post_type ) {
		$chain = array();

		// Climb to each settings parent, stopping if one repeats

		while ( $post_type && ! in_array( $post_type, $chain, true ) ) {
			$chain[] = $post_type;
			$post_type = md_post_type_settings_parent( $post_type );
		}

		return array_reverse( $chain );
	}

	/**
	 * Follow $keys into an array, null when one is missing.
	 *
	 * @since 6.2.2
	 */

	private function walk( $value, $keys ) {
		foreach ( $keys as $key ) {
			if ( ! is_array( $value ) || ! array_key_exists( $key, $value ) )
				return null;

			$value = $value[$key];
		}

		return $value;
	}
}
