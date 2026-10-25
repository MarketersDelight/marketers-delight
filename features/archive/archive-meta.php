<?php
/*
 * Configure and display aggregate details beneath archive titles.
 *
 * @since 6.0
 */

class md_archive_meta extends md_api {

	/**
	 * Register the default Archive Meta items.
	 *
	 * @since 6.0
	 */

	public function actions() {
		add_filter( 'md_archive_meta_items', array( $this, 'items' ), 10, 2 );
	}

	/**
	 * Register Meta under the shared Archive settings group.
	 *
	 * @since 6.0
	 */

	public function register() {
		$this->name = __( 'Meta', 'md' );

		$args = array(
			'name' => $this->name,
			'child_of' => array( 'archive', 'page_settings' ),
			'fields' => $this->fields()
		);

		return array(
			'admin_page' => $args,
			'term' => $args
		);
	}

	/**
	 * Build the storage schema from registered meta items.
	 *
	 * @since 6.0
	 */

	public function fields() {
		$fields = array(
			'builder_type' => array( 'type' => 'text' ),
			'builder_area' => array( 'type' => 'text' ),
			'name' => array( 'type' => 'text' ),
			'scope' => array( 'type' => 'checkbox', 'options' => array( 'post_type_only', 'paged' ) )
		);

		foreach ( md_archive_meta_items() as $item )
			if ( ! empty( $item['fields'] ) )
				$fields = array_merge( $fields, $item['fields'] );

		return array(
			'builder' => array(
				'type' => 'builder',
				'fields' => $fields
			),
			'display' => array(
				'type' => 'checkbox',
				'options' => array( 'hide_page_title' )
			)
		);
	}

	/**
	 * Render the Archive Meta builder.
	 *
	 * @since 6.0
	 */

	public function admin_fields() {
		$post_type = $this->fields->screen_post_type();

		$scope = $this->_get_screen['is_admin'] && empty( $this->_get_screen['is_taxonomy'] ) && (bool) get_post_type_object( $post_type );

		echo '<div class="md-' . esc_attr( $this->_clean_id ) . ' md-tab-content active">';

		$this->fields->field( 'builder', array(
			'type' => 'builder',
			'title' => __( 'Add Archive Meta', 'md' ),
			'description' => __( 'Choose the aggregate details shown beneath the archive title.', 'md' ),
			'areas' => array(
				'archives' => array(
					'title' => __( 'Archive Meta', 'md' ),
					'description' => __( 'Drag items into the order they should appear beneath the title.', 'md' )
				)
			),
			'elements' => $this->elements( $post_type ),
			'scope' => (bool) $scope
		) );

		echo '<hr class="md-sep-small" />';

		$this->fields->field( 'display', array(
			'type' => 'checkbox',
			'options' => array(
				'hide_page_title' => __( 'Hide in Page Title', 'md' )
			),
			'inherit' => array(
				'hide_page_title' => array(
					'on' => __( 'Hide in Page Title', 'md' ),
					'off' => __( 'Show in Page Title', 'md' )
				)
			),
			'description' => __( 'Hide from the main Page Title, and optionally add back in a <b>Title Section</b>.', 'md' )
		) );

		echo '</div>';
	}

	/**
	 * Prepare meta items available to the current post type.
	 *
	 * @since 6.0
	 */

	private function elements( $post_type ) {
		$elements = md_archive_meta_items( $post_type );

		foreach ( $elements as $id => $element ) {
			if ( ! empty( $element['admin_icon'] ) )
				$elements[$id]['icon'] = $element['admin_icon'];

			if ( empty( $element['admin_callback'] ) )
				$elements[$id]['admin_callback'] = array( $this, 'item_fields' );
		}

		return $elements;
	}

	/**
	 * Explain the shared label override available to meta items.
	 *
	 * @since 6.0
	 */

	public function item_fields() {
		echo '<p class="description">' . esc_html__( 'Use the item label above to override its default text.', 'md' ) . '</p>';
	}

	/**
	 * Render Post Count text controls.
	 *
	 * @since 6.0
	 */

	public function post_count_fields( $group, $type, $fields ) {
		$this->source_field( $group, $fields );

		$fields->field( array( 'builder', $group, 'singular_name' ), array(
			'type' => 'text',
			'label' => __( 'Singular Text', 'md' ),
			'placeholder' => __( '{count} post', 'md' )
		) );

		echo '<p class="description">' . wp_kses_post( __( 'Use <code>{count}</code> in the text above to insert the number. The singular text is used only when the count is one.', 'md' ) ) . '</p>';
	}

	/**
	 * Render Last Added controls.
	 *
	 * @since 6.0.4
	 */

	public function last_added_fields( $group, $type, $fields ) {
		$this->source_field( $group, $fields );
		$this->item_fields();
	}

	/**
	 * Choose which post type an item counts. Empty counts the archive's
	 * own posts.
	 *
	 * @since 6.0.4
	 */

	private function source_field( $group, $fields ) {
		$fields->field( array( 'builder', $group, 'source' ), array(
			'type' => 'select',
			'label' => __( 'Post Type', 'md' ),
			'empty_label' => __( 'This archive', 'md' ),
			'options' => md_query_loop_sources()
		) );
	}

