<?php
/**
 * Create the admin page for the drag and drop Byline
 * builder that is available across various settings screens.
 *
 * @since 6.0
 */

class md_byline extends md_api {

	/**
	 * Load byline action hooks and filters.
	 *
	 * @since 6.0
	 */

	public function actions() {
		add_filter( 'md_byline', array( $this, 'byline_items' ) );
	}

	/**
	 * Create admin page and terms fields.
	 *
	 * @since 5.0
	 */

	public function register() {
		$this->name = __( 'Byline', 'md' );

		return array(
			'admin_page' => array(
				'name' => $this->name,
				'parent_group' => 'page_settings',
				'fields' => $this->fields()
			),
			'term' => array(
				'name' => $this->name,
				'parent_group' => 'page_settings',
				'fields' => $this->fields()
			)
		);
	}

	/**
	 * Register known custom fields data to save.
	 *
	 * @since 6.0
	 */

	public function fields() {
		$fields = array(
			'builder_type' => array( 'type' => 'text' ),
			'builder_area' => array( 'type' => 'text' ),
			'name' => array( 'type' => 'text' ),
			'dropin' => array( 'type' => 'text' ),
			'title' => array( 'type' => 'text' ),
			'label' => array( 'type' => 'text' ),
			'position' => array(
				'type' => 'select',
				'options' => array( 'before_headline', 'after_headline', 'before_post', 'after_post' )
			),
			'settings' => array(
				'type' => 'checkbox',
				'options' => array( 'label', 'avatar', 'first_name', 'hide', 'relative', 'first' )
			),
			'time' => array( 'type' => 'number' ),
			'image_size' => array( 'type' => 'number' ),
			'term' => array(
				'type' => 'select',
				'dynamic' => true
			)
		);

		foreach ( md_byline_items() as $byline_id => $byline_fields )
			if ( isset( $byline_fields['fields'] ) )
				$fields = array_merge( $fields, $byline_fields['fields'] );

		return array(
			'builder' => array(
				'type' => 'builder',
				'fields' => $fields
			)
		);
	}

	/**
	 * Load HTML template for settings fields.
	 *
	 * @since 6.0
	 */

	public function admin_fields() {
		$tabs = array( 'archives' => __( 'Archives', 'md' ) );
		$screen = get_current_screen();

		if ( ! in_array( $screen->base, array( 'post', 'post-new', 'term' ) ) )
			$tabs['single'] = __( 'Single', 'md' );

		include md_template( 'admin/byline', true );
	}

	/**
	 * A list of elements that can be added to Byline areas.
	 *
	 * @since 6.0
	 */

	public function byline_items() {
		return array(
			'author' => array(
				'title' => __( 'Author', 'md' ),
				'hide_title' => false,
				'color' => '#a424ec',
				'icon' => 'admin-users',
				'callback' => array( $this, 'author' )
			),
			'date' => array(
				'title' => __( 'Date', 'md' ),
				'hide_title' => false,
				'color' => '#d44c3c',
				'icon' => 'calendar',
				'callback' => array( $this, 'date' )
			),
			'last-updated' => array(
				'title' => __( 'Last Updated', 'md' ),
				'hide_title' => false,
				'color' => '#8c7310',
				'icon' => 'clock',
				'callback' => array( $this, 'last_updated' )
			),
			'comments' => array(
				'title' => __( 'Comments', 'md' ),
				'hide_title' => false,
				'color' => '#ff6000',
				'icon' => 'admin-comments',
				'callback' => array( $this, 'comments' )
			),
			'category' => array(
				'title' => __( 'Category', 'md' ),
				'hide_title' => false,
				'color' => '#7d695c',
				'icon' => 'category',
				'callback' => array( $this, 'category' )
			),
			'badge' => array(
				'title' => __( 'Badge', 'md' ),
				'hide_title' => false,
				'color' => '#1eb54b',
				'icon' => 'warning',
				'callback' => array( $this, 'badge' )
			),
			'edit' => array(
				'title' => __( 'Edit', 'md' ),
				'hide_title' => false,
				'color' => '#2772af',
				'icon' => 'edit',
				'callback' => array( $this, 'edit' )
			)
		);
	}

	/**
	 * Author fields.
	 *
	 * @since 6.0
	 */

	public function author( $group ) {
		$this->fields->byline_fields( $group );

		$this->fields->field( array( 'builder', $group, 'settings' ), array(
			'type' => 'checkbox',
			'label' => __( 'Settings', 'md' ),
			'wrap_classes' => 'md-sep-micro',
			'options' => array(
				'first_name' => __( 'Show author first name', 'md' ),
				'avatar' => __( 'Show avatar', 'md' )
			)
		) );

		$this->fields->field( array( 'builder', $group, 'image_size' ), array(
			'type' => 'number',
			'label' => __( 'Avatar size', 'md' ),
			'unit' => 'px',
			'placeholder' => 30
		) );
	}

	/**
	 * Post date fields.
	 *
	 * @since 6.0
	 */

	public function date( $group ) {
		$this->fields->byline_fields( $group );

		$this->fields->field( array( 'builder', $group, 'settings' ), array(
			'type' => 'checkbox',
			'label' => __( 'Settings', 'md' ),
			'wrap_classes' => 'md-sep-micro',
			'options' => array(
				'relative' => __( 'Show relative date', 'md' )
			)
		) );
	}

	/**
	 * Last Updated date fields.
	 *
	 * @since 6.0
	 */

	public function last_updated( $group ) {
		$this->fields->byline_fields( $group );

		$this->fields->field( array( 'builder', $group, 'title' ), array(
			'type' => 'text',
			'label' => __( 'Prefix', 'md' ),
			'placeholder' => __( 'Last updated:', 'md' ),
			'style' => 'width: 30%'
		) );
	}

	/**
	 * Comments fields.
	 *
	 * @since 6.0
	 */

	public function comments( $group ) {
		$this->fields->byline_fields( $group );

		$this->fields->field( array( 'builder', $group, 'settings' ), array(
			'type' => 'checkbox',
			'label' => __( 'Settings', 'md' ),
			'wrap_classes' => 'md-sep-micro',
			'options' => array(
				'label' => __( 'Show comments label', 'md' ),
				'hide' => __( 'Hide if zero comments', 'md' )
			)
		) );

		$this->fields->field( array( 'builder', $group, 'title' ), array(
			'type' => 'text',
			'label' => __( 'Label for zero comments', 'md' ),
			'style' => 'width: 30%'
		) );
	}

	/**
	 * Category fields.
	 *
	 * @since 6.0
	 */

	public function category( $group ) {
		$this->fields->byline_fields( $group );

		if ( isset( $_GET['post_type'] ) )
			$post_type = esc_attr( $_GET['post_type'] );
		elseif ( isset( $_GET['page'] ) )
			$post_type = esc_attr( $_GET['page'] );

		$post_type = md_clean_id( $post_type );
		$terms = get_object_taxonomies( $post_type );

		$options = array();

		foreach ( $terms as $order => $term )
			$options[$term] = ucwords( str_replace( '_', ' ', $term ) );

		$options['all'] = __( 'Show all', 'md' );

		$this->fields->field( array( 'builder', $group, 'term' ), array(
			'type' => 'select',
			'label' => __( 'Only show', 'md' ),
			'empty_label' => __( 'Use default category', 'md' ),
			'wrap_classes' => 'md-sep-micro',
			'options' => $options
		) );

		$this->fields->field( array( 'builder', $group, 'settings' ), array(
			'type' => 'checkbox',
			'options' => array(
				'first' => __( 'Only show first category', 'md' )
			)
		) );
	}

	/**
	 * Badge fields.
	 *
	 * @since 6.0
	 */

	public function badge( $group ) {
		$this->fields->byline_fields( $group );

		$this->fields->field( array( 'builder', $group, 'title' ), array(
			'type' => 'text',
			'label' => __( 'Text', 'md' ),
			'placeholder' => 'New!',
			'style' => 'width: 30%',
			'wrap_classes' => 'md-sep-micro'
		) );

		$this->fields->field( array( 'builder', $group, 'time' ), array(
			'type' => 'number',
			'label' => __( 'New duration', 'md' ),
			'placeholder' => 7,
			'unit' => __( 'days', 'md' )
		) );
	}

	/**
	 * Edit fields.
	 *
	 * @since 6.0
	 */

	public function edit( $group ) {
		$this->fields->byline_fields( $group );

		$this->fields->field( array( 'builder', $group, 'title' ), array(
			'type' => 'text',
			'label' => __( 'Label', 'md' ),
			'style' => 'width: 30%'
		) );
	}

}

new md_byline;