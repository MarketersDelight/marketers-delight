<?php if ( ! in_array( 'edit', $byline ) ) : ?>
	<span class="byline-edit byline-item byline-icon">
		<a href="<?php echo get_edit_post_link( $post_id ); ?>" class="post-edit-link">
			<?php echo md_icon( 'pencil' ); ?>
			<?php echo __( 'Edit', 'md' ); ?>
		</a>
	</span>
<?php endif; ?>
