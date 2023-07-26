<div class="page-title">

	<?php if ( $title ) : ?>
		<h1 class="page-headline"><?php echo md_text_field( $title ); ?></h1>
	<?php endif; ?>

	<?php if ( $description ) : ?>
		<div class="page-description">
			<?php echo wpautop( $description ); ?>
		</div>
	<?php endif; ?>

</div>
