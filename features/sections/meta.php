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
		add_filter( 'md_byline', array( $this, 'byline_items' ), 20 );
	}

	/**
	 * Offer site stats as byline items, so a single post or page can show
	 * the same counts an archive title does. Items opt in with singles.
	 *
	 * @since 6.1
	 */

	public function byline_items( $items ) {
		foreach ( md_archive_meta_items() as $type => $item )
			if ( ! empty( $item['singles'] ) && ! isset( $items[$type] ) )
				$items[$type] = array_merge( $item, array( 'template' => array( $this, 'byline_item' ) ) );

		return $items;
	}

	/**
	 * Render a stat as a byline item, with Archive Meta's value markup.
	 *
	 * @since 6.1
	 */

	public function byline_item( $fields ) {
		$type = $fields['builder_type'] ?? '';
		$items = md_archive_meta_items();

		if ( empty( $items[$type]['callback'] ) || ! is_callable( $items[$type]['callback'] ) )
			return;

		$value = call_user_func( $items[$type]['callback'], $fields, $fields['post_type'] ?? md_get_post_type() );

		if ( ! $value )
			return;

		$icon = ! empty( $items[$type]['meta_icon'] ) ? md_icon( $items[$type]['meta_icon'] ) : '';

		echo '<span class="' . md_byline_classes( $fields, 'byline-' . str_replace( '_', '-', $type ) ) . '">' . $icon . '<span class="byline-value">' . md_archive_meta_value( $value ) . '</span></span>';
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
			'position' => array(
				'type' => 'select',
				'options' => array_keys( md_title_meta_positions() )
			),
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
			'description' => __( 'Choose the aggregate details shown in the archive title.', 'md' ),
			'wrap_classes' => 'md-tabs',
			'active_tab' => 'archives',
			'tabs' => array(
				'archives' => array(
					'label' => __( 'Archive Title', 'md' ),
					'context' => 'archives'
				),
				'category_entry' => array(
					'label' => __( 'Category Entry', 'md' ),
					'context' => 'category_entry'
				)
			),
			'areas' => array(
				'archives' => array(
					'title' => __( 'Archive Title', 'md' ),
					'description' => __( 'Drag items into order, and set where each one shows in the archive title.', 'md' ),
					'tab' => 'archives'
				),
				'category_entry' => array(
					'title' => __( 'Category Entry', 'md' ),
					'description' => __( 'Details about each category, shown in its title when the loop lists posts by category.', 'md' ),
					'tab' => 'category_entry'
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
			'description' => __( 'Hide from the main Page Title, and optionally add back in a <b>Section Title</b>.', 'md' )
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

		foreach ( $elements as $id => $element )
			$elements[$id]['admin_callback'] = array( $this, 'item_fields' );

		return $elements;
	}

	/**
	 * Render every item's Position, then the item's own controls.
	 *
	 * @since 6.1
	 */

	public function item_fields( $group, $type, $fields, $row = array() ) {
		$fields->field( array( 'builder', $group, 'position' ), array(
			'type' => 'select',
			'label' => __( 'Position', 'md' ),
			'default' => 'after_title',
			'options' => md_title_meta_positions()
		) );

		$items = md_archive_meta_items( $fields->screen_post_type() );
		$callback = $items[$type]['admin_callback'] ?? null;

		if ( is_callable( $callback ) )
			call_user_func( $callback, $group, $type, $fields, $row );
		else
			$this->label_note();
	}

	/**
	 * Explain the shared label override available to meta items.
	 *
	 * @since 6.0
	 */

	public function label_note() {
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
	 * @since 6.1
	 */

	public function last_added_fields( $group, $type, $fields ) {
		$this->source_field( $group, $fields );

		$fields->field( array( 'builder', $group, 'settings' ), array(
			'type' => 'checkbox',
			'label' => __( 'Settings', 'md' ),
			'options' => array(
				'updated' => __( 'Use the last updated date', 'md' ),
				'date' => __( 'Show the date instead of how long ago', 'md' )
			)
		) );

		$this->label_note();
	}

	/**
	 * Choose which post type an item counts. Empty counts the archive's
	 * own posts.
	 *
	 * @since 6.1
	 */

	private function source_field( $group, $fields ) {
		$fields->field( array( 'builder', $group, 'source' ), array(
			'type' => 'select',
			'label' => __( 'Post Type', 'md' ),
			'empty_label' => __( 'This post type', 'md' ),
			'options' => md_query_loop_sources()
		) );
	}

	/**
	 * Return an item's chosen post type when it isn't the archive's own.
	 *
	 * @since 6.1
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
	 * Explain the Text item, whose label is its whole value.
	 *
	 * @since 6.1
	 */

	public function text_fields() {
		echo '<p class="description">' . esc_html__( 'Enter the text in the item label above. Numbers in it are emphasized like the other stats.', 'md' ) . '</p>';
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
				'icon' => 'admin-post',
				'meta_icon' => 'file',
				'color' => '#2271b1',
				'singles' => true,
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
				'icon' => 'admin-comments',
				'meta_icon' => 'chat',
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
				'icon' => 'calendar-alt',
				'meta_icon' => 'calendar',
				'color' => '#d44c3c',
				'singles' => true,
				'fields' => array(
					'source' => array( 'type' => 'text' ),
					'settings' => array(
						'type' => 'checkbox',
						'options' => array( 'updated', 'date' )
					)
				),
				'callback' => array( $this, 'last_added' ),
				'admin_callback' => array( $this, 'last_added_fields' )
			),
			'text' => array(
				'title' => __( 'Text', 'md' ),
				'placeholder' => __( 'Founded 2019', 'md' ),
				'icon' => 'editor-textcolor',
				'color' => '#646970',
				'singles' => true,
				'callback' => array( $this, 'text' ),
				'admin_callback' => array( $this, 'text_fields' )
			)
		) );

		return $items;
	}

	/**
	 * Format the published post count for an archive or term.
	 *
	 * @since 6.0
	 */

	public function post_count( $fields, $post_type, $term = null ) {
		$source = $this->source( $fields, $post_type );
		$term = $this->item_term( $term );

		if ( $source )
			$count = (int) wp_count_posts( $source )->publish;
		elseif ( $term )
			$count = md_term_post_count( $term, $post_type );
		else
			$count = (int) wp_count_posts( $post_type )->publish;

		if ( $source )
			$url = get_post_type_archive_link( $source );
		elseif ( $term )
			$url = get_term_link( $term );
		else
			$url = get_post_type_archive_link( $post_type );

		return md_archive_meta_link( md_count_text( $count, $fields, __( '{count} post', 'md' ), __( '{count} posts', 'md' ) ), $url );
	}

	/**
	 * Format the approved comment count for an archive or term.
	 *
	 * @since 6.0
	 */

	public function comment_count( $fields, $post_type, $term = null ) {
		return md_count_text( md_archive_comment_total( $post_type, $this->item_term( $term ) ), $fields, __( '{count} comment', 'md' ), __( '{count} comments', 'md' ) );
	}

	/**
	 * Print a Text item's own words.
	 *
	 * @since 6.1
	 */

	public function text( $fields ) {
		return $fields['name'] ?? '';
	}

	/**
	 * Format when the newest entry of an archive or term was added, or
	 * last updated.
	 *
	 * @since 6.0
	 */

	public function last_added( $fields, $post_type, $term = null ) {
		$source = $this->source( $fields, $post_type );
		$term = $this->item_term( $term );
		$updated = ! empty( $fields['settings']['updated'] );
		$args = array(
			'post_type' => $source ?: $post_type,
			'post_status' => 'publish',
			'posts_per_page' => 1,
			'orderby' => $updated ? 'modified' : 'date',
			'order' => 'DESC',
			'fields' => 'ids'
		);

		if ( ! $source && $term )
			$args['tax_query'] = array( array(
				'taxonomy' => $term->taxonomy,
				'terms' => $term->term_id
			) );

		$posts = get_posts( $args );

		if ( ! $posts )
			return '';

		$time = $updated ? get_post_modified_time( 'U', false, $posts[0] ) : get_post_time( 'U', false, $posts[0] );
		$date = ! empty( $fields['settings']['date'] ) ? date_i18n( get_option( 'date_format' ), $time ) : sprintf( __( '%s ago', 'md' ), human_time_diff( $time, current_time( 'U' ) ) );
		$label = ! empty( $fields['name'] ) ? $fields['name'] : ( $updated ? __( 'last updated', 'md' ) : __( 'last added', 'md' ) );

		// The whole date reads as the value, like a count's number

		return esc_html( $label ) . ' <strong>' . esc_html( $date ) . '</strong>';
	}

	/**
	 * The term an item measures: the given one, or the term archive
	 * being viewed.
	 *
	 * @since 6.1
	 */

	private function item_term( $term = null ) {
		if ( $term instanceof WP_Term )
			return $term;

		$queried = get_queried_object();

		return $queried instanceof WP_Term ? $queried : null;
	}
}

new md_archive_meta;
