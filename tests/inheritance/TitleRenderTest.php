<?php
/**
 * Tests md_title_template(): it renders only the data it's given, never the
 * current page's settings, so other features (Title Sections) can pass
 * their own data. Page Title markup is covered by TitleSnapshotTest.
 *
 * @since 6.0.4
 */

class TitleRenderTest extends MD_InheritanceTestCase {

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

	private function data( $data = array() ) {
		return array_merge( array(
			'title' => 'Section title',
			'description' => '',
			'media' => array(),
			'cover' => array(),
			'cta' => array( 'page_cta' => '' ),
			'identity' => array(),
			'header_cover' => false,
			'has_sidebar' => false,
			'loop' => array()
		), $data );
	}

	private function render( $data ) {
		ob_start();
		md_title_template( 'page', $data );

		return ob_get_clean();
	}

	public function test_renders_only_the_given_title() {
		$html = $this->render( $this->data() );

		$this->assertStringContainsString( '<h1 class="title">Section title</h1>', $html );
		$this->assertStringNotContainsString( 'Page title', $html );
	}

	public function test_empty_data_does_not_fall_back_to_the_page() {
		$html = $this->render( $this->data() );

		$this->assertStringNotContainsString( 'Page description', $html, 'Description fell back to the page' );
		$this->assertStringNotContainsString( 'Page CTA', $html, 'CTA fell back to the page' );
		$this->assertStringNotContainsString( 'image-12', $html, 'Featured media fell back to the page' );
		$this->assertStringNotContainsString( 'cover', $html, 'Cover fell back to the page' );
	}

	public function test_renders_the_given_description_media_cover_and_cta() {
		$html = $this->render( $this->data( array(
			'description' => 'Section description',
			'media' => array( 'media_type' => 'image', 'position' => 'left', 'image' => array( 'id' => 34 ) ),
			'cover' => array( 'position' => 'header_cover', 'bg_color' => '#1f1d1c', 'display' => array( 'disable_overlay' => true ) ),
			'header_cover' => 'header_cover',
			'cta' => array( 'page_cta' => 'custom', 'custom_html' => '<a href="#section-cta">Section CTA</a>' )
		) ) );

		$this->assertStringContainsString( 'image-inline image-left cover text-white', $html );
		$this->assertStringContainsString( 'background-color: #1f1d1c;', $html );
		$this->assertStringContainsString( 'image-34', $html );
		$this->assertStringContainsString( '<p>Section description</p>', $html );
		$this->assertStringContainsString( 'Section CTA', $html );
		$this->assertStringNotContainsString( 'Page', $html );
	}

}
