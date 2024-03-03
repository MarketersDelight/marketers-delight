<span class="byline-edit byline-item">
	<a href="<?php echo get_edit_post_link( get_the_ID() ); ?>" class="post-edit-link">
		<?php echo md_icon( 'pencil' ) . ( ! empty( $fields['title'] ) ? ' ' . esc_html( $fields['title'] ) : '' ); ?>
	</a>
</span>
