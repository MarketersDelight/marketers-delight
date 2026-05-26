<?php

echo '<nav class="breadcrumbs" aria-label="' . __( 'Breadcrumbs', 'md' ) . '">'.
	 '<ol' . ( ! md_has_sidebar() && ! is_singular() ? ' class="inner"' : '' ) . '>'.
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