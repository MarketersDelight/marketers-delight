<?php if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$style = '';
		$classes = get_post_class( 'teaser' );
		$classes[] = 'standard';
		$classes = array_diff( $classes, array( 'post-box' ) );

		if ( $columns > 1 )
			if ( $c <= $featured ) {
				$classes = array_diff( $classes, array( 'standard' ) );
				$classes[] = 'featured';
			}
			else
				$style = md_style( array( 'flex_basis' => round( 100 / $columns ) . '%' ) );

		$classes = join( ' ', $classes );
	?>

	<article id="post_<?php the_ID(); ?>" class="<?php echo esc_attr( $classes ); ?>"<?php echo $style; ?>>
		<div class="post-box">

			<?php if ( has_post_thumbnail() ) : ?>
				<?php md_featured_image( 'full', array(
					'position' => 'above_headline',
					'hide_caption' => true
				) ); ?>
			<?php endif; ?>

			<div class="teaser-content">

				<?php md_hook_before_headline(); ?>

				<<?php echo $h; ?> class="post-title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</<?php echo $h; ?>>

				<?php md_hook_after_headline(); ?>

				<?php if ( $content !== 'hide' ) : ?>
					<?php md_the_content(); ?>
				<?php endif; ?>

			</div>

		</div>
	</article>

	<?php md_hook_x_loop( $c ); ?>

	<?php $c++; endwhile; ?>

<?php else : ?>

	<?php md_404_template(); ?>

<?php endif; ?>
