<?php
/**
 * Tests md_get_loop() (features/loop/functions.php) — the
 * shortcode/manual-loop path of the Loop inheritance cascade. Unlike
 * loop_query_vars(), this merges whole per-tier field arrays together
 * (array_merge($post_type, $tax, $single)) rather than resolving one field
 * at a time, and covers the wider field set (columns, featured, etc).
 *
 * Plain, unconditional cascade for every field except the four term-ID
 * fields (category_include/exclude, include_cats/exclude_cats), which are
 * resolved separately via md_module( ..., array( 'inherit_post_type' =>
 * false ) ) since a term ID inherited from a broader tier may not exist in
 * the narrower tier's own context at all.
 *
 * @since 6.0
 */

class GetLoopTest extends MD_InheritanceTestCase {

	private function set_option_loop( $post_type_loop = array(), $taxonomy_loop = array() ) {
		$post = array( 'loop' => $post_type_loop );

		if ( $taxonomy_loop )
			$post['category'] = array( 'loop' => $taxonomy_loop );

		md_test_set_option( 'marketers_delight', array( 'post' => $post ) );
	}

	private function set_taxonomy_query( $term_id = 42, $post_type = 'post' ) {
		md_test_set_query( array(
			'is_category' => true,
			'queried_object' => (object) array( 'term_id' => $term_id, 'taxonomy' => 'category' ),
			'queried_object_id' => $term_id,
			'post_type' => $post_type,
		) );
	}

	private function register_collection_loop() {
		md_test_set_filter( 'md_filter_loops', array(
			'article' => array(
				'name' => 'Article'
			),
			'collection' => array(
				'name' => 'Collection',
				'single' => true,
				'archive_style' => 'plain',
				'style_target' => 'group',
				'defaults' => array(
					'columns' => 5,
					'display' => 'grid'
				),
				'args' => array(
					'token' => 'registered'
				)
			)
		) );
	}

	// A post-type-tier-only field (columns) now cascades onto a taxonomy
	// archive unconditionally -- no render-mode gating (the bug fix: this
	// used to get silently stripped back to a hardcoded default of 1).

	public function test_post_type_only_columns_cascades_onto_taxonomy_archive() {
		$this->set_option_loop( array( 'columns' => 3 ) );
		$this->set_taxonomy_query();

		$loop = md_get_loop();

		$this->assertSame( 3, $loop['columns'] );
	}

	// An explicit taxonomy-tier override still wins over the post-type tier.

	public function test_explicit_taxonomy_tier_columns_overrides_post_type_tier() {
		$this->set_option_loop( array( 'columns' => 3 ), array( 'columns' => 5 ) );
		$this->set_taxonomy_query();

		$loop = md_get_loop();

		$this->assertSame( 5, $loop['columns'] );
	}

	// posts_per_page cascades the same way regardless of loop_type or
	// whether the term has children -- no more coupling between "does this
	// render as a category grid" and "does this field inherit."

	public function test_post_type_only_posts_per_page_cascades_regardless_of_children() {
		$this->set_option_loop( array( 'loop_type' => 'category', 'posts_per_page' => 6 ) );
		$this->set_taxonomy_query();
		md_test_set_term_children( 42, 'category', array() );

		$loop = md_get_loop();

		$this->assertArrayNotHasKey( 'by_category', $loop );
		$this->assertSame( 6, $loop['posts_per_page'] );
	}

	/**
	 * Term-ID fields (category_exclude here): a post-type-tier value does
	 * NOT leak onto a taxonomy archive -- an ID list meant for the post
	 * type's own top-level archive may not even reference this term's
	 * siblings/children.
	 */

	public function test_post_type_tier_category_exclude_does_not_leak_onto_taxonomy_archive() {
		$this->set_option_loop( array( 'category_exclude' => '12,15' ) );
		$this->set_taxonomy_query();

		$loop = md_get_loop();

		$this->assertNull( $loop['category_exclude'] );
	}

	// An explicit taxonomy-tier value for a term-ID field is respected.

	public function test_explicit_taxonomy_tier_category_exclude_is_respected() {
		$this->set_option_loop( array( 'category_exclude' => '12,15' ), array( 'category_exclude' => '99' ) );
		$this->set_taxonomy_query();

		$loop = md_get_loop();

		$this->assertSame( '99', $loop['category_exclude'] );
	}

	// An explicit term-level (term meta) value for a term-ID field wins over
	// both the taxonomy and post-type tiers.

	public function test_term_meta_category_include_wins_over_taxonomy_and_post_type() {
		$this->set_option_loop( array( 'category_include' => '1' ), array( 'category_include' => '2' ) );
		$this->set_taxonomy_query();
		md_test_set_term_meta( 42, array( 'loop' => array( 'category_include' => '3' ) ) );

		$loop = md_get_loop();

		$this->assertSame( '3', $loop['category_include'] );
	}

	// A loop template may provide presentation defaults, but they sit below
	// every existing inheritance tier and below call-site arguments.

	public function test_registered_defaults_preserve_the_inheritance_hierarchy() {
		$this->register_collection_loop();
		$this->set_option_loop(
			array( 'loop' => 'collection', 'display' => 'post-type' ),
			array( 'display' => 'taxonomy' )
		);
		$this->set_taxonomy_query();
		md_test_set_term_meta( 42, array( 'loop' => array( 'display' => 'term' ) ) );

		$loop = md_get_loop();

		$this->assertSame( 'term', $loop['display'] );
		$this->assertSame( 5, $loop['columns'] );

		$loop = md_get_loop( array( 'display' => 'manual' ) );

		$this->assertSame( 'manual', $loop['display'] );
	}

