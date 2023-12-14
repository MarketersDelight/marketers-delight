<?php if ( md_has_sidebar() ) : ?>

	<div class="sidebar">

		<?php md_hook_before_sidebar(); ?>

		<?php dynamic_sidebar( md_get_sidebar_id() ); ?>

		<?php md_hook_after_sidebar(); ?>

	</div>

<?php endif; ?>
