<?php
/**
 * Holds various components of the global Page Header features.
 * Load template to hooks, and break apart various pieces into
 * conditional functions for versatile placement.
 *
 * @since 5.6
 */

class md_page_header {

	/**
	 * Load Page Header template to designated hook.
	 *
	 * @since 5.6
	 */

	public function template() {
//		add_action( 'md_hook_content', array( $this, 'html' ) );

		$image_position = $this->get_image( 'position' );
		if ( ! empty( $image_position ) ) {
			$image_hook = 'md_page_header_bottom';
			if ( in_array( $image_position, array( 'left', 'before_headline' ) ) )
				$image_hook = 'md_page_header_top';
			add_action( $image_hook, array( $this, 'image' ) );
		}
	}

	/**
	 * Get page title and description from different page sources.
	 *
	 * @since 5.6
	 */

	public function get_header( $field ) {
		$args = array();

		if ( is_post_type_archive() ) {
			$args['title'] = post_type_archive_title( '', false );
			$args['description'] = md_post_type_field( 'archives_text' );
		}
		elseif ( is_home() || is_singular( 'post' ) ) {
			$args['title'] = md_post_type_field( 'archives_title' );
			$args['description'] = md_post_type_field( 'archives_text' );
		}
		elseif ( is_tax() && get_queried_object() ) {
			$args['title'] = single_term_title( '', false );
			$args['description'] = md_term_meta( 'archives_text' );
		}
		elseif ( is_category() ) {
			$args['title'] = single_cat_title( '', false );
			$args['description'] = category_description();
		}
		elseif ( is_tag() )
			$args['title'] = single_tag_title( '', false );
		elseif ( is_author() )
			$args['title'] = get_the_author();

		if ( has_filter( 'md_page_title' ) )
			$args['title'] = do_action( 'md_page_title' );

		if ( has_filter( 'md_page_description' ) )
			$args['description'] = do_action( 'md_page_description' );

		return isset( $args[$field] ) ? $args[$field] : '';
	}

	/**
	 * A helper method to easily get Featured image attributes.
	 *
	 * @since 5.6
	 */

	public function get_image( $field = null ) {
		$image = array();
		$position = md_featured_image_position();

		$image['id'] = md_post_type_field( array( 'featured_image', 'image', 'id' ) );
		$image['position'] = md_post_type_field( array( 'featured_image', 'position' ), $position );
		if ( empty( $image['position'] ) )
			$image['position'] = 'right';

		if ( isset( $field ) )
			return $image[$field];

		return $image;
	}

	/**
	 * Render image HTML template.
	 *
	 * @since 5.6
	 */

	public function image() {
		$image_id = $this->get_image( 'id' );

		if ( $image_id ) {
			$position = $this->get_image( 'position' );
			echo
				'<div class="page-header-image image-' . esc_attr( $position ) . '">'.
				wp_get_attachment_image( esc_attr( $image_id ), 'full' ).
				'</div>';
		}
	}

	/**
 	 * Displays titles for various types of archives.
 	 *
 	 * @since 4.0
 	 * @moved 5.6
 	 * @formerly archives_title()
 	 */

	public function html() {
		$title = $this->get_header( 'title' );
		$description = $this->get_header( 'description' );

		if ( $title || $description )
			include( md_template( 'page-header', true ) );
	}

}

$md_page_header = new md_page_header;
add_action( 'template_redirect', array( $md_page_header, 'template' ) );