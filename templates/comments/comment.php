<li id="comment-<?php echo $comment_id; ?>" <?php comment_class( $classes ); ?>>

	<a href="<?php echo esc_url( $comment_link ); ?>" class="comment-timeline">
		<span class="comment-timeline-text"<?php echo sprintf( __( 'Jump to comment %s', 'md' ), $comment_id ); ?></span>
	</a>

	<div class="comment-details">

		<?php if ( $avatar_size > 0 ): ?>
			<div class="comment-avatar">
				<?php echo get_avatar( $comment, $avatar_size ); ?>
			</div>
		<?php endif; ?>

		<div class="comment-byline">
			<span class="comment-toggle" data-comment-toggle="comment-<?php echo $comment_id; ?>"><?php echo __( '<span class="show-comment">Show</span><span class="hide-comment">Hide</span> comment' ); ?></span>
			<p class="comment-author"><?php echo get_comment_author_link(); ?></p>
			<p class="comment-date byline-item"><a href="<?php echo esc_url( $comment_link ); ?>"><?php comment_date(); ?></a></p>
		</div>

	</div>

	<div id="comment-content-<?php comment_ID(); ?>" class="comment-content">

		<?php if ( $comment->comment_approved == 0 ) : ?>
			<p class="comment-awaiting-moderation"><?php echo md_icon( 'exclamation', array( 'classes' => 'circle-icon micro mr-small' ) ) . __( 'Your comment is awaiting moderation.', 'md' ); ?></p>
		<?php endif; ?>

		<?php comment_text(); ?>

	</div>

	<div class="comment-controls">
		<?php edit_comment_link( md_icon( 'pencil' ) . __( 'Edit', 'md' ) ); ?>
		<?php comment_reply_link( array_merge( $args, array(
			'add_below'  => 'comment-content',
			'depth' => $depth,
			'max_depth' => $args['max_depth'],
			'reply_text' => md_icon( 'plus' ) . __( 'Reply', 'md' )
		) ) ); ?>
	</div>
