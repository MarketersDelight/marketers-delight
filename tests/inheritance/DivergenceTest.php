<?php
/**
 * Runs IDENTICAL fixture data through both md_api::loop_query_vars() (drives
 * the main WP archive query) and md_get_loop() (drives shortcode/manual
 * loops and what templates actually render) for posts_per_page, the one
 * field both implementations touch, and checks whether they agree.
 *
 * They're two independently-coded cascades over the same option data, but
 * now that both are a plain, unconditional post_type -> taxonomy -> term
 * cascade with no render-mode gating, they agree BY DESIGN, not by
 * coincidence -- unlike before this fix, when they happened to agree only in
 * the specific cases tested, via two unrelated conditions.
 *
 * @since 6.0
 */

class DivergenceTest extends MD_InheritanceTestCase {

	private function query_vars_posts_per_page( $taxonomy, $term_id ) {
		$api = $this->make_api( 'post', 'category' );
		$wp = new stdClass;
		$wp->query_vars = array();

		$this->call( $api, 'loop_query_vars', array( $wp, $taxonomy, $term_id ) );

		return isset( $wp->query_vars['posts_per_page'] ) ? $wp->query_vars['posts_per_page'] : null;
	}

	private function set_up( $loop_type, $has_children ) {
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'loop' => array( 'loop_type' => $loop_type, 'posts_per_page' => 6 )
		) ) );
		md_test_set_term_children( 42, 'category', $has_children ? array( 43 ) : array() );
		md_test_set_query( array(
			'is_category' => true,
			'queried_object' => (object) array( 'term_id' => 42, 'taxonomy' => 'category' ),
			'queried_object_id' => 42,
			'post_type' => 'post',
		) );
	}

	private function assert_both_agree_on_six( $loop_type, $has_children ) {
		$this->set_up( $loop_type, $has_children );
		$query_vars_result = $this->query_vars_posts_per_page( 'category', 42 );

		$this->set_up( $loop_type, $has_children );
		$loop = md_get_loop();

		$this->assertSame( 6, $query_vars_result );
		$this->assertSame( 6, $loop['posts_per_page'] );
	}

	public function test_agree_with_category_loop_type_and_children() {
		$this->assert_both_agree_on_six( 'category', true );
	}

	public function test_agree_with_category_loop_type_and_no_children() {
		$this->assert_both_agree_on_six( 'category', false );
	}

	public function test_agree_with_plain_loop_type() {
		$this->assert_both_agree_on_six( 'post_listing', false );
	}

}
