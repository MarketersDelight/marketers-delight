<article id="post_<?php the_ID(); ?>" <?php post_class( $classes ); ?><?php echo md_style( $style ); ?>>

	<?php md_featured_image( array(
		'hide' => 'below_headline',
		'position' => $featured_image_position,
		'loop' => $loop
	) ); ?>

	<div class="post-content">

		<?php md_title( array(
			'loop' => $loop
		) ); ?>

		<?php md_featured_image( array(
			'position' => $featured_image_position,
			'show' => 'below_headline',
			'loop' => $loop
		) ); ?>

		<div class="the-content">

			<?php md_the_content( $loop ); ?>

		</div>

	</div>

</article>