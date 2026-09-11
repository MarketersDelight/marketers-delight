<?php
/**
 * Tests breadcrumb route resolution independently from its HTML template.
 *
 * @since 6.0
 */

class MD_Breadcrumbs_Test_Fields {

	public $args = array();

	public function field( $id, $args ) {
		$this->args[$id] = $args;
	}

}

class BreadcrumbsTest extends MD_InheritanceTestCase {

	protected function breadcrumbs() {
		$reflection = new ReflectionClass( 'md_breadcrumbs' );

		return $reflection->newInstanceWithoutConstructor();
	}

	public function test_visibility_resolves_the_complete_setting_group() {
		md_test_set_query( array(
			'is_singular' => true,
			'post_type' => 'post'
		) );

		$this->assertFalse( $this->breadcrumbs()->has() );

		md_test_set_option( 'marketers_delight', array(
			'post' => array(
				'layout' => array(
					'breadcrumbs' => array( 'add' => true )
				)
			)
		) );

		$this->assertTrue( $this->breadcrumbs()->has() );

		md_test_set_post_meta( array(
			'layout' => array(
				'breadcrumbs' => array( 'remove' => true )
			)
		) );

		$this->assertFalse( $this->breadcrumbs()->has() );
	}

	public function test_enabled_top_level_page_is_not_silently_excluded() {
		md_test_set_query( array(
			'is_page' => true,
			'is_singular' => true,
			'queried_object_id' => 1,
			'post_type' => 'page',
			'post_parent_id' => 0
		) );
		md_test_set_option( 'marketers_delight', array(
			'page' => array(
				'layout' => array(
					'breadcrumbs' => array( 'add' => true )
				)
			)
		) );

		$this->assertTrue( $this->breadcrumbs()->has() );
	}

	public function test_term_can_restore_breadcrumbs_removed_by_its_taxonomy() {
		md_test_set_query( array(
			'is_tax' => true,
			'queried_object_id' => 9,
			'queried_object' => (object) array(
				'term_id' => 9,
				'taxonomy' => 'bookshelf_topic'
			),
			'post_type' => 'book_quote'
		) );
		md_test_set_option( 'marketers_delight', array(
			'book_quote' => array(
				'layout' => array(
					'breadcrumbs' => array( 'add' => true )
				),
				'bookshelf_topic' => array(
					'layout' => array(
						'breadcrumbs' => array( 'remove' => true )
					)
				)
			)
		) );
		md_test_set_term_meta( 9, array(
			'layout' => array(
				'breadcrumbs' => array( 'add' => true )
			)
		) );

		$this->assertTrue( $this->breadcrumbs()->has() );
	}

	public function test_custom_post_uses_archive_without_guessing_taxonomy() {
		md_test_set_query( array(
			'is_singular' => true,
			'queried_object_id' => 1,
			'post_type' => 'book_quote'
		) );
		md_test_set_post_type_object( 'book_quote', 'Book Quotes' );
		md_test_set_title( 1, 'A clipped quote title' );
		md_test_set_object_taxonomies( 'book_quote', array( 'bookshelf_topic' ) );
		md_test_set_post_terms( 1, 'bookshelf_topic', array( 7 ) );

		$breadcrumbs = $this->breadcrumbs()->get();

		$this->assertSame( array( 'home', 'archive', 'current' ), array_keys( $breadcrumbs ) );
		$this->assertSame( 'Book Quotes', $breadcrumbs['archive']['label'] );
		$this->assertSame( 'A clipped quote title', $breadcrumbs['current']['label'] );
	}

	public function test_home_label_uses_the_global_breadcrumb_setting() {
		md_test_set_query( array(
			'is_post_type_archive' => true,
			'post_type' => 'book_quote',
			'queried_object' => (object) array(
				'name' => 'book_quote',
				'labels' => (object) array( 'name' => 'Book Quotes' )
			)
		) );
		md_test_set_post_type_object( 'book_quote', 'Book Quotes' );
		md_test_set_option( 'marketers_delight', array(
			'breadcrumbs' => array( 'home_label' => 'Start here' )
		) );

		$breadcrumbs = $this->breadcrumbs()->get();

		$this->assertSame( 'Start here', $breadcrumbs['home']['label'] );
	}

	public function test_simple_link_mode_returns_one_post_type_link() {
		md_test_set_query( array(
			'is_singular' => true,
			'queried_object_id' => 1,
			'post_type' => 'book_quote'
		) );
		md_test_set_post_type_object( 'book_quote', 'Book Quotes' );
		md_test_set_option( 'marketers_delight', array(
			'breadcrumbs' => array(
				'simple_link' => array( 'enable' => true )
			)
		) );

		$breadcrumbs = $this->breadcrumbs()->get();

		$this->assertSame( array( 'archive-link' ), array_keys( $breadcrumbs ) );
		$this->assertSame( '← All Book Quotes', $breadcrumbs['archive-link']['label'] );
		$this->assertSame( 'https://example.test/book_quote/', $breadcrumbs['archive-link']['url'] );
	}

	public function test_simple_link_mode_keeps_the_trail_when_no_archive_exists() {
		md_test_set_query( array(
			'is_page' => true,
			'is_singular' => true,
			'queried_object_id' => 1,
			'post_type' => 'page'
		) );
		md_test_set_post_type_object( 'page', 'Pages', false, true );
		md_test_set_option( 'marketers_delight', array(
			'breadcrumbs' => array(
				'simple_link' => array( 'enable' => true )
			)
		) );

		$breadcrumbs = $this->breadcrumbs()->get();

		$this->assertSame( array( 'home', 'current' ), array_keys( $breadcrumbs ) );
	}

	public function test_native_post_uses_blog_and_category_hierarchy() {
		md_test_set_query( array(
			'is_singular' => true,
			'queried_object_id' => 1,
			'post_type' => 'post'
		) );
		md_test_set_option( 'page_for_posts', 10 );
		md_test_set_title( 10, 'Journal' );
		md_test_set_title( 1, 'A Post' );
		md_test_set_term( 4, 'category', 'Parent' );
		md_test_set_ancestors( 5, 'category', array( 4 ) );
		md_test_set_categories( 1, array(
			(object) array(
				'term_id' => 5,
				'taxonomy' => 'category',
				'name' => 'Child'
			)
		) );

		$breadcrumbs = $this->breadcrumbs()->get();

		$this->assertSame(
			array( 'home', 'blog', 'category-4', 'category', 'current' ),
			array_keys( $breadcrumbs )
		);
		$this->assertSame( 'Journal', $breadcrumbs['blog']['label'] );
		$this->assertSame( 'Parent', $breadcrumbs['category-4']['label'] );
		$this->assertSame( 'Child', $breadcrumbs['category']['label'] );
	}

	public function test_taxonomy_archive_uses_unambiguous_post_type_archive() {
		$term = (object) array(
			'term_id' => 9,
			'taxonomy' => 'bookshelf_topic',
			'name' => 'Liberty'
		);

		md_test_set_query( array(
			'is_tax' => true,
			'queried_object' => $term
		) );
		md_test_set_taxonomy( 'bookshelf_topic', true, array( 'book_quote' ) );
		md_test_set_post_type_object( 'book_quote', 'Book Quotes' );
		md_test_set_term( 8, 'bookshelf_topic', 'Philosophy' );
		md_test_set_ancestors( 9, 'bookshelf_topic', array( 8 ) );

		$breadcrumbs = $this->breadcrumbs()->get();

		$this->assertSame( array( 'home', 'archive', 'term-8', 'term' ), array_keys( $breadcrumbs ) );
		$this->assertSame( 'Book Quotes', $breadcrumbs['archive']['label'] );
		$this->assertSame( 'Philosophy', $breadcrumbs['term-8']['label'] );
		$this->assertSame( 'Liberty', $breadcrumbs['term']['label'] );
	}

	public function test_month_archive_builds_date_hierarchy() {
		md_test_set_query( array(
			'is_date' => true,
			'is_month' => true,
			'query_var' => array(
				'year' => 2026,
				'monthnum' => 8
			)
		) );
		md_test_set_option( 'page_for_posts', 10 );
		md_test_set_option( 'date_format', 'F j, Y' );
		md_test_set_title( 10, 'Journal' );

		$breadcrumbs = $this->breadcrumbs()->get();

		$this->assertSame( array( 'home', 'blog', 'year', 'current' ), array_keys( $breadcrumbs ) );
		$this->assertSame( 2026, $breadcrumbs['year']['label'] );
		$this->assertSame( 'https://example.test/2026/', $breadcrumbs['year']['url'] );
		$this->assertSame( 'August 2026', $breadcrumbs['current']['label'] );
	}

	public function test_combined_stream_date_archive_keeps_stream_breadcrumb_links() {
		md_test_set_query( array(
			'is_date' => true,
			'is_month' => true,
			'post_type' => 'stream',
			'query_var' => array(
				'post_type' => array( 'stream', 'stream_activity' ),
				'year' => 2026,
				'monthnum' => 2
			)
		) );
		md_test_set_post_type_object( 'stream', 'Stream' );
		md_test_set_post_type_object( 'stream_activity', 'Stream Activity', false );

		$breadcrumbs = $this->breadcrumbs()->get();

		$this->assertSame( array( 'home', 'archive', 'year', 'current' ), array_keys( $breadcrumbs ) );
		$this->assertSame( 'Stream', $breadcrumbs['archive']['label'] );
		$this->assertSame( 'https://example.test/2026/?post_type=stream', $breadcrumbs['year']['url'] );
		$this->assertSame( 'February 2026', $breadcrumbs['current']['label'] );
	}

	public function test_custom_post_date_archive_uses_its_post_type_archive() {
		md_test_set_query( array(
			'is_date' => true,
			'is_year' => true,
			'query_var' => array(
				'post_type' => 'book_quote',
				'year' => 2026
			)
		) );
		md_test_set_post_type_object( 'book_quote', 'Book Quotes' );

		$breadcrumbs = $this->breadcrumbs()->get();

		$this->assertSame( array( 'home', 'archive', 'current' ), array_keys( $breadcrumbs ) );
		$this->assertSame( 'Book Quotes', $breadcrumbs['archive']['label'] );
		$this->assertSame( 2026, $breadcrumbs['current']['label'] );
	}

	public function test_pagination_links_the_resolved_endpoint_and_appends_page() {
		md_test_set_query( array(
			'is_search' => true,
			'is_paged' => true,
			'search_query' => 'books',
			'query_var' => array( 'paged' => 3 )
		) );

		$breadcrumbs = $this->breadcrumbs()->get();

		$this->assertSame( array( 'home', 'search', 'page' ), array_keys( $breadcrumbs ) );
		$this->assertSame( 'https://example.test/page/1/', $breadcrumbs['search']['url'] );
		$this->assertSame( 'Page 3', $breadcrumbs['page']['label'] );
		$this->assertSame( '', $breadcrumbs['page']['url'] );
	}

	public function test_integrations_can_replace_the_resolved_trail() {
		md_test_set_query( array(
			'is_singular' => true,
			'queried_object_id' => 1,
			'post_type' => 'book_quote'
		) );
		md_test_set_post_type_object( 'book_quote', 'Book Quotes' );
		md_test_set_filter( 'md_filter_breadcrumbs', array(
			'home' => array( 'label' => 'Home', 'url' => '/' ),
			'book' => array( 'label' => 'Atlas Shrugged', 'url' => '/books/atlas-shrugged/' ),
			'current' => array( 'label' => 'Quote 2 of 3', 'url' => '' )
		) );

		$breadcrumbs = $this->breadcrumbs()->get();

		$this->assertSame( array( 'home', 'book', 'current' ), array_keys( $breadcrumbs ) );
		$this->assertSame( 'Quote 2 of 3', $breadcrumbs['current']['label'] );
	}

	public function test_admin_field_uses_parent_post_type_inheritance() {
		md_test_set_settings_parent( 'book_quote', 'bookshelf' );
		md_test_set_option( 'marketers_delight', array(
			'bookshelf' => array(
				'layout' => array(
					'breadcrumbs' => array( 'add' => true )
				)
			)
		) );
		$fields = new MD_Breadcrumbs_Test_Fields;
		$admin = $this->breadcrumbs();

		$admin->layout_field( $fields, array(
			'post_type' => 'book_quote',
			'is_admin' => true,
			'is_taxonomy' => false,
			'is_term' => false,
			'taxonomy' => ''
		) );

		$this->assertArrayHasKey( 'remove', $fields->args['breadcrumbs']['options'] );
	}

	public function test_child_settings_page_uses_its_owner_instead_of_host_menu_post_type() {
		md_test_set_settings_parent( 'stream_thread', 'stream' );
		md_test_set_option( 'marketers_delight', array(
			'stream' => array(
				'layout' => array(
					'breadcrumbs' => array( 'add' => true )
				)
			)
		) );
		$fields = new MD_Breadcrumbs_Test_Fields;
		$admin = $this->breadcrumbs();

		$admin->layout_field( $fields, array(
			'post_type' => 'stream',
			'settings_post_type' => 'stream_thread',
			'is_admin' => true,
			'is_taxonomy' => false,
			'is_term' => false,
			'taxonomy' => ''
		) );

		$this->assertArrayHasKey( 'remove', $fields->args['breadcrumbs']['options'] );
		$this->assertArrayNotHasKey( 'add', $fields->args['breadcrumbs']['options'] );
	}

	public function test_admin_field_uses_taxonomy_inheritance_on_terms() {
		md_test_set_option( 'marketers_delight', array(
			'book_quote' => array(
				'layout' => array(
					'breadcrumbs' => array( 'add' => true )
				),
				'bookshelf_topic' => array(
					'layout' => array(
						'breadcrumbs' => array( 'remove' => true )
					)
				)
			)
		) );
		$fields = new MD_Breadcrumbs_Test_Fields;
		$admin = $this->breadcrumbs();

		$admin->layout_field( $fields, array(
			'post_type' => 'book_quote',
			'is_admin' => false,
			'is_taxonomy' => false,
			'is_term' => true,
			'taxonomy' => 'bookshelf_topic'
		) );

		$this->assertArrayHasKey( 'add', $fields->args['breadcrumbs']['options'] );
	}

}
