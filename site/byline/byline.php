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
				'color' => '#2772af',
				'icon' => 'admin-users',
				'callback' => array( $this, 'author' )
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

		foreach ( $items as $item )
			include( md_template( "byline/$item", true ) );
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
					)
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
/*
		$this->fields->field( array( 'builder', $group, 'author' ), array(
			'type' => 'text',
			'label' => __( 'Author', 'md' )
		) ); ?>
*/
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
