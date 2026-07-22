<?php
/**
 * Tests md_module() (functions/theme-functions.php:508-528) -- the
 * general-purpose tier-fallback helper (term_meta -> taxonomy -> post_type on
 * a category/tax page). Unlike md_taxonomy_field()/md_post_type_field(), its
 * taxonomy-tier fallback call omits explicit $post_type/$taxonomy args and
 * instead relies on get_queried_object() -- so it only resolves correctly
 * when called in the actual current-page request context.
 *
 * @since 6.0
 */

class ModuleFallbackTest extends MD_InheritanceTestCase {

	// On a taxonomy archive, with the current page context set correctly,
	// md_module() cascades term_meta -> taxonomy -> post_type same as the
	// other two implementations.

	public function test_resolves_correctly_when_current_page_context_matches() {
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'loop' => array( 'columns' => 3 ) ) ) );
		md_test_set_query( array(
			'is_category' => true,
			'queried_object' => (object) array( 'term_id' => 42, 'taxonomy' => 'category' ),
			'queried_object_id' => 42,
			'post_type' => 'post',
		) );

		$this->assertSame( 3, md_module( array( 'loop', 'columns' ), null, array( 'id' => 42 ) ) );
	}

	/**
	 * LATENT BUG, not a real tiering exception: md_taxonomy_field() and
	 * md_post_type_field() both accept explicit $post_type/$taxonomy
	 * arguments so a caller can resolve a value for ANY term, independent of
	 * what page is currently rendering. md_module()'s taxonomy-tier fallback
	 * (theme-functions.php:517, `md_taxonomy_field( $keys, null )`) does not
	 * -- it always reads get_queried_object() internally. So calling
	 * md_module() for a term other than the one actually being viewed
	 * silently resolves against the WRONG taxonomy context (or none at all),
	 * unlike the other two cascades which are safe to call for any tier.
	 */

	public function test_taxonomy_fallback_ignores_the_requested_term_and_uses_current_page_context_instead() {
		// Page context is viewing term 42 on 'category'...
		md_test_set_query( array(
			'is_category' => true,
			'queried_object' => (object) array( 'term_id' => 42, 'taxonomy' => 'category' ),
			'queried_object_id' => 42,
			'post_type' => 'post',
		) );
		// ...but the taxonomy tier value only exists for a DIFFERENT term (99)
		// with no matching taxonomy-tier data for term 42's actual taxonomy path.
		md_test_set_option( 'marketers_delight', array( 'post' => array() ) );
		md_test_set_term_meta( 99, array( 'loop' => array( 'columns' => 7 ) ) );

		// Asking md_module() to resolve for term 99 (id=99) still reads the
		// CURRENT PAGE's queried object (term 42) for its taxonomy-tier
		// fallback, not term 99's own context -- the $id argument only
		// controls the term_meta lookup, not the taxonomy-tier one.

		$result = md_module( array( 'loop', 'columns' ), null, array( 'id' => 99 ) );

		// term_meta(99) resolves correctly (7) because md_term_meta() does
		// accept an explicit $id -- it's specifically the taxonomy-tier
		// fallback inside md_module() that can't be pointed at an arbitrary term.

		$this->assertSame( 7, $result );
	}

	// $args['inherit_post_type'] defaults to true -- omitting it (or passing
	// no 4th argument at all) is identical to today's behavior for every
	// existing caller.

	public function test_inherit_post_type_defaults_to_true() {
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'loop' => array( 'columns' => 3 ) ) ) );
		md_test_set_query( array(
			'is_category' => true,
			'queried_object' => (object) array( 'term_id' => 42, 'taxonomy' => 'category' ),
			'queried_object_id' => 42,
			'post_type' => 'post',
		) );

		$this->assertSame( 3, md_module( array( 'loop', 'columns' ), null, array( 'id' => 42 ) ) );
	}

	/**
	 * inherit_post_type: false is a deliberate FEATURE (used by Loop's
	 * term-ID fields), not related to the latent-bug finding above -- when
	 * neither term_meta nor the taxonomy tier has a value, it stops there
	 * and returns the bare $default instead of falling through to
	 * md_post_type_field().
	 */

	public function test_inherit_post_type_false_stops_before_post_type_tier() {
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'loop' => array( 'category_exclude' => '12' ) ) ) );
		md_test_set_query( array(
			'is_category' => true,
			'queried_object' => (object) array( 'term_id' => 42, 'taxonomy' => 'category' ),
			'queried_object_id' => 42,
			'post_type' => 'post',
		) );

		$result = md_module( array( 'loop', 'category_exclude' ), null, array( 'id' => 42, 'inherit_post_type' => false ) );

		$this->assertNull( $result );
	}

	/**
	 * The singular-branch fix: the is_singular()/is_404() branch used to
	 * read md_post_meta() only, with no md_post_type_field() fallback --
	 * unlike every hand-rolled cascade elsewhere in the theme (e.g.
	 * md_media_position()). Now it falls through the same way the taxonomy
	 * branch already did.
	 */

	public function test_singular_branch_falls_through_to_post_type_tier_when_post_meta_unset() {
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'loop' => array( 'excerpt_length' => 40 ) ) ) );
		md_test_set_query( array( 'is_singular' => true, 'post_type' => 'post' ) );

		$this->assertSame( 40, md_module( array( 'loop', 'excerpt_length' ) ) );
	}

	// Post meta still wins over the post-type tier when it's actually set.

	public function test_singular_branch_post_meta_wins_over_post_type_tier() {
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'loop' => array( 'excerpt_length' => 40 ) ) ) );
		md_test_set_query( array( 'is_singular' => true, 'post_type' => 'post' ) );
		md_test_set_post_meta( array( 'loop' => array( 'excerpt_length' => 10 ) ) );

		$this->assertSame( 10, md_module( array( 'loop', 'excerpt_length' ) ) );
	}

}
