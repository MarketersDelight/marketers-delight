<article id="post_<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

	<?php md_featured_image( $loop ); ?>

	<div class="post-headline headline block">
		<?php md_title( array( 'loop' => $loop ) ); ?>
	</div>

	<?php
		$loop['featured_image'] = 'remove';
		md_content( $loop );
	?>

</article>
