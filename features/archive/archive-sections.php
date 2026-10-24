<?php
/**
 * Configure and display reusable sections around archive loops.
 *
 * @since 6.0
 */

class md_archive_sections extends md_api {

	private $main_query;

	/**
	 * Register Archive Sections query hooks.
	 *
	 * @since 6.0
	 */

	public function actions() {
		add_filter( 'query_vars', array( $this, 'query_vars' ) );
	}

	/**
	 * Register the archive query variable available to extensions.
	 *
	 * @since 6.0
	 */

	public function query_vars( $vars ) {
		$vars[] = 'filter';

		return $vars;
	}

	/**
	 * Keep the main query available to Archive Sections without reading globals.
	 *
	 * @since 6.0
	 */

	public function pre_get_posts( $query ) {
		if ( $query->is_main_query() )
			$this->main_query = $query;
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
			'child_of' => array( 'archive', 'page_settings', 'author' ),
			'fields' => $this->fields()
		);

		return array(
			'admin_page' => $args,
			'term' => array(
				'name' => $this->name,
				'child_of' => array( 'archive', 'page_settings' ),
				'fields' => $this->fields()
			)
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
			'name' => array( 'type' => 'text' ),
			'scope' => array( 'type' => 'checkbox', 'options' => array( 'post_type_only', 'paged' ) )
		);

		foreach ( $this->elements() as $element ) {
			$element_fields = $element['fields'] ?? array();

			// A function name is only called in the admin, where settings are saved

			if ( is_string( $element_fields ) )
				$element_fields = is_admin() ? call_user_func( $element_fields ) : array();

			if ( $element_fields )
				$fields = array_merge( $fields, $element_fields );
		}

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
		$post_type = $this->fields->screen_post_type( true );

		echo '<div class="md-' . esc_attr( $this->_clean_id ) . ' md-tab-content">';

		$builder_args = array(
			'type' => 'builder',
			'wrap_classes' => 'md-tabs',
			'title' => __( 'Add Archive Widgets', 'md' ),
			'description' => __( 'Add reusable content before or after the archive entries.', 'md' ),
			'active_tab' => 'before_loop',
			'tabs' => array(
				'before_loop' => __( 'Before Loop', 'md' ),
				'after_loop' => __( 'After Loop', 'md' ),
				'before_sidebar' => __( 'Before Sidebar', 'md' ),
				'after_sidebar' => __( 'After Sidebar', 'md' )
			),
			'areas' => array(
				'before_loop' => array(
					'title' => __( 'Before Loop', 'md' ),
					'description' => __( 'Shown before the archive entries.', 'md' ),
					'tab' => 'before_loop'
				),
				'after_loop' => array(
					'title' => __( 'After Loop', 'md' ),
					'description' => __( 'Shown after the archive entries and pagination.', 'md' ),
					'tab' => 'after_loop'
				),
				'before_sidebar' => array(
					'title' => __( 'Before Sidebar', 'md' ),
					'description' => __( 'Shown before the sidebar widgets.', 'md' ),
					'tab' => 'before_sidebar'
				),
				'after_sidebar' => array(
					'title' => __( 'After Sidebar', 'md' ),
					'description' => __( 'Shown after the sidebar widgets.', 'md' ),
					'tab' => 'after_sidebar'
				)
			),
			'elements' => $this->elements( $post_type )
		);

		if ( empty( $this->_get_screen['is_taxonomy'] ) && $post_type !== 'author' && get_post_type_object( $post_type ) )
			$builder_args['scope'] = true;

		if ( $post_type === 'author' ) {
			$builder_args['populate'] = true;
			$builder_args['save_empty'] = true;
			$builder_args['defaults'] = md_get_post_type_builder( 'archive_sections', 'author', true );
		}

		$this->fields->field( 'builder', $builder_args );

		echo '</div>';
	}

	/**
	 * Get section elements available to the current post type. An element's
	 * callback outputs one root element. Set 'classes' => 'col-full' for a
	 * full-width element; others share a row with their neighbors. 'fields'
	 * takes the save rules, or a function name that returns them, so a
	 * costly schema is only built in the admin.
	 *
	 * @since 6.0
	 */

	public function elements( $post_type = null ) {
		$elements = apply_filters( 'md_archive_sections_builder_elements', array(
			'query_loop' => array(
				'title' => __( 'Query Loop', 'md' ),
				'placeholder' => __( 'Loop heading...', 'md' ),
				'icon' => 'list-view',
				'color' => '#3858a4',
				'classes' => 'col-full',
				'fields' => md_query_loop_fields(),
				'callback' => 'md_query_loop',
				'admin_callback' => 'md_query_loop_admin_fields'
			),
			'taxonomy_filter' => array(
				'title' => __( 'Taxonomy Filter', 'md' ),
				'placeholder' => __( 'Taxonomy Filter', 'md' ),
				'icon' => 'filter',
				'color' => '#2271b1',
				'classes' => 'col-full',
				'fields' => md_taxonomy_filter_fields(),
				'callback' => 'md_taxonomy_filter',
				'admin_callback' => 'md_taxonomy_filter_admin_fields'
			),
			'title_section' => array(
				'title' => __( 'Title Section', 'md' ),
				'placeholder' => __( 'Section title...', 'md' ),
				'icon' => 'heading',
				'color' => '#8a5e0e',
				'classes' => 'col-full',
				'fields' => 'md_title_section_fields',
				'callback' => 'md_title_section',
				'admin_callback' => 'md_title_section_admin_fields'
			)
		) );

		if ( $post_type === 'author' )
			unset( $elements['taxonomy_filter'] );

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

	/**
	 * Wrap a run of neighboring sections so they share one row, which
	 * scrolls horizontally on mobile around the Loop unless one of its
	 * elements sets scroll to false.
	 *
	 * @since 6.0
	 */

	private function group( $sections, $area, $scroll = true ) {
		if ( $sections === '' )
			return '';

		$classes = 'archive-sections-group columns-fluid-2 gap-single';

		if ( $scroll && in_array( $area, array( 'before_loop', 'after_loop' ), true ) )
			$classes .= ' scroll-mobile';

		return '<div class="' . esc_attr( $classes ) . '">' . $sections . '</div>';
	}

	/**
	 * Render callable elements assigned to one section area. Each element's
	 * callback must output a single root element: full-width elements (classes
	 * col-full) print straight into the area's stack, and neighboring
	 * elements share a row as grid items.
	 *
	 * @since 6.0
	 */

	private function html( $area, $args ) {
		if ( ! $this->is_archive_loop( $args ) )
			return;

		$output = $group = '';
		$scroll = true;
		$is_author = is_author() && empty( $args['query'] ) && empty( $args['in_loop'] );
		$post_type = $is_author ? 'author' : ( $args['loop']['post_type'] ?? md_get_post_type() );
		$builder = md_get_post_type_builder( 'archive_sections', $post_type, $is_author );
		$elements = $this->elements( $post_type );

		foreach ( $builder as $id => $fields ) {
			if ( md_builder_row_hidden( $fields ) )
				continue;

			if ( ( $fields['builder_area'] ?? '' ) !== $area )
				continue;

			$type = $fields['builder_type'] ?? '';
			$callback = $elements[$type]['callback'] ?? null;

			if ( ! is_callable( $callback ) )
				continue;

			ob_start();

			$value = call_user_func( $callback, $fields, $post_type, $id, $this->main_query );
			$html = ob_get_clean();

			if ( is_string( $value ) )
				$html .= $value;

			$html = trim( $html );

			if ( $html === '' )
				continue;

			if ( strpos( ' ' . ( $elements[$type]['classes'] ?? '' ) . ' ', ' col-full ' ) !== false ) {
				$output .= $this->group( $group, $area, $scroll ) . $html;
				$group = '';
				$scroll = true;
			}
			else {
				$group .= $html;
				$scroll = $scroll && ( $elements[$type]['scroll'] ?? true );
			}
		}

		$output .= $this->group( $group, $area, $scroll );

		if ( $output )
			echo '<div class="archive-sections stack">' . $output . '</div>';
	}
}

new md_archive_sections;
