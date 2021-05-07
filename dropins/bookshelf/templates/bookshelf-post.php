<?php if ($listing == 'excerpt' || $c % $columns_count == 1) : // every Xth book, wrap in new <div> ?>
    <div class="bookshelf-row clear mb-single">
<?php endif; ?>
    <div class="book"<?php echo $listing == 'grid' ? 'style="width: ' . round(100 / $columns_count) . '%;"' : ''; ?>>
        <?php if (!empty($post['content'])) : ?>
            <span class="book-thumb md-popup-trigger"
                  data-popup="md_popup_bookshelf_<?php echo esc_attr($post['post_id']); ?>">
			<?php echo get_the_post_thumbnail($post['post_id'], 'md-book'); ?>
		</span>
        <?php else : ?>
            <span class="book-thumb">
			<a href="<?php echo !empty($post['download_url']) ? esc_url($post['download_url']) : $post['link']; ?>"
               target="_blank">
				<?php echo get_the_post_thumbnail($post['post_id'], 'md-book'); ?>
			</a>
		</span>
        <?php endif; ?>
    </div>
<?php if ($listing == 'excerpt') : ?>
    <div class="book-text block-half-lr">
        <h4 class="small-title mb-small"><?php echo md_text_field($post['title']); ?></h4>
        <div class="book-text-content block-half-bot">
            <?php echo wpautop($post['excerpt']); ?>
            <p>
                <?php if (!empty($post['content'])) : ?>
                    <span class="book-review middot"><a
                                href="<?php echo esc_url($post['link']); ?>"><?php echo $post['string']; ?></a></span>
                <?php endif; ?>
                <?php if (!empty($post['download_url'])) : ?>
                    <span class="book-download middot"><a href="<?php echo esc_url($post['download_url']); ?>"
                                                          target="_blank"><?php echo esc_html($post['default_text']); ?></a></span>
                <?php endif; ?>
                <?php if (!is_post_type_archive('bookshelf')) : ?>
                    <span class="book-link middot"><a
                                href="<?php echo get_post_type_archive_link('bookshelf'); ?>"><?php echo __('All books', 'md'); ?></a></span>
                <?php endif; ?>
            </p>
        </div>
    </div>
<?php endif; ?>
<?php if ($listing == 'excerpt' || $c % $columns_count == 0) : ?>
    </div>
    <?php $cc = 0; endif; ?>
<?php if (class_exists('md_popup')) : ?>
    <?php md_popup(array(
        'id' => $post['post_id'],
        'callback' => array($this, 'popup'),
        'atts' => $post
    )); ?>
<?php endif; ?>