<div class="bookshelf-<?php echo esc_attr($listing); ?> block-single-tb">
	<?php
	if (!empty($args)) {
		$query = new WP_Query(array(
				'post_type' => 'bookshelf',
				'post__in' => isset($args['post_id']) ? $args['post_id'] : '',
				'posts_per_page' => isset($args['posts_per_page']) ? $args['posts_per_page'] : '',
				'orderby' => 'rand'
		));
		if ($query->have_posts())
			while ($query->have_posts()) {
				$query->the_post();
				$post = $this->the_post();
				include(md_template($this->dir, 'bookshelf/bookshelf-post', true));
				$c++;
				$cc++;
			}
	} elseif (have_posts())
		while (have_posts()) {
			the_post();
			$post = $this->the_post();
			include(md_template($this->dir, 'bookshelf/bookshelf-post', true));
			$c++;
			$cc++;
		}
	wp_reset_postdata();
	if ($listing != 'excerpt' && $c % $columns_count != 1) // just in case
		echo '</div>';
	?>
</div>
