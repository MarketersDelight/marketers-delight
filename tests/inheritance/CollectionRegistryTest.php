<?php

class CollectionRegistryTest extends MD_InheritanceTestCase {

	public function test_returns_all_or_one_filtered_collection() {
		$collections = array(
			'book_quotes' => array(
				'parent' => 'bookshelf',
				'post_type' => 'book_quote'
			)
		);
		md_test_set_filter( 'md_filter_collections', $collections );

		$this->assertSame( $collections, md_collections() );
		$this->assertSame( $collections['book_quotes'], md_collections( 'book_quotes' ) );
		$this->assertSame( array(), md_collections( 'missing' ) );
	}
}
