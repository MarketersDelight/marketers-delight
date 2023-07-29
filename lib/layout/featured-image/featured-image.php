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
		$this->sanitize = $this->_data( 'sanitize' );
		add_action( 'md_layout_post_before_content_options', array( $this, 'featured_image_position' ) );
		add_action( 'md_hook_page_title_fields', array( $this, 'featured_image_fields' ) );
	}

	/**
	 * Register meta box and term.
	 *
	 * @since 4.3.5
	 */

	public function register() {
		return array(
			'term' => array(
				'name' => __( 'Featured Image', 'md' ),
				'position' => 5,
				'fields' => $this->fields(),
				'callback' => array( $this, 'featured_image_fields' )
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
			'position' => array(
				'type' => 'select',
				'options' => array_keys( $this->sanitize->values['featured_image'] )
			)
		);
	}

	/**
	 * Featured Image Position field template.
	 *
	 * @since 5.6
	 */

	public function featured_image_fields() {
		$screen = get_current_screen();
		$classes = 'md-sep-small';
		$is_post = in_array( $screen->base, array( 'post', 'post-new' ) ) ? true : false;
		if ( ! $is_post )
			$classes .= ' md-field-row';
	?>
		<?php $this->featured_image_position(); ?>

		<?php if ( ! $is_post ) : ?>
			<div class="md-field-row md-sep-small">
				<?php $this->fields->field( 'image', array(
					'type' => 'upload',
					'upload_type' => 'media',
					'label' => __( 'Upload Image', 'md' )
				) ); ?>
			</div>
		<?php endif; ?>
	<?php }

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
