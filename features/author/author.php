<?php
/**
 * Configure the sitewide Author archive settings and primary query.
 *
 * @since 6.0
 */

class md_author extends md_api {

	/**
	 * Use authors' uploaded photos as their avatars.
	 *
	 * @since 6.1
	 */

	public function actions() {
		add_filter( 'pre_get_avatar_data', array( $this, 'avatar_data' ), 10, 2 );
	}

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

		return array(
			'admin_page' => array(
				'name' => $this->name,
				'parent_slug' => 'users.php',
				'has_groups' => true,
				'fields' => array_merge(
					$this->fields->data->page_fields(),
					apply_filters( 'md_filter_admin_page_page_settings', array() ),
					apply_filters( 'md_filter_admin_page_optins', array() )
				)
			),
			'user_meta' => array(
				'name' => __( 'Author', 'md' ),
				'fields' => array(
					'photo' => array(
						'type' => 'upload',
						'upload_type' => 'media'
					)
				)
			)
		);
	}

	/**
	 * Render the Author photo field on user profiles.
	 *
	 * @since 6.1
	 */

	public function user_meta( $user_meta ) {
		$this->fields->field( 'photo', array(
			'type' => 'upload',
			'upload_type' => 'media',
			'label' => __( 'Author photo', 'md' ),
			'description' => __( 'Shown in post titles and on your author page, and as your avatar in bylines, the author box and comments.', 'md' ),
			'user_meta' => $user_meta
		) );
	}

	/**
	 * Swap an author's avatar for their uploaded photo, so it shows without
	 * a request to Gravatar. Authors without a photo keep their avatar.
	 *
	 * @since 6.1
	 */

	public function avatar_data( $args, $id_or_email ) {
		if ( $id_or_email instanceof WP_User )
			$user_id = $id_or_email->ID;
		elseif ( $id_or_email instanceof WP_Post )
			$user_id = (int) $id_or_email->post_author;
		elseif ( $id_or_email instanceof WP_Comment )
			$user_id = (int) $id_or_email->user_id;
		elseif ( is_numeric( $id_or_email ) )
			$user_id = (int) $id_or_email;
		else {
			$user = is_string( $id_or_email ) ? get_user_by( 'email', $id_or_email ) : false;
			$user_id = $user ? $user->ID : 0;
		}

		$photo_id = $user_id ? md_author_photo_id( $user_id ) : 0;
		$url = $photo_id ? wp_get_attachment_image_url( $photo_id, array( $args['size'], $args['size'] ) ) : '';

		if ( $url ) {
			$args['url'] = $url;
			$args['found_avatar'] = true;
		}

		return $args;
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
