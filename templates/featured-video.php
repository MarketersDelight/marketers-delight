<?php do_action("{$this->_id}_before"); ?>

    <div class="featured-video video-embed video-wrap">

        <?php do_action("{$this->_id}_top"); ?>

        <?php if ($meta['service'] != 'embed') :
            $url = $this->get_video_url();
            ?>

            <iframe width="640" height="480" src="<?php echo esc_url($url); ?>" frameborder="0"
                    allowfullscreen></iframe>

        <?php elseif ($meta['service'] == 'embed' && !empty($meta['embed'])) : ?>

            <?php echo do_shortcode($meta['embed']); ?>

        <?php endif; ?>

        <?php do_action("{$this->_id}_bottom"); ?>

    </div>

<?php do_action("{$this->_id}_after"); ?>