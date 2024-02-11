<article id="post_<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

	<div class="post-box">

		<?php md_featured_image( $loop ); ?>

		<div class="post-header">
			<?php md_title( array( 'loop' => $loop ) ); ?>
		</div>

		<?php
			$loop['featured_image'] = 'remove';
			md_content( $loop );
		?>

	</div>

</article>
