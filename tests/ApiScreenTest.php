<?php
/**
 * Tests md_api::_get_screen(): every instance asks for the same screen, so
 * it builds once per screen, and a screen change during a request is seen.
 *
 * @since 6.2
 */

if ( ! function_exists( 'get_current_screen' ) ) {
	function get_current_screen() {
		return $GLOBALS['__test_current_screen'];
	}
}

class ApiScreenTest extends MD_TestCase {

	private function screen( $id, $base ) {
		$GLOBALS['__test_current_screen'] = (object) array(
			'id' => $id,
			'base' => $base,
			'post_type' => '',
			'taxonomy' => ''
		);
		$api = ( new ReflectionClass( 'md_api' ) )->newInstanceWithoutConstructor();
		$method = new ReflectionMethod( $api, '_get_screen' );
		$method->setAccessible( true );

		return $method->invoke( $api );
	}

	public function test_a_screen_builds_once() {
		md_test_set_filter( 'md_taxonomy_groups', array( 'first' => true ) );
		$first = $this->screen( 'api-screen-test-once', 'dashboard' );

		md_test_set_filter( 'md_taxonomy_groups', array( 'second' => true ) );
		$again = $this->screen( 'api-screen-test-once', 'dashboard' );

		$this->assertSame( array( 'first' => true ), $first['taxonomy_groups'] );
		$this->assertSame( $first, $again );
	}

	public function test_a_new_screen_is_not_given_the_last_one() {
		$dashboard = $this->screen( 'api-screen-test-dashboard', 'dashboard' );
		$post = $this->screen( 'api-screen-test-post', 'post' );

		$this->assertTrue( $dashboard['is_admin'] );
		$this->assertFalse( $dashboard['is_post'] );
		$this->assertSame( 'post', $post['base'] );
		$this->assertTrue( $post['is_post'] );
	}

}
