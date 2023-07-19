<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post();
		$image = wp_get_attachment_url( get_post_thumbnail_id() );
	?>
		<article id="post_<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="content-inner">
				<?php if ( ! empty( $image ) ) : ?>
					<div class="featured-image">
						<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'md' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
							<?php the_post_thumbnail( 'md-block' ); ?>
						</a>
					</div>
				<?php endif; ?>
				<div class="post-content">
					<div class="content-headline">
						<?php if ( in_array( $byline_position, array( '', 'before_headline' ) ) ) : ?>
							<div class="byline">
								<?php md_byline_item( 'badge' ); ?>
								<?php md_byline_item( 'date' ); ?>
								<?php md_byline_item( 'category' ); ?>
							</div>
						<?php endif; ?>
						<h1 class="headline"><a href="<?php the_permalink(); ?>" title="<?php echo sprintf( __( 'Permanent Link to %s', 'md' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h1>
					</div>
					<?php if ( $content !== 'hide' ) : ?>
						<div class="content-text">
							<?php md_the_content(); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="content-footer <?php echo md_byline_classes(); ?>">
				<?php md_byline_item( 'comments', array( 'classes' => 'micro-text' ) ); ?>
				<div class="content-footer-author">
					<?php if ( $byline_position == 'after_headline' ) : ?>
						<?php md_byline_item( 'badge' ); ?>
					<?php endif; ?>
					<?php md_byline_item( 'author', array( 'avatar_size' => 40 ) ); ?>
					<?php if ( $byline_position == 'after_headline' ) : ?>
						<?php md_byline_item( 'date' ); ?>
						<?php md_byline_item( 'category' ); ?>
					<?php endif; ?>
					<a href="<?php the_permalink(); ?>" class="more-link button button-small mr-small"><?php echo esc_html( md_read_more_text() ); ?></a>
					<?php md_byline_item( 'edit' ); ?>
				</div>
			</div>
			<?php do_action( 'md_hook_loop_blocks_bottom' ); ?>
		</article>
		<?php md_hook_x_loop( $c ); ?>
	<?php $c++; endwhile; ?>
<?php else : ?>
	<?php md_404_template(); ?>
<?php endif; ?>