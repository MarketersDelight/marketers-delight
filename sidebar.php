<?php if ( md_has_sidebar() ) : ?>
	<div class="sidebar format">
		<?php md_hook_before_sidebar(); ?>
		<?php md_sidebar(); ?>
		<?php md_hook_after_sidebar(); ?>
	</div>
<?php endif; ?>
