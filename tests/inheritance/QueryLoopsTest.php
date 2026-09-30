<?php
/**
 * Query Loop context, filters, sorting, and independent pagination.
 */

if ( ! class_exists( 'WP_Query' ) ) {
	class WP_Query {
		public $query;
		public $max_num_pages = 4;

		public function __construct( $query = array() ) {
			$this->query = $query;
		}

		public function get( $key ) {
			return $this->query[$key] ?? null;
		}
	}
}

if ( ! class_exists( 'md_fields_data' ) ) {
	class md_fields_data {
		public $values = array( 'featured_image' => array() );
	}
}

if ( ! function_exists( 'wp_unslash' ) ) {
	function wp_unslash( $value ) {
		return $value;
	}
}

if ( ! function_exists( 'sanitize_title' ) ) {
	function sanitize_title( $value ) {
		return strtolower( trim( $value ) );
	}
}

if ( ! function_exists( 'esc_url' ) ) {
	function esc_url( $url ) {
		return $url;
	}
}

if ( ! function_exists( 'esc_attr__' ) ) {
	function esc_attr__( $value, $domain = 'default' ) {
		return $value;
	}
}

require_once dirname( __DIR__, 2 ) . '/features/loop/query-loops.php';

class QueryLoopsTest extends MD_InheritanceTestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['__test_post_types']['post'] = (object) array(
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'labels' => (object) array( 'name' => 'Blog' )
		);
		$GLOBALS['__test_object_taxonomies']['post'] = array( 'category' );
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'loop' => array( 'posts_per_page' => 12, 'orderby' => 'date' )
		) ) );
	}

	public function test_current_author_and_latest_are_distinct_contexts() {
		md_test_set_query( array( 'is_author' => true, 'queried_object_id' => 7 ) );

		$this->assertSame( array( 'author' => 7 ), md_query_loop_context( 'current' ) );
		$this->assertSame( array(), md_query_loop_context( 'latest' ) );
		$this->assertNull( md_query_loop_context( 'search' ) );
	}

	public function test_taxonomy_without_terms_filters_to_assigned_posts() {
		$query = md_query_loop_query( array( 'source' => 'post', 'taxonomy' => 'category' ), 'blog' );

		$this->assertSame( array( 'taxonomy' => 'category', 'operator' => 'EXISTS' ), $query->query['tax_query'][0] );
	}

	public function test_term_slugs_filter_the_selected_taxonomy() {
		$query = md_query_loop_query( array(
			'source' => 'post',
			'taxonomy' => 'category',
			'terms' => 'news, updates'
		), 'blog' );

		$this->assertSame( array( 'taxonomy' => 'category', 'field' => 'slug', 'terms' => array( 'news', 'updates' ) ), $query->query['tax_query'][0] );
		$this->assertNull( md_query_loop_query( array( 'source' => 'post', 'taxonomy' => 'unrelated' ), 'blog' ) );
	}

	public function test_search_uses_relevance_until_sort_is_selected() {
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'loop' => array( 'date' => array( 'group' => true ) )
		) ) );
		md_test_set_query( array( 'is_search' => true, 'query_var' => array( 's' => 'hello' ) ) );
		$query = md_query_loop_query( array( 'source' => 'post' ), 'blog' );
		$this->assertArrayNotHasKey( 'orderby', $query->query );

		$query = md_query_loop_query( array( 'source' => 'post', 'orderby' => 'title' ), 'blog' );
		$this->assertSame( 'title', $query->query['orderby'] );
	}

	public function test_random_source_order_falls_back_to_date_when_paginated() {
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'loop' => array( 'orderby' => 'rand' )
		) ) );
		$query = md_query_loop_query( array( 'source' => 'post', 'pagination' => 'page_numbers' ), 'blog' );

		$this->assertSame( 'date', $query->query['orderby'] );
	}



	public function test_previous_next_pagination_has_no_numbered_links() {
		$query = new WP_Query( array( 'paged' => 2 ) );
		ob_start();
		md_query_loop_pagination( $query, 'blog', 'prev_next' );
		$html = ob_get_clean();

		$this->assertStringContainsString( 'Previous', $html );
		$this->assertStringContainsString( 'Next', $html );
		$this->assertStringNotContainsString( '<ul', $html );
		$this->assertStringNotContainsString( 'page-numbers current', $html );
	}
}
