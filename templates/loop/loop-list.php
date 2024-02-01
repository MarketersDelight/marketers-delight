<article id="post_<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

	<div class="post-box">

		<?php md_featured_image( array(
			'show' => array( 'above_headline' ),
			'loop' => $loop
		) ); ?>

		<div class="post-header">

			<?php md_title( array(
				'loop' => $loop,
				'image' => array(
					'hide' => array( 'above_headline', 'below_headline' )
				)
			) ); ?>

		</div>

		<?php md_featured_image( array(
			'show' => array( 'below_headline' ),
			'loop' => $loop
		) ); ?>

		<?php md_content( $loop ); ?>

	</div>

</article>
