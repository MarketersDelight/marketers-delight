<?php
/**
 * Template Name: Builder Template
 * Template Post Type: post, page
 */
?>

<?php get_header(); ?>

<div class="builder format">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php do_action( 'builder_template_' . get_the_ID() ); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	<?php endif; ?>
</div>

<?php get_footer(); ?>