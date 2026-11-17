<?php
/**
 * Tests md_value() (api/values.php): level order, absent versus 0, contexts,
 * empty paths, the source of each value and inherit_post_type.
 *
 * @since 6.2.2
 */

class ResolveTest extends MD_InheritanceTestCase {

	private function single( $post_id = 5 ) {
		md_test_set_query( array( 'is_singular' => true, 'the_id' => $post_id, 'post_type' => 'post' ) );
	}

	private function category( $term_id = 42 ) {
		md_test_set_query( array(
			'is_category' => true,
			'queried_object' => (object) array( 'term_id' => $term_id, 'taxonomy' => 'category' ),
			'queried_object_id' => $term_id,
			'post_type' => 'post'
		) );
	}

	public function test_post_value_wins_over_post_type() {
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'layout' => array( 'content_style' => 'border' ) ) ) );
		md_test_set_post_meta( array( 'layout' => array( 'content_style' => 'plain' ) ), 5 );
		$this->single();

		$this->assertSame( array( 'value' => 'plain', 'source' => 'post' ), md_value( array( 'key' => array( 'layout', 'content_style' ), 'page' => true, 'source' => true ) ) );
	}

	public function test_post_type_fills_in_when_the_post_has_nothing() {
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'layout' => array( 'content_style' => 'border' ) ) ) );
		$this->single();

		$this->assertSame( array( 'value' => 'border', 'source' => 'post_type' ), md_value( array( 'key' => array( 'layout', 'content_style' ), 'page' => true, 'source' => true ) ) );
	}

	public function test_a_stored_zero_wins_over_lower_levels() {
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'loop' => array( 'columns' => 3 ) ) ) );
		md_test_set_post_meta( array( 'loop' => array( 'columns' => 0 ) ), 5 );
		$this->single();

		$this->assertSame( 0, md_value( array( 'key' => array( 'loop', 'columns' ), 'page' => true ) ) );
	}

	public function test_default_when_no_level_stores_it() {
		md_test_set_option( 'marketers_delight', array() );
		$this->single();

		$this->assertSame( array( 'value' => 'box', 'source' => 'default' ), md_value( array( 'key' => array( 'layout', 'content_style' ), 'default' => 'box', 'page' => true, 'source' => true ) ) );
	}

	public function test_term_then_taxonomy_then_post_type() {
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'loop' => array( 'columns' => 3 ),
			'category' => array( 'loop' => array( 'columns' => 2 ) )
		) ) );
		$this->category();

		$this->assertSame( array( 'value' => 2, 'source' => 'taxonomy' ), md_value( array( 'key' => array( 'loop', 'columns' ), 'page' => true, 'source' => true ) ) );

		md_test_set_term_meta( 42, array( 'loop' => array( 'columns' => 4 ) ) );

		$this->assertSame( array( 'value' => 4, 'source' => 'term' ), md_value( array( 'key' => array( 'loop', 'columns' ), 'page' => true, 'id' => 42, 'source' => true ) ) );
	}

	public function test_site_is_the_default_context() {
		md_test_set_option( 'marketers_delight', array( 'colors' => array( 'design' => 'plain' ) ) );
		md_test_set_post_meta( array( 'colors' => array( 'design' => 'border' ) ), 5 );
		$this->single();

		$this->assertSame( 'plain', md_value( array( 'colors', 'design' ) ) );
	}

	public function test_site_context_reads_site_only() {
		md_test_set_option( 'marketers_delight', array( 'colors' => array( 'design' => 'plain' ), 'post' => array( 'colors' => array( 'design' => 'border' ) ) ) );
		$this->single();

		$this->assertSame( array( 'value' => 'plain', 'source' => 'site' ), md_value( array( 'key' => array( 'colors', 'design' ), 'source' => true ) ) );
	}

	public function test_tag_and_date_pages_read_the_post_type_then_the_site() {
		md_test_set_option( 'marketers_delight', array(
			'cta' => array( 'text' => 'Site' ),
			'post' => array( 'loop' => array( 'columns' => 3 ) )
		) );

		foreach ( array( 'is_tag', 'is_date' ) as $page ) {
			md_test_set_query( array( $page => true, 'post_type' => 'post' ) );

			$this->assertSame( 3, md_value( array( 'key' => array( 'loop', 'columns' ), 'page' => true ) ) );
			$this->assertSame( 'Site', md_value( array( 'key' => array( 'cta', 'text' ), 'page' => true ) ) );
		}
	}

	public function test_search_reads_the_site() {
		md_test_set_option( 'marketers_delight', array(
			'loop' => array( 'columns' => 2 ),
			'post' => array( 'loop' => array( 'columns' => 3 ) )
		) );
		md_test_set_query( array( 'is_search' => true, 'post_type' => 'post' ) );

		$this->assertSame( 2, md_value( array( 'key' => array( 'loop', 'columns' ), 'page' => true ) ) );
	}

	public function test_post_type_context_merges_settings_parents() {
		md_test_set_settings_parent( 'docs', 'post' );
		md_test_set_option( 'marketers_delight', array(
			'post' => array( 'loop' => array( 'columns' => 3, 'featured' => 1 ) ),
			'docs' => array( 'loop' => array( 'columns' => 2 ) )
		) );

		$this->assertSame( array( 'columns' => 2, 'featured' => 1 ), md_value( array( 'key' => 'loop', 'post_type' => 'docs' ) ) );
	}

	public function test_post_context_reads_that_post_wherever_the_page_is() {
		md_test_set_post_meta( array( 'layout' => array( 'content_style' => 'plain' ) ), 9 );
		$this->category();

		$this->assertSame( 'plain', md_value( array( 'key' => array( 'layout', 'content_style' ), 'post' => 9 ) ) );
	}

	public function test_empty_path_is_the_whole_value() {
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'loop' => array( 'columns' => 3 ) ) ) );

		$this->assertSame( array( 'loop' => array( 'columns' => 3 ) ), md_value( array( 'key' => null, 'post_type' => 'post' ) ) );
	}

	public function test_without_post_type_inheritance_a_single_stops_at_the_post() {
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'subtitle' => array( 'lede' => 'Post lede' ) ) ) );
		$this->single();

		$this->assertNull( md_value( array( 'key' => array( 'subtitle', 'lede' ), 'page' => true, 'inherit_post_type' => false ) ) );
		$this->assertSame( 'Post lede', md_value( array( 'key' => array( 'subtitle', 'lede' ), 'page' => true ) ) );
	}

	public function test_taxonomy_without_post_type_inheritance() {
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'loop' => array( 'columns' => 3 ),
			'category' => array( 'loop' => array( 'featured' => 1 ) )
		) ) );
		$args = array( 'taxonomy' => 'category', 'post_type' => 'post' );

		$this->assertSame( 3, md_value( array_merge( $args, array( 'key' => array( 'loop', 'columns' ) ) ) ) );
		$this->assertNull( md_value( array_merge( $args, array( 'key' => array( 'loop', 'columns' ), 'inherit_post_type' => false ) ) ) );
	}

	public function test_short_form_reads_the_site_with_a_default() {
		md_test_set_option( 'marketers_delight', array( 'header' => array( 'layout' => 'center' ) ) );

		$this->assertSame( 'center', md_value( array( 'header', 'layout' ), 'left' ) );
		$this->assertSame( 'left', md_value( array( 'header', 'layout_mobile' ), 'left' ) );
		$this->assertSame( array( 'layout' => 'center' ), md_value( 'header' ) );
	}
}
