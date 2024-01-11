<div class="<?php echo esc_attr( $classes ); ?>">

	<?php if ( ! is_singular() ) : ?><a href="<?php the_permalink(); ?>"><?php endif; ?>

		<?php the_post_thumbnail(); ?>

	<?php if ( ! is_singular() ) : ?></a><?php endif; ?>

	<?php echo md_get_caption(); ?>

</div>

<?php md_hook_after_featured_image(); ?>
