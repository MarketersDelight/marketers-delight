<?php $image = wp_get_attachment_url( get_post_thumbnail_id() ); ?>

<article id="post_<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( ! empty( $image ) ) : ?>
		<div class="featured-image">
			<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'md' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
				<?php the_post_thumbnail( 'thumbnail' ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="post-content">

		<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

		<?php if ( has_excerpt() ) : ?>
			<div class="excerpt">
				<?php the_excerpt(); ?>
			</div>
		<?php endif; ?>

		<?php md_byline(); ?>

	</div>

</article>
