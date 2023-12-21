<?php
/**
 * A settings area to create versatile bylines across post
 * types and their various screens.
 *
 * @since 5.6
 */

class md_byline extends md_api {

	/**
	 * A list of elements that can be added to Byline areas.
	 *
	 * @since 5.6
	 */

	public function elements() {
		return array(
			'author' => array(
				'title' => __( 'Author', 'md' ),
				'hide_title' => false,
				'color' => '#2772af',
				'icon' => 'admin-users',
				'callback' => array( $this, 'author' )
			),
			'date' => array(
				'title' => __( 'Date', 'md' ),
				'hide_title' => false,
				'color' => '#2772af',
				'icon' => 'calendar',
				'callback' => array( $this, 'date' )
			),
			'last-updated' => array(
				'title' => __( 'Last Updated', 'md' ),
				'hide_title' => false,
				'color' => '#2772af',
				'icon' => 'clock',
				'callback' => array( $this, 'last_updated' )
			),
			'comments' => array(
				'title' => __( 'Comments', 'md' ),
				'hide_title' => false,
				'color' => '#2772af',
				'icon' => 'admin-comments',
				'callback' => array( $this, 'comments' )
			),
			'category' => array(
				'title' => __( 'Category', 'md' ),
				'hide_title' => false,
				'color' => '#2772af',
				'icon' => 'category',
				'callback' => array( $this, 'category' )
			),
			'badge' => array(
				'title' => __( 'Badge', 'md' ),
				'hide_title' => false,
				'color' => '#2772af',
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
	 * Load templates to frontend.
	 *
	 * @since 5.6
	 */

	public function template() {
		add_action( 'md_hook_post_header_top', array( $this, 'before_headline' ) );
//		add_action( 'md_hook_header', array( $templates, 'template' ) );
	}

	/**
	 * Render byline items added before the headline.
	 *
	 * @since 5.6
	 */

	public function before_headline() {
		$items = md_get_byline( 'before_headline' );

		if ( empty( $items ) )
			return;

		echo '<div class="byline">';

		foreach ( $items as $item => $fields )
			include( md_template( "byline/$item", true ) );

		echo '</div>';
	}

	/**
	 * Create admin page and meta box.
	 *
	 * @since 5.6
	 */

	public function fields() {
		return array(
			'builder' => array(
				'type' => 'builder',
				'fields' => array(
					'type' => array( 'type' => 'text' ),
					'area' => array( 'type' => 'text' ),
					'title' => array( 'type' => 'text' ),
					'position' => array(
						'type' => 'select',
						'options' => array( 'before_headline', 'after_headline', 'after_post' )
					),
					'settings' => array(
						'type' => 'checkbox',
						'options' => array( 'label', 'avatar', 'first_name', 'hide', 'relative' )
					),
					'time' => array( 'type' => 'number' ),
					'image_size' => array( 'type' => 'number' )
				)
			)
		);
	}

	/**
	 * Display position field across various groups.
	 *
	 * @since 5.6
	 */

	public function position( $group ) {
		$this->fields->field( array( 'builder', $group, 'position' ), array(
			'type' => 'select',
			'label' => __( 'Position', 'md' ),
			'wrap_classes' => 'md-sep-micro',
			'options' => array(
				'before_headline' => __( 'Before Headline', 'md' ),
				'after_headline' =>  __( 'After Headline', 'md' ),
				'after_post' =>  __( 'After Post', 'md' )
			)
		) );
	}

	/**
	 * Author fields.
	 *
	 * @since 5.6
	 */

	public function author( $group ) {
		$this->position( $group );

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
	 * @since 5.6
	 */

	public function date( $group ) {
		$this->position( $group );

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
	 * @since 5.6
	 */

	public function last_updated( $group ) {
		$this->position( $group );

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
	 * @since 5.6
	 */

	public function comments( $group ) {
		$this->position( $group );

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
	 * @since 5.6
	 */

	public function category( $group ) {
		$this->position( $group );
	}

	/**
	 * Badge fields.
	 *
	 * @since 5.6
	 */

	public function badge( $group ) {
		$this->position( $group );

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
	 * @since 5.6
	 */

	public function edit( $group ) {
		$this->position( $group );

		$this->fields->field( array( 'builder', $group, 'title' ), array(
			'type' => 'text',
			'label' => __( 'Label', 'md' ),
			'style' => 'width: 30%'
		) );
	}

	/**
	 * Single Post Settings fields template.
	 *
	 * @since 5.6
	 */

	public function admin_fields() {
		include( 'admin-page.php' );
	}

}

new md_byline;
