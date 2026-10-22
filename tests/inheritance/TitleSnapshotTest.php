<?php
/**
 * Snapshot tests for md_title(): the full Page Title markup across page
 * types, covers, Featured Media positions and CTAs, rendered with the
 * theme's real templates. Guards the resolver/renderer split (see
 * MD/deploy/title-sections/PLAN.md): every snapshot must stay identical.
 *
 * A missing snapshot is recorded from the current output and the test is
 * marked incomplete. Delete a snapshot file to record it again.
 *
 * @since 6.0.4
 */

class TitleSnapshotTest extends MD_InheritanceTestCase {

	private $dir;

	protected function setUp(): void {
		parent::setUp();

		$this->dir = __DIR__ . '/fixtures/title-snapshots';

		md_test_use_real_templates();
		md_test_set_post_type_object( 'post', 'Posts' );
		md_test_set_object_taxonomies( 'post', array( 'category' ) );
	}

	/**
	 * @dataProvider page_titles
	 */

	public function test_page_title_markup_is_unchanged( $name, $query, $option, $term_meta = array() ) {
		md_test_set_query( array_merge( array( 'post_type' => 'post' ), $query ) );
		md_test_set_option( 'marketers_delight', $option );

		foreach ( $term_meta as $term_id => $meta )
			md_test_set_term_meta( $term_id, $meta );

		$this->assertSnapshot( $name, $this->render( 'page' ) );
	}

	public function test_single_post_header_cover_markup_is_unchanged() {
		md_test_set_query( array( 'is_singular' => true, 'post_type' => 'post', 'queried_object_id' => 1 ) );
		// get_the_title() without an ID reads post 0 in this suite's stub
		md_test_set_title( 0, 'A single post' );
		md_test_set_option( 'marketers_delight', array( 'post' => array() ) );
		md_test_set_post_meta( array(
			'page_cover' => array( 'position' => 'header_cover', 'bg_color' => '#2a1938' ),
			'page_cta' => array( 'page_cta' => 'custom', 'custom_html' => '<a class="button" href="#join">Join</a>' )
		) );

		$this->assertSnapshot( 'single-header-cover', $this->render( 'post' ) );
	}

	public static function page_titles() {
		$home = array( 'is_home' => true );
		$blog = array( 'archives_title' => 'Blog', 'archives_text' => 'Everything I have published.' );
		$cases = array(
			array( 'home-defaults', $home, array() ),
			array( 'home-title-description', $home, array( 'post' => $blog ) ),
			array( 'home-header-cover', $home, array( 'post' => $blog + array(
				'page_cover' => array( 'position' => 'header_cover', 'bg_color' => '#2a1938' )
			) ) ),
			array( 'home-headline-cover-photo', $home, array( 'post' => $blog + array(
				'page_cover' => array( 'position' => 'headline_cover', 'photo' => array( 'id' => 21 ) )
			) ) ),
			array( 'home-cover-dark-text', $home, array( 'post' => $blog + array(
				'page_cover' => array( 'position' => 'header_cover', 'bg_color' => '#f3d48b', 'display' => array( 'alternate' => true, 'disable_overlay' => true ) )
			) ) ),
			array( 'home-cta-custom', $home, array( 'post' => $blog + array(
				'page_cta' => array( 'page_cta' => 'custom', 'custom_html' => '<a class="button" href="#join">Join</a>' )
			) ) ),
			array( 'home-cover-media-cta', $home, array( 'post' => $blog + array(
				'page_cover' => array( 'position' => 'header_cover', 'bg_color' => '#2a1938', 'display' => array( 'disable_overlay' => true ) ),
				'featured_media' => array( 'media_type' => 'image', 'position' => 'right', 'image' => array( 'id' => 12 ) ),
				'page_cta' => array( 'page_cta' => 'custom', 'custom_html' => '<form class="form"></form>' )
			) ) ),
			array( 'home-sidebar', $home, array( 'post' => $blog + array(
				'layout' => array( 'sidebar' => array( 'global' => true ) )
			) ) ),
			array( 'category-term-overrides', array( 'is_category' => true, 'is_archive' => true, 'queried_object' => new WP_Term( array( 'term_id' => 42, 'taxonomy' => 'category', 'name' => 'Aktywa', 'slug' => 'aktywa' ) ), 'queried_object_id' => 42 ), array( 'post' => $blog + array(
				'category' => array( 'featured_media' => array( 'media_type' => 'image', 'position' => 'left', 'image' => array( 'id' => 12 ) ) )
			) ), array( 42 => array(
				'hero' => array( 'archives_title' => 'Aktywa', 'archives_text' => 'Stocks and property.', 'archives_color' => '#201f1d' ),
				'page_cover' => array( 'position' => 'header_cover', 'bg_color' => '#1f1d1c' )
			) ) ),
			array( 'category-defaults', array( 'is_category' => true, 'is_archive' => true, 'queried_object' => new WP_Term( array( 'term_id' => 43, 'taxonomy' => 'category', 'name' => 'Raporty', 'slug' => 'raporty' ) ), 'queried_object_id' => 43 ), array( 'post' => $blog ) ),
			array( 'author', array( 'is_author' => true, 'is_archive' => true, 'queried_object_id' => 3 ), array() ),
			array( 'search', array( 'is_search' => true ), array() )
		);

		foreach ( array( 'left', 'right', 'title_left', 'title_right', 'title_center', 'above_headline', 'below_headline', 'center' ) as $position )
			$cases[] = array( 'home-media-' . str_replace( '_', '-', $position ), $home, array( 'post' => $blog + array(
				'featured_media' => array( 'media_type' => 'image', 'position' => $position, 'image' => array( 'id' => 12 ) )
			) ) );

		$named = array();

		foreach ( $cases as $case )
			$named[$case[0]] = $case;

		return $named;
	}

	private function render( $context ) {
		ob_start();
		md_title( $context );

		return ob_get_clean();
	}

	private function assertSnapshot( $name, $html ) {
		$file = "{$this->dir}/$name.html";

		if ( ! file_exists( $file ) ) {
			if ( ! is_dir( $this->dir ) )
				mkdir( $this->dir, 0755, true );

			file_put_contents( $file, $html );

			$this->markTestIncomplete( "Recorded snapshot $name.html" );
		}

		$this->assertSame( file_get_contents( $file ), $html, "Title markup changed: $name" );
	}

}
