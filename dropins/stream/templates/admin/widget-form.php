<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title', 'md'); ?>:</label>
	<input type="text" id="<?php echo $this->get_field_id('title'); ?>"
		   name="<?php echo $this->get_field_name('title'); ?>" value="<?php esc_attr_e($val['title']); ?>"
		   class="widefat"/>
</p>

<p>
	<label for="<?php echo $this->get_field_id('description'); ?>"><?php echo __('Description', 'md'); ?>:</label>
	<textarea id="<?php echo $this->get_field_id('description'); ?>"
			  name="<?php echo $this->get_field_name('description'); ?>" class="widefat"
			  rows="4"><?php printf('%s', esc_textarea($val['description'])); ?></textarea>
</p>

<p>
	<label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php echo __('Show number of posts:', 'md'); ?></label><br/>
	<input type="number" id="<?php echo $this->get_field_id('posts_per_page'); ?>"
		   name="<?php echo $this->get_field_name('posts_per_page'); ?>"
		   value="<?php echo esc_attr($val['posts_per_page']); ?>" class="widefat" placeholder="3" style="width: 25%;"/>
</p>

<p>
	<label for="<?php echo $this->get_field_id('excerpt_length'); ?>"><?php echo __('Excerpt words length:', 'md'); ?></label><br/>
	<input type="number" id="<?php echo $this->get_field_id('excerpt_length'); ?>"
		   name="<?php echo $this->get_field_name('excerpt_length'); ?>"
		   value="<?php echo esc_attr($val['excerpt_length']); ?>" class="widefat" placeholder="20"
		   style="width: 25%;"/>
</p>

<p>
	<label for="<?php echo $this->get_field_id('excerpt_text'); ?>"><?php echo __('Excerpt text', 'md'); ?>
		:</label><br/>
	<input type="text" id="<?php echo $this->get_field_id('excerpt_text'); ?>"
		   name="<?php echo $this->get_field_name('excerpt_text'); ?>"
		   value="<?php esc_attr_e($val['excerpt_text']); ?>" placeholder="<?php echo __('read more', 'md'); ?>"
		   class="widefat" style="width: 60%;"/>
</p>

<p>
	<input id="<?php echo $this->get_field_id('hide_title'); ?>" type="checkbox"
		   name="<?php echo $this->get_field_name('hide_title'); ?>" value="1" <?php checked($val['hide_title']); ?> />
	<label for="<?php echo $this->get_field_id('hide_title'); ?>"><?php echo __('<b>Remove</b> stream title', 'md'); ?></label>
	<br/>
	<input id="<?php echo $this->get_field_id('hide_text'); ?>" type="checkbox"
		   name="<?php echo $this->get_field_name('hide_text'); ?>" value="1" <?php checked($val['hide_text']); ?> />
	<label for="<?php echo $this->get_field_id('hide_text'); ?>"><?php echo __('<b>Remove</b> stream text', 'md'); ?></label>
</p>
