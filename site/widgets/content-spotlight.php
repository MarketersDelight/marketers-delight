<?php

class md_content_spotlight extends WP_Widget {

	public function __construct() {
		parent::__construct( 'md_content_spotlight', __( 'MD &rarr; Content Spotlight', 'md' ), array(
			'description'                 => __( 'Create a nicely designed image banner and link to any page on your site.', 'md' ),
			'customize_selective_refresh' => true
		) );
	}

	public function widget( $args, $val ) {
		$widget_title = ! empty( $val['widget_title'] ) ? $val['widget_title'] : '';
		$title = $val['title'];
		$intro = $val['intro'];
		$link = $val['link'];
		$link_new = ! empty( $val['link_new'] ) ? $val['link_new'] : '';
		$image = $val['image'];
		$html = ! empty( $link ) ? 'a href="' . esc_url( $link ) . '"' . ( ! empty( $link_new ) ? ' target="_blank"' : '' ) : 'div';
		$html_c = ! empty( $link ) ? 'a' : 'div';
		$src = ! empty( $image ) ? " style=\"background-image: url('$image');\"" : '';
		$image_classes = ! empty( $image ) ? 'image-overlay' : '';
		if ( ! $intro && ! $title && ! $link && ! $image )
			return false;
		include( md_template( 'widgets/content-spotlight', true ) );
	}

	public function update( $new, $val ) {
		$sanitize = new md_sanitize;
		$val['title'] = $sanitize->text( $new['title'] );
		$val['widget_title'] = $sanitize->text( $new['widget_title'] );
		$val['intro'] = $sanitize->text( $new['intro'] );
		$val['link'] = $sanitize->url( $new['link'] );
		$val['image'] = esc_url( $new['image'] );
		if ( isset( $new['link_new'] ) )
			$val['link_new'] = $sanitize->checkbox( $new['link_new'] );
		return $val;
	}

	public function form( $val ) {
		$val = wp_parse_args( (array) $val, array(
			'widget_title' => '',
			'intro' => '',
			'title' => '',
			'link' => '',
			'link_new' => '',
			'image' => ''
		) );
		$display = ( ! empty( $val['image'] ) ? 'block' : 'none' );
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'widget_title' ); ?>"><?php _e( 'Outer Widget Title', 'md' ); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" value="<?php echo esc_attr( $val['widget_title'] ); ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'intro' ); ?>"><?php _e( 'Box Intro', 'md' ); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'intro' ); ?>" name="<?php echo $this->get_field_name( 'intro' ); ?>" value="<?php echo esc_attr( $val['intro'] ); ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Box Title', 'md' ); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $val['title'] ); ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Link', 'md' ); ?>:</label>
			<input type="url" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" value="<?php echo esc_attr( $val['link'] ); ?>" class="widefat" placeholder="<?php _e( 'https://', 'md' ); ?>" />
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'link_new' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'link_new' ); ?>" value="1" <?php checked( $val['link_new'] ); ?> />
			<label for="<?php echo $this->get_field_id( 'link_new' ); ?>"><?php _e( 'Open link in new window', 'md' ); ?></label>
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
						<input type="url" class="md-upload-url regular-text" name="<?php echo $this->get_field_name( 'image' ); ?>" id="<?php echo $this->get_field_id( 'image' ); ?>" value="<?php echo esc_url( $val['image'] ); ?>" placeholder="https://" />
					</div>
				</div>
				<div class="md-upload-buttons">
					<input type="button" class="md-upload-add button" value="<?php echo __( 'Add Image', 'md' ); ?>" />
					<input type="button" class="md-upload-remove button" value="<?php echo __( 'Remove Image', 'md' ); ?>" />
				</div>
			</div>
		</div>
	<?php wp_enqueue_media(); }

}
