<?php $count = 0; ?>

<div id="comments" class="comments content-inner">

	<?php if (have_comments()) : ?>

		<div class="comments-title">
			<h3 class="micro-title"><?php echo md_icon('chat', array('classes' => 'byline-item-icon')); ?> <?php echo sprintf(_nx('1 comment', '%1$s comments', get_comments_number(), 'comments title', 'md'), number_format_i18n(get_comments_number())); ?>
				<span><a href="#respond"><?php echo __('add your comment', 'md'); ?></a></span></h3>
		</div>

		<div class="comments-area">
			<ol class="comments-list">
				<?php wp_list_comments(array(
						'type' => 'comment',
						'callback' => 'md_comment',
						'avatar_size' => 52
				)); ?>
			</ol>
			<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
				<div class="pagination">
					<?php paginate_comments_links(array(
							'prev_text' => '<i class="' . md_icon('angle-left', true) . '"></i> ' . __('Previous', 'md'),
							'next_text' => __('Next', 'md') . ' <i class="' . md_icon('angle-right', true) . '"></i>',
					)); ?>
				</div>
			<?php endif; ?>
		</div>

	<?php endif; ?>

	<?php comment_form(array(
			'title_reply' => __('Leave a Comment', 'md'),
			'comment_notes_before' => false,
			'comment_notes_after' => false,
			'logged_in_as' => false,
			'cancel_reply_link' => __('Cancel', 'md')
	)); ?>

</div>
