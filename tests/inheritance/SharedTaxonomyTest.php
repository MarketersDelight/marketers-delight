<?php
/**
 * Tests taxonomies shared by several post types (6.2.4): which taxonomies
 * count as shared, the post type a shared term page belongs to, and where
 * its settings come from.
 *
 * @since 6.2.4
 */

class SharedTaxonomyTest extends MD_InheritanceTestCase {

	private function post_type( $post_type, $label, $parent = null ) {
		md_test_set_post_type_object( $post_type, $label );
		$GLOBALS['__test_post_types'][$post_type]->public = true;
		$GLOBALS['__test_post_types'][$post_type]->publicly_queryable = true;
		$GLOBALS['__test_post_types'][$post_type]->exclude_from_search = false;

		if ( $parent )
			$GLOBALS['__test_post_types'][$post_type]->md_settings_parent = $parent;
	}

	private function site( array $post_types ) {
		md_test_set_filter( 'md_post_type_meta', array_merge( array( 'post', 'page' ), $post_types ) );
		$this->post_type( 'post', 'Posts' );

		foreach ( $post_types as $post_type )
			$this->post_type( $post_type, ucfirst( $post_type ) );
	}

	private function term_page( $taxonomy, $first_post_type, $term_id = 7 ) {
		md_test_set_query( array(
			'is_category' => $taxonomy === 'category',
			'is_tax' => $taxonomy !== 'category',
			'is_archive' => true,
			'queried_object' => (object) array( 'term_id' => $term_id, 'taxonomy' => $taxonomy ),
			'queried_object_id' => $term_id,
			'post_type' => $first_post_type
		) );
	}

	public function test_a_taxonomy_on_two_md_post_types_is_shared() {
		$this->site( array( 'stream' ) );
		md_test_set_taxonomy( 'category', true, array( 'post', 'stream' ), true );

		$this->assertTrue( md_is_shared_taxonomy( 'category' ) );
	}

	public function test_a_taxonomy_on_one_post_type_is_not_shared() {
		$this->site( array( 'stream' ) );
		md_test_set_taxonomy( 'category', true, array( 'post' ), true );

		$this->assertFalse( md_is_shared_taxonomy( 'category' ) );
		$this->assertFalse( md_is_shared_taxonomy( '' ) );
		$this->assertFalse( md_is_shared_taxonomy( 'missing' ) );
	}

	public function test_a_child_post_type_does_not_make_a_taxonomy_shared() {
		$this->site( array( 'book' ) );
		$this->post_type( 'highlight', 'Highlights', 'book' );
		md_test_set_filter( 'md_post_type_meta', array( 'post', 'page', 'book', 'highlight' ) );
		md_test_set_taxonomy( 'topic', true, array( 'highlight', 'book' ), true );

		$this->assertFalse( md_is_shared_taxonomy( 'topic' ) );
	}

	public function test_the_home_post_type_is_the_first_one_registered() {
		md_test_set_taxonomy( 'dropin_category', true, array( 'post', 'download', 'stream' ), true );

		$this->assertSame( 'post', md_taxonomy_post_type( 'dropin_category' ) );
		$this->assertFalse( md_taxonomy_post_type( 'missing' ) );
	}

	public function test_a_shared_term_page_belongs_to_its_home_post_type() {
		$this->site( array( 'stream' ) );
		md_test_set_taxonomy( 'dropin_category', true, array( 'post', 'stream' ), true );
		$this->term_page( 'dropin_category', 'stream' );

		$this->assertSame( 'post', md_get_post_type() );
	}

	public function test_a_post_on_a_shared_term_page_keeps_its_own_post_type() {
		$this->site( array( 'stream' ) );
		md_test_set_taxonomy( 'dropin_category', true, array( 'post', 'stream' ), true );
		$this->term_page( 'dropin_category', 'stream' );

		$this->assertSame( 'stream', md_get_post_type( 12 ) );
	}

	public function test_an_unshared_term_page_is_unchanged() {
		$this->site( array( 'stream' ) );
		md_test_set_taxonomy( 'stream_category', true, array( 'stream' ), true );
		$this->term_page( 'stream_category', 'stream' );

		$this->assertSame( 'stream', md_get_post_type() );
	}