	// Manual loops resolve their post type from the query, retain it for
	// templates/classes, and still inherit that post type's Loop settings.

	public function test_manual_query_retains_its_resolved_post_type() {
		$this->register_collection_loop();
		md_test_set_option( 'marketers_delight', array(
			'download' => array(
				'loop' => array(
					'loop' => 'collection',
					'columns' => 3
				)
			)
		) );

		$loop = md_get_loop( array(
			'query' => array( 'post_type' => 'download' )
		) );

		$this->assertSame( 'download', $loop['post_type'] );
		$this->assertSame( 'collection', $loop['loop'] );
		$this->assertSame( 3, $loop['columns'] );
		$this->assertSame( 'grid', $loop['display'] );
		$this->assertSame( 'registered', $loop['template']['token'] );
		$this->assertStringContainsString( 'loop-download', $loop['loop_classes'] );
	}

	public function test_date_grouping_cascades_onto_a_taxonomy_post_listing() {
		$this->set_option_loop( array( 'date' => array( 'group' => true ) ) );
		$this->set_taxonomy_query();

		$loop = md_get_loop();

		$this->assertTrue( $loop['by_date'] );
	}

	public function test_category_grouping_takes_precedence_over_date_grouping() {
		$this->set_option_loop( array(
			'loop_type' => 'category_posts',
			'date' => array( 'group' => true )
		) );
		$this->set_taxonomy_query();
		md_test_set_term_children( 42, 'category', array( 43 ) );

		$loop = md_get_loop();

		$this->assertTrue( $loop['by_category'] );
		$this->assertArrayNotHasKey( 'by_date', $loop );
	}

	public function test_manual_date_query_forces_chronological_results() {
		$loop = md_get_loop( array(
			'query' => array(
				'post_type' => 'post',
				'orderby' => 'rand'
			),
			'date' => array( 'group' => true )
		) );

		$this->assertTrue( $loop['by_date'] );
		$this->assertSame( 'date', $loop['query']['orderby'] );
		$this->assertSame( 1, $loop['query']['ignore_sticky_posts'] );
	}

	public function test_singular_loop_does_not_create_date_groups() {
		md_test_set_query( array( 'is_singular' => true ) );

		$loop = md_get_loop( array( 'date' => array( 'group' => true ) ) );

		$this->assertArrayNotHasKey( 'by_date', $loop );
	}

	public function test_loop_date_returns_a_stable_month_key_and_label() {
		md_test_set_post_time( strtotime( '2026-08-26 12:00:00 UTC' ) );

		$date = md_get_loop_date();

		$this->assertSame( '2026-08', $date['month'] );
		$this->assertSame( 'August 2026', $date['label'] );
		$this->assertSame( 'https://example.test/2026/08/', $date['url'] );
	}

	public function test_loop_date_link_preserves_custom_post_type_and_taxonomy() {
		md_test_set_post_time( strtotime( '2026-08-26 12:00:00 UTC' ) );
		md_test_set_taxonomy( 'stream_categories', true, array( 'stream' ) );
		$GLOBALS['__test_taxonomies']['stream_categories']->query_var = 'stream_categories';
		md_test_set_query( array(
			'is_tax' => true,
			'post_type' => 'stream',
			'query_var' => array( 'post_type' => 'stream' ),
			'queried_object' => (object) array(
				'taxonomy' => 'stream_categories',
				'slug' => 'notes'
			)
		) );

		$date = md_get_loop_date();

		$this->assertSame( 'https://example.test/2026/08/?post_type=stream&stream_categories=notes', $date['url'] );
	}

	public function test_loop_date_does_not_link_the_current_month_archive() {
		md_test_set_post_time( strtotime( '2026-08-26 12:00:00 UTC' ) );
		md_test_set_query( array(
			'is_month' => true,
			'query_var' => array(
				'year' => 2026,
				'monthnum' => 8
			)
		) );

		$date = md_get_loop_date();

		$this->assertSame( '', $date['url'] );
	}

	public function test_loop_classes_use_dynamic_columns_without_numbered_utility_classes() {
		$this->register_collection_loop();

		$loop = md_get_loop( array(
			'loop' => 'collection',
			'columns' => 7
		) );

		$this->assertStringContainsString( 'columns', $loop['loop_classes'] );
		$this->assertStringContainsString( 'has-mobile-columns', $loop['loop_classes'] );
		$this->assertStringNotContainsString( 'columns-7', $loop['loop_classes'] );

		$loop = md_get_loop( array(
			'loop' => 'collection',
			'columns' => 2
		) );

		$this->assertStringNotContainsString( 'has-mobile-columns', $loop['loop_classes'] );
		$this->assertStringNotContainsString( 'columns-2', $loop['loop_classes'] );
	}

	// A registered archive style decorates the collection itself without
	// replacing the global/post-type/single content-style inheritance chain.

	public function test_registered_archive_style_does_not_replace_singular_inheritance() {
		$this->register_collection_loop();
		md_test_set_option( 'marketers_delight', array(
			'colors' => array( 'design' => 'box' ),
			'post' => array( 'loop' => array( 'loop' => 'collection' ) )
		) );
		md_test_set_query( array(
			'is_archive' => true,
			'post_type' => 'post'
		) );

		$this->assertSame( 'plain', md_get_loop()['style'] );

		md_test_set_query( array(
			'is_archive' => false,
			'is_singular' => true,
			'queried_object_id' => 1
		) );

		$this->assertSame( 'box', md_get_loop()['style'] );
		$this->assertSame( 'border', md_get_loop( array( 'style' => 'border' ) )['style'] );
	}

}
