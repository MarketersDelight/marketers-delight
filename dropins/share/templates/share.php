<a href="<?php echo esc_html($url); ?>"
   class="share-button share-<?php echo esc_attr($share); ?><?php echo esc_attr($class); ?>"
   style="<?php echo $color_prop; ?>: <?php echo esc_attr($color); ?>;<?php echo $style_class == 'bold' ? 'width: ' . (100 / $count) . '%;' : ''; ?>"<?php echo $action; ?>>
	<span class="share-text">
		<span class="share-icon <?php echo esc_attr($icon); ?>"></span>
		<?php if ($share == 'like') :
            $likes = md_post_meta(array('share', 'likes'), $post_id);
            ?>
            <span class="share-count"><?php echo !empty($likes) ? esc_html($likes) : '0'; ?></span>
        <?php elseif ($share == 'comments') :
            $comments = get_comments_number($post_id);
            ?>
            <span class="share-comment-count"><?php echo !empty($comments) ? esc_html($comments) : '0'; ?></span>
        <?php endif; ?>
	</span>
</a>