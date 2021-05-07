<?php echo $args['before_widget']; ?>

<?php if ($val['title']) : ?>
    <?php echo $args['before_title']; ?><?php echo $val['title']; ?><?php echo $args['after_title']; ?>
<?php endif; ?>

<?php if ($val['description']) : ?>
    <?php echo wpautop($val['description']); ?>
<?php endif; ?>

<?php if ($stream->have_posts()) : ?>

    <div class="stream-widget">
        <div class="stream-widget-head">
            <?php $stream_templates->title(array(
                'context' => 'widget',
                'title_classes' => 'micro-title mb-none',
            )); ?>
        </div>
        <div class="stream-loop">

            <?php while ($stream->have_posts()) : $stream->the_post();
                $likes = md_post_meta(array('share', 'likes'));
                $first_name = get_the_author_meta('first_name');
                $author = !empty($first_name) ? $first_name : get_the_author();
                $post_date = get_post_timestamp();
                $date = human_time_diff($post_date);
                if ($post_date > strtotime('-4 weeks'))
                    $date = $date . __(' ago', 'md');
                else {
                    $date = get_the_date();
                    $label = __('on ', 'md');
                }
                ?>

                <div class="stream-widget-post">

					<span class="stream-widget-icon">
						<?php echo md_icon('chat'); ?>
					</span>

                    <?php if (get_the_title() && empty($val['hide_title'])) : ?>
                        <p class="stream-widget-title mb-small"><a
                                    href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></p>
                    <?php endif; ?>

                    <?php if (empty($val['hide_text'])) : ?>
                        <div class="stream-widget-text mb-half">
                            <?php echo wp_trim_words(get_the_excerpt(), $excerpt_length, '... <a href="' . get_permalink() . '">' . esc_html($excerpt_text) . '</a>'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="stream-widget-byline clear">
                        <span class="stream-byline-avatar mr-small"><?php echo get_avatar(get_the_author_meta('ID'), 18); ?></span>
                        <span class="stream-byline-author middot"><?php echo esc_html($author); ?></span>
                        <span class="stream-byline-date"><a
                                    href="<?php echo get_permalink(); ?>"><?php echo $date; ?></a></span>
                        <?php if (md_has('share')) : ?>
                            <?php $share->share_button(array(
                                'post_id' => get_the_ID(),
                                'style' => 'minimal',
                                'html' => 'span',
                                'show' => array('like')
                            )); ?>
                        <?php endif; ?>
                    </div>

                </div>

                <?php $c++; endwhile; ?>

        </div>

        <div class="stream-widget-more">
            <a href="<?php echo get_post_type_archive_link('stream'); ?>"
               class="button button-small"><?php echo __('see more posts &rarr;', 'md'); ?></a>
        </div>

    </div>

<?php endif; ?>

<?php wp_reset_query(); ?>

<?php echo $args['after_widget']; ?>