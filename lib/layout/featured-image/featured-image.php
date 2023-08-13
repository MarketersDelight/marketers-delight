<?php
/**
 * Build meta box and taxonomy controls to add MD Featured Image
 * upload fields where needed, and extend onto WordPress' controls.
 *
 * @since 4.3.5
 */

class md_featured_image extends md_api {

	/**
	 * Include related files.
	 *
	 * @since 5.6
	 */

	public function includes() {
		include_once( 'image-functions.php' );
	}

	/**
	 * Run actions and filters.
	 *
	 * @since 4.3.5
	 */

	public function actions() {
		$this->name = __( 'Featured Image', 'md' );
		$this->sanitize = $this->_data( 'sanitize' );
		add_action( 'md_layout_post_before_content_options', array( $this, 'featured_image_position' ) );
	}

	/**
	 * Register meta box and term.
	 *
	 * @since 4.3.5
	 */

	public function register() {
		return array(
			'meta_box' => array(
				'fields' => $this->fields()
			),
			'term' => array(
				'name' => $this->name,
				'fields' => $this->fields(),
				'callback' => array( $this, 'admin_fields' )
			)
		);
	}

	/**
	 * Set options for save.
	 *
	 * @since 4.3.5
	 */

	public function fields() {
		return array(
			'image' => array(
				'type' => 'upload',
				'upload_type' => 'media'
			),
			'image_width' => array(
				'desktop' => array( 'type' => 'range' ),
				'tablet' => array( 'type' => 'range' ),
				'mobile' => array( 'type' => 'range' )
			),
			'position' => array(
				'type' => 'select',
				'options' => array_keys( $this->sanitize->values['featured_image'] )
			)
		);
	}

	/**
	 * Featured Image admin fields for use on various admin screens.
	 *
	 * @since 5.6
	 */

	public function admin_fields() { ?>
		<div class="md-widget md-toggle md-sep-small">
			<h3 class="md-widget-title"><?php echo esc_html( $this->name ); ?></h3>
			<div class="md-widget-item md-featured-image wrap">
				<?php $this->featured_image_fields(); ?>
			</div>
		</div>
	<?php }

	/**
	 * Grouped Featured Image option fields.
	 *
	 * @since 5.6
	 */

	public function featured_image_fields() {
		$screen = get_current_screen();
		$is_post = in_array( $screen->base, array( 'post', 'post-new' ) ) ? true : false;
		include( 'admin-fields.php' );
	}

	/**
	 * Featured Image Position admin field on its own.
	 *
	 * @since 5.6
	 */

	public function featured_image_position() {
		$screen = get_current_screen();
		$is_post = in_array( $screen->base, array( 'post', 'post-new' ) ) ? true : false;
	?>
		<div class="md-sep-small<?php echo ! $is_post ? ' md-field-row' : ''; ?>">
			<?php $this->fields->field( 'position', array(
				'type' => 'select',
				'label' => __( 'Featured Image', 'md' ),
				'empty_label' => __( 'Use default position', 'md' ),
				'options' => $this->sanitize->values['featured_image']
			) ); ?>
		</div>
	<?php }

	/**
	 * Load featured image in various positions across templates.
	 *
	 * @since 4.8.3
	 */

	public function template() {
		add_action( 'md_hook_content_item', array( $this, 'above_headline' ) );
		add_action( 'md_hook_content_item', array( $this, 'below_headline' ), 30 );
	}

	/**
	 * Insert featured image above/below headline with in-post check.
	 *
	 * @since 4.1
	 * @moved 5.6
	 */

	public function above_headline() {
		$position = md_featured_image_position();
		if ( has_post_thumbnail() && $position == 'above_headline' )
			md_featured_image();
	}

	public function below_headline() {
		$position = md_featured_image_position();
		if ( has_post_thumbnail() && $position == 'below_headline' )
			md_featured_image();
	}

}

new md_featured_image;
