<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'md' ); ?>:</label>

	<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $val['title'] ); ?>" class="widefat" />
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'quote' ); ?>"><?php _e( 'Quoted Text', 'md' ); ?>:</label>

	<textarea id="<?php echo $this->get_field_id( 'quote' ); ?>" name="<?php echo $this->get_field_name( 'quote' ); ?>" rows="8" class="large-text"><?php echo esc_attr( $val['quote'] ); ?></textarea>
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'author' ); ?>"><?php _e( 'Quote Author', 'md' ); ?>:</label>

	<input type="text" id="<?php echo $this->get_field_id( 'author' ); ?>" name="<?php echo $this->get_field_name( 'author' ); ?>" value="<?php echo esc_attr( $val['author'] ); ?>" class="widefat" />
</p>

<div class="md">
	<div class="md-upload md-upload-media<?php echo ! empty( $val['image'] ) ? ' has-upload' : ''; ?>">
		<div class="md-uploader">
			<div class="md-upload-preview md-upload-add">
				<div class="md-upload-previewer">
					<span class="dashicons dashicons-upload"></span>
					<p class="md-upload-preview-text"><?php echo __( 'Click to upload', 'md' ); ?></p>
				</div>
				<div class="md-upload-preview-image">
					<img src="<?php echo $val['image']; ?>" alt="<?php echo __( 'Preview Image', 'md' ); ?>" />
				</div>
			</div>
			<div class="md-upload-controls">
				<label class="md-label" for="<?php echo $this->get_field_id( 'image' ); ?>"><?php echo __( 'Image URL', 'md' ); ?></label>
				<input type="url" class="md-upload-url regular-text" name="<?php echo $this->get_field_name( 'image' ); ?>" id="<?php echo $this->get_field_id( 'image' ); ?>" value="<?php echo $val['image']; ?>" placeholder="https://" />
			</div>
		</div>
		<div class="md-upload-buttons">
			<input type="button" class="md-upload-add button" value="<?php echo __( 'Add Image', 'md' ); ?>" />
			<input type="button" class="md-upload-remove button" value="<?php echo __( 'Remove Image', 'md' ); ?>" />
		</div>
	</div>
</div>
<?php wp_enqueue_media(); ?>