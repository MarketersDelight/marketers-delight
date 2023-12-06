<div id="content" class="<?php echo md_content_box_classes(); ?>">

	<?php md_hook_content_box_top(); ?>

	<div class="inner">

		<?php md_hook_content_top(); ?>

		<main class="<?php echo md_content_classes(); ?>">

			<?php md_hook_before_content(); ?>

			<?php md_hook_content(); ?>

			<?php md_hook_after_content(); ?>

		</main>

		<?php get_sidebar(); ?>

		<?php md_hook_content_bottom(); ?>

	</div>

	<?php md_hook_content_box_bottom(); ?>

</div>
