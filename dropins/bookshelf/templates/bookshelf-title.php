<div class="bookshelf-title format block-half-tb<?php echo !$has_sidebar ? ' text-center' : ''; ?>">
	<?php if (is_tax($tax)) : ?>
		<h1><?php echo single_cat_title(); ?></h1>
	<?php else : ?>
		<?php if ($title) : ?>
			<h1><?php echo $title; ?></h1>
		<?php endif; ?>
	<?php endif; ?>
	<div class="micro-text mb-half">
		<?php if (is_tax($tax)) : ?>
			<?php echo category_description(); ?>
		<?php else : ?>
			<?php echo wpautop($text); ?>
		<?php endif; ?>
	</div>
	<?php if (!$has_categories) : ?>
		<div class="bookshelf-categories">
			<span class="bookshelf-category<?php echo is_post_type_archive('bookshelf') ? ' is-active' : ''; ?> middot"><a
						href="<?php echo get_post_type_archive_link('bookshelf'); ?>"
						class="bookshelf-category-link"><?php echo __('All Books', 'md'); ?></a></span>
			<?php if (!empty($terms)) : ?>
				<?php foreach ($terms as $term) : ?>
					<span class="bookshelf-category<?php echo $term_id == $term->term_id ? ' is-active' : ''; ?> middot"><a
								href="<?php echo get_term_link($term->term_taxonomy_id); ?>"
								class="bookshelf-category-link>"><?php echo $term->name; ?></a></span>
					<?php $c++; endforeach; ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
