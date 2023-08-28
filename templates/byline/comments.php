<?php if ( ! in_array( 'comments', $byline ) && md_has_comments() ) :
	$comments = get_comments_number( $post_id );
?>
	<span class="byline-comments byline-item<?php echo isset( $args['classes'] ) ? ' ' . $args['classes'] : ''; ?>">
		<a href="<?php echo get_comments_link( $post_id ); ?>">
			<?php echo md_icon( 'chat' ); ?>
			<?php echo sprintf( _nx( '1 <span class="byline-comments-label">comment</span>', '%1$s <span class="byline-comments-label">comments</span>', $comments, 'Number of comments', 'md' ), number_format_i18n( $comments ) ); ?></a>
	</span>
<?php endif; ?>
