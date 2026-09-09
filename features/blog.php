<?php
/**
 * Create Post Settings admin pages.
 *
 * @since 6.0
 */

class md_post extends md_api {

	public $post_type = 'post';
	public $taxonomy = 'category';

	/**
	 * Create admin page and meta box.
	 *
	 * @since 6.0
	 */

	public function register() {
		return array(
			'admin_page' => array(
				'name' => __( 'Settings', 'md' ),
				'parent_slug' => 'edit.php',
				'has_groups' => true,
				'fields' => array_merge(
					$this->fields->data->page_fields(),
					apply_filters( 'md_filter_admin_page_page_settings', array() ),
					apply_filters( 'md_filter_admin_page_optins', array() ),
				)
			),
			'taxonomy' => true
		);
	}

	/**
	 * Change Posts to Blog in WP admin.
	 *
	 * @since 6.0
	 */

	public function init() {
		$this->plural = __( 'Blog', 'md' );
		$this->singular = __( 'Post', 'md' );

    	$post_type = get_post_type_object( 'post' );
		$post_type->labels->name = $post_type->labels->menu_name = $this->plural;
	}

	/**
	 * Admin page template with newly registered page_settings group.
	 * Other classes can hook their own custom fields into this settings group.
	 *
	 * @since 6.0
	 */

	public function admin_page() {
		$this->fields->admin_header();

		echo '<div class="md-content-wrap-med">';

		$this->fields->page_fields();

		do_action( 'md_admin_page_page_settings' );

		$this->fields->save();

		echo '</div>';
	}

	/**
	 * Add user overwrites to the main loop query.
	 *
	 * @since 6.0
	 */

	public function template() {
		if ( is_404() ) add_filter( 'md_setting_id', function() {
			return md_setting( array( 'settings', '404_page' ) );
		} );
	}

	/**
	 * We could just inherit the parse_query logic from md_api, but
	 * we need to run specific checks for the post post type only,
	 * since WP doesn't treat it the same as CPTs.
	 *
	 * @since 6.0
	 */

	public function parse_query( $wp ) {
		if ( is_admin() || ! $wp->is_main_query() )
			return $wp;

		$is_term = $wp->is_category || $wp->is_tag;

		if ( ! $wp->is_home && ! $is_term )
			return $wp;

		$taxonomy = $wp->is_category ? 'category' : 'post_tag';
		$term_id = $wp->is_category ? $wp->get( 'cat' ) : (int) $wp->get( 'tag_id' );

		if ( $is_term && ! $term_id ) {
			$slug = $wp->is_category ? basename( $wp->get( 'category_name' ) ) : $wp->get( 'tag' );
			$obj = $slug ? get_term_by( 'slug', $slug, $taxonomy ) : false;
			$term_id = $obj ? $obj->term_id : 0;
		}

		if ( ! $is_term )
			$taxonomy = '';

		$this->loop_query_vars( $wp, $taxonomy, $term_id );

		// Make accommodations to show sticky posts on category pages

		$sticky = empty( $wp->query_vars['ignore_sticky_posts'] ) ? md_get_sticky( 'post' ) : array();

		if ( $sticky ) {
			if ( $term_id && $taxonomy ) {
				$term_sticky = array();

				foreach ( $sticky as $id )
					if ( has_term( $term_id, $taxonomy, $id ) )
						$term_sticky[] = $id;

				$sticky = $term_sticky;
			}

			if ( $sticky ) {
				$wp->set( 'post__not_in', $sticky );

				if ( ! get_query_var( 'paged' ) )
					add_filter( 'the_posts', array( $this, '_prepend_sticky' ), 10, 2 );
			}
		}

		return $wp;
	}

}

new md_post;
