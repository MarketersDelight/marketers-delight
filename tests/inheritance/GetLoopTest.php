<?php
/**
 * Tests md_get_loop() (functions/loop-functions.php:273-395) — the
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

}
