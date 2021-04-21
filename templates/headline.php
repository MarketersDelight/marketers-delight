<<?php echo md_content_item_headline_html(); ?> class="<?php echo md_headline_classes(); ?>"<?php echo md_featured_image_cover(); ?>>

	<?php md_hook_headline_top(); ?>

	<div class="content-inner">

		<?php md_hook_before_headline(); ?>

		<?php if ( is_singular() || is_404() ) : ?>
			<h1 class="headline entry-title"><?php echo get_the_title(); ?></h1>
		<?php else : ?>
			<h1 class="headline entry-title">
				<a href="<?php the_permalink(); ?>" title="<?php echo sprintf( __( 'Permanent Link to %s', 'md' ), the_title_attribute( 'echo=0' ) ); ?>"><?php echo get_the_title(); ?></a>
			</h1>
		<?php endif; ?>

		<?php md_hook_after_headline(); ?>

	</div>

	<?php md_hook_headline_bottom(); ?>

</<?php echo md_content_item_headline_html(); ?>>