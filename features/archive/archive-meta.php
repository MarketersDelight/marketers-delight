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
		$fields->field( array( 'builder', $group, 'singular_name' ), array(
			'type' => 'text',
			'label' => __( 'Singular Text', 'md' ),
			'placeholder' => __( '{count} post', 'md' )
		) );

		echo '<p class="description">' . wp_kses_post( __( 'Use <code>{count}</code> in the text above to insert the number. The singular text is used only when the count is one.', 'md' ) ) . '</p>';
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

	public function items( $post_type = null ) {
		$items = apply_filters( 'md_archive_meta_items', array(
			'post_count' => array(
				'title' => __( 'Post Count', 'md' ),
				'placeholder' => __( '{count} posts', 'md' ),
				'icon' => 'file',
				'admin_icon' => 'admin-post',
				'color' => '#2271b1',
				'fields' => array(
					'singular_name' => array( 'type' => 'text' )
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
				'callback' => array( $this, 'last_added' )
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

		foreach ( $builder as $fields ) {
			if ( ( $fields['builder_area'] ?? '' ) !== 'archives' )
				continue;

			$type = $fields['builder_type'] ?? '';
			$callback = $items[$type]['callback'] ?? null;

			if ( ! is_callable( $callback ) )
				continue;

			$value = call_user_func( $callback, $fields, $post_type );

			if ( $value )
				$output .= '<span class="byline-item middot">' . md_icon( $items[$type]['icon'] ) . '<span class="byline-label">' . wp_kses_post( $value ) . '</span></span>';
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
		$args = array(
			'post_type' => $post_type,
			'post_status' => 'publish',
			'status' => 'approve',
			'type' => 'comment',
			'count' => true
		);
		$queried = get_queried_object();

		if ( $queried instanceof WP_Term ) {
			$post_ids = get_posts( array(
				'post_type' => $post_type,
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'fields' => 'ids',
				'tax_query' => array( array(
					'taxonomy' => $queried->taxonomy,
					'terms' => $queried->term_id
				) )
			) );

			if ( ! $post_ids )
				return $this->comment_count_text( $fields, 0 );

			$args['post__in'] = $post_ids;
		}

		return $this->comment_count_text( $fields, (int) get_comments( $args ) );
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
		$label = ! empty( $fields['name'] ) ? $fields['name'] : __( 'last added', 'md' );

		return esc_html( sprintf( __( '%1$s %2$s ago', 'md' ), $label, $relative ) );
	}
}

new md_archive_meta;
