<?php

class WordPressTest extends MD_InheritanceTestCase {

	public function test_nonindexable_singular_adds_noindex_and_is_removed_from_sitemap() {
		$post = (object) array(
			'ID' => 25,
			'post_type' => 'book_quote',
			'post_title' => ''
		);

		md_test_set_post_type_object( 'book_quote', 'Book Highlights', true, false, 'Book Highlight', false );
		md_test_set_post_type_object( 'bookshelf', 'Books', true, false, 'Book', true );
		md_test_set_query( array(
			'is_singular' => true,
			'post_type' => 'book_quote',
			'queried_object' => $post,
			'queried_object_id' => 25
		) );

		$robots = md_robots_noindex_singular( array( 'index' => true ) );
		$post_types = md_sitemaps_post_types( array(
			'book_quote' => get_post_type_object( 'book_quote' ),
			'bookshelf' => get_post_type_object( 'bookshelf' )
		) );

		$this->assertArrayNotHasKey( 'index', $robots );
		$this->assertTrue( $robots['noindex'] );
		$this->assertArrayNotHasKey( 'book_quote', $post_types );
		$this->assertArrayHasKey( 'bookshelf', $post_types );
	}

	public function test_pagination_relationships_preserve_existing_attributes() {
		$this->assertSame( 'class="button" rel="prev"', md_previous_posts_link_attributes( 'class="button"' ) );
		$this->assertSame( 'aria-label="More" rel="next"', md_next_posts_link_attributes( 'aria-label="More"' ) );

		$html = '<a class="prev page-numbers">Previous</a><a class="next page-numbers">Next</a>';
		$expected = '<a rel="prev" class="prev page-numbers">Previous</a><a rel="next" class="next page-numbers">Next</a>';

		$this->assertSame( $expected, md_paginate_links_output( $html ) );
	}

	public function test_embed_rewrite_filter_removes_only_embed_routes() {
		$rules = array(
			'articles/?$' => 'index.php?post_type=post',
			'articles/embed/?$' => 'index.php?post_type=post&embed=true'
		);

		$this->assertSame( array(
			'articles/?$' => 'index.php?post_type=post'
		), md_disable_embed_rewrites( $rules ) );
	}

}
