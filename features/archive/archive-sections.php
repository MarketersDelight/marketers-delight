<?php
/**
 * Configure and display reusable sections around archive loops.
 *
 * @since 6.0
 */

class md_archive_sections extends md_api {

	/**
	 * Register the archive query variable available to extensions.
	 *
	 * @since 6.0
	 */

	public function actions() {
		add_filter( 'query_vars', function( $vars ) {
			$vars[] = 'filter';
			return $vars;
		} );
	}

	/**
	 * Attach Archive Sections to the existing Loop hooks.
	 *
	 * @since 6.0
	 */

	public function template() {
		add_action( 'md_hook_loop_before', array( $this, 'before_loop' ), 20 );
		add_action( 'md_hook_loop_after', array( $this, 'after_loop' ) );
		add_action( 'md_hook_before_sidebar', array( $this, 'before_sidebar' ) );
		add_action( 'md_hook_after_sidebar', array( $this, 'after_sidebar' ) );
	}

	/**
	 * Register Sections under the shared Archive settings group.
	 *
	 * @since 6.0
	 */

	public function register() {
		$this->name = __( 'Sections', 'md' );

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
	 * Build the storage schema from registered section elements.
	 *
	 * @since 6.0
	 */

	public function fields() {
		$fields = array(
			'builder_type' => array( 'type' => 'text' ),
			'builder_area' => array( 'type' => 'text' ),
			'name' => array( 'type' => 'text' )
		);

		foreach ( $this->elements() as $element )
			if ( ! empty( $element['fields'] ) )
				$fields = array_merge( $fields, $element['fields'] );

		return array(
			'builder' => array(
				'type' => 'builder',
				'fields' => $fields
			)
		);
	}

	/**
	 * Render the before and after Loop builder areas.
	 *
	 * @since 6.0
	 */

	public function admin_fields() {
		$post_type = $this->_get_screen['post_type'];
		$page_post_type = md_clean_id( $this->_get_screen['page'] );

		if ( $this->_get_screen['is_admin'] && get_post_type_object( $page_post_type ) )
			$post_type = $page_post_type;

		echo '<div class="md-' . esc_attr( $this->_clean_id ) . ' md-tab-content">';

		$this->fields->field( 'builder', array(
			'type' => 'builder',
			'title' => __( 'Add Archive Widgets', 'md' ),
			'description' => __( 'Add reusable content before or after the archive entries.', 'md' ),
			'areas' => array(
				'before_loop' => array(
					'title' => __( 'Before Loop', 'md' ),
					'description' => __( 'Shown before the archive entries.', 'md' )
				),
				'after_loop' => array(
					'title' => __( 'After Loop', 'md' ),
					'description' => __( 'Shown after the archive entries and pagination.', 'md' )
				),
				'before_sidebar' => array(
					'title' => __( 'Before Sidebar', 'md' ),
					'description' => __( 'Shown before the sidebar widgets.', 'md' )
				),
				'after_sidebar' => array(
					'title' => __( 'After Sidebar', 'md' ),
					'description' => __( 'Shown after the sidebar widgets.', 'md' )
				)
			),
			'elements' => $this->elements( $post_type )
		) );

		echo '</div>';
	}

	/**
	 * Get section elements available to the current post type.
	 *
	 * @since 6.0
	 */

	public function elements( $post_type = null ) {
		$elements = apply_filters( 'md_archive_sections_builder_elements', array(
			'taxonomy_filter' => array(
				'title' => __( 'Taxonomy Filter', 'md' ),
				'placeholder' => __( 'Taxonomy Filter', 'md' ),
				'icon' => 'filter',
				'color' => '#2271b1',
				'classes' => 'col-full',
				'fields' => $this->taxonomy_fields(),
				'callback' => array( $this, 'taxonomy_filter' ),
				'admin_callback' => array( $this, 'taxonomy_admin' )
			)
		) );

		if ( $post_type )
			foreach ( $elements as $id => $element )
				if ( ! empty( $element['post_types'] ) && ! in_array( $post_type, (array) $element['post_types'], true ) )
					unset( $elements[$id] );

		foreach ( $elements as $id => $element ) {
			if ( ! empty( $element['admin_icon'] ) )
				$elements[$id]['icon'] = $element['admin_icon'];

			if ( empty( $element['admin_callback'] ) )
				$elements[$id]['admin_callback'] = array( $this, 'element_fields' );
		}

		return $elements;
	}

	/**
	 * Provide an empty admin callback for elements without controls.
	 *
	 * @since 6.0
	 */

	public function element_fields() {}

	/**
	 * Define the fields stored by a Taxonomy Filter section.
	 *
	 * @since 6.0
	 */

	private function taxonomy_fields() {
		return array(
			'taxonomy' => array(
				'type' => 'select',
				'options' => array_values( get_taxonomies() )
			),
			'taxonomy_settings' => array(
				'type' => 'checkbox',
				'options' => array( 'hide_empty' )
			),
			'all_label' => array( 'type' => 'text' )
		);
	}

	/**
	 * Render Taxonomy Filter controls inside a builder row.
	 *
	 * @since 6.0
	 */

	public function taxonomy_admin( $group, $type, $fields ) {
		$options = array();
		$post_type = $this->_get_screen['post_type'];
		$page_post_type = md_clean_id( $this->_get_screen['page'] );

		if ( $this->_get_screen['is_admin'] && get_post_type_object( $page_post_type ) )
			$post_type = $page_post_type;

		$taxonomies = get_object_taxonomies( $post_type, 'objects' );

		foreach ( $taxonomies as $taxonomy => $object )
			if ( $object->public )
				$options[$taxonomy] = $object->labels->name;

		include md_template( 'features', 'archive/admin/taxonomy-filter', true );
	}

	/**
	 * Render taxonomy navigation from a section builder row.
	 *
	 * @since 6.0
	 */

	public function taxonomy_filter( $fields, $post_type ) {
		$current_id = get_queried_object_id();
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		$taxonomy = $fields['taxonomy'] ?? '';
		$options = $fields['taxonomy_settings'] ?? array();

		if ( empty( $taxonomies[$taxonomy] ) ) {
			$taxonomy = is_tax() || is_category() ? get_queried_object()->taxonomy : '';

			if ( empty( $taxonomies[$taxonomy] ) )
				foreach ( $taxonomies as $id => $object )
					if ( $object->public ) {
						$taxonomy = $id;
						break;
					}
		}

		if ( ! $taxonomy )
			return;

		$terms = get_terms( array(
			'taxonomy' => $taxonomy,
			'hide_empty' => ! empty( $options['hide_empty'] ),
			'pad_counts' => true
		) );

		if ( is_wp_error( $terms ) || ! $terms )
			return;

		$active = is_post_type_archive( $post_type ) || ( is_home() && $post_type === 'post' );
		$total = (int) wp_count_posts( $post_type )->publish;

		if ( $post_type === 'post' ) {
			$page_for_posts = get_option( 'page_for_posts' );
			$archive_url = $page_for_posts ? get_permalink( $page_for_posts ) : home_url( '/' );
		}
		else
			$archive_url = get_post_type_archive_link( $post_type );

		include md_template( 'features', 'archive/taxonomy-filter', true );
	}

	/**
	 * Render configured sections before the primary archive Loop.
	 *
	 * @since 6.0
	 */

	public function before_loop( $args = array() ) {
		$this->html( 'before_loop', $args );
	}

	/**
	 * Render configured sections after the primary archive Loop.
	 *
	 * @since 6.0
	 */

	public function after_loop( $args = array() ) {
		$this->html( 'after_loop', $args );
	}

	/**
	 * Render sections before the sidebar widgets.
	 *
	 * @since 6.0
	 */

	public function before_sidebar() {
		$this->html( 'before_sidebar', array( 'loop' => array( 'post_type' => md_get_post_type() ) ) );
	}

	/**
	 * Render sections after the sidebar widgets.
	 *
	 * @since 6.0
	 */

	public function after_sidebar() {
		$this->html( 'after_sidebar', array( 'loop' => array( 'post_type' => md_get_post_type() ) ) );
	}

	/**
	 * Render callable elements assigned to one section area.
	 *
	 * @since 6.0
	 */

	private function html( $area, $args ) {
		if ( ! $this->is_archive_loop( $args ) )
			return;

		$output = $group = '';
		$group_classes = 'archive-sections-group col-full columns-fluid-2 gap-single';
		$post_type = $args['loop']['post_type'] ?? md_get_post_type();
		$builder = md_get_post_type_builder( 'archive_sections', $post_type );
		$elements = $this->elements( $post_type );

		foreach ( $builder as $fields ) {
			if ( ( $fields['builder_area'] ?? '' ) !== $area )
				continue;

			$type = $fields['builder_type'] ?? '';
			$callback = $elements[$type]['callback'] ?? null;

			if ( ! is_callable( $callback ) )
				continue;

			ob_start();

			$value = call_user_func( $callback, $fields, $post_type );
			$html = ob_get_clean();

			if ( is_string( $value ) )
				$html .= $value;

			if ( trim( $html ) !== '' ) {
				$classes = 'archive-section';

				if ( ! empty( $elements[$type]['classes'] ) )
					$classes .= ' ' . $elements[$type]['classes'];

				$section = '<div class="' . esc_attr( $classes ) . '">' . $html . '</div>';

				if ( strpos( " $classes ", ' col-full ' ) !== false ) {
					$output .= $this->group( $group, $group_classes, $area ) . $section;
					$group = '';
				}
				else
					$group .= $section;
			}
		}

		$output .= $this->group( $group, $group_classes, $area );

		if ( $output )
			echo
				'<div class="archive-sections archive-sections-' . esc_attr( str_replace( '_', '-', $area ) ) . ' columns-fluid-2 gap-single">'.
				$output.
				'</div>';
	}

	/**
	 * Wrap a run of neighboring sections so they share one row, which
	 * scrolls horizontally on mobile around the Loop.
	 *
	 * @since 6.0
	 */

	private function group( $sections, $classes, $area ) {
		if ( $sections === '' )
			return '';

		if ( in_array( $area, array( 'before_loop', 'after_loop' ), true ) )
			$classes .= ' scroll-mobile';

		return '<div class="' . esc_attr( $classes ) . '">' . $sections . '</div>';
	}

	/**
	 * Confirm the current Loop is the page's primary archive Loop.
	 *
	 * @since 6.0
	 */

	private function is_archive_loop( $args ) {
		if ( ! is_home() && ! is_archive() )
			return false;

		if ( ! empty( $args['query'] ) || ! empty( $args['in_loop'] ) )
			return false;

		return empty( $args['loop']['in_loop'] );
	}
}

new md_archive_sections;
