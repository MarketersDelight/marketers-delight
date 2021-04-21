<div id="content_box" class="<?php echo md_content_box_classes(); ?>">
	<div class="inner">
		<div id="content" class="<?php echo md_content_classes(); ?>">
			<?php md_hook_before_content(); ?>
			<?php md_hook_content(); ?>
			<?php md_hook_after_content(); ?>
		</div>
		<?php get_sidebar(); ?>
	</div>
	<?php md_hook_content_box_bottom(); ?>
</div>