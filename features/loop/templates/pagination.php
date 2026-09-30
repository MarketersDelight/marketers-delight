<?php
$prelabel = md_icon( 'angle-left', array( 'classes' => 'prev-icon' ) );
$prelabel .= ! empty( $loop['previous_label'] ) ? $loop['previous_label'] : __( 'Previous', 'md' );

$nxtlabel = ! empty( $loop['next_label'] ) ? $loop['next_label'] : __( 'Next', 'md' );
$nxtlabel .= md_icon( 'angle-right', array( 'classes' => 'next-icon' ) );
$query_pagination = ! empty( $loop['query'] ) && $loop['query'] instanceof WP_Query && ! empty( $page_arg );
$pagination_base = $query_pagination
	? str_replace( $big, '%#%', add_query_arg( array( $page_arg => $big ), get_pagenum_link( 1 ) ) )
	: str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) );
?>

<nav class="pagination <?php echo esc_attr( $classes ); ?>" aria-label="<?php echo __( 'Previous and next pages', 'md' ); ?>">

	<?php if ( $type == 'prev_next' && $query_pagination ) {
		if ( $page > 1 )
			echo '<a class="prev page-numbers" href="' . esc_url( add_query_arg( array( $page_arg => $page - 1 ), get_pagenum_link( 1 ) ) ) . '">' . $prelabel . '</a>';

		if ( $page < $total )
			echo '<a class="next page-numbers" href="' . esc_url( add_query_arg( array( $page_arg => $page + 1 ), get_pagenum_link( 1 ) ) ) . '">' . $nxtlabel . '</a>';
	}
	elseif ( $type == 'prev_next' ) {
		previous_posts_link( $prelabel, $total );
		next_posts_link( $nxtlabel, $total );
	}
	else echo paginate_links( array(
		'base' => $pagination_base,
		'type' => 'list',
		'format' => $query_pagination ? '' : '?paged=%#%',
		'current' => $page,
		'prev_text' => $prelabel,
		'next_text' => $nxtlabel,
		'total' => $total
	) ); ?>

</nav>
