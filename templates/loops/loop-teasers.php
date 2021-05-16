<?php if (have_posts()) :
	$post_class = array();
	$featured = !empty($loop['featured']) ? $loop['featured'] : 0;
	$columns = !empty($loop['columns']) ? $loop['columns'] : 2;
	$columns_classes = $columns > 1 ? " columns-$columns columns-single columns-flex" : '';
	?>
	<div class="blog-teasers<?php echo esc_attr($columns_classes); ?>">
		<?php while (have_posts()) : the_post();
			if ($columns > 1) {
				$post_class = array();
				if ($c <= $featured)
					$post_class[] = 'featured-col';
				else
					$post_class[] = 'col';
			}
			?>
			<article id="post_<?php the_ID(); ?>" <?php post_class($post_class); ?>>
				<?php md_hook_teaser_top(); ?>
				<?php if (has_post_thumbnail()) : ?>
					<?php md_featured_image('above_headline', 'full'); ?>
				<?php endif; ?>
				<div class="<?php echo md_teaser_classes(); ?>">
					<?php md_hook_before_headline(); ?>
					<h1 class="headline <?php echo in_array('featured-col', $post_class) ? 'med-title' : 'teaser-title'; ?>">
						<a href="<?php the_permalink(); ?>"
						   title="<?php echo esc_attr(sprintf(__('Permalink to %s', 'md'), the_title_attribute('echo=0'))); ?>"
						   rel="bookmark"><?php the_title(); ?></a></h1>
					<?php md_hook_after_headline(); ?>
					<?php if ($content !== 'hide') : ?>
						<?php md_the_content(); ?>
					<?php endif; ?>
					<?php md_hook_teaser_bottom(); ?>
				</div>
			</article>
			<?php md_hook_x_loop($c); ?>
			<?php $c++; endwhile; ?>
	</div>
<?php else : ?>
	<?php md_404_template(); ?>
<?php endif; ?>
