<?php
/**
 * Tests md_get_loop() (features/loop/functions.php) — the
 * shortcode/manual-loop path of the Loop inheritance cascade and its
 * explicit defaults option. Unlike
 * loop_query_vars(), this recursively replaces whole per-tier field arrays
 * rather than resolving one field at a time, and covers the wider field set
 * (columns, featured, etc).
 *
 * Plain, unconditional cascade with recursive replacement so sibling options
 * continue to inherit. The four term-ID fields (category_include/exclude and
 * include_cats/exclude_cats) are resolved separately via md_module( ...,
 * array( 'inherit_post_type' =>
 * false ) ) since a term ID inherited from a broader tier may not exist in
 * the narrower tier's own context at all.
 *
 * @since 6.0
 */

class GetLoopTest extends MD_InheritanceTestCase {

	public function test_mobile_columns_can_be_used_with_one_desktop_column() {
		$classes = md_loop_classes( array(
			'post_type' => 'post',
			'loop' => 'article',
			'loop_type' => '',
			'style' => 'plain',
			'style_target' => 'entry',
			'columns' => 1,
			'columns_mobile' => 2
		) );

		$this->assertContains( 'columns', explode( ' ', $classes['loop'] ) );
		$this->assertNotContains( 'row', explode( ' ', $classes['loop'] ) );
	}

	public function test_category_posts_box_style_puts_the_box_on_the_category_section_only() {
		foreach ( array( 'list' => 'group', 'article' => 'entry' ) as $loop => $target ) {
			$classes = md_loop_classes( array(
				'post_type' => 'post',
				'loop' => $loop,
				'loop_type' => 'category_posts',
				'by_category' => true,
				'style' => 'box',
				'style_target' => $target,
				'columns' => 1
			) );
			$category = explode( ' ', $classes['category'] );
			$inner = explode( ' ', $classes['loop'] );

			$this->assertContains( 'box-group', $category, $loop );
			$this->assertNotContains( 'box-group', $inner, $loop );
			$this->assertNotContains( 'box-entry', $inner, $loop );
			$this->assertNotContains( 'box-style', $inner, $loop );
			$this->assertContains( 'plain-style', $inner, $loop );
			$this->assertContains( 'box-style', explode( ' ', $classes['categories'] ), $loop );
		}
	}

	public function test_category_posts_setting_without_category_sections_keeps_the_posts_boxed() {
		$this->set_option_loop( array( 'loop_type' => 'category_posts' ) );
		$this->set_taxonomy_query();

		$loop = md_get_loop();

		$this->assertArrayNotHasKey( 'by_category', $loop );
		$this->assertStringContainsString( 'box-entry', $loop['loop_classes'] );
		$this->assertStringNotContainsString( 'plain-style', $loop['loop_classes'] );
		$this->assertSame( 'entry', $loop['category_classes'] );
	}

	public function test_category_posts_border_and_plain_styles_keep_their_own_loop_classes() {
		foreach ( array( 'border', 'plain' ) as $style ) {
			$classes = md_loop_classes( array(
				'post_type' => 'post',
				'loop' => 'list',
				'loop_type' => 'category_posts',
				'style' => $style,
				'style_target' => 'group',
				'columns' => 1
			) );

			$this->assertContains( "{$style}-style", explode( ' ', $classes['loop'] ), $style );
			$this->assertContains( "{$style}-group", explode( ' ', $classes['loop'] ), $style );
			$this->assertNotContains( 'box-group', explode( ' ', $classes['category'] ), $style );
		}
	}

	public function test_category_view_still_boxes_the_category_section() {
		$classes = md_loop_classes( array(
			'post_type' => 'post',
			'loop' => 'article',
			'loop_type' => 'category',
			'style' => 'box',
			'style_target' => 'group',
			'columns' => 1
		) );

		$this->assertContains( 'box-group', explode( ' ', $classes['category'] ) );
	}

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

