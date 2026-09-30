<?php
/**
 * Configure the sitewide Author archive settings and primary query.
 *
 * @since 6.0
 */

class md_author extends md_api {

	/**
	 * Apply Author Loop controls to WordPress's primary author query.
	 *
	 * @since 6.0
	 */

	public function pre_get_posts( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_author() )
			return;

		$loop = md_post_type_field( 'loop', array(), 'author' );

		if ( ! empty( $loop['posts_per_page'] ) )
			$query->set( 'posts_per_page', absint( $loop['posts_per_page'] ) );

		if ( ! empty( $loop['date']['group'] ) )
			$query->set( 'orderby', 'date' );
		elseif ( ! empty( $loop['orderby'] ) )
			$query->set( 'orderby', sanitize_key( $loop['orderby'] ) );

		if ( ! empty( $loop['order'] ) )
			$query->set( 'order', strtoupper( sanitize_key( $loop['order'] ) ) === 'ASC' ? 'ASC' : 'DESC' );
	}

	/**
	 * Register the sitewide Author Pages settings screen.
	 *
	 * @since 6.0
	 */

	public function register() {
		$this->name = __( 'Author Pages', 'md' );

		return array( 'admin_page' => array(
			'name' => $this->name,
			'parent_slug' => 'users.php',
			'has_groups' => true,
			'fields' => array_merge(
				$this->fields->data->page_fields(),
				apply_filters( 'md_filter_admin_page_page_settings', array() ),
				apply_filters( 'md_filter_admin_page_optins', array() )
			)
		) );
	}

	/**
	 * Render the Author Pages settings screen.
	 *
	 * @since 6.0
	 */

	public function admin_page() {
		$this->fields->admin_header( array( 'archive' => array(
			'title' => __( 'Author Pages Settings', 'md' ),
			'description' => __( 'Set sitewide defaults for author archive pages.', 'md' )
		) ) );

		echo '<div class="md-content-wrap-med">';

		$this->fields->page_fields();

		do_action( 'md_admin_page_page_settings' );
		do_action( 'md_admin_page_author' );

		$this->fields->save();

		echo '</div>';
	}
}

new md_author( 'md_author' );
