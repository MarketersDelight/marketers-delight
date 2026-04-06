<?php
	$prelabel = md_icon( 'angle-left', array( 'classes' => 'prev-icon' ) );
	$prelabel .= ! empty( $loop['previous_label'] ) ? $loop['previous_label'] : __( 'Previous', 'md' );

	$nxtlabel = ! empty( $loop['next_label'] ) ? $loop['next_label'] : __( 'Next', 'md' );
	$nxtlabel .= md_icon( 'angle-right', array( 'classes' => 'next-icon' ) );
?>

<nav class="pagination <?php echo esc_attr( $classes ); ?>">

	<?php if ( $type == 'prev_next' ) {
		previous_posts_link( $prelabel, $total );
		next_posts_link( $nxtlabel, $total );
	}
	else echo paginate_links( array(
//		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'type' => 'list',
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var( 'paged' ) ),
		'prev_text' => $prelabel,
		'next_text' => $nxtlabel,
		'total' => $total
	) ); ?>

</nav>
