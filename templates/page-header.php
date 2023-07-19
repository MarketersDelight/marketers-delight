<div class="page-header">

	<?php do_action( 'md_page_header_top' ); ?>

	<div class="page-title">

		<?php if ( $title ) : ?>
			<h1 class="page-headline"><?php echo md_text_field( $title ); ?></h1>
		<?php endif; ?>

		<?php if ( $description ) : ?>
			<?php echo wpautop( $description ); ?>
		<?php endif; ?>

	</div>

	<?php do_action( 'md_page_header_bottom' ); ?>

</div>