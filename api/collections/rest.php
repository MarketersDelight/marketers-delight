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
		$item_id = absint( $request['item_id'] );
		$method = $request->get_method();

		if ( $method === WP_REST_Server::DELETABLE )
			$action = 'trash';
		else
			$action = $item_id ? 'update' : 'create';

		return $this->get_collection( $request )->can( $action, absint( $request['parent_id'] ), $item_id, $request->get_param( 'fields' ) );
	}

	/**
	 * Create or update a collection item.
	 *
	 * @since 6.0
	 */

	public function save_item( $request ) {
		$collection = $this->get_collection( $request );
		$item_id = absint( $request['item_id'] );
		$saved = $collection->save_item( absint( $request['parent_id'] ), $item_id, $request->get_param( 'fields' ) );

		if ( is_wp_error( $saved ) )
			return $saved;

		$response = $this->item_response( get_post( $saved ), $collection );

		if ( ! $item_id )
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
		$trashed = $collection->trash_item( absint( $request['item_id'] ) );

		if ( is_wp_error( $trashed ) )
			return $trashed;

		return rest_ensure_response( array(
			'count' => $collection->count_items( absint( $request['parent_id'] ) )
		) );
	}

	/**
	 * Return the item's ID, its rendered row and the current manager count.
	 *
	 * @since 6.0
	 */

	protected function item_response( $item, $collection ) {
		return rest_ensure_response( array(
			'id' => $item->ID,
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
