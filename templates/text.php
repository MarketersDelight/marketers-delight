<div id="the_content" class="the-content">

	<?php if ( md_has_inline_featured_image() ) : ?>
		<?php md_featured_image(); ?>
	<?php endif; ?>

	<?php md_the_content(); ?>

</div>

<?php md_hook_post_controls(); ?>
