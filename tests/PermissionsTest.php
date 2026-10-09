<?php

class PermissionsTest extends MD_TestCase {

	public function test_standalone_rest_meta_uses_its_parent_box_permission() {
		$api = new class extends md_api {
			public function __construct() {}

			public function register_meta_for_test( $meta_box ) {
				$this->register = array( 'meta_box' => $meta_box );
				$this->_register_standalone_meta();
			}
		};

		$api->register_meta_for_test( array(
			'post_type' => array( 'book' ),
			'fields' => array( 'rating' => array( 'type' => 'number', 'standalone' => true ) )
		) );
		$callback = $GLOBALS['__test_registered_meta']['book']['rating']['auth_callback'];
		md_test_set_capability( 'manage_options', false );
		$this->assertFalse( $callback( true, 'rating', 10, 1 ) );

		$api->register_meta_for_test( array(
			'edit_post' => true,
			'post_type' => array( 'book' ),
			'fields' => array( 'rating' => array( 'type' => 'number', 'standalone' => true ) )
		) );
		$callback = $GLOBALS['__test_registered_meta']['book']['rating']['auth_callback'];
		$this->assertTrue( $callback( false, 'rating', 10, 1 ) );
		md_test_set_capability( 'edit_post', false );
		$this->assertFalse( $callback( true, 'rating', 10, 1 ) );
	}
}
