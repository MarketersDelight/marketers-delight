<div class="pagination">

	<div class="pagination-wrap">

		<?php
			if ( $type == 'prev_next' ) {
				previous_posts_link( $prelabel, $total );
				next_posts_link( $nxtlabel, $total );
			}
			else {
				$paginate = paginate_links( array(
					'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format' => '?paged=%#%',
					'current' => max( 1, get_query_var( 'paged' ) ),
					'prev_text' => '<i class="' . md_icon( 'angle-left', true ) . '"></i> ' . $prelabel,
					'next_text' => $nxtlabel . ' <i class="' . md_icon( 'angle-right', true ) . '"></i>',
					'total' => $total
				) );

				if ( $paginate )
					echo $paginate;
		} ?>

	</div>

</div>
