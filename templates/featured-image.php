<div class="featured-image">

	<?php if ( $permalink ) : ?><a href="<?php echo esc_url( $permalink ); ?>"><?php endif; ?>

	<?php the_post_thumbnail( esc_attr( $size ) ); ?>

	<?php if ( $permalink ) : ?></a><?php endif; ?>

	<?php echo md_get_caption(); ?>

</div>

<?php md_hook_after_featured_image(); ?>
