<div class="<?php echo md_headline_classes(); ?>"<?php echo md_cover_style(); ?>>

	<?php md_hook_before_headline(); ?>

	<<?php echo $h; ?> class="headline">
		<?php echo md_title( get_the_title(), get_permalink() ); ?>
	</<?php echo $h; ?>>

	<?php md_hook_after_headline(); ?>

</div>
