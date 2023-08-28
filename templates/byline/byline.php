<div class="<?php echo esc_attr( $classes ); ?>">

	<?php md_hook_byline_top(); ?>

	<?php foreach ( $byline_items as $item => $label ) : ?>
		<?php md_byline_item( $item, $args ); ?>
	<?php endforeach; ?>

	<?php md_hook_byline_bottom(); ?>

</div>
