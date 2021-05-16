<?php if (md_has_sidebar()) : ?>
	<aside class="sidebar">
		<?php md_hook_before_sidebar(); ?>
		<?php md_sidebar(); ?>
		<?php md_hook_after_sidebar(); ?>
	</aside>
<?php endif; ?>