	/**
	 * Return an item's chosen post type when it isn't the archive's own.
	 *
	 * @since 6.0.4
	 */

	private function source( $fields, $post_type ) {
		$source = sanitize_key( $fields['source'] ?? '' );

		return $source && $source !== $post_type && post_type_exists( $source ) ? $source : '';
	}

	/**
	 * Render Comment Count text controls.
	 *
	 * @since 6.0
	 */

	public function comment_count_fields( $group, $type, $fields ) {
		$fields->field( array( 'builder', $group, 'singular_name' ), array(
			'type' => 'text',
			'label' => __( 'Singular Text', 'md' ),
			'placeholder' => __( '{count} comment', 'md' )
		) );

		echo '<p class="description">' . wp_kses_post( __( 'Use <code>{count}</code> in the text above to insert the number. The singular text is used only when the count is one.', 'md' ) ) . '</p>';
	}

	/**
	 * Get the registered Archive Meta items.
	 *
	 * @since 6.0
	 */

	public function items( $items, $post_type = null ) {
		$items = array_merge( $items, array(
			'post_count' => array(
				'title' => __( 'Post Count', 'md' ),
				'placeholder' => __( '{count} posts', 'md' ),
				'icon' => 'file',
				'admin_icon' => 'admin-post',
				'color' => '#2271b1',
				'fields' => array(
					'singular_name' => array( 'type' => 'text' ),
					'source' => array( 'type' => 'text' )
				),
				'callback' => array( $this, 'post_count' ),
				'admin_callback' => array( $this, 'post_count_fields' )
			),
			'comment_count' => array(
				'title' => __( 'Comment Count', 'md' ),
				'placeholder' => __( '{count} comments', 'md' ),
				'icon' => 'chat',
				'admin_icon' => 'admin-comments',
				'color' => '#ff6000',
				'fields' => array(
					'singular_name' => array( 'type' => 'text' )
				),
				'callback' => array( $this, 'comment_count' ),
				'admin_callback' => array( $this, 'comment_count_fields' )
			),
			'last_added' => array(
				'title' => __( 'Last Added', 'md' ),
				'placeholder' => __( 'last added', 'md' ),
				'icon' => 'calendar',
				'color' => '#d44c3c',
				'fields' => array(
					'source' => array( 'type' => 'text' )
				),
				'callback' => array( $this, 'last_added' ),
				'admin_callback' => array( $this, 'last_added_fields' )
			)
		) );

		return $items;
	}

	/**
	 * Format the published post count for an archive.
	 *
	 * @since 6.0
	 */

	public function post_count( $fields, $post_type ) {
		$queried = get_queried_object();
		$source = $this->source( $fields, $post_type );

		if ( $source )
			$count = (int) wp_count_posts( $source )->publish;
		else
			$count = (int) ( $queried instanceof WP_Term ? $queried->count : wp_count_posts( $post_type )->publish );
		$number = number_format_i18n( $count );
		$template = $count === 1 ? __( '{count} post', 'md' ) : __( '{count} posts', 'md' );

		if ( ! empty( $fields['name'] ) )
			$template = $count === 1 && ! empty( $fields['singular_name'] ) ? $fields['singular_name'] : $fields['name'];

		return esc_html( strtr( $template, array( '{count}' => $number ) ) );
	}

	/**
	 * Format the approved comment count for an archive.
	 *
	 * @since 6.0
	 */

	public function comment_count( $fields, $post_type ) {
		return $this->comment_count_text( $fields, md_archive_comment_total( $post_type ) );
	}

	/**
	 * Apply configured singular and plural Comment Count text.
	 *
	 * @since 6.0
	 */

	private function comment_count_text( $fields, $count ) {
		$number = number_format_i18n( $count );
		$template = $count === 1 ? __( '{count} comment', 'md' ) : __( '{count} comments', 'md' );

		if ( ! empty( $fields['name'] ) )
			$template = $count === 1 && ! empty( $fields['singular_name'] ) ? $fields['singular_name'] : $fields['name'];

		return esc_html( strtr( $template, array( '{count}' => $number ) ) );
	}

	/**
	 * Format the relative date of the newest archive entry.
	 *
	 * @since 6.0
	 */

	public function last_added( $fields, $post_type ) {
		$source = $this->source( $fields, $post_type );
		$args = array(
			'post_type' => $source ?: $post_type,
			'post_status' => 'publish',
			'posts_per_page' => 1,
			'orderby' => 'date',
			'order' => 'DESC',
			'fields' => 'ids'
		);

		if ( ! $source && ( is_tax() || is_category() ) ) {
			$term = get_queried_object();
			$args['tax_query'] = array( array(
				'taxonomy' => $term->taxonomy,
				'terms' => $term->term_id
			) );
		}

		$posts = get_posts( $args );

		if ( ! $posts )
			return '';

		$relative = human_time_diff( get_post_time( 'U', false, $posts[0] ), current_time( 'U' ) );
		$label = ! empty( $fields['name'] ) ? $fields['name'] : __( 'last added', 'md' );

		// The whole time reads as the value, like a count's number

		return esc_html( $label ) . ' <strong>' . esc_html( sprintf( __( '%s ago', 'md' ), $relative ) ) . '</strong>';
	}
}

new md_archive_meta;
