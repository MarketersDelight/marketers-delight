<?php
/**
 * Regressions from the 6.2.2 release review: MD's meta key is protected on every
 * request, PHP callers still pass built CTA HTML, and header rows print only
 * through registered callbacks.
 *
 * @since 6.2.2
 */

function md_test_release_row( $field ) {
	echo 'Beacon:' . $field['id'];
}

class ReleaseReviewTest extends MD_InheritanceTestCase {

	public function test_marketers_delight_meta_is_protected_without_admin() {
		$this->assertTrue( md_protect_meta_key( false, 'marketers_delight' ) );
		$this->assertFalse( md_protect_meta_key( false, 'subtitle' ) );
		$this->assertTrue( md_protect_meta_key( true, '_edit_lock' ) );
		$this->assertStringNotContainsString( 'is_protected_meta', file_get_contents( dirname( __DIR__, 2 ) . '/admin/admin.php' ) );
	}

	public function test_a_saved_cta_html_key_is_not_printed() {
		md_test_set_query( array( 'is_singular' => true, 'post_type' => 'post' ) );
		md_test_set_title( 1, 'Hello' );
		md_test_set_post_meta( array( 'page_cta' => array(
			'page_cta' => 'custom',
			'custom_html' => '<a href="#join">Join</a>',
			'html' => '<p class="stored-html">Stored</p>'
		) ) );

		$this->assertArrayNotHasKey( 'html', md_get_cta( 'post' ) );
		$this->assertArrayNotHasKey( 'html', md_title_data( 'post' )['cta'] );

		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'archives_title' => 'Blog',
			'page_cta' => array( 'page_cta' => 'links', 'html' => '<p class="stored-html">Stored</p>' )
		) ) );
		md_test_set_query( array( 'is_home' => true, 'post_type' => 'post' ) );

		$this->assertArrayNotHasKey( 'html', md_get_cta( 'page' ) );
		$this->assertArrayNotHasKey( 'html', md_title_data( 'page' )['cta'] );
	}

	public function test_php_callers_can_still_pass_built_html() {
		md_test_use_real_templates();
		ob_start();
		md_cta( 'floating_bar', array( 'page_cta' => 'email', 'html' => '<form class="built"></form>' ) );
		$html = ob_get_clean();

		$this->assertStringContainsString( '<form class="built"></form>', $html );
	}

	public function test_builder_rows_print_only_through_registered_callbacks() {
		md_test_set_option( 'marketers_delight', array( 'header' => array( 'builder' => array(
			'a1' => array( 'builder_type' => 'compile', 'builder_area' => 'primary' ),
			'a2' => array( 'builder_type' => 'get_cta_html', 'builder_area' => 'primary' ),
			'a3' => array( 'builder_type' => 'search', 'builder_area' => 'primary' ),
			'a4' => array( 'builder_type' => 'beacon_menu', 'builder_area' => 'aside' ),
			'a5' => array( 'builder_type' => 'inactive_dropin', 'builder_area' => 'aside', 'note' => 'Kept' ),
			'a6' => array( 'builder_type' => 'link', 'builder_area' => array( 'x' ) )
		) ) ) );
		md_test_set_filter( 'md_header_builder_elements', array(
			'beacon_menu' => array( 'callback' => 'md_test_release_row' )
		) );

		$builder = md_get_builder( 'header' );

		$this->assertSame( array( 'search' => array( 'a3' ), 'beacon_menu' => array( 'a4' ) ), $builder['elements'] );
		$this->assertSame( 'md_search', $builder['data']['primary'][0]['callback'] );
		$this->assertSame( 'md_test_release_row', $builder['data']['aside'][0]['callback'] );
		$this->assertSame( 'Kept', $builder['fields']['a5']['note'], 'Rows that don\'t print stay in the builder' );
		$this->assertSame( 'md_search', md_builder_callback( 'header', 'search', array() ) );
		$this->assertSame( '', md_builder_callback( 'byline', 'menu', array() ), 'Built-ins belong to the Header' );
	}

	public function test_editor_and_page_agree_when_a_post_has_both_keys() {
		md_test_set_option( 'marketers_delight', array( 'effects' => array( 'box_style' => array( 'remove' => true ) ) ) );
		md_test_set_post_meta( array( 'layout' => array( 'content_style' => 'plain', 'content' => array( 'box_style' => 0 ) ) ), 7 );
		md_test_set_query( array( 'is_singular' => true, 'queried_object_id' => 7, 'the_id' => 7, 'post_type' => 'post' ) );

		$this->assertSame( 'box', md_loop_style( array( 'post_id' => 7, 'post_type' => 'post' ) ), 'Editor' );
		$this->assertSame( 'box', md_loop_style(), 'Page' );
	}

	public function test_a_category_kept_as_box_under_a_plain_post_type() {
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'layout' => array( 'content' => array( 'box_style' => true ) ),
			'category' => array( 'layout' => array( 'content' => array( 'box_style' => 0 ) ) )
		) ) );
		md_test_set_query( array(
			'is_category' => true,
			'is_archive' => true,
			'post_type' => 'post',
			'queried_object' => new WP_Term( array( 'term_id' => 42, 'taxonomy' => 'category', 'name' => 'News' ) ),
			'queried_object_id' => 42
		) );

		$this->assertSame( 'box', md_loop_style() );
	}

	public function test_a_term_reads_its_own_new_key_then_its_own_legacy_key() {
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'layout' => array( 'content' => array( 'box_style' => 0 ) ) ) ) );
		md_test_set_query( array(
			'is_category' => true,
			'is_archive' => true,
			'post_type' => 'post',
			'queried_object' => new WP_Term( array( 'term_id' => 42, 'taxonomy' => 'category', 'name' => 'News' ) ),
			'queried_object_id' => 42
		) );

		md_test_set_term_meta( 42, array( 'layout' => array( 'content_style' => 'plain', 'content' => array( 'box_style' => 0 ) ) ) );
		$this->assertSame( 'box', md_loop_style(), 'Its own new key wins' );

		md_test_set_term_meta( 42, array( 'layout' => array( 'content_style' => 'plain' ) ) );
		$this->assertSame( 'plain', md_loop_style(), 'Its own legacy key over a Box post type' );

		md_test_set_term_meta( 42, array() );
		$this->assertSame( 'box', md_loop_style(), 'Nothing of its own inherits' );
	}

	public function test_a_tag_reads_its_own_box_style() {
		md_test_set_option( 'marketers_delight', array( 'effects' => array( 'box_style' => array( 'remove' => true ) ) ) );
		md_test_set_query( array(
			'is_tag' => true,
			'is_archive' => true,
			'post_type' => 'post',
			'queried_object' => new WP_Term( array( 'term_id' => 43, 'taxonomy' => 'post_tag', 'name' => 'Tips' ) ),
			'queried_object_id' => 43
		) );

		$this->assertSame( 'plain', md_loop_style() );

		md_test_set_term_meta( 43, array( 'layout' => array( 'content' => array( 'box_style' => 0 ) ) ) );
		$this->assertSame( 'box', md_loop_style() );
	}

	public function test_header_area_class_is_escaped() {
		$source = file_get_contents( dirname( __DIR__, 2 ) . '/header.php' );

		$this->assertStringContainsString( "echo '<div class=\"header-' . esc_attr( \$section ) . '\">';", $source );
	}

}
