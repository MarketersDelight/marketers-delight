<div class="<?php echo esc_attr( $classes ); ?>">

	<?php if ( ! is_singular() ) : ?><a href="<?php the_permalink(); ?>"><?php endif; ?>

		<?php the_post_thumbnail( $size ); ?>

	<?php if ( ! is_singular() ) : ?></a><?php endif; ?>

	<?php if ( ! isset( $args['hide_caption'] ) ) : ?>
		<?php md_get_caption(); ?>
	<?php endif; ?>

	<?php md_hook_featured_image_bottom(); ?>

</div>

<?php md_hook_after_featured_image(); ?>
