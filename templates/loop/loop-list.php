<article id="post_<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

	<div class="post-box">

		<?php
			$loop['show_image'] = array( 'above_headline' );
			md_featured_image( $loop );
		?>

		<div class="post-header">
			<?php
				$loop['show_image'] = array( '', 'right', 'left' );
				md_title( array( 'loop' => $loop ) );
			?>
		</div>

		<?php
			$loop['show_image'] = array( 'center', 'below_headline' );
			md_content( $loop );
		?>

	</div>

</article>
