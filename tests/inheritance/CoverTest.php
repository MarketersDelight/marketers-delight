<?php
/**
 * Tests Page Cover resolution between archive pages and posts rendered inside
 * archive Loops.
 *
 * @since 6.0
 */

class CoverTest extends MD_InheritanceTestCase {

	private function set_post_type_archive() {
		md_test_set_query( array(
			'is_archive' => true,
			'is_post_type_archive' => true,
			'post_type' => 'post'
		) );
	}

	private function set_category_archive() {
		md_test_set_query( array(
			'is_archive' => true,
			'is_category' => true,
			'post_type' => 'post',
			'queried_object' => (object) array(
				'term_id' => 42,
				'taxonomy' => 'category'
			),
			'queried_object_id' => 42
		) );
	}

	public function test_archive_page_uses_post_type_cover() {
		md_test_set_option( 'marketers_delight', array(
			'post' => array(
				'page_cover' => array( 'position' => 'headline_cover' )
			)
		) );
		$this->set_post_type_archive();

		$this->assertSame( 'headline_cover', md_cover( 'page' )['position'] );
		$this->assertSame( 'headline_cover', md_cover()['position'] );
	}

	public function test_archive_post_cover_is_hidden_when_inheritance_is_disabled() {
		md_test_set_post_meta( array(
			'page_cover' => array( 'position' => 'headline_cover' )
		) );
		$this->set_post_type_archive();

		$this->assertSame( array(), md_cover( 'post' ) );
	}

	public function test_archive_post_inherits_its_single_cover_when_enabled() {
		md_test_set_option( 'marketers_delight', array(
			'post' => array(
				'loop' => array(
					'inherit' => array( 'page_cover' => 1 )
				)
			)
		) );
		md_test_set_post_meta( array(
			'page_cover' => array( 'position' => 'headline_cover' )
		) );
		$this->set_post_type_archive();

		$this->assertSame( 'headline_cover', md_cover( 'post' )['position'] );
	}

	public function test_taxonomy_inheritance_setting_enables_post_cover() {
		md_test_set_option( 'marketers_delight', array(
			'post' => array(
				'category' => array(
					'loop' => array(
						'inherit' => array( 'page_cover' => 1 )
					)
				)
			)
		) );
		md_test_set_post_meta( array(
			'page_cover' => array( 'position' => 'headline_cover' )
		) );
		$this->set_category_archive();

		$this->assertSame( 'headline_cover', md_cover( 'post' )['position'] );
	}

	public function test_category_page_cover_remains_separate_from_loop_post_cover() {
		md_test_set_option( 'marketers_delight', array(
			'post' => array(
				'category' => array(
					'page_cover' => array( 'position' => 'header_cover' ),
					'loop' => array(
						'inherit' => array( 'page_cover' => 1 )
					)
				)
			)
		) );
		md_test_set_post_meta( array(
			'page_cover' => array( 'position' => 'headline_cover' )
		) );
		$this->set_category_archive();

		$this->assertSame( 'header_cover', md_cover( 'page' )['position'] );
		$this->assertSame( 'headline_cover', md_cover( 'post' )['position'] );
	}

	public function test_taxonomy_false_checkbox_override_preserves_cover_siblings() {
		md_test_set_option( 'marketers_delight', array(
			'post' => array(
				'page_cover' => array(
					'position' => 'headline_cover',
					'display' => array( 'alternate' => true, 'disable_overlay' => true )
				),
				'category' => array(
					'page_cover' => array( 'display' => array( 'disable_overlay' => 0 ) )
				)
			)
		) );
		$this->set_category_archive();

		$cover = md_cover( 'page' );

		$this->assertSame( 'headline_cover', $cover['position'] );
		$this->assertTrue( $cover['display']['alternate'] );
		$this->assertSame( 0, $cover['display']['disable_overlay'] );
	}
}
