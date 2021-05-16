<div id="md_popup_bookshelf_<?php echo esc_attr($post['post_id']); ?>"
	 class="md-popup md-popup-bookshelf block-mid format">
	<div class="columns-30-70 columns-single">
		<div class="col col1 mb-single text-center">
			<?php echo get_the_post_thumbnail($post['post_id'], 'large', array('class' => 'mb-half')); ?>
			<?php if (!empty($post['rating'])) : ?>
				<div class="book-rating mb-small">
					<?php $sc = 0;
					while ($sc < $post['rating']) : $sc++; ?>
						<?php echo md_icon('star'); ?>
					<?php endwhile; ?>
				</div>
			<?php endif; ?>
			<div class="mb-half">
				<p><b><?php echo __('Read:', 'md'); ?></b> <?php echo get_the_date('F Y', $post['post_id']); ?></p>
			</div>
			<?php if (!empty($post['download_url'])) : ?>
				<p><a href="<?php echo esc_url($post['download_url']); ?>"
					  class="button button-small button-radio width-full"
					  target="_blank"><?php echo $post['default_text']; ?></a></p>
			<?php endif; ?>
		</div>
		<div class="col col2">
			<div class="text-sep mb-single">
				<div class="large-title mb-small"><?php echo get_the_title($post['post_id']); ?></div>
				<?php if (!empty($post['author'])) : ?>
					<p><em><?php _e('by', 'md'); ?></em> <?php echo esc_html($post['author']); ?></p>
				<?php endif; ?>
			</div>
			<?php echo apply_filters('the_content', $post['content']); ?>
		</div>
	</div>
	<div class="md-popup-close md-popup-close-corner">&times;</div>
</div>
