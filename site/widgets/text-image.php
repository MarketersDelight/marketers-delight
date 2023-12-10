<?php

class md_text_image extends WP_Widget {

	public $_allowed_html = array(
		'span' => array(
			'class'  => array(),
			'id'     => array(),
		),
		'br' => array(),
		'b'  => array(),
		'i'  => array()
	);

	public function __construct() {
		parent::__construct( 'md_text_image', __( 'MD &rarr; Text and Image', 'md' ), array(
			'description' => __( 'Create a Text widget with a small right-aligned image as well as a button.', 'md' ),
			'customize_selective_refresh' => true
		) );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
	}

	public function scripts( $hook ){
		if ( $hook == 'widgets.php' || is_customize_preview() )
			wp_enqueue_media();
	}

	public function widget( $args, $val ) {
		$title = $val['title'];
		$desc = apply_filters( 'widget_text', $val['desc'] );
		$image = $val['image'];
		$button_text = $val['button_text'];
		$button_link = $val['button_link'];
		$button_html = ! empty( $button_link ) ? 'a href="' . esc_url( $button_link ) . '"' : 'span';
		$button_html_c = ! empty( $button_link ) ? 'a' : 'span';
		include( md_template( 'widgets/text-image', true ) );
	}

	public function update( $new, $val ) {
		$val['title'] = wp_kses( $new['title'], $this->_allowed_html );
		$val['desc'] = $new['desc'];
		$val['image'] = ! empty( $new['image'] ) ? strip_tags( $new['image'] ) : '';
		$val['button_text'] = strip_tags( $new['button_text'] );
		$val['button_link'] = esc_url( $new['button_link'] );

		return $val;
	}

	public function form( $val ) {
		$val = wp_parse_args( (array) $val, array(
			'title' => '',
			'desc' => '',
			'image' => '',
			'button_text' => '',
			'button_link' => ''
		) );

		$display = ! empty( $val['image'] ) ? 'block' : 'none';
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'md' ); ?>:</label>

			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $val['title'] ); ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'dec' ); ?>"><?php _e( 'Description', 'md' ); ?>:</label>

			<textarea id="<?php echo $this->get_field_id( 'desc' ); ?>" name="<?php echo $this->get_field_name( 'desc' ); ?>" rows="8" class="large-text"><?php echo esc_attr( $val['desc'] ); ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'button_link' ); ?>"><?php _e( 'Link', 'md' ); ?>:</label>

			<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'button_link' ); ?>" id="<?php echo $this->get_field_id( 'button_link' ); ?>" value="<?php echo $val['button_link']; ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php _e( 'Button Text', 'md' ); ?>:</label>

			<input type="text" id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" value="<?php echo esc_attr( $val['button_text'] ); ?>" class="widefat" />
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
	<?php wp_enqueue_media(); }
}
