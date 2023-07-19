<div class="<?php echo $classes; ?>">
	<?php foreach ( $byline_items as $item => $label ) : ?>
		<?php md_byline_item( $item ); ?>
	<?php endforeach; ?>
</div>