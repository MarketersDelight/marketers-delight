<div class="<?php echo md_headline_classes( array( 'page-title' ) ); ?>"<?php echo md_featured_image_cover(); ?>>

	<?php md_hook_before_page_title(); ?>

	<div class="inner">

		<?php if ( $title ) : ?>
			<h1 class="page-headline"><?php echo md_text_field( $title ); ?></h1>
		<?php endif; ?>

		<?php if ( $description ) : ?>
			<div class="page-description">
				<?php echo wpautop( $description ); ?>
			</div>
		<?php endif; ?>

	</div>

</div>
