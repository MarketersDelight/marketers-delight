<?php
/**
 * Tests md_api::loop_query_vars() (api/api.php:430-~460) — the main-archive-query
 * path of the Loop inheritance cascade (global default -> post type -> taxonomy
 * -> term), invoked directly via reflection since it's protected. Only
 * posts_per_page/order/orderby flow through this function; the wider Loop field
 * set is covered by GetLoopTest instead.
 *
 * Plain, unconditional cascade -- no render-mode gating. A taxonomy tab left
 * blank always inherits the post-type tier's resolved value, regardless of
 * loop_type or whether the term has children (that used to be gated by an
 * escape hatch; removed as a bug fix, see FINDINGS.md).
 *
 * @since 6.0
 */

class LoopQueryVarsTest extends MD_InheritanceTestCase {

	private function query_vars( $taxonomy = '', $term_id = 0 ) {
		$api = $this->make_api( 'post', 'category' );
		$wp = new stdClass;
		$wp->query_vars = array();

		$this->call( $api, 'loop_query_vars', array( $wp, $taxonomy, $term_id ) );

		return $wp->query_vars;
	}

	// With nothing set anywhere, posts_per_page falls all the way back to the
	// global "posts_per_page" WP option; order/orderby are left unset since
	// their $default is null.

	public function test_all_tiers_unset_uses_global_wp_option_default() {
		md_test_set_option( 'posts_per_page', 8 );

		$vars = $this->query_vars();

		$this->assertSame( 8, $vars['posts_per_page'] );
		$this->assertArrayNotHasKey( 'order', $vars );
	}

	// Post-type tier alone wins when no taxonomy/term context is present.

	public function test_post_type_tier_wins_with_no_taxonomy_or_term() {
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'loop' => array( 'posts_per_page' => 6 ) ) ) );

		$vars = $this->query_vars();

		$this->assertSame( 6, $vars['posts_per_page'] );
	}

	// An explicit taxonomy-tier override wins over the post-type tier.

	public function test_explicit_taxonomy_tier_value_overrides_post_type_tier() {
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'loop' => array( 'posts_per_page' => 6 ),
			'category' => array( 'loop' => array( 'posts_per_page' => 9 ) )
		) ) );

		$vars = $this->query_vars( 'category' );

		$this->assertSame( 9, $vars['posts_per_page'] );
	}

	// A blank taxonomy tab inherits the post-type tier's value -- the bug fix:
	// this used to require a specific loop_type + child-term combination to
	// happen at all; now it's the plain, unconditional cascade.

	public function test_unset_taxonomy_tier_inherits_post_type_value() {
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'loop' => array( 'posts_per_page' => 6 ) ) ) );

		$vars = $this->query_vars( 'category' );

		$this->assertSame( 6, $vars['posts_per_page'] );
	}

	// Term meta wins over every option-table tier when present.

	public function test_term_meta_wins_over_all_option_tiers() {
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'loop' => array( 'posts_per_page' => 6 ),
			'category' => array( 'loop' => array( 'posts_per_page' => 9 ) )
		) ) );
		md_test_set_term_meta( 42, array( 'loop' => array( 'posts_per_page' => 3 ) ) );

		$vars = $this->query_vars( 'category', 42 );

		$this->assertSame( 3, $vars['posts_per_page'] );
	}

	// A term with no term-meta override falls all the way back through
	// taxonomy -> post type, proving the full 4-tier chain threads correctly,
	// regardless of loop_type or child-term state (no gating anywhere now).

	public function test_term_falls_through_full_chain_when_unset() {
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'loop' => array( 'loop_type' => 'category', 'posts_per_page' => 6 )
		) ) );
		md_test_set_term_children( 42, 'category', array() );

		$vars = $this->query_vars( 'category', 42 );

		$this->assertSame( 6, $vars['posts_per_page'] );
	}

	public function test_term_falls_through_regardless_of_children_or_loop_type() {
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'loop' => array( 'loop_type' => 'post_listing', 'posts_per_page' => 6 )
		) ) );
		md_test_set_term_children( 42, 'category', array( 43 ) );

		$vars = $this->query_vars( 'category', 42 );

		$this->assertSame( 6, $vars['posts_per_page'] );
	}

}
