<div class="<?php echo md_headline_classes( array( 'page-title', 'format' ) ); ?>"<?php echo md_featured_image_cover(); ?>>

	<?php md_hook_before_page_title(); ?>

	<?php if ( $title ) : ?>
		<h1 class="page-headline"><?php echo md_text_field( $title ); ?></h1>
	<?php endif; ?>

	<?php if ( $description ) : ?>
		<div class="page-description">
			<?php echo wpautop( $description ); ?>
		</div>
	<?php endif; ?>

	<?php md_hook_after_page_title(); ?>

</div>
