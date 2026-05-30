<?php echo

'<nav class="breadcrumbs" aria-label="' . __( 'Breadcrumbs', 'md' ) . '">'.
'<ol>'.
'<li class="breadcrumbs-home"><a href="' . home_url() . '" aria-label="' . __( 'Home', 'md' ) . '">' . get_bloginfo( 'name' ) . '</a></li>';

if ( ! is_search() && ! is_page() && ! is_404() && ( ! empty( $blog_id ) || $post_type !== 'post' ) )
	echo '<li><a href="' . get_post_type_archive_link( $post_type ) . '">' . esc_html( $post_type_title ) . '</a></li>';

if ( ! empty( $term ) ) {
	foreach ( array_reverse( get_ancestors( $term->term_id, $term->taxonomy, 'taxonomy' ) ) as $ancestor_id ) {
		$ancestor = get_term( $ancestor_id, $term->taxonomy );
		echo '<li><a href="' . esc_url( get_term_link( $ancestor ) ) . '">' . esc_html( $ancestor->name ) . '</a></li>';
	}

	if ( is_singular() )
		echo '<li><a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a></li>';
	else
		echo '<li>' . esc_html( $term->name ) . '</li>';
}

if ( is_paged() )
	echo '<li>' . sprintf( __( 'Page %s', 'md' ), get_query_var( 'paged' ) ) . '</li>';

if ( is_year() )
	echo '<li>' . get_the_date( 'Y' ) . '</li>';

if ( is_month() )
	echo '<li>' . get_the_date( 'F Y' ) . '</li>';

if ( is_day() )
	echo '<li>' . get_the_date( 'F j, Y' ) . '</li>';

if ( is_author() )
	echo '<li>' . get_the_author_meta( 'display_name' ) . '</li>';

if ( is_search() )
	echo '<li>' . sprintf( __( 'Search results: %s', 'md' ), get_search_query() ) . '</li>';

if ( is_404() )
	echo '<li>' . __( 'Nothing Found', 'md' ) . '</li>';

if ( is_page() ) {
	foreach ( array_reverse( get_post_ancestors( $post_id ) ) as $ancestor_id )
		echo '<li><a href="' . esc_url( get_permalink( $ancestor_id ) ) . '">' . esc_html( get_the_title( $ancestor_id ) ) . '</a></li>';
}

if ( get_query_var( 'filter' ) )
	echo '<li>' . __( 'Filter results', 'md' ) . ' ' . md_icon( 'angle-down' ) . '</li>';

echo '</ol>'.
	 '</nav>';