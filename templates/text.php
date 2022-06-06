<div class="content-text">
	<div id="the_content" class="content-inner">
		<?php md_hook_content_item_text_top(); ?>
		<?php if ( md_has_inline_featured_image() ) : ?>
			<?php md_featured_image(); ?>
		<?php endif; ?>
		<?php md_the_content(); ?>
	</div>
</div>