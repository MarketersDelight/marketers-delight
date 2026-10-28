<?php
/**
 * Tests Author photos: the uploaded photo, the Author photo title image
 * on singles, and that a post's own image keeps its place.
 *
 * @since 6.1
 */

class AuthorPhotoTest extends MD_InheritanceTestCase {

	protected function setUp(): void {
		parent::setUp();

		md_test_set_post_type_object( 'post', 'Posts' );
		md_test_set_query( array( 'is_singular' => true, 'post_type' => 'post', 'queried_object_id' => 1 ) );
		md_test_set_user_meta( 3, array( 'author' => array( 'photo' => array( 'id' => 55 ) ) ) );
	}

	private function title_image( $settings = array() ) {
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'author_photo' => array_merge( array( 'display' => array( 'show' => true ) ), $settings )
		) ) );
	}

	public function test_photo_id_is_the_uploaded_image() {
		$this->assertSame( 55, md_author_photo_id( 3 ) );
		$this->assertSame( 0, md_author_photo_id( 4 ), 'No upload, no photo' );

		md_test_set_user_meta( 4, array( 'author' => array( 'photo' => array( 'id' => 950 ) ) ) );

		$this->assertSame( 0, md_author_photo_id( 4 ), 'A file that isn\'t an image is no photo' );
	}

	public function test_title_image_is_off_by_default() {
		md_test_set_option( 'marketers_delight', array( 'post' => array() ) );

		$this->assertSame( array(), md_get_title_author_media() );
	}

	public function test_title_image_uses_the_authors_photo_and_settings() {
		$this->title_image( array( 'position' => 'title_right', 'mobile' => 'beside', 'image_width' => 160 ) );

		$media = md_get_title_author_media();

		$this->assertSame( 55, $media['image']['id'] );
		$this->assertSame( 3, $media['author'] );
		$this->assertSame( 'title_right', $media['position'] );
		$this->assertSame( 'beside', $media['mobile'] );
		$this->assertSame( 160, $media['image_width'] );
	}

	public function test_title_image_defaults_to_title_left() {
		$this->title_image();

		$media = md_get_title_author_media();

		$this->assertSame( 'title_left', $media['position'] );
	}

	public function test_a_post_can_hide_its_title_image() {
		$this->title_image();
		md_test_set_post_meta( array( 'layout' => array( 'content' => array( 'hide_title_image' => true ) ) ) );

		$this->assertSame( array(), md_get_title_author_media() );
	}

	public function test_no_uploaded_photo_means_no_title_image() {
		$this->title_image();
		md_test_set_user_meta( 3, array() );
		$GLOBALS['__test_avatar_found'] = true;

		$this->assertSame( array(), md_get_title_author_media(), 'A Gravatar alone never shows in titles' );
	}

	public function test_title_shows_the_photo_without_a_featured_image() {
		$this->title_image();

		$data = md_title_data( 'post' );

		$this->assertSame( 55, $data['media']['image']['id'] );
		$this->assertSame( 'title_left', $data['media']['position'] );
	}

	public function test_a_featured_image_in_the_title_wins_over_the_photo() {
		$this->title_image();
		md_test_set_post_meta( array( 'featured_media' => array( 'position' => 'title_right' ) ) );
		$GLOBALS['__test_thumbnail_id'] = 12;

		$data = md_title_data( 'post' );

		$this->assertSame( 12, $data['media']['image']['id'] );
		$this->assertArrayNotHasKey( 'author', $data['media'] );
	}

	public function test_a_featured_image_in_the_content_leaves_the_title_to_the_photo() {
		$this->title_image();
		md_test_set_post_meta( array( 'featured_media' => array( 'position' => 'right' ) ) );
		$GLOBALS['__test_thumbnail_id'] = 12;

		$data = md_title_data( 'post' );

		$this->assertSame( 55, $data['media']['image']['id'] );
	}

}