	public function test_an_empty_term_page_uses_the_home_post_type() {
		md_test_set_taxonomy( 'dropin_category', true, array( 'post', 'stream' ), true );
		$this->term_page( 'dropin_category', '' );

		$this->assertSame( 'post', md_get_post_type() );
	}

	public function test_shared_term_settings_read_the_home_post_type_whatever_post_comes_first() {
		$this->site( array( 'stream' ) );
		md_test_set_taxonomy( 'dropin_category', true, array( 'post', 'stream' ), true );
		md_test_set_option( 'marketers_delight', array(
			'post' => array( 'loop' => array( 'columns' => 3 ), 'dropin_category' => array( 'loop' => array( 'featured' => 1 ) ) ),
			'stream' => array( 'loop' => array( 'columns' => 1 ) )
		) );
		$this->term_page( 'dropin_category', 'stream' );

		$this->assertSame( 3, md_value( array( 'key' => array( 'loop', 'columns' ), 'page' => true ) ) );
		$this->assertSame( 1, md_value( array( 'key' => array( 'loop', 'featured' ), 'page' => true ) ) );
	}

	public function test_a_term_page_narrowed_by_post_type_is_still_the_terms_page() {
		$this->site( array( 'stream' ) );
		md_test_set_taxonomy( 'dropin_category', true, array( 'post', 'stream' ), true );
		md_test_set_option( 'marketers_delight', array(
			'post' => array( 'archives_title' => 'Blog archive', 'loop' => array( 'columns' => 3 ) ),
			'stream' => array( 'loop' => array( 'columns' => 1 ) )
		) );
		md_test_set_term_meta( 7, array( 'loop' => array( 'columns' => 2 ) ) );
		md_test_set_term( 7, 'dropin_category', 'Share' );
		$this->term_page( 'dropin_category', 'stream' );
		md_test_set_query( array( 'is_post_type_archive' => true, 'query_var' => array( 'post_type' => 'stream' ) ) );

		$this->assertSame( array( 'value' => 2, 'source' => 'term' ), md_value( array( 'key' => array( 'loop', 'columns' ), 'page' => true, 'source' => true ) ) );
		$this->assertSame( array( 'stream' ), md_loop_post_types( array( 'post_type' => 'post' ) ) );
	}

	public function test_by_default_an_item_from_another_post_type_uses_the_page_settings() {
		$this->site( array( 'stream' ) );
		md_test_set_option( 'marketers_delight', array( 'stream' => array( 'loop' => array( 'content' => 'full' ) ) ) );
		md_test_set_query( array( 'post_type' => 'stream' ) );

		$loop = md_loop_item( array( 'post_type' => 'post', 'content' => 'excerpt' ) );

		$this->assertSame( 'post', $loop['post_type'] );
		$this->assertSame( 'excerpt', $loop['content'] );
	}

	public function test_a_nested_loop_takes_the_page_layout() {
		$this->site( array( 'stream' ) );
		md_test_set_option( 'marketers_delight', array( 'stream' => array( 'loop' => array( 'columns' => 4, 'columns_mobile' => 2 ) ) ) );

		$loop = md_get_loop( array(
			'query' => array( 'post_type' => 'stream' ),
			'page_layout' => array( 'columns' => 2, 'columns_mobile' => 1, 'has_sidebar' => true )
		) );

		$this->assertSame( 2, $loop['columns'] );
		$this->assertSame( 1, $loop['columns_mobile'] );
		$this->assertTrue( $loop['is_slim'] );
	}

	public function test_a_template_keeps_the_settings_it_declares_as_its_own() {
		$this->site( array( 'book' ) );
		md_test_set_filter( 'md_filter_loops', array(
			'article' => array( 'name' => 'Article' ),
			'shelf' => array( 'name' => 'Shelf', 'own_settings' => array( 'columns', 'columns_mobile' ) )
		) );
		md_test_set_option( 'marketers_delight', array( 'book' => array( 'loop' => array( 'loop' => 'shelf', 'columns' => 5, 'columns_mobile' => 3 ) ) ) );

		$loop = md_get_loop( array(
			'query' => array( 'post_type' => 'book' ),
			'page_layout' => array( 'columns' => 1, 'columns_mobile' => 1, 'has_sidebar' => true )
		) );

		$this->assertSame( 5, $loop['columns'] );
		$this->assertSame( 3, $loop['columns_mobile'] );
		$this->assertTrue( $loop['has_sidebar'] );
	}

