<?php
/**
 * Create Post Settings admin pages.
 *
 * @since 6.0
 */

class md_post extends md_api {

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
			)
		);
	}

	/**
	 * Change Posts to Blog in WP admin.
	 *
	 * @since 6.0
	 */

	public function init() {
    	$post_type = get_post_type_object( 'post' );
		$post_type->labels->name = $post_type->labels->menu_name = __( 'Blog', 'md' );
	}

	/**
	 * Admin page template with newly registered page_settings group.
	 * Other classes can hook their own custom fields into this settings group.
	 *
	 * @since 6.0
	 */

	public function admin_page() {
		echo '<h1>' . __( 'Blog Settings', 'md' ) . '</h1>'.
			 '<hr class="md-sep-small" />'.
			 '<div class="md-content-wrap-med">';

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

	public function parse_query( $wp ) {
		$type = 'post';

		if ( ! is_admin() && $wp->is_main_query() && ( $wp->is_home || $wp->is_category ) ) {
			$per_page = md_post_type_field( array( 'loop', 'posts_per_page' ), get_option( 'posts_per_page' ), $type );
			$order = md_post_type_field( array( 'loop', 'order' ), null, $type );
			$orderby = md_post_type_field( array( 'loop', 'orderby' ), null, $type );

			$wp->query_vars['posts_per_page'] = esc_attr( $per_page );

			if ( $order )
				$wp->query_vars['order'] = esc_attr( $order );

			if ( $orderby )
				$wp->query_vars['orderby'] = esc_attr( $orderby );
		}

		return $wp;
	}

}

new md_post;