<?php
/**
 * Load and register Collections declared by active MD extensions.
 *
 * @since 6.0
 */

class md_collections_api {

	/**
	 * Register Collections after extensions have populated the registry.
	 *
	 * @since 6.0
	 */

	public function init() {
		add_action( 'init', array( $this, 'register' ), 20 );
	}

	/**
	 * Load the Collections API and register each configured Collection.
	 *
	 * @since 6.0
	 */

	public function register() {
		$collections = md_collections();

		if ( empty( $collections ) )
			return;

		require_once MD_DIR . 'api/collections/fields.php';
		require_once MD_DIR . 'api/collections/collections.php';

		if ( is_admin() )
			require_once MD_DIR . 'api/collections/admin.php';

		foreach ( $collections as $id => $args ) {
			$collection = new md_collection( $id, $args );
			$collection->register();

			if ( is_admin() ) {
				$admin = new md_collection_admin( $collection );
				$admin->register();
			}
		}

		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * Load the Collections REST controller and register its routes.
	 *
	 * @since 6.0
	 */

	public function register_rest_routes() {
		require_once MD_DIR . 'api/collections/rest.php';

		$controller = new md_collections_rest;
		$controller->register_routes();
	}

}
