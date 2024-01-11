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

		add_action( 'md_layout_post_after_header', array( $this, 'featured_image_position' ) );
		add_action( 'md_hook_page_title_fields', array( $this, 'featured_image_fields' ) );
		add_filter( 'md_page_settings_fields', array( $this, 'save' ) );
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
				'position' => 10,
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
		$sanitize = $this->_data( 'sanitize' );

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
				'options' => array_keys( $sanitize->values['featured_image'] )
			)
		);
	}

	/**
	 * Filter featured image fields to be saved in other settings groups.
	 *
	 * @since 5.6
	 */

	public function save( $save ) {
		$save[$this->_clean_id] = $this->fields();

		return $save;
	}

	/**
	 * Grouped Featured Image option fields.
	 *
	 * @since 5.6
	 */

	public function featured_image_fields() {
		$screen = get_current_screen();
		$is_post = in_array( $screen->base, array( 'post', 'post-new' ) ) ? true : false;

		if ( $is_post )
			$this->featured_image_position();
		else
			include( 'admin-fields.php' );
	}

	/**
	 * Featured Image Position admin field on its own.
	 *
	 * @since 5.6
	 */

	public function featured_image_position() {
		$classes = '';
		$label = __( 'Position', 'md' );
		$screen = get_current_screen();
		$sanitize = $this->_data( 'sanitize' );
		$is_post = in_array( $screen->base, array( 'post', 'post-new' ) ) ? true : false;

		if ( $is_post ) {
			$label = $this->name;
			$classes = ' md-sep-small-top';
		}
	?>
		<div class="md-sep-small<?php echo $classes; ?>">
			<?php $this->fields->field( 'position', array(
				'type' => 'select',
				'label' => $label,
				'empty_label' => __( 'Use default position', 'md' ),
				'options' => $sanitize->values['featured_image']
			) ); ?>
		</div>
	<?php }

}

new md_featured_image;
