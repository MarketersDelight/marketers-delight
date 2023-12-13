<?php do_action( "md_hook_before_{$context}_header" ); ?>

<div class="<?php echo esc_attr( $classes ); ?>"<?php echo $style; ?>>

	<?php md_overlay( $cover ); ?>

	<?php do_action( "md_hook_{$context}_header_top" ); ?>

	<div class="title-wrap">

		<?php do_action( "md_hook_before_{$context}_title" ); ?>

		<<?php echo $h; ?> class="title"><?php echo md_title( $title, $permalink ); ?></<?php echo $h; ?>>

		<?php do_action( "md_hook_after_{$context}_title" ); ?>

	</div>

	<?php do_action( "md_hook_{$context}_header_bottom" ); ?>

	<?php if ( $caption ) : ?>
		<?php echo md_text_field( $caption ); ?>
	<?php endif; ?>

</div>

<?php do_action( "md_hook_after_{$context}_header" ); ?>
