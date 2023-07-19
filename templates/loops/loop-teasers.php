<?php if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$post_classes = array();
		if ( $columns > 1 )
			if ( $c <= $featured )
				$post_classes[] = 'featured-col';
			else
				$post_classes[] = 'col';
	?>

		<div id="post_<?php the_ID(); ?>"<?php post_class( $post_classes ); ?>>

			<?php if ( has_post_thumbnail() ) : ?>
				<?php md_featured_image( 'above_headline', 'full', array( 'hide_caption' => true ) ); ?>
			<?php endif; ?>

			<div class="teaser">

				<?php md_hook_before_headline(); ?>

				<<?php echo $h; ?> class="headline">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</<?php echo $h; ?>>

				<?php md_hook_after_headline(); ?>

				<?php if ( $content !== 'hide' ) : ?>
					<?php md_the_content(); ?>
				<?php endif; ?>

				<?php if ( $c <= $featured ) : ?>
					<?php md_hook_post_actions(); ?>
				<?php endif; ?>

			</div>
		</div>

		<?php md_hook_x_loop( $c ); ?>

	<?php $c++; endwhile; ?>

<?php else : ?>

	<?php md_404_template(); ?>

<?php endif; ?>