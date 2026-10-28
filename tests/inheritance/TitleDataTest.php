<?php
/**
 * Tests md_title_data(), the Page Title resolver: when it skips the title,
 * and the values it resolves for the blog home, a category, and a single
 * post. Markup is covered by TitleSnapshotTest.
 *
 * @since 6.1
 */

class TitleDataTest extends MD_InheritanceTestCase {

	protected function setUp(): void {
		parent::setUp();

		md_test_set_post_type_object( 'post', 'Posts' );
		md_test_set_object_taxonomies( 'post', array( 'category' ) );
	}

	public function test_has_title_filter_skips_the_title() {
		md_test_set_query( array( 'is_home' => true, 'post_type' => 'post' ) );
		md_test_set_filter( 'md_has_page_title', false );

		$this->assertSame( array(), md_title_data( 'page' ) );
	}

	public function test_removed_headline_skips_the_page_title() {
		md_test_set_query( array( 'is_home' => true, 'post_type' => 'post' ) );
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'layout' => array( 'content' => array( 'headline' => true ) )
		) ) );

		$this->assertSame( array(), md_title_data( 'page' ) );
	}

	public function test_empty_page_title_skips_the_title() {
		md_test_set_query( array( 'is_home' => true, 'post_type' => 'missing' ) );

		$this->assertSame( array(), md_title_data( 'page' ) );
	}

	public function test_blog_home_resolves_its_settings() {
		md_test_set_query( array( 'is_home' => true, 'post_type' => 'post' ) );
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'archives_title' => 'Blog',
			'archives_text' => 'Everything I have published.',
			'page_cover' => array( 'position' => 'header_cover', 'bg_color' => '#2a1938' ),
			'featured_media' => array( 'media_type' => 'image', 'position' => 'right', 'image' => array( 'id' => 12 ) ),
			'page_cta' => array( 'page_cta' => 'custom', 'custom_html' => '<a href="#join">Join</a>' )
		) ) );

		$data = md_title_data( 'page' );

		$this->assertSame( 'Blog', $data['title'] );
		$this->assertSame( 'Everything I have published.', $data['description'] );
		$this->assertSame( 'right', $data['media']['position'] );
		$this->assertSame( 12, $data['media']['image']['id'] );
		$this->assertSame( '#2a1938', $data['cover']['bg_color'] );
		$this->assertSame( 'header_cover', $data['header_cover'] );
		$this->assertSame( 'custom', $data['cta']['page_cta'] );
		$this->assertSame( array(), $data['archive_meta'], 'No Archive Meta items, no markup' );
	}

	public function test_category_resolves_term_overrides() {
		md_test_set_query( array(
			'is_category' => true,
			'is_archive' => true,
			'post_type' => 'post',
			'queried_object' => new WP_Term( array( 'term_id' => 42, 'taxonomy' => 'category', 'name' => 'Aktywa' ) ),
			'queried_object_id' => 42
		) );
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'page_cta' => array( 'page_cta' => 'custom', 'custom_html' => '<a href="#join">Join</a>' )
		) ) );
		md_test_set_term_meta( 42, array(
			'hero' => array( 'archives_title' => 'Assets', 'archives_text' => 'Stocks and property.', 'archives_color' => '#201f1d' ),
			'page_cover' => array( 'position' => 'header_cover', 'bg_color' => '#1f1d1c' )
		) );

		$data = md_title_data( 'page' );

		$this->assertSame( 'Assets', $data['title'] );
		$this->assertSame( 'Stocks and property.', $data['description'] );
		$this->assertSame( '#1f1d1c', $data['cover']['bg_color'] );
		$this->assertSame( '#201f1d', $data['identity']['color'] );
		$this->assertSame( 'custom', $data['cta']['page_cta'], 'Categories inherit the post type CTA' );
	}

	public function test_archive_meta_comes_from_the_title_filter() {
		md_test_set_query( array( 'is_home' => true, 'post_type' => 'post' ) );
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'archives_title' => 'Blog' ) ) );
		md_test_set_filter( 'md_title_archive_meta', function( $meta, $area ) {
			return $area === 'archives' ? array( 'after_title' => '<div class="byline after-title archive-meta"></div>' ) : $meta;
		} );

		$this->assertSame( array( 'after_title' ), array_keys( md_title_data( 'page' )['archive_meta'] ) );
	}

	public function test_archive_meta_is_empty_without_sections() {
		md_test_set_query( array( 'is_home' => true, 'post_type' => 'post' ) );
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'archives_title' => 'Blog' ) ) );

		$this->assertSame( array(), md_title_data( 'page' )['archive_meta'] );
	}

	public function test_category_cta_saved_without_a_type_inherits() {
		md_test_set_query( array(
			'is_category' => true,
			'is_archive' => true,
			'post_type' => 'post',
			'queried_object' => new WP_Term( array( 'term_id' => 42, 'taxonomy' => 'category', 'name' => 'Raporty' ) ),
			'queried_object_id' => 42
		) );
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'archives_title' => 'Blog',
			'page_cta' => array( 'page_cta' => 'custom', 'custom_html' => '<a href="#join">Join</a>' ),
			'category' => array( 'page_cta' => array( 'email_title' => 'Leftover' ) )
		) ) );
		md_test_set_term_meta( 42, array( 'hero' => array( 'archives_title' => 'Raporty' ) ) );

		$data = md_title_data( 'page' );

		$this->assertSame( 'custom', $data['cta']['page_cta'] );
	}

	public function test_category_dont_show_stops_the_cta() {
		md_test_set_query( array(
			'is_category' => true,
			'is_archive' => true,
			'post_type' => 'post',
			'queried_object' => new WP_Term( array( 'term_id' => 42, 'taxonomy' => 'category', 'name' => 'Raporty' ) ),
			'queried_object_id' => 42
		) );
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'page_cta' => array( 'page_cta' => 'custom', 'custom_html' => '<a href="#join">Join</a>' ),
			'category' => array( 'page_cta' => array( 'page_cta' => 'remove' ) )
		) ) );
		md_test_set_term_meta( 42, array( 'hero' => array( 'archives_title' => 'Raporty' ) ) );

		$data = md_title_data( 'page' );

		$this->assertSame( 'remove', $data['cta']['page_cta'] );
	}

	public function test_empty_category_still_inherits_post_type_settings() {
		md_test_set_taxonomy( 'category', true, array( 'post' ), true );
		md_test_set_query( array(
			'is_category' => true,
			'is_archive' => true,
			'post_type' => false,
			'queried_object' => new WP_Term( array( 'term_id' => 42, 'taxonomy' => 'category', 'name' => 'Newsletter' ) ),
			'queried_object_id' => 42
		) );
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'page_cta' => array( 'page_cta' => 'custom', 'custom_html' => '<a href="#join">Join</a>' )
		) ) );
		md_test_set_term_meta( 42, array( 'hero' => array( 'archives_title' => 'Newsletter' ) ) );

		$data = md_title_data( 'page' );

		$this->assertSame( 'post', md_get_post_type() );
		$this->assertSame( 'custom', $data['cta']['page_cta'] );
	}

	public function test_single_post_resolves_its_own_cta_and_no_identity() {
		md_test_set_query( array( 'is_singular' => true, 'post_type' => 'post', 'queried_object_id' => 1 ) );
		md_test_set_option( 'marketers_delight', array( 'post' => array() ) );
		md_test_set_post_meta( array(
			'page_cta' => array( 'page_cta' => 'custom', 'custom_html' => '<a href="#join">Join</a>' )
		) );

		$data = md_title_data( 'post' );

		$this->assertSame( 'custom', $data['cta']['page_cta'] );
		$this->assertSame( array(), $data['identity'] );
		$this->assertSame( array(), $data['archive_meta'] );
	}

}

