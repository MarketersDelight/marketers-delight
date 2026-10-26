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

	public function test_archive_meta_prints_at_its_positions() {
		$html = $this->render( $this->data( array(
			'description' => 'Section description',
			'cta' => array( 'page_cta' => 'custom', 'custom_html' => '<a href="#join">Join</a>' ),
			'archive_meta' => array(
				'before_title' => '<div class="byline before-title archive-meta">Before</div>',
				'inside_title' => '<div class="byline inside-title archive-meta">Inside</div>',
				'after_title' => '<div class="byline after-title archive-meta">After</div>'
			)
		) ) );

		$order = array( 'Before', '<h1 class="title">Section title</h1>', 'Inside', 'Section description', 'After', 'Join' );
		$last = -1;

		foreach ( $order as $needle ) {
			$position = strpos( $html, $needle );

			$this->assertIsInt( $position, "$needle prints" );
			$this->assertGreaterThan( $last, $position, "$needle prints in order" );

			$last = $position;
		}

		$this->assertStringContainsString( '<div class="title-inline">', $html );
	}

	public function test_title_aside_meta_splits_the_title_into_regions() {
		$html = $this->render( $this->data( array(
			'description' => 'Section description',
			'cta' => array( 'page_cta' => 'custom', 'custom_html' => '<a href="#join">Join</a>' ),
			'archive_meta' => array(
				'after_title' => '<div class="byline after-title archive-meta">After</div>',
				'byline_aside' => '<div class="byline byline-aside archive-meta">Aside</div>'
			)
		) ) );

		$main = strpos( $html, '<div class="title-main">' );
		$aside = strpos( $html, '<div class="title-aside"><div class="byline byline-aside archive-meta">Aside</div>' );

		$this->assertStringContainsString( 'has-aside', $html );
		$this->assertIsInt( $main );
		$this->assertIsInt( $aside );
		$this->assertGreaterThan( strpos( $html, 'After' ), $aside, 'Main holds the title, description and After Title meta' );
		$this->assertGreaterThan( strpos( $html, 'Join' ), $aside, 'The CTA stays in main by default' );
	}

	public function test_cta_placed_in_title_aside_splits_the_title() {
		$html = $this->render( $this->data( array(
			'cta' => array( 'page_cta' => 'custom', 'custom_html' => '<a href="#join">Join</a>', 'title_position' => 'byline_aside' )
		) ) );

		$this->assertStringContainsString( 'has-aside', $html );
		$this->assertGreaterThan( strpos( $html, '<div class="title-aside">' ), strpos( $html, 'Join' ), 'The CTA prints in the aside' );
	}

	public function test_cta_placed_before_the_title() {
		$html = $this->render( $this->data( array(
			'cta' => array( 'page_cta' => 'custom', 'custom_html' => '<a href="#join">Join</a>', 'title_position' => 'before_title' )
		) ) );

		$this->assertGreaterThan( strpos( $html, 'Join' ), strpos( $html, '<h1 class="title">' ) );
		$this->assertStringNotContainsString( 'has-aside', $html );
	}

	public function test_no_title_aside_meta_prints_no_regions() {
		$html = $this->render( $this->data( array(
			'cta' => array( 'page_cta' => 'custom', 'custom_html' => '<a href="#join">Join</a>' ),
			'archive_meta' => array( 'after_title' => '<div class="byline after-title archive-meta">After</div>' )
		) ) );

		$this->assertStringNotContainsString( 'has-aside', $html );
		$this->assertStringNotContainsString( 'title-main', $html );
		$this->assertStringNotContainsString( 'title-aside', $html );
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
