<div id="the_content" class="<?php echo esc_attr( $classes ); ?>">

	<?php if ( md_has_inline_featured_image() ) : ?>
		<?php md_featured_image(); ?>
	<?php endif; ?>

	<?php md_the_content( $loop ); ?>

</div>
