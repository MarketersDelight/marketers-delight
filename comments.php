<div id="comments" class="comments post-footer">
	<div class="wrap">

		<?php if ( have_comments() ) : ?>
		<div class="content-title">
			<h3 class="title"><?php echo md_icon( 'chat', array( 'classes' => 'byline-item-icon' ) ); ?> <?php echo sprintf( _nx( '1 comment', '%1$s comments', get_comments_number(), 'comments title', 'md' ), number_format_i18n( get_comments_number() ) ); ?></h3>
			<a href="#respond" class="button button-small gray"><?php echo md_icon( 'plus' ) . __( 'add comment', 'md' ); ?></a>
		</div>
		<?php endif; ?>

		<?php md_hook_before_comments_list(); ?>

		<?php if ( have_comments() ) : ?>

		<ol class="comments-list">
			<?php wp_list_comments( array(
				'type' => 'comment',
				'callback' => 'md_comment',
				'avatar_size' => 52
			) ); ?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<div class="pagination">
			<?php paginate_comments_links( array(
				'prev_text' => md_icon( 'angle-left' ) . __( 'Previous', 'md' ),
				'next_text' => __( 'Next', 'md' ) . md_icon( 'angle-right' ) . '"></i>',
			) ); ?>
		</div>
		<?php endif; ?>

		<?php endif; ?>

		<?php md_hook_after_comments_list(); ?>

	</div>
</div>
