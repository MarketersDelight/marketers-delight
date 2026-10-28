<?php
/**
 * Tests md_term_meta() from functions/meta-functions.php: an explicit term
 * ID always wins, and only a missing ID falls back to the admin screen's
 * tag_ID or the current page's queried term.
 *
 * @since 6.1
 */

class TermMetaTest extends MD_InheritanceTestCase {

	protected function tearDown(): void {
		unset( $_GET['tag_ID'] );
		parent::tearDown();
	}

	// CSS compiles run in the admin (menu, widget and settings saves) and
	// read category colors by explicit term ID. That ID must not be
	// replaced by the edit screen's tag_ID (or by nothing at all).

	public function test_explicit_id_is_used_in_the_admin() {
		md_test_set_query( array( 'is_admin' => true ) );
		md_test_set_term_meta( 65, array( 'hero' => array( 'archives_color' => '#2256bb' ) ) );

		$this->assertSame( '#2256bb', md_term_meta( array( 'hero', 'archives_color' ), 65, '' ) );
	}

	public function test_missing_id_uses_the_admin_screens_term() {
		md_test_set_query( array( 'is_admin' => true ) );
		md_test_set_term_meta( 12, array( 'hero' => array( 'archives_color' => '#cc3430' ) ) );
		$_GET['tag_ID'] = '12';

		$this->assertSame( '#cc3430', md_term_meta( array( 'hero', 'archives_color' ), null, '' ) );
	}

	public function test_missing_id_uses_the_queried_term_on_the_front_end() {
		md_test_set_query( array( 'is_category' => true, 'queried_object_id' => 42 ) );
		md_test_set_term_meta( 42, array( 'hero' => array( 'archives_color' => '#438f31' ) ) );

		$this->assertSame( '#438f31', md_term_meta( array( 'hero', 'archives_color' ), null, '' ) );
	}

}
