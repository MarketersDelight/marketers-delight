<?php if ( ! in_array( 'edit', $byline ) ) : ?>
	<?php edit_post_link( '<i class="' . md_icon( 'pencil', true ) . '"></i>', '<span class="byline-icon byline-edit byline-item">', '</span>' ); ?>
<?php endif; ?>