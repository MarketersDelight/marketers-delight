<?php
/**
 * Create and store loops settings data.
 *
 * @since 5.1
 */

class md_loop extends md_api {

	/**
	 * Include related files.
	 *
	 * @since 5.6
	 */

	public function includes() {
		include_once( 'loop-functions.php' );
	}

	/**
	 * Create meta box and terms.
	 *
	 * @since 5.0
	 */

	public function register() {
		$this->name = __( 'Loop', 'md' );
		$fields = $this->fields();

		return array(
			'admin_page' => array(
				'name' => $this->name,
				'parent' => 'design',
				'fields' => $fields
			),
			'term' => array(
				'name' => $this->name,
				'fields' => $fields,
				'position' => 40,
				'callback' => array( $this, 'admin_template' )
			)
		);
	}

	/**
	 * Register fields for save.
	 *
	 * @since 5.1
	 * @moved 5.6
	 */

	public function fields() {
		$cta_ids = array();
		$cta = md_setting( array( 'cta', 'forms' ) );
		$sanitize = new md_sanitize;

		if ( ! empty( $cta ) )
			foreach ( $cta as $cta_id => $cta_fields )
				$cta_ids[] = $cta_id;

		return array(
			'archives' => array(
				'type' => 'select',
				'options' => md_loops( 'ids' )
			),
			'category_posts' => array(
				'type' => 'checkbox',
				'options' => array( 'enable' )
			),
			'featured' => array( 'type' => 'number' ),
			'columns' => array( 'type' => 'number' ),
			'posts_per_page' => array( 'type' => 'number' ),
			'featured_image' => array(
				'type' => 'select',
				'options' => array_keys( $sanitize->values['featured_image'] )
			),
			'byline' => array(
				'type' => 'checkbox',
				'options' => md_byline_items( 'ids' )
			),
			'byline_position' => array(
				'type' => 'select',
				'options' => array( 'after_headline' )
			),
			'byline_settings' => array(
				'type' => 'checkbox',
				'options' => array( 'relative_date', 'author_first_name' )
			),
			'content' => array(
				'type' => 'select',
				'options' => array( 'full', 'excerpt', 'hide' )
			),
			'excerpt_length' => array( 'type' => 'number' ),
			'excerpt_more' => array( 'type' => 'text' ),
			'read_more' => array( 'type' => 'text' ),
			'pagination' => array(
				'type' => 'select',
				'options' => array( 'page_numbers', 'prev_next' )
			),
			'previous_label' => array( 'type' => 'text' ),
			'next_label' => array( 'type' => 'text' ),
			'cta_x_loop' => array( 'type' => 'number' ),
			'x_cta' => array(
				'type' => 'select',
				'options' => $cta_ids
			)
		);
	}

	/**
	 * Add settings template and script to Page Settings sections.
	 *
	 * @since 5.6
	 */

	public function admin_fields() {
		$screen = get_current_screen();
		$page = isset( $_GET['page'] ) ? esc_attr( $_GET['page'] ) : '';
		$screen_base = ! empty( $page ) ? $page : $screen->base;

		do_action( "md_layout_{$screen_base}_before_settings" );

		$this->admin_template();

		do_action( "md_layout_{$screen_base}_after_settings" );
	}

	/**
	 * Call template with required data passed down.
	 *
	 * @since 5.1
	 */

	public function admin_template() {
		$cta_options = array();
		$screen = get_current_screen();
		$sanitize = new md_sanitize;
		$cta = md_setting( array( 'cta', 'forms' ), array() );
		$loops_options = md_loops( 'options' );
		unset( $loops_options['default'] );

		foreach ( $cta as $cta_id => $cta_fields )
			$cta_options[$cta_id] = ! empty( $cta_fields['name'] ) ? $cta_fields['name'] : __( 'Untitled', 'md' );
	?>
		<div class="md-widget md-toggle md-sep-small">
			<h3 class="md-widget-title"><?php echo esc_html( $this->name ); ?></h3>
			<div class="md-widget-item">
				<?php include( 'loop-settings.php' ); ?>
			</div>
		</div>
	<?php }

}

new md_loop;
