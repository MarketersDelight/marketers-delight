<?php

class CollectionRestTest extends MD_TestCase {

	protected $controller;

	protected function setUp(): void {
		parent::setUp();

		md_test_set_filter( 'md_filter_collections', array(
			'book_quotes' => array(
				'parent' => 'bookshelf',
				'post_type' => 'book_quote',
				'fields' => array(
					'content' => array( 'type' => 'textarea', 'source' => 'post_content' ),
					'location' => array( 'type' => 'text' )
				)
			)
		) );
		$collection = new md_collection( 'book_quotes' );
		$collection->register();
		$this->controller = new md_collection_rest_controller;

		md_test_set_post( array(
			'ID' => 10,
			'post_type' => 'bookshelf',
			'post_parent' => 0,
			'post_status' => 'publish'
		) );
		md_test_set_post( array(
			'ID' => 25,
			'post_type' => 'book_quote',
			'post_parent' => 10,
			'post_status' => 'publish',
			'post_content' => 'A useful quote.'
		) );
		$GLOBALS['__test_object_meta'][25]['book_quote_location'] = 'Page 42';
	}

	protected function request( $args = array() ) {
		return new WP_REST_Request( array_merge( array(
			'collection' => 'book_quotes',
			'parent_id' => 10,
			'item_id' => 25
		), $args ) );
	}

	public function test_collection_permission_uses_the_parent_post_type_and_capability() {
		$this->assertTrue( $this->controller->permissions_check( $this->request() ) );

		md_test_set_capability( 'edit_post', false );
		$error = $this->controller->permissions_check( $this->request() );

		$this->assertSame( 'md_collection_permission', $error->get_error_code() );
	}

	public function test_item_permission_rejects_an_item_from_another_parent() {
		$GLOBALS['__test_posts'][25]->post_parent = 11;
		$error = $this->controller->permissions_check( $this->request( array( '_method' => 'PATCH' ) ) );

		$this->assertSame( 'md_collection_item', $error->get_error_code() );
	}

	public function test_create_permission_checks_the_item_post_type_capability() {
		md_test_set_capability( 'edit_book_quotes', false );
		$error = $this->controller->permissions_check( $this->request( array(
			'_method' => 'POST',
			'item_id' => 0
		) ) );

		$this->assertSame( 'md_collection_permission', $error->get_error_code() );
	}

	public function test_response_only_contains_collection_manager_data() {
		$method = new ReflectionMethod( $this->controller, 'item_response' );
		$collection = new class( 'book_quotes' ) extends md_collection {
			public function render_item( $item ) {
				return '<article>Quote</article>';
			}

			public function count_items( $parent_id, $post_status = null ) {
				return 1;
			}
		};
		$response = $method->invoke( $this->controller, $GLOBALS['__test_posts'][25], $collection );

		$this->assertSame( array(
			'html' => '<article>Quote</article>',
			'count' => 1
		), $response->get_data() );
	}
}