	public function test_manual_query_can_use_explicit_loop_defaults_instead_of_source_settings() {
		$this->register_collection_loop();
		md_test_set_option( 'marketers_delight', array(
			'download' => array( 'loop' => array( 'loop' => 'collection', 'columns' => 4, 'content' => 'full' ) )
		) );

		$loop = md_get_loop( array(
			'query' => array( 'post_type' => 'download' ),
			'loop_defaults' => array( 'loop' => 'article', 'columns' => 1, 'content' => 'excerpt' )
		) );

		$this->assertSame( 'article', $loop['loop'] );
		$this->assertSame( 1, $loop['columns'] );
		$this->assertSame( 'excerpt', $loop['content'] );
		$this->assertSame( 'download', $loop['post_type'] );
	}

	public function test_date_grouping_cascades_onto_a_taxonomy_post_listing() {
		$this->set_option_loop( array( 'loop_type' => 'month' ) );
		$this->set_taxonomy_query();

		$loop = md_get_loop();

		$this->assertTrue( $loop['by_date'] );
		$this->assertSame( 'entry', $loop['style_target'] );
	}

	public function test_taxonomy_loop_type_overrides_month_grouping() {
		$this->set_option_loop(
			array( 'loop_type' => 'month' ),
			array( 'loop_type' => 'post_listing' )
		);
		$this->set_taxonomy_query();

		$loop = md_get_loop();

		$this->assertArrayNotHasKey( 'by_date', $loop );
	}

	public function test_term_level_top_level_zero_override_is_not_filtered_out() {
		$this->set_option_loop( array( 'future_toggle' => true ) );
		$this->set_taxonomy_query();
		md_test_set_term_meta( 42, array( 'loop' => array( 'future_toggle' => 0 ) ) );

		$loop = md_get_loop();

		$this->assertSame( 0, $loop['future_toggle'] );
	}

	public function test_category_loop_type_does_not_create_month_groups() {
		$this->set_option_loop( array(
			'loop_type' => 'category_posts'
		) );
		$this->set_taxonomy_query();
		md_test_set_term_children( 42, 'category', array( 43 ) );

		$loop = md_get_loop();

		$this->assertTrue( $loop['by_category'] );
		$this->assertArrayNotHasKey( 'by_date', $loop );
	}

	public function test_category_posts_box_style_belongs_to_the_category_section() {
		$this->set_option_loop( array( 'loop_type' => 'category_posts' ) );
		$this->set_taxonomy_query();
		md_test_set_term_children( 42, 'category', array( 43 ) );

		$loop = md_get_loop();

		$this->assertSame( 'entry', $loop['style_target'] );
		$this->assertStringContainsString( 'plain-entry', $loop['loop_classes'] );
		$this->assertStringNotContainsString( 'box-entry', $loop['loop_classes'] );
		$this->assertSame( 'entry box-group', $loop['category_classes'] );
	}

	public function test_category_overview_keeps_style_on_category_result_items() {
		$this->set_option_loop( array( 'loop_type' => 'category' ) );
		$this->set_taxonomy_query();
		md_test_set_term_children( 42, 'category', array( 43 ) );

		$loop = md_get_loop();

		$this->assertSame( 'group', $loop['style_target'] );
		$this->assertSame( 'entry box-group', $loop['category_classes'] );
	}

	public function test_category_posts_keeps_an_explicit_loop_group_style_target() {
		$this->register_collection_loop();
		$this->set_option_loop( array( 'loop' => 'collection', 'loop_type' => 'category_posts' ) );
		$this->set_taxonomy_query();
		md_test_set_term_children( 42, 'category', array( 43 ) );

		$loop = md_get_loop();

		$this->assertSame( 'group', $loop['style_target'] );
		$this->assertStringContainsString( 'plain-group', $loop['loop_classes'] );
		$this->assertSame( 'entry', $loop['category_classes'] );
	}

	public function test_manual_date_query_forces_chronological_results() {
		$loop = md_get_loop( array(
			'query' => array(
				'post_type' => 'post',
				'orderby' => 'rand'
			),
			'loop_type' => 'month'
		) );

		$this->assertTrue( $loop['by_date'] );
		$this->assertSame( 'date', $loop['query']['orderby'] );
		$this->assertSame( 1, $loop['query']['ignore_sticky_posts'] );
	}

	public function test_singular_loop_does_not_create_date_groups() {
		md_test_set_query( array( 'is_singular' => true ) );

		$loop = md_get_loop( array( 'loop_type' => 'month' ) );

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
