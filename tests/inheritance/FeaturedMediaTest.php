<?php
/**
 * Tests author archive image and avatar fallback behavior.
 *
 * @since 6.0
 */

class FeaturedMediaTest extends MD_InheritanceTestCase {

	private function set_author_media( $media = array() ) {
		md_test_set_query( array( 'is_author' => true, 'queried_object_id' => 7 ) );

		md_test_set_option( 'marketers_delight', array(
			'author' => array(
				'featured_media' => $media
			)
		) );
	}

	public function test_author_photo_takes_priority_over_sitewide_image() {
		$this->set_author_media( array(
			'image' => array( 'id' => 42 )
		) );
		md_test_set_user_meta( 7, array( 'author' => array( 'photo' => array( 'id' => 55 ) ) ) );

		$media = md_has_media( 'page' );

		$this->assertSame( 7, $media['author'] );
		$this->assertSame( 55, $media['image']['id'] );
	}

	public function test_sitewide_image_is_used_when_author_has_no_photo() {
		$this->set_author_media( array(
			'image' => array( 'id' => 42 )
		) );
		$GLOBALS['__test_avatar_found'] = true;

		$media = md_has_media( 'page' );

		$this->assertArrayNotHasKey( 'author', $media, 'A Gravatar alone never replaces the image' );
		$this->assertSame( 42, $media['image']['id'] );
	}

	public function test_author_media_is_empty_when_no_avatar_or_sitewide_image_exists() {
		$this->set_author_media();

		$this->assertNull( md_has_media( 'page' ) );
	}

	public function test_author_page_title_position_does_not_override_loop_position() {
		$this->set_author_media( array(
			'position' => 'center'
		) );
		md_test_set_option( 'marketers_delight', array(
			'author' => array(
				'featured_media' => array( 'position' => 'center' ),
				'layout' => array( 'featured_image' => 'right' ),
				'loop' => array( 'featured_image' => 'left' )
			)
		) );

		$this->assertSame( 'center', md_media_position( 'page' ) );
		$this->assertSame( 'left', md_media_position( 'post' ) );
	}

	public function test_author_layout_featured_image_is_loop_position_fallback() {
		md_test_set_query( array( 'is_author' => true, 'queried_object_id' => 7 ) );
		md_test_set_option( 'marketers_delight', array(
			'author' => array(
				'featured_media' => array( 'position' => 'center' ),
				'layout' => array( 'featured_image' => 'right' )
			)
		) );

		$this->assertSame( 'right', md_media_position( 'post' ) );
	}

	public function test_loop_media_returns_the_resolved_loop_position() {
		$this->set_author_media( array(
			'position' => 'center'
		) );
		$GLOBALS['__test_thumbnail_id'] = 42;

		$media = md_has_media( 'post', array(
			'loop' => array( 'featured_image' => 'left' )
		) );

		$this->assertSame( 'left', $media['position'] );
	}
}
