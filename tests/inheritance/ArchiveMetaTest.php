<?php
/**
 * Tests md_get_archive_meta() builder areas: the archive title's items and
 * Category Entry items stay apart, Category Entry items measure the term
 * they're given, and term post counts include child terms.
 *
 * @since 6.0.4
 */

class ArchiveMetaTest extends MD_InheritanceTestCase {

	protected function setUp(): void {
		parent::setUp();

		md_test_set_post_type_object( 'post', 'Posts' );
		md_test_set_object_taxonomies( 'post', array( 'category' ) );
		md_test_set_query( array( 'is_home' => true, 'post_type' => 'post' ) );
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'archive_meta' => array( 'builder' => array(
				'title-count' => array( 'builder_type' => 'test_term', 'builder_area' => 'archives' ),
				'entry-count' => array( 'builder_type' => 'test_term', 'builder_area' => 'category_entry', 'position' => 'inside_title' )
			) )
		) ) );
		md_test_set_filter( 'md_archive_meta_items', array(
			'test_term' => array( 'callback' => 'md_test_archive_meta_term' )
		) );
	}

	public function test_areas_keep_their_own_items() {
		$title = md_get_archive_meta();
		$entry = md_get_archive_meta( 'category_entry', new WP_Term( array( 'term_id' => 3, 'taxonomy' => 'category', 'name' => 'Grief' ) ), 'post' );

		$this->assertSame( array( 'after_title' ), array_keys( $title ) );
		$this->assertSame( array( 'inside_title' ), array_keys( $entry ) );
	}

	public function test_category_entry_items_measure_the_given_term() {
		$entry = md_get_archive_meta( 'category_entry', new WP_Term( array( 'term_id' => 3, 'taxonomy' => 'category', 'name' => 'Grief' ) ), 'post' );

		$this->assertStringContainsString( 'Grief', $entry['inside_title'] );
	}

	public function test_category_entry_items_show_outside_archive_pages() {
		md_test_set_query( array( 'is_home' => false, 'is_singular' => true, 'post_type' => 'post', 'queried_object_id' => 1 ) );

		$entry = md_get_archive_meta( 'category_entry', new WP_Term( array( 'term_id' => 3, 'taxonomy' => 'category', 'name' => 'Grief' ) ), 'post' );

		$this->assertSame( array(), md_get_archive_meta(), 'The archive title area only runs on archives' );
		$this->assertArrayHasKey( 'inside_title', $entry );
	}

	public function test_meta_links_to_another_page() {
		$GLOBALS['wp'] = (object) array( 'request' => '' );

		$this->assertSame( '<a href="https://example.test/bookshelf/"><strong>446</strong> books</a>', md_archive_meta_link( '446 books', 'https://example.test/bookshelf/' ) );

		unset( $GLOBALS['wp'] );
	}

	public function test_meta_never_links_to_the_page_being_viewed() {
		$GLOBALS['wp'] = (object) array( 'request' => 'bookshelf' );

		$this->assertSame( '446 books', md_archive_meta_link( '446 books', 'https://example.test/bookshelf/' ) );
		$this->assertSame( '446 books', md_archive_meta_link( '446 books', '' ) );

		unset( $GLOBALS['wp'] );
	}

}


function md_test_archive_meta_term( $fields, $post_type, $term = null ) {
	return $term ? 'posts in ' . $term->name : '12 posts';
}