	public function test_loop_post_types_come_from_the_query_then_a_shared_term_page() {
		$this->site( array( 'stream' ) );
		md_test_set_taxonomy( 'category', true, array( 'post', 'stream' ), true );

		$this->assertSame( array( 'post', 'stream' ), md_loop_post_types( array( 'post_type' => 'post', 'query' => array( 'post_type' => array( 'post', 'stream' ) ) ) ) );
		$this->assertSame( array( 'post' ), md_loop_post_types( array( 'post_type' => 'post' ) ) );

		$this->term_page( 'category', 'post' );

		$this->assertSame( array( 'post', 'stream' ), md_loop_post_types( array( 'post_type' => 'post' ) ) );

		md_test_set_query( array( 'query_var' => array( 'post_type' => 'stream' ) ) );

		$this->assertSame( array( 'stream' ), md_loop_post_types( array( 'post_type' => 'post' ) ) );
	}

	public function test_a_mixed_query_groups_by_post_type_and_reads_the_first_post_types_settings() {
		$this->site( array( 'stream' ) );
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'loop' => array( 'columns' => 2 ) ) ) );

		$loop = md_get_loop( array( 'query' => array( 'post_type' => array( 'post', 'stream' ) ), 'loop_type' => 'post_type' ) );

		$this->assertSame( 'post', $loop['post_type'] );
		$this->assertSame( 2, $loop['columns'] );
		$this->assertTrue( $loop['by_type'] );
		$this->assertStringContainsString( 'category-posts', $loop['categories_classes'] );
	}

	public function test_one_post_type_never_groups_by_post_type() {
		$this->site( array( 'stream' ) );

		$loop = md_get_loop( array( 'query' => array( 'post_type' => 'post' ), 'loop_type' => 'post_type' ) );

		$this->assertArrayNotHasKey( 'by_type', $loop );
	}

	public function test_own_look_turns_on_only_when_post_types_can_mix() {
		$this->site( array( 'stream' ) );

		$mixed = md_get_loop( array( 'query' => array( 'post_type' => array( 'post', 'stream' ) ), 'mixed' => array( 'own_look' => true ) ) );
		$single = md_get_loop( array( 'query' => array( 'post_type' => 'post' ), 'mixed' => array( 'own_look' => true ) ) );

		$this->assertTrue( $mixed['own_look'] );
		$this->assertStringContainsString( 'loop-post', $mixed['loop_classes'] );
		$this->assertArrayNotHasKey( 'own_look', $single );
	}

	public function test_a_post_from_another_post_type_gets_that_post_types_own_loop() {
		$this->site( array( 'stream' ) );
		md_test_set_option( 'marketers_delight', array( 'stream' => array( 'loop' => array( 'loop' => 'timeline', 'columns' => 1 ) ) ) );
		md_test_set_query( array( 'post_type' => 'stream' ) );

		$loop = md_loop_own_look( array( 'post_type' => 'post', 'loop' => 'article', 'heading_offset' => 1 ) );

		$this->assertSame( 'stream', $loop['post_type'] );
		$this->assertSame( 'timeline', $loop['loop'] );
		$this->assertTrue( $loop['own_wrap'] );
		$this->assertSame( 1, $loop['heading_offset'] );
		$this->assertStringContainsString( 'loop-timeline', $loop['loop_classes'] );
		$this->assertStringContainsString( 'nested', $loop['loop_classes'] );
		$this->assertSame( 1, $loop['columns'] );
	}

	public function test_a_post_of_the_pages_post_type_keeps_the_page_loop() {
		$this->site( array( 'stream' ) );
		md_test_set_query( array( 'post_type' => 'post' ) );
		$page = array( 'post_type' => 'post', 'loop' => 'article', 'own_look' => true );

		$this->assertSame( $page, md_loop_own_look( $page ) );
	}

	public function test_columns_style_only_when_there_are_columns() {
		$this->assertSame( '', md_loop_columns_style( array( 'columns' => 1 ) ) );
		$this->assertSame( ' style="--md-loop-columns: 3; --md-loop-columns-mobile: 2"', md_loop_columns_style( array( 'columns' => 3, 'columns_mobile' => 2 ) ) );
	}


}
