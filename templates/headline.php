<?php do_action( "md_hook_before_{$context}_header", "before_{$context}_header" ); ?>

<div class="<?php echo esc_attr( $classes ); ?>"<?php echo $style; ?>>

	<?php md_overlay( $cover ); ?>

	<?php md_title( $args ); ?>

</div>

<?php do_action( "md_hook_after_{$context}_header", "after_{$context}_header" ); ?>
