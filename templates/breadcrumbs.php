<?php

echo '<nav class="breadcrumbs">'.
	 '<ol class="inner">'.
	 '<li class="breadcrumbs-home"><a href="' . get_site_url() . '" aria-label="' . __( 'Home', 'md' ) . '">' . get_bloginfo( 'name' ) . '</a></li>';

if ( ! empty( $post_type ) && ! is_search() && ! is_page() && ! is_404() && ( ! empty( $blog_id ) || $post_type !== 'post' ) )
	echo '<li><a href="' . get_post_type_archive_link( $post_type ) . '">' . esc_html( $post_type_title ) . '</a></li>';

if ( ! empty( $category_url ) && ( ! is_post_type_archive() && ! is_home() && ! is_page() && ! is_search() && ! is_author() ) && ( is_singular() || is_category() || is_tag() || is_tax() ) )
	echo '<li><a href="' . esc_url( $category_url ) . '">' . esc_html( $category_title ) . '</a></li>';

if ( is_paged() )
	echo '<li>' . sprintf( __( 'Page %s', 'md' ), get_query_var( 'paged' ) ) . '</li>';

if ( is_year() )
	echo '<li>' . get_the_date( 'Y' ) . '</li>';

if ( is_month() )
	echo '<li>' . get_the_date( 'F Y' ) . '</li>';

if ( is_day() )
	echo get_the_date( 'F j, Y' ) . '</li>';

if ( is_author() )
	echo '<li>' . get_the_author_meta( 'display_name' ) . '</li>';

if ( is_search() )
	echo '<li>' . sprintf( __( 'Search results: %s', 'md' ), get_search_query() ) . '</li>';

if ( is_404() )
	echo '<li>' . __( 'Nothing Found', 'md' ) . '</li>';

if ( is_page() && wp_get_post_parent_id( $post_id ) ) {
	$parent_id = wp_get_post_parent_id( $post_id );
	echo '<li><a href="' . get_permalink( $parent_id ) . '">' . get_the_title( $parent_id ) . '</a></li>';
}

if ( get_query_var( 'filter' ) )
	echo '<li>' . __( 'Filter results', 'md' ) . ' ' . md_icon( 'angle-down' ) . '</li>';

echo '</ol>'.
	 '</nav>';

if ( get_query_var( 'filter' ) ) {
	$total = 0;
	$filters = array();
	$url = urldecode( home_url( add_query_arg( null, null ) ) );
	$post_type = sanitize_text_field( get_query_var( 'post_type' ) );
	$taxonomies = get_object_taxonomies( $post_type );

	foreach ( $taxonomies as $taxonomy ) {
		$tax = sanitize_text_field( get_query_var( $taxonomy ) );
		if ( ! $tax ) continue;
		$filters[$taxonomy] = $tax;
	}

	$filters = array_merge( $_GET, $filters );

	unset( $filters['filter'] );
	unset( $filters['post_type'] );

	foreach ( $filters as $key => $value ) {
		if ( get_query_var( $key ) ) {
			$filters[$key] = explode( ',', sanitize_text_field( $value ) );
			$total += count( $filters[$key] );
		}
		else unset( $filters[$key] );
	}

	echo '<div class="query-filters">';

	foreach ( $filters as $tax => $terms ) {
		$filter_url = get_post_type_archive_link( $post_type );

		foreach ( $terms as $slug ) {
			if ( $total > 1 )
				$filter_url = str_replace( array( "$slug", ',,' , ',&', '=,' ), array( '', ',', '&', '=' ), $url );
			echo '<a href="' . esc_url( rtrim( $filter_url, ',' ) ) . '" class="filter-link">' . md_icon( 'cancel', array( 'classes' => 'filter-icon' ) ) . '<span class="filter-label">' . esc_html( $slug ) . '</span></a>';
		}
	}

	echo '</div>';

}