<?php
/**
 * Tests md_title_data(), the Page Title resolver: when it skips the title,
 * and the values it resolves for the blog home, a category, and a single
 * post. Markup is covered by TitleSnapshotTest.
 *
 * @since 6.0.4
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

	public function test_hide_in_page_title_turns_off_archive_meta() {
		md_test_set_query( array( 'is_home' => true, 'post_type' => 'post' ) );
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'archives_title' => 'Blog',
			'archive_meta' => array(
				'builder' => array( 'count' => array( 'builder_type' => 'test_posts', 'builder_area' => 'archives' ) ),
				'display' => array( 'hide_page_title' => true )
			)
		) ) );
		md_test_set_filter( 'md_archive_meta_items', array(
			'test_posts' => array( 'icon' => '', 'callback' => 'md_test_title_data_posts' )
		) );

		$data = md_title_data( 'page' );

		$this->assertSame( array(), $data['archive_meta'] );
	}

	public function test_archive_meta_resolves_by_position() {
		md_test_set_query( array( 'is_home' => true, 'post_type' => 'post' ) );
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'archives_title' => 'Blog',
			'archive_meta' => array( 'builder' => array(
				'count' => array( 'builder_type' => 'test_posts', 'builder_area' => 'archives' ),
				'aside' => array( 'builder_type' => 'test_posts', 'builder_area' => 'archives', 'position' => 'byline_aside' ),
				'before' => array( 'builder_type' => 'test_posts', 'builder_area' => 'archives', 'position' => 'before_title' )
			) )
		) ) );
		md_test_set_filter( 'md_archive_meta_items', array(
			'test_posts' => array( 'icon' => '', 'callback' => 'md_test_title_data_posts' )
		) );

		$meta = md_title_data( 'page' )['archive_meta'];

		$this->assertSame( array( 'after_title', 'byline_aside', 'before_title' ), array_keys( $meta ), 'Items without a position are After Title' );
		$this->assertStringStartsWith( '<div class="byline after-title archive-meta">', $meta['after_title'] );
		$this->assertStringContainsString( '<strong>12</strong> posts', $meta['byline_aside'] );
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


function md_test_title_data_posts() {
	return '12 posts';
}
