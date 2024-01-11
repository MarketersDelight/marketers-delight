<article id="post_<?php the_ID(); ?>" <?php post_class( $classes ); ?><?php echo md_style( $style ); ?>>

	<div class="post-box">

		<?php if ( md_has_headline() && ! md_has_headline_cover() ) : ?>
			<?php md_headline(); ?>
		<?php endif; ?>

		<?php md_content_text( $loop ); ?>

		<?php md_hook_content_item(); ?>

	</div>

</article>
