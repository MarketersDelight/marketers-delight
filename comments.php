<section id="comments" class="comments post-footer item">
	<?php echo ! md_has_sidebar() ? '<div class="wrap">' : ''; ?>

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
		<nav class="pagination">
			<?php paginate_comments_links( array(
				'prev_text' => md_icon( 'angle-left' ) . __( 'Previous', 'md' ),
				'next_text' => __( 'Next', 'md' ) . md_icon( 'angle-right' ) . '"></i>',
			) ); ?>
		</nav>
		<?php endif; ?>

		<?php endif; ?>

		<?php md_hook_after_comments_list(); ?>

	<?php echo ! md_has_sidebar() ? '</div>' : ''; ?>
</section>