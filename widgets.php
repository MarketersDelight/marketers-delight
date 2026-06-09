<?php
/**
 * Create widget interface and frontend output.
 *
 * @since 5.2
 */

class md_accordion_widget extends WP_Widget {

	private $terms_order = array();

	/**
	 * Create widget attributes and fire any needed actions.
	 *
	 * @since 5.2
	 */

	public function __construct() {
		$this->terms_order = array(
			'count' => __( 'Posts count', 'md' ),
			'slug' => __( 'Slug', 'md' ),
			'id' => __( 'Category ID', 'md' ),
			'term_order' => __( 'Category Order', 'md' )
		);

		parent::__construct( 'md_accordion_widget', __( 'MD &rarr; Accordion Nav', 'md' ), array(
			'description' => __( 'List category links in a highly organized accordion widget.', 'md' ),
			'customize_selective_refresh' => true
		) );
	}

	/**
	 * Build the frontend template with lots of sorting possibilities.
	 *
	 * @since 5.2
	 */

	public function widget( $args, $val ) {

		// Setup main variables

		$c = 1;
		$terms = $current = $terms_args = $get_terms = $filter_post_ids = array();
		$page_id = get_queried_object_id();
		$page_taxonomies = get_object_taxonomies( md_get_post_type() );

		// Determine desired taxonomy

		$tax = 'category';

		if ( ! empty( $val['taxonomy'] ) )
			$tax = $val['taxonomy'];
		elseif ( ! empty( $page_taxonomies ) )
			$tax = $page_taxonomies[0];

		$taxonomy = get_taxonomy( $tax );
		$post_type = $taxonomy->object_type;

		// Set Term_Query args

		$terms_args['taxonomy'] = $tax;

		if ( ! empty( $val['direction'] ) )
			$terms_args['order'] = 'DESC';

		if ( ! empty( $val['order'] ) )
			$terms_args['orderby'] = sanitize_key( $val['order'] );

		if ( ! empty( $val['exclude'] ) )
			$terms_args['exclude'] = esc_html( $val['exclude'] );

		// Option: restrict terms to only show posts that share term

		$filter_term_id = 0;

		if ( ! empty( $val['filter_term'] ) )
			$filter_term_id = absint( $val['filter_term'] );
		elseif ( ! empty( $val['auto_filter'] ) && ! empty( $val['filter_taxonomy'] ) && ( $page_dropin = get_the_terms( $page_id, $val['filter_taxonomy'] ) ) )
			$filter_term_id = $page_dropin[0]->term_id;

		if ( $filter_term_id ) {
			$filter_term_query = new WP_Term_Query( array(
				'include' => array( $filter_term_id ),
				'hide_empty' => false
			) );
			$filter_terms = $filter_term_query->get_terms();
			$filter_tax = ! empty( $filter_terms ) ? $filter_terms[0]->taxonomy : null;

			if ( $filter_tax ) {
				$filter_post_query = new WP_Query( array(
					'post_type' => $post_type,
					'posts_per_page' => -1,
					'fields' => 'ids',
					'tax_query' => array( array(
						'taxonomy' => $filter_tax,
						'field' => 'term_id',
						'terms' => $filter_term_id,
					) )
				) );
				$filter_post_ids = $filter_post_query->posts;
			}
		}

		// Build terms list, scoped to filtered posts when a filter term is active

		if ( ! empty( $filter_post_ids ) )
			$terms_data = wp_get_object_terms( $filter_post_ids, $tax, $terms_args );
		elseif ( $filter_term_id )
			$terms_data = array();
		else
			$terms_data = get_terms( $terms_args );

		foreach ( $terms_data as $term )
			$terms[$term->term_id] = $term;

		// Push the current term to the top of the list

		if ( is_tax() || is_singular() ) {
			if ( is_singular() ) {
				$get_terms = get_the_terms( $page_id, $tax );
				$get_terms = ! empty( $get_terms ) ? $get_terms[0] : array();
			}
			elseif ( ! empty( $terms[$page_id] ) )
				$get_terms = $terms[$page_id];

			if ( ! empty( $get_terms ) ) {
				$current = $terms[$get_terms->term_id];
				unset( $terms[$get_terms->term_id] );
				array_unshift( $terms, $current );
			}
		}

		// Render template

		include md_template( 'accordion', true );
	}

	/**
	 * Sanitize saved data.
	 *
	 * @since 5.2
	 */

	public function update( $new, $val ) {
		$valid_taxonomies = array_keys( get_taxonomies( array( 'public' => true ) ) );
		$order = array_keys( $this->terms_order );

		foreach ( array( 'title', 'see_more', 'exclude', 'classes' ) as $text )
			$val[$text] = sanitize_text_field( $new[$text] );

		foreach ( array( 'filter_term', 'posts_per_category' ) as $number )
			$val[$number] = absint( $new[$number] ) ?: '';

		$val['settings']  = array();

		foreach ( array( 'open', 'show_count' ) as $key )
			if ( ! empty( $new['settings'][ $key ] ) )
				$val['settings'][ $key ] = true;

		$val['taxonomy'] = in_array( $new['taxonomy'], $valid_taxonomies, true ) ? $new['taxonomy'] : '';
		$val['filter_taxonomy'] = in_array( $new['filter_taxonomy'], $valid_taxonomies, true ) ? $new['filter_taxonomy'] : '';
		$val['auto_filter'] = ! empty( $new['auto_filter'] ) ? true : '';
		$val['direction'] = in_array( $new['direction'], array( 'DESC' ), true ) ? $new['direction'] : '';
		$val['order'] = in_array( $new['order'], $order, true ) ? $new['order'] : '';

		return $val;
	}

	/**
	 * Build widget form settings.
	 *
	 * @since 5.2
	 */

	public function form( $val ) {
		$val = wp_parse_args( (array) $val, array(
			'title' => '',
			'see_more' => '',
			'taxonomy' => '',
			'filter_term' => '',
			'filter_taxonomy' => '',
			'auto_filter' => '',
			'posts_per_category' => '',
			'direction' => '',
			'order' => '',
			'exclude' => '',
			'classes' => '',
			'settings' => array()
		) );

		$taxonomies = get_taxonomies( array( 'public' => true ) );

		include md_template( 'admin/widget-accordion', true );
	}

}