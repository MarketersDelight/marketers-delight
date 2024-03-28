<?php do_action( "md_hook_before_{$context}_header", "before_{$context}_header" ); ?>

<div class="<?php echo esc_attr( $classes ); ?>"<?php echo $style; ?>>

	<?php

	md_overlay( $cover );

	do_action( "md_hook_{$context}_header_top", "{$context}_header_top" );

	md_title( $args );

	do_action( "md_hook_{$context}_header_bottom", "{$context}_header_bottom" );

	if ( ! empty( $cover['photo']['id'] ) )
		echo md_get_caption( $cover['photo']['id'] );

	?>

</div>

<?php do_action( "md_hook_after_{$context}_header", "after_{$context}_header" ); ?>
