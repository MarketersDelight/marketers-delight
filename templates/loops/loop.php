<?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
		<?php md_template('content-item'); ?>
		<?php md_hook_x_loop($c); ?>
		<?php $c++; endwhile; ?>
<?php else : ?>
	<?php md_404_template(); ?>
<?php endif; ?>
