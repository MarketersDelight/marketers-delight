<?php if ( ( $position == 'header_cover' || $position == 'header_cover_full' ) && have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>
		<?php md_template( 'headline' ); ?>
	<?php endwhile; ?>

<?php else : ?>

	<div class="featured-image<?php echo ! empty( $position ) ? ' image-' . esc_attr( $position ) : ''; ?><?php echo md_featured_image_alignment_classes( $position, $args ); ?>" <?php echo $style; ?>>

		<?php if ( ! is_singular() ) : ?><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'md' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php endif; ?>

			<?php the_post_thumbnail( $size ); ?>

		<?php if ( ! is_singular() ) : ?></a><?php endif; ?>

		<?php if ( ! isset( $args['hide_caption'] ) ) : ?>
			<?php md_featured_image_caption(); ?>
		<?php endif; ?>

		<?php md_hook_featured_image_bottom(); ?>

	</div>

<?php endif; ?>

<?php md_hook_after_featured_image(); ?>