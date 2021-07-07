<li id="comment-<?php comment_ID(); ?>" <?php comment_class( array( 'format', ( ! empty( $args['has_children'] ) ? 'parent' : '' ) ) ); ?>>

	<article class="comment-body">

		<footer class="comment-byline byline">

			<?php if ( $args['avatar_size'] != 0 ): ?>
				<div class="byline-avatar">
					<?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
					<?php if ( $comment->user_id == $post->post_author ) : ?>
						<span class="byline-avatar-author <?php echo md_icon( 'pencil', true ); ?>" title="<?php echo __( 'Post Author', 'md' ); ?>"></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="comment-byline-meta">

				<div class="byline-author">
					<?php echo get_comment_author_link(); ?>
				</div>

				<span class="byline-date middot">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php comment_date(); ?>
						</time>
					</a>
				</span>

				<?php comment_reply_link( array_merge( $args, array(
					'add_below'  => 'comment-content',
					'depth' => $depth,
					'max_depth' => $args['max_depth'],
					'reply_text' => __( 'Reply', 'md' ),
					'before' => '<span class="comment-reply">',
					'after' => '</span>'
				) ) ); ?>

				<?php edit_comment_link( '<i class="' . md_icon( 'pencil', true ) . '"></i>', '<span class="byline-icon byline-edit">', '</span>' ); ?>
			</div>

		</footer>

		<div class="comment-content-wrap">

			<div id="comment-content-<?php comment_ID(); ?>" class="comment-content">

				<?php if ( $comment->comment_approved == 0 ) : ?>
					<p class="comment-awaiting-moderation"><?php echo __( 'Your comment is awaiting moderation.', 'md' ); ?></p>
				<?php endif; ?>

				<?php comment_text(); ?>

			</div>

		</div>

	</article>