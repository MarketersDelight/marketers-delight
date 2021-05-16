<?php if (is_category() || is_tax()) :
	$position = md_term_meta(array('featured_image', 'position'));
	$image = md_term_meta(array('featured_image', 'image', 'url'));
	$classes = !empty($position) ? ' ' . md_headline_classes() : '';
	$description = category_description();
	?>

	<div class="archives-title<?php echo $classes; ?>"<?php echo md_featured_image_cover($image, $position); ?>>
		<?php md_hook_headline_top(); ?>
		<?php if (!empty($image) && (in_array($position, array('', 'left', 'right', 'center')))) : ?>
			<?php md_featured_image_tax(); ?>
		<?php endif; ?>
		<h1 class="headline entry-title"><?php single_cat_title(); ?></h1>
		<?php if (!empty($description)) : ?>
			<div class="micro-text text-sec">
				<?php echo category_description(); ?>
			</div>
		<?php endif; ?>
		<?php md_hook_headline_bottom(); ?>
	</div>

<?php elseif (is_search()) : ?>

	<div class="archives-title">
		<h1 class="med-title"><?php echo __('Search Results For: ', 'md');
			the_search_query(); ?></h1>
		<?php get_search_form(); ?>
	</div>

<?php elseif (is_author()) : ?>

	<?php md_author_box(); ?>

<?php elseif (is_tag()) : ?>

	<div class="archives-title">
		<h1 class="med-title"><?php single_tag_title(); ?></h1>
		<?php echo category_description(); ?>
	</div>

<?php elseif (is_day()) : ?>

	<div class="archives-title">
		<h1 class="med-title"><?php echo get_the_date(); ?></h1>
	</div>

<?php elseif (is_month()) : ?>

	<div class="archives-title">
		<h1 class="med-title"><?php echo get_the_date('F Y'); ?></h1>
	</div>

<?php elseif (is_year()) : ?>

	<div class="archives-title">
		<h1 class="med-title"><?php echo get_the_date('Y'); ?></h1>
	</div>

<?php elseif (is_post_type_archive()) : ?>

	<div class="archives-title">
		<h1 class="med-title"><?php post_type_archive_title(); ?></h1>
	</div>

<?php endif; ?>
