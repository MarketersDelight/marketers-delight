<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<<?php echo $html; ?> id="post_<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php md_hook_content_item(); ?>

		</<?php echo $html; ?>>

		<?php md_hook_x_loop( $c ); ?>

	<?php $c++; endwhile; ?>

<?php else : ?>

	<?php md_404_template(); ?>

<?php endif; ?>
