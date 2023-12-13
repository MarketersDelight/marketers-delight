<article id="post_<?php the_ID(); ?>" <?php post_class( $classes ); ?><?php echo md_style( $style ); ?>>
	<div class="post-box">
		<?php md_hook_content_item(); ?>
	</div>
</article>
