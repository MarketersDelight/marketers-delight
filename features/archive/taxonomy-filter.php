<?php

/**
 * Configure and display taxonomy filters on archive pages.
 *
 * @since 6.0
 */

class md_taxonomy_filter extends md_api {

	public function actions() {
		add_filter( 'query_vars', array( $this, 'query_vars' ) );
	}

	public function query_vars( $vars ) {
		$vars[] = 'filter';

		return $vars;
	}

	public function template() {
		add_action( 'md_hook_loop_before', array( $this, 'html' ) );
	}

	public function register() {
		$this->name = __( 'Taxonomy Filter', 'md' );

		$args = array(
			'name' => $this->name,
			'child_of' => array( 'archive_header', 'page_settings' ),
			'fields' => $this->fields()
		);

		return array(
			'admin_page' => $args,
			'term' => $args
		);
	}

	public function fields() {
		return array(
			'enabled' => array(
				'type' => 'checkbox',
				'options' => array( 'taxonomy_filter' )
			),
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

	public function admin_fields() {
		$post_type = $this->_get_screen['post_type'];
		$page_post_type = md_clean_id( $this->_get_screen['page'] );

		if ( $this->_get_screen['is_admin'] && get_post_type_object( $page_post_type ) )
			$post_type = $page_post_type;

		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		$options = array();

		foreach ( $taxonomies as $taxonomy => $object )
			if ( $object->public )
				$options[$taxonomy] = $object->labels->name;

		echo '<div class="md-' . esc_attr( $this->_clean_id ) . ' md-tab-content md-conditional">';

		if ( ! $options ) {
			echo '<p>' . esc_html__( 'No public taxonomies are registered for this post type.', 'md' ) . '</p></div>';
			return;
		}

		$enabled = $this->fields->module( array( 'enabled', 'taxonomy_filter' ) );

		$this->fields->field( 'enabled', array(
			'type' => 'checkbox',
			'classes' => 'md-conditional-option',
			'options' => array(
				'taxonomy_filter' => __( 'Show taxonomy filter', 'md' )
			)
		) );

		echo '<div class="md-conditional-item md-conditional-1 md-sep-small" style="display:' . ( $enabled ? 'block' : 'none' ) . '">';

		$this->fields->field( 'taxonomy', array(
			'type' => 'select',
			'label' => __( 'Taxonomy', 'md' ),
			'empty_label' => __( 'Auto-detect taxonomy', 'md' ),
			'options' => $options
		) );

		$this->fields->field( 'taxonomy_settings', array(
			'type' => 'checkbox',
			'label' => __( 'Settings', 'md' ),
			'options' => array(
				'hide_empty' => __( 'Hide empty terms', 'md' )
			)
		) );

		$this->fields->field( 'all_label', array(
			'type' => 'text',
			'label' => __( 'All label', 'md' ),
			'placeholder' => __( 'All', 'md' )
		) );

		echo '</div></div>';
	}

	public function html() {
		if ( ! is_home() && ! is_archive() )
			return;

		$settings = md_module( 'taxonomy_filter', array() );

		if ( empty( $settings['enabled']['taxonomy_filter'] ) )
			return;

		$current_id = get_queried_object_id();
		$post_type = md_get_post_type();
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		$taxonomy = $settings['taxonomy'] ?? '';
		$options = $settings['taxonomy_settings'] ?? array();

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

		include md_template( 'taxonomy-filter', true );
	}
}

new md_taxonomy_filter;
