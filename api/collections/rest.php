<?php
/**
 * The Collections functionality uses the WP REST API
 * for its interactive and storage functionality. Extra
 * handling is just needed for dealing with the REST API.
 *
 * @since 6.0
 */

class md_collections_rest {

	/**
	 * Register Collection and accessible routes.
	 *
	 * @since 6.0
	 */

	public function register_routes() {
		$collection = '/collections/(?P<collection>[a-z0-9_-]+)/(?P<parent_id>\d+)';

		register_rest_route( 'md/v1', $collection, array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'permissions_check' )
			),
			array(
				'methods' => WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'save_item' ),
				'permission_callback' => array( $this, 'permissions_check' )
			)
		) );

		register_rest_route( 'md/v1', "$collection/(?P<item_id>\d+)", array(
			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array( $this, 'save_item' ),
				'permission_callback' => array( $this, 'permissions_check' )
			),
			array(
				'methods' => WP_REST_Server::DELETABLE,
				'callback' => array( $this, 'delete_item' ),
				'permission_callback' => array( $this, 'permissions_check' )
			)
		) );
	}

	/**
	 * Make sure we are in the expected collection, post, and editor session.
	 *
	 * @since 6.0
	 */

	public function permissions_check( $request ) {
		$collection = $this->get_collection( $request );
		$parent_id = absint( $request['parent_id'] );

		if ( ! $collection->parent || ! $collection->post_type || get_post_type( $parent_id ) !== $collection->parent )
			return new WP_Error( 'md_collection_parent', __( 'Collection parent not found.', 'md' ), array( 'status' => 404 ) );

		if ( ! current_user_can( 'edit_post', $parent_id ) )
			return new WP_Error( 'md_collection_permission', __( 'You cannot edit this Collection.', 'md' ), array( 'status' => 403 ) );

		$item_id = absint( $request['item_id'] );

		if ( $item_id ) {
			if ( get_post_type( $item_id ) !== $collection->post_type || wp_get_post_parent_id( $item_id ) !== $parent_id )
				return new WP_Error( 'md_collection_item', __( 'Collection item not found.', 'md' ), array( 'status' => 404 ) );

			$capability = $request->get_method() === WP_REST_Server::DELETABLE ? 'delete_post' : 'edit_post';

			if ( ! current_user_can( $capability, $item_id ) )
				return new WP_Error( 'md_collection_permission', __( 'You cannot modify this Collection item.', 'md' ), array( 'status' => 403 ) );
		}
		elseif ( $request->get_method() === WP_REST_Server::CREATABLE ) {
			$post_type = get_post_type_object( $collection->post_type );

			if ( ! $post_type || ! current_user_can( $post_type->cap->create_posts ) )
				return new WP_Error( 'md_collection_permission', __( 'You cannot create Collection items.', 'md' ), array( 'status' => 403 ) );
		}

		return true;
	}

	/**
	 * Return a page of Collection items.
	 *
	 * @since 6.0
	 */

	public function get_items( $request ) {
		$collection = $this->get_collection( $request );
		$exclude = wp_parse_id_list( $request['exclude'] );
		$query = $collection->query( $request['parent_id'], array(
			'post__not_in' => $exclude
		) );

		$html = '';

		foreach ( $query->posts as $post )
			$html .= $collection->render_item( $post );

		return rest_ensure_response( array(
			'html' => $html,
			'has_more' => $query->max_num_pages > 1
		) );
	}

	/**
	 * Create or update a collection item.
	 *
	 * @since 6.0
	 */

	public function save_item( $request ) {
		$collection = $this->get_collection( $request );
		$parent_id = absint( $request['parent_id'] );
		$item_id = absint( $request['item_id'] );
		$is_new = ! $item_id;

		if ( $is_new && get_post_status( $parent_id ) !== 'publish' )
			return new WP_Error( 'md_collection_parent_status', __( 'Publish the parent before adding Collection items.', 'md' ), array( 'status' => 400 ) );

		$fields = $request->get_param( 'fields' );
		$fields = is_array( $fields ) ? $fields : array();
		$terms_permission = $this->terms_permissions_check( $collection, $fields );

		if ( is_wp_error( $terms_permission ) )
			return $terms_permission;

		if ( $is_new ) {
			$item_id = wp_insert_post( array(
				'post_type' => $collection->post_type,
				'post_parent' => $parent_id,
				'post_status' => 'publish',
				'post_author' => get_current_user_id(),
				'post_title' => $collection->item_title( $fields )
			), true );

			if ( is_wp_error( $item_id ) )
				return $item_id;
		}

		$saved = $collection->save( $item_id, $fields );

		if ( is_wp_error( $saved ) ) {
			if ( $is_new )
				wp_delete_post( $item_id, true );

			return $saved;
		}

		$response = $this->item_response( get_post( $item_id ), $collection );

		if ( $is_new )
			$response->set_status( 201 );

		return $response;
	}

	/**
	 * Move a collection item to Trash.
	 *
	 * @since 6.0
	 */

	public function delete_item( $request ) {
		$collection = $this->get_collection( $request );
		$parent_id = absint( $request['parent_id'] );
		$item_id = absint( $request['item_id'] );
		$post = wp_trash_post( $item_id );

		if ( ! $post )
			return new WP_Error( 'md_collection_trash', __( 'The Collection item could not be moved to Trash.', 'md' ), array( 'status' => 500 ) );

		return rest_ensure_response( array(
			'count' => $collection->count_items( $parent_id )
		) );
	}

	/**
	 * Check assign/create capabilities before changing collection terms.
	 *
	 * @since 6.0
	 */

	protected function terms_permissions_check( $collection, $values ) {
		foreach ( $collection->fields as $id => $field ) {
			if ( $field['source'] !== 'taxonomy' || ! array_key_exists( $id, $values ) )
				continue;

			$taxonomy = get_taxonomy( $field['taxonomy'] ?? '' );

			if ( ! $taxonomy || ! current_user_can( $taxonomy->cap->assign_terms ) )
				return new WP_Error( 'md_collection_terms', __( 'You cannot assign these terms.', 'md' ), array( 'status' => 403 ) );

			foreach ( (array) $values[$id] as $term )
				if ( ! term_exists( $term, $taxonomy->name ) && ! current_user_can( $taxonomy->cap->manage_terms ) )
					return new WP_Error( 'md_collection_terms', __( 'You cannot create new terms.', 'md' ), array( 'status' => 403 ) );
		}

		return true;
	}

	/**
	 * Return the rendered item and current manager count.
	 *
	 * @since 6.0
	 */

	protected function item_response( $item, $collection ) {
		return rest_ensure_response( array(
			'html' => $collection->render_item( $item ),
			'count' => $collection->count_items( $item->post_parent )
		) );
	}

	/**
	 * Resolve the Collection requested by a REST operation.
	 *
	 * @since 6.0
	 */

	protected function get_collection( $request ) {
		return new md_collection( sanitize_key( $request['collection'] ) );
	}

}
