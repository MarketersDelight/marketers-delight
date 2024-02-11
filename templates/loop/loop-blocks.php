<?php
	if ( ! in_array( $loop['featured_image'], array( 'above_headline', 'below_headline' ) ) )
		$classes .= ' columns';
?>

<article id="post_<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

	<?php md_featured_image( $loop ); ?>

	<div class="post-box">

		<div class="post-header">
			<?php md_title( $loop ); ?>
		</div>

		<?php
			$content_loop = $loop;
			$content_loop['featured_image'] = 'remove';
			$content_loop['post_footer']['remove'] = true;
			md_content( $content_loop );
		?>

	</div>

	<?php md_byline( 'after_post', array(
		'classes' => 'post-footer',
		'loop' => $loop
	) ); ?>

</article>
