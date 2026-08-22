<?php
/**
 * Tests Byline item registration and post type scope.
 *
 * @since 6.0
 */

class BylineTest extends MD_InheritanceTestCase {

	public function test_items_can_be_scoped_to_post_types() {
		md_test_set_filter( 'md_byline', array(
			'date' => array(
				'title' => 'Date'
			),
			'book_rating' => array(
				'title' => 'Book Rating',
				'post_types' => array( 'bookshelf' )
			)
		) );

		$this->assertSame( array( 'date', 'book_rating' ), array_keys( md_byline_items() ) );
		$this->assertSame( array( 'date', 'book_rating' ), array_keys( md_byline_items( 'bookshelf' ) ) );
		$this->assertSame( array( 'date' ), array_keys( md_byline_items( 'post' ) ) );
		$this->assertSame( array( 'date' ), array_keys( md_byline_items( '' ) ) );
	}

	public function test_render_keeps_native_items_missing_from_the_frontend_registry() {
		md_test_set_filter( 'md_byline', array(
			'share' => array(
				'title' => 'Share'
			),
			'book_rating' => array(
				'title' => 'Book Rating',
				'post_types' => array( 'bookshelf' )
			)
		) );

		md_byline( 'before_title', array(
			'loop' => array(
				'post_type' => 'post',
				'remove_byline' => array()
			),
			'items' => array(
				'date' => array(),
				'share' => array(),
				'book_rating' => array()
			)
		) );

		$this->assertSame( array( 'date', 'share' ), $GLOBALS['__test_rendered_byline_items'] );
	}

}
