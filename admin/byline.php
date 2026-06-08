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
		$fields = $this->fields();

		return array(
			'admin_page' => array(
				'name' => $this->name,
				'group' => 'page_settings',
				'fields' => $fields
			),
			'term' => array(
				'name' => $this->name,
				'group' => 'page_settings',
				'fields' => $fields
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
			'title' => array( 'type' => 'text' ),
			'title' => array( 'type' => 'text' ),
			'dropin' => array( 'type' => 'text' ),
			'position' => array(
				'type' => 'select',
				'options' => array( 'before_title', 'after_title', 'before_post', 'after_post' )
			),
			'settings' => array(
				'type' => 'checkbox',
				'options' => array( 'label', 'avatar', 'first_name', 'hide', 'relative', 'first', 'alt' )
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
		$active_tab = '';
		$tabs = $areas = array();
		$screen = $this->_get_screen;

		if ( $screen['is_taxonomy'] || $screen['is_term'] ) {
			$active_tab = 'archives';
			$tabs = array(
				'archives' => array(
					'label' => __( 'Archives', 'md' ),
					'context' => 'post'
				),
				'category_entry' => array(
					'label' => __( 'Category Entry', 'md' ),
					'context' => 'category_entry'
				)
			);
			$areas = array(
				'archives' => array(
					'title' => __( 'Archives', 'md' ),
					'description' => __( 'Override post bylines for this taxonomy\'s archive pages.', 'md' ),
					'tab' => 'archives'
				),
				'category_entry' => array(
					'title' => __( 'Category Entry', 'md' ),
					'description' => __( 'Override category entry bylines for this taxonomy.', 'md' ),
					'tab' => 'category_entry'
				)
			);
		}
		elseif ( $screen['is_admin'] ) {
			$active_tab = 'archives';
			$tabs = array(
				'archives' => array(
					'label' => __( 'Archives', 'md' ),
					'context' => 'post'
				),
				'single' => array(
					'label' => __( 'Single', 'md' ),
					'context' => 'post'
				),
				'category_entry' => array(
					'label' => __( 'Category Entry', 'md' ),
					'context' => 'category_entry'
				)
			);
			$areas = array(
				'archives' => array(
					'title' => __( 'Archives', 'md' ),
					'description' => __( 'Byline items shown in post listing loops.', 'md' ),
					'tab' => 'archives'
				),
				'single' => array(
					'title' => __( 'Single', 'md' ),
					'description' => __( 'Byline items shown on single post pages.', 'md' ),
					'tab' => 'single'
				),
				'category_entry' => array(
					'title' => __( 'Category Entry', 'md' ),
					'description' => __( 'Byline items shown on category section headers. Applies when loop type is set to List Posts by Category.', 'md' ),
					'tab' => 'category_entry'
				)
			);
		}

		echo
			'<div class="md-widget md-toggle md-sep-small">'.
			'<h3 class="md-widget-title">' . __( 'Byline', 'md' ) . '</h3>';

		$this->fields->field( 'builder', array(
			'type' => 'builder',
			'title' => __( 'Edit Byline', 'md' ),
			'wrap_classes' => 'md-widget-item md-tabs',
			'active_tab' => $active_tab,
			'tabs' => $tabs,
			'areas' => $areas,
			'elements' => md_byline_items()
		) );

		echo '</div>';
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
				'context' => 'post',
				'hide_title' => false,
				'color' => '#a424ec',
				'icon' => 'admin-users',
				'callback' => array( $this, 'author' )
			),
			'date' => array(
				'title' => __( 'Date', 'md' ),
				'context'  => 'post',
				'hide_title' => false,
				'color' => '#d44c3c',
				'icon' => 'calendar',
				'callback' => array( $this, 'date' )
			),
			'comments' => array(
				'title' => __( 'Comments', 'md' ),
				'context' => 'post',
				'hide_title' => false,
				'color' => '#ff6000',
				'icon' => 'admin-comments',
				'callback' => array( $this, 'comments' )
			),
			'category' => array(
				'title' => __( 'Category', 'md' ),
				'context' => 'post',
				'hide_title' => false,
				'color' => '#7d695c',
				'icon' => 'category',
				'callback' => array( $this, 'category' )
			),
			'badge' => array(
				'title' => __( 'Badge', 'md' ),
				'context' => 'post',
				'hide_title' => false,
				'color' => '#1eb54b',
				'icon' => 'warning',
				'callback' => array( $this, 'badge' )
			),
			'edit' => array(
				'title' => __( 'Edit', 'md' ),
				'context' => 'post',
				'hide_title' => false,
				'color' => '#2772af',
				'icon' => 'edit',
				'callback' => array( $this, 'edit' )
			),
			'category/post-count' => array(
				'title' => __( 'Post Count', 'md' ),
				'context' => 'category_entry',
				'color' => '#e07b2a',
				'icon' => 'editor-ul',
				'callback' => array( $this, 'post_count' )
			),
			'category/date' => array(
				'title' => __( 'Date', 'md' ),
				'context' => 'category_entry',
				'color' => '#d44c3c',
				'icon' => 'calendar-alt',
				'callback' => array( $this, 'category_date' )
			)
		);
	}

	/**
	 * Edit post link.
	 *
	 * @since 6.0
	 */

	public function edit( $group ) {
		$this->fields->byline_fields( $group );
	}

	/**
	 * Category post count fields.
	 *
	 * @since 6.0
	 */

	public function post_count( $group ) {
		$this->fields->byline_fields( $group );

		$this->fields->field( array( 'builder', $group, 'settings' ), array(
			'type' => 'checkbox',
			'label' => __( 'Settings', 'md' ),
			'wrap_classes' => 'md-sep-micro',
			'options' => array(
				'label' => __( 'Show label', 'md' ),
				'hide' => __( 'Hide if zero', 'md' )
			)
		) );

		echo '<div class="columns-2 columns-single"><div class="col">';

		$this->fields->field( array( 'builder', $group, 'title' ), array(
			'type' => 'text',
			'label' => __( 'Singular label', 'md' ),
			'placeholder' => __( 'post', 'md' )
		) );

		echo '</div><div class="col">';

		$this->fields->field( array( 'builder', $group, 'label' ), array(
			'type' => 'text',
			'label' => __( 'Plural label', 'md' ),
			'placeholder' => __( 'posts', 'md' )
		) );

		echo '</div></div>';
	}

	/**
	 * Category date fields.
	 *
	 * @since 6.0
	 */

	public function category_date( $group ) {
		$this->fields->byline_fields( $group );

		$this->fields->field( array( 'builder', $group, 'settings' ), array(
			'type' => 'checkbox',
			'label' => __( 'Settings', 'md' ),
			'wrap_classes' => 'md-sep-micro',
			'options' => array(
				'label' => __( 'Show label', 'md' ),
				'relative' => __( 'Show relative date', 'md' ),
				'alt' => __( 'Show as <strong>Last Updated</strong> date', 'md' )
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

		$this->fields->field( array( 'builder', $group, 'time' ), array(
			'type' => 'number',
			'label' => __( 'New duration', 'md' ),
			'placeholder' => 7,
			'unit' => __( 'days', 'md' )
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
				'label' => __( 'Show label', 'md' ),
				'relative' => __( 'Show relative date', 'md' ),
				'alt' => __( 'Show as <strong>Last Updated</strong> date', 'md' )
			)
		) );
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
				'label' => __( 'Hide label', 'md' ),
				'avatar' => __( 'Show avatar', 'md' ),
				'first_name' => __( 'Show author first name', 'md' )
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
				'label' => __( 'Show label', 'md' ),
				'hide' => __( 'Hide if zero comments', 'md' )
			)
		) );

		$this->fields->field( array( 'builder', $group, 'title' ), array(
			'type' => 'text',
			'label' => __( 'Zero comments label', 'md' ),
			'style' => 'width: 30%',
			'wrap_classes' => 'md-sep-small'
		) );

		echo
			'<p><b>Available tokens for use in comment labels:</b></p>'.
			'<p class="description"><code>{count}</code> the number of comments</p>'.
			'<p class="description"><code>{label}</code> the singular or plural label of <i>comment(s)</i></p>'.
			'<p class="description"><code>{comments}</code> the full default comments text string</p>';
	}

	/**
	 * Category fields.
	 *
	 * @since 6.0
	 */

	public function category( $group ) {
		$this->fields->byline_fields( $group );

		if ( isset( $_GET['post_type'] ) )
			$post_type = sanitize_text_field( $_GET['post_type'] );
		elseif ( isset( $_GET['page'] ) )
			$post_type = sanitize_text_field( $_GET['page'] );

		$post_type = md_clean_id( $post_type );
		$terms = get_object_taxonomies( $post_type );

		$options = array();

		foreach ( $terms as $order => $term )
			$options[$term] = ucwords( str_replace( '_', ' ', $term ) );

		$options['all'] = __( 'Show all', 'md' );

		$this->fields->field( array( 'builder', $group, 'settings' ), array(
			'type' => 'checkbox',
			'label' => __( 'Only show', 'md' ),
			'options' => array(
				'first' => __( 'Only show first category', 'md' )
			)
		) );

		$this->fields->field( array( 'builder', $group, 'term' ), array(
			'type' => 'select',
			'empty_label' => __( 'Use default category', 'md' ),
			'wrap_classes' => 'md-sep-micro',
			'options' => $options
		) );
	}

}

new md_byline;