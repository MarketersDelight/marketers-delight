<?php
/*
 * Configure and display aggregate details beneath archive titles.
 *
 * @since 6.0
 */

class md_archive_meta extends md_api {

	/**
	 * Attach Archive Meta beneath archive page titles.
	 *
	 * @since 6.0
	 */

	public function template() {
		add_action( 'md_hook_page_title_bottom', array( $this, 'html' ) );
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
			'name' => array( 'type' => 'text' )
		);

		foreach ( $this->items() as $item )
			if ( ! empty( $item['fields'] ) )
				$fields = array_merge( $fields, $item['fields'] );

		return array(
			'builder' => array(
				'type' => 'builder',
				'fields' => $fields
			)
		);
	}

	/**
	 * Render the Archive Meta builder.
	 *
	 * @since 6.0
	 */

	public function admin_fields() {
		$post_type = $this->_get_screen['post_type'];
		$page_post_type = md_clean_id( $this->_get_screen['page'] );

		if ( $this->_get_screen['is_admin'] && get_post_type_object( $page_post_type ) )
			$post_type = $page_post_type;

		echo '<div class="md-' . esc_attr( $this->_clean_id ) . ' md-tab-content active">';

		$this->fields->field( 'builder', array(
			'type' => 'builder',
			'title' => __( 'Add Archive Meta', 'md' ),
			'description' => __( 'Choose the aggregate details shown beneath the archive title.', 'md' ),
			'value' => $this->fields->module( 'builder', md_get_post_type_builder( 'archive_meta', $post_type ) ),
			'areas' => array(
				'archives' => array(
					'title' => __( 'Archive Meta', 'md' ),
					'description' => __( 'Drag items into the order they should appear beneath the title.', 'md' )
				)
			),
			'elements' => $this->elements( $post_type )
		) );

		echo '</div>';
	}

	/**
	 * Prepare meta items available to the current post type.
	 *
	 * @since 6.0
	 */

	private function elements( $post_type ) {
		$elements = $this->items( $post_type );

		foreach ( $elements as $id => $element ) {
			if ( ! empty( $element['admin_icon'] ) )
				$elements[$id]['icon'] = $element['admin_icon'];

			if ( empty( $element['callback'] ) )
				$elements[$id]['callback'] = array( $this, 'item_fields' );
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
	 * Get the registered Archive Meta items.
	 *
	 * @since 6.0
	 */

	public function items( $post_type = null ) {
		$items = apply_filters( 'md_archive_meta_items', array(
			'post_count' => array(
				'title' => __( 'Post Count', 'md' ),
				'icon' => 'file',
				'admin_icon' => 'admin-post',
				'color' => '#2271b1',
				'render' => array( $this, 'post_count' )
			),
			'last_added' => array(
				'title' => __( 'Last Added', 'md' ),
				'icon' => 'calendar',
				'color' => '#d44c3c',
				'render' => array( $this, 'last_added' )
			)
		), $post_type );

		if ( $post_type )
			foreach ( $items as $id => $item )
				if ( ! empty( $item['post_types'] ) && ! in_array( $post_type, (array) $item['post_types'], true ) )
					unset( $items[$id] );

		return $items;
	}

	/**
	 * Render configured meta items on archive titles.
	 *
	 * @since 6.0
	 */

	public function html() {
		if ( ! is_home() && ! is_archive() )
			return;

		$post_type = md_get_post_type();
		$builder = md_get_post_type_builder( 'archive_meta', $post_type );
		$items = $this->items( $post_type );
		$output = '';

		if ( is_category() || is_tax() )
			$builder = md_module( array( 'archive_meta', 'builder' ), $builder );

		foreach ( $builder as $fields ) {
			if ( ( $fields['builder_area'] ?? '' ) !== 'archives' )
				continue;

			$type = $fields['builder_type'] ?? '';
			$render = $items[$type]['render'] ?? null;

			if ( ! is_callable( $render ) )
				continue;

			$value = call_user_func( $render, $fields, $post_type );

			if ( $value )
				$output .= '<span class="byline-item">' . md_icon( $items[$type]['icon'] ) . '<span class="byline-label">' . wp_kses_post( $value ) . '</span></span>';
		}

		if ( $output )
			echo '<div class="archive-meta byline">' . $output . '</div>';
	}

	/**
	 * Format the published post count for an archive.
	 *
	 * @since 6.0
	 */

	public function post_count( $fields, $post_type ) {
		$queried = get_queried_object();
		$object = get_post_type_object( $post_type );
		$count = (int) ( $queried instanceof WP_Term ? $queried->count : wp_count_posts( $post_type )->publish );
		$label = ! empty( $fields['name'] ) ? $fields['name'] : ( $count === 1 ? $object->labels->singular_name : $object->labels->name );

		return number_format_i18n( $count ) . ' ' . esc_html( $label );
	}

	/**
	 * Format the relative date of the newest archive entry.
	 *
	 * @since 6.0
	 */

	public function last_added( $fields, $post_type ) {
		$args = array(
			'post_type' => $post_type,
			'post_status' => 'publish',
			'posts_per_page' => 1,
			'orderby' => 'date',
			'order' => 'DESC',
			'fields' => 'ids'
		);

		if ( is_tax() || is_category() ) {
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
		$label = ! empty( $fields['name'] ) ? $fields['name'] : __( 'Last added', 'md' );

		return esc_html( sprintf( __( '%1$s %2$s ago', 'md' ), $label, $relative ) );
	}
}

new md_archive_meta;
