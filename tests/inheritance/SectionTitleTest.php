<?php
/**
 * Tests the Section Title Archive Sections element: its row-to-data
 * mapping, its markup through the shared Page Title renderer, and that its
 * fields are the Featured Media, Page Cover and Page CTA schemas.
 *
 * @since 6.1
 */

class SectionTitleTest extends MD_InheritanceTestCase {

	protected function setUp(): void {
		parent::setUp();

		md_test_use_real_templates();
		md_test_set_post_type_object( 'post', 'Posts' );
		md_test_set_object_taxonomies( 'post', array( 'category' ) );

		// The page has every Page Title setting, so a fallback would show

		md_test_set_query( array( 'is_home' => true, 'post_type' => 'post' ) );
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'archives_title' => 'Page title',
			'archives_text' => 'Page description',
			'page_cover' => array( 'position' => 'header_cover', 'bg_color' => '#2a1938' ),
			'featured_media' => array( 'media_type' => 'image', 'position' => 'right', 'image' => array( 'id' => 12 ) ),
			'page_cta' => array( 'page_cta' => 'custom', 'custom_html' => '<a href="#page-cta">Page CTA</a>' )
		) ) );
	}

	private function render( $row ) {
		ob_start();
		md_section_title( $row, 'post', 'section1', null );

		return ob_get_clean();
	}

	public function test_fields_reuse_the_featured_media_cover_and_cta_schemas() {
		$fields = md_section_title_fields();

		$this->assertSame( md_featured_media_fields(), $fields['section_media'] );
		$this->assertSame( md_page_cover_fields(), $fields['section_cover'] );
		$this->assertSame( md_page_cta_fields(), $fields['section_cta'] );
	}

	public function test_data_defaults_the_image_to_title_left_and_skips_empty_parts() {
		$data = md_section_title_data( array(
			'section_title' => 'Blog',
			'section_media' => array( 'image' => array( 'id' => 34 ) )
		) );

		$this->assertSame( 'title_left', $data['media']['position'] );
		$this->assertSame( 'image', $data['media']['media_type'] );
		$this->assertSame( array(), $data['cover'], 'No background means no cover' );
		$this->assertSame( array(), $data['cta'] );
		$this->assertSame( array(), $data['archive_meta'], 'Archive Meta is opt-in' );
		$this->assertFalse( $data['header_cover'] );
	}

	public function test_a_background_makes_a_cover() {
		$data = md_section_title_data( array( 'section_cover' => array( 'bg_color' => '#1f1d1c' ) ) );

		$this->assertSame( 'headline_cover', $data['cover']['position'] );
	}

	public function test_renders_a_section_title_with_h2_and_no_page_fallback() {
		$html = $this->render( array(
			'section_title' => 'Blog',
			'section_text' => 'Everything I have published.',
			'section_media' => array( 'image' => array( 'id' => 34 ), 'image_width' => 64 )
		) );

		$this->assertStringStartsWith( '<section class="section-title entry-title wide image-title title-left">', $html );
		$this->assertStringEndsWith( '</section>', trim( $html ) );
		$this->assertStringNotContainsString( '<header', $html );
		$this->assertStringContainsString( '<h2 class="title">Blog</h2>', $html );
		$this->assertStringContainsString( 'image-34', $html );
		$this->assertStringContainsString( '<p>Everything I have published.</p>', $html );

		foreach ( array( 'Page title', 'Page description', 'Page CTA', 'image-12', 'cover' ) as $page_value )
			$this->assertStringNotContainsString( $page_value, $html, "Fell back to the page: $page_value" );
	}

	public function test_mobile_image_position_option() {
		$html = $this->render( array(
			'section_title' => 'Blog',
			'section_media' => array( 'image' => array( 'id' => 34 ), 'mobile' => 'beside' )
		) );
		$stacked = $this->render( array(
			'section_title' => 'Blog',
			'section_media' => array( 'image' => array( 'id' => 34 ) )
		) );

		$this->assertStringContainsString( 'title-left mobile-beside">', $html );
		$this->assertStringNotContainsString( 'mobile-', $stacked );
	}

	public function test_heading_level_option() {
		$html = $this->render( array( 'section_title' => 'Blog', 'section_heading' => 'h3' ) );

		$this->assertStringContainsString( '<h3 class="title">Blog</h3>', $html );
	}

	public function test_renders_inline_image_cover_and_cta() {
		$html = $this->render( array(
			'section_title' => 'The Vault',
			'section_text' => 'Members only.',
			'section_media' => array( 'image' => array( 'id' => 34 ), 'position' => 'right' ),
			'section_cover' => array( 'bg_color' => '#1f1d1c', 'display' => array( 'disable_overlay' => true ) ),
			'section_cta' => array( 'page_cta' => 'custom', 'custom_html' => '<a href="#join">Join</a>' )
		) );

		$this->assertStringContainsString( 'section-title entry-title wide image-inline image-right cover text-white', $html );
		$this->assertStringContainsString( 'background-color: #1f1d1c;', $html );
		$this->assertStringContainsString( '<div class="cta cta-custom"><a href="#join">Join</a></div>', $html );
		$this->assertStringNotContainsString( 'overlay', $html );
	}

	public function test_lines_up_inline_beside_a_sidebar() {
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'layout' => array( 'sidebar' => array( 'global' => true ) )
		) ) );

		$html = $this->render( array( 'section_title' => 'Blog' ) );

		$this->assertStringStartsWith( '<section class="section-title entry-title inline">', $html );
	}

	public function test_archive_meta_option_shows_the_meta_tab_items() {
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'archive_meta' => array( 'builder' => array(
				'count' => array( 'builder_type' => 'test_count', 'builder_area' => 'archives' )
			) )
		) ) );
		md_test_set_filter( 'md_archive_meta_items', array(
			'test_count' => array( 'icon' => '', 'callback' => 'md_test_archive_meta_count' )
		) );

		$row = array( 'section_title' => 'Blog' );
		$hidden = $this->render( $row );

		$row['section_display'] = array( 'archive_meta' => true );
		$shown = $this->render( $row );

		$this->assertStringNotContainsString( 'posts', $hidden );
		$this->assertStringContainsString( '<strong>12</strong> posts', $shown );
	}

	public function test_dont_show_cta_prints_nothing() {
		$html = $this->render( array(
			'section_title' => 'Blog',
			'section_cta' => array( 'page_cta' => 'remove', 'custom_html' => '<a href="#join">Join</a>' )
		) );

		$fields = md_page_cta_fields();

		$this->assertStringNotContainsString( 'cta', $html );
		$this->assertContains( 'remove', $fields['page_cta']['options'] );
	}

	public function test_empty_row_renders_nothing() {
		$this->assertSame( '', $this->render( array( 'section_cover' => array( 'bg_color' => '#1f1d1c' ) ) ) );
	}

}


function md_test_archive_meta_count() {
	return '12 posts';
}
