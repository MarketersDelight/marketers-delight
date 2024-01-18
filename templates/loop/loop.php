<article id="post_<?php the_ID(); ?>" <?php post_class( $classes ); ?><?php echo md_style( $style ); ?>>

	<div class="post-box">

		<?php
			if ( ! md_has_headline_cover() )
				md_headline( array( 'loop' => $loop ) );

			md_content_text( $loop );

			md_hook_content_item();
		?>

	</div>

</article>
