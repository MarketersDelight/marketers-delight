<div class="pagination">
	<?php
		if ( $type == 'prev_next' )
			posts_nav_link( '<span class="pagination-sep">/</span>', $prelabel, $nxtlabel );
		else {
			$paginate = paginate_links( array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format' => '?paged=%#%',
				'current' => max( 1, get_query_var( 'paged' ) ),
				'prev_text' => '<i class="' . md_icon( 'angle-left', true ) . '"></i> ' . $prelabel,
				'next_text' => $nxtlabel . ' <i class="' . md_icon( 'angle-right', true ) . '"></i>',
				'total' => $wp_query->max_num_pages
			) );
			if ( $paginate )
				echo $paginate;
		} ?>
</div>
