<?php

class DocumentTitleTest extends MD_InheritanceTestCase {

	public function test_untitled_singular_uses_registered_singular_label() {
		$post = (object) array(
			'ID' => 25,
			'post_type' => 'book_quote',
			'post_title' => '',
			'post_content' => ''
		);

		md_test_set_post_type_object( 'book_quote', 'Book Highlights', true, false, 'Book Highlight' );
		md_test_set_query( array(
			'is_singular' => true,
			'post_type' => 'book_quote',
			'queried_object' => $post,
			'queried_object_id' => 25
		) );

		$parts = md_document_title_parts( array( 'title' => '', 'site' => 'Example' ) );

		$this->assertSame( 'Book Highlight', $parts['title'] );
		$this->assertSame( 'Example', $parts['site'] );
	}

	private function untitled_title( $post ) {
		md_test_set_post_type_object( 'book_quote', 'Book Highlights', true, false, 'Book Highlight' );
		md_test_set_query( array(
			'is_singular' => true,
			'post_type' => 'book_quote',
			'queried_object' => $post,
			'queried_object_id' => 25
		) );

		return md_document_title_parts( array( 'title' => '', 'site' => 'Example' ) )['title'];
	}

	public function test_untitled_post_uses_its_first_words() {
		$title = $this->untitled_title( (object) array(
			'post_type' => 'book_quote',
			'post_content' => '<p>You do not <em>rise</em> to the level of your goals, you fall to your systems.</p>'
		) );

		$this->assertSame( 'You do not rise to the level of your…', $title );
	}

	public function test_first_words_skip_links_and_shortcodes() {
		$title = $this->untitled_title( (object) array(
			'post_type' => 'book_quote',
			'post_content' => '[gallery ids="1"] https://example.test/a Shipped it today'
		) );

		$this->assertSame( 'Shipped it today', $title );
	}

	public function test_a_post_that_is_only_a_link_uses_the_label() {
		$this->assertSame( 'Book Highlight', $this->untitled_title( (object) array(
			'post_type' => 'book_quote',
			'post_content' => 'https://example.test/a'
		) ) );
	}

	public function test_password_protected_text_stays_out_of_the_title() {
		$this->assertSame( 'Book Highlight', $this->untitled_title( (object) array(
			'post_type' => 'book_quote',
			'post_content' => 'Secret words',
			'post_password' => 'pw'
		) ) );
	}

	public function test_archive_uses_plaintext_md_page_title() {
		md_test_set_post_type_object( 'book_quote', 'Book Highlights', true, false, 'Book Highlight' );
		md_test_set_option( 'marketers_delight', array(
			'book_quote' => array( 'archives_title' => 'Reading <em>Notes</em> &amp; Ideas' )
		) );
		md_test_set_query( array(
			'is_post_type_archive' => true,
			'is_archive' => true,
			'post_type' => 'book_quote'
		) );

		$parts = md_document_title_parts( array( 'title' => 'Book Highlights', 'site' => 'Example' ) );

		$this->assertSame( 'Reading Notes & Ideas', $parts['title'] );
	}

	public function test_api_document_title_personalizes_only_an_untitled_matching_post() {
		$post = (object) array(
			'ID' => 25,
			'post_type' => 'book_quote',
			'post_title' => '',
			'post_content' => ''
		);
		$api = new class extends md_api {
			public $post_type = 'book_quote';

			public function __construct() {}

			public function document_title( $title, $post ) {
				return 'A <em>highlight</em> from Book ' . $post->ID;
			}
		};

		md_test_set_post_type_object( 'book_quote', 'Book Highlights', true, false, 'Book Highlight' );
		md_test_set_query( array(
			'is_singular' => true,
			'post_type' => 'book_quote',
			'queried_object' => $post,
			'queried_object_id' => 25
		) );

		$parts = $api->_document_title( md_document_title_parts( array( 'title' => '' ) ) );

		$this->assertSame( 'A highlight from Book 25', $parts['title'] );

		$post->post_title = 'Saved title';
		$parts = $api->_document_title( array( 'title' => 'Saved title' ) );

		$this->assertSame( 'Saved title', $parts['title'] );
	}

}
