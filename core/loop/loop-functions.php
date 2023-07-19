<?php

/**
 * A list of Loops registered to MD's settings.
 *
 * @since 5.1
 */

function md_loops( $sort = null ) {
	$data = array();
	$loops = apply_filters( 'md_filter_loops', array(
		'default' => array(
			'name' => __( 'Default', 'md' )
		),
		'teasers' => array(
			'name' => __( 'Teasers', 'md' ),
			'columns' => true
		),
		'blocks' => array(
			'name' => __( 'Blocks', 'md' )
		),
		'stream' => array(
			'name' => __( 'Stream', 'md' )
		),
		'docs' => array(
			'name' => __( 'Docs', 'md' )
		)
	) );

	if ( isset( $sort ) ) {
		foreach ( $loops as $id => $fields )
			if ( $sort == 'ids' )
				$data[] = $id;
			elseif ( $sort == 'options' )
				$data[$id] = $fields['name'];
	}
	else
		$data = $loops;

	return $data;
}

/**
 * Set the type of Loop to display on any given page.
 *
 * @since 4.6.4
 */

function md_get_loop( $keys = null, $default = null ) {
	$loop = md_setting( 'loop' );
	$type = 'default';
	$global = md_setting( array( 'loop', 'archives' ), $type );

	if ( has_filter( 'md_filter_loop_type' ) )
		$type = apply_filters( 'md_filter_loop_type', $type );
	elseif ( is_category() || is_tax() ) {
		$term = md_term_meta( array( 'loop', 'archives' ) );
		if ( $term ) {
			$loop = md_term_meta( 'loop' );
			$type = $term;
		}
	}
	elseif ( is_home() || is_post_type_archive() ) {
		$post_type = get_post_type();
		$option = md_setting( $post_type );
		if ( $option )
			$loop = $option;
		$type = md_setting( array( $post_type, 'loop', 'archives' ), $global );
	}
	elseif ( ( is_tag() || is_author() || is_search() ) && ( ! empty( $global ) || $global == 'default' ) )
		$type = $global;
	elseif ( is_singular() ) {
		$loop['byline'] = md_setting( array( 'content', 'byline' ) );
		$loop['byline_position'] = md_setting( array( 'content', 'byline_position' ) );
	}

	if ( isset( $keys ) ) {
		$c = 0;
		$fields = array();

		if ( $keys == 'fields' )
			return $loop;

		if ( is_string( $keys ) )
			$keys = (array) $keys;

		foreach ( $keys as $key ) {
			$fields = ! empty( $loop[$key] ) ? $loop[$key] : ( $c == 0 ? array() : '' );
			$c++;
		}

		if ( empty( $fields ) && isset( $default ) )
			$fields = $default;

		return $fields;
	}

	return $type;
}