<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>

		<article id="post_<?php the_ID(); ?>" <?php post_class(); ?>>

			<div class="post-inner">

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="featured-image">
						<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'md-block' ); ?></a>
					</div>
				<?php endif; ?>

				<div class="post-content">

					<?php if ( in_array( 'category', $byline ) ||  in_array( 'badge', $byline ) ) : ?>
						<div class="byline">
							<?php md_byline_item( 'badge' ); ?>
							<?php md_byline_item( 'category' ); ?>
						</div>
					<?php endif; ?>

					<h2 class="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

					<?php if ( $content !== 'hide' ) : ?>
						<div class="post-text">
							<?php md_the_content(); ?>
						</div>
					<?php endif; ?>

				</div>

			</div>

			<div class="post-footer">

				<div class="post-footer-actions">
					<?php md_byline_item( 'comments' ); ?>
					<?php md_byline_item( 'edit' ); ?>
				</div>

				<div class="post-footer-meta">
					<?php md_byline_item( 'author', array( 'avatar_size' => 40 ) ); ?>
					<?php md_byline_item( 'date' ); ?>
				</div>

				<a href="<?php the_permalink(); ?>" class="more-link button button-small"><?php echo md_read_more_text(); ?></a>

			</div>

		</article>

		<?php md_hook_x_loop( $c ); ?>

	<?php $c++; endwhile; ?>

<?php else : ?>
	<?php md_404_template(); ?>
<?php endif; ?>
