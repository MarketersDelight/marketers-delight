<article id="post_<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

	<div class="post-box">

		<?php
			if ( ! md_has_headline_cover() )
				md_headline( array( 'loop' => $loop ) );

			$loop['image_inline'] = true;

			md_content( $loop );

			md_hook_content_item();
		?>

	</div>

</article>
