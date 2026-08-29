<?php
/**
 * Tests Byline item registration and post type scope.
 *
 * @since 6.0
 */

class BylineTest extends MD_InheritanceTestCase {

	public function test_child_builder_replaces_only_its_configured_area() {
		md_test_set_settings_parent( 'book_quote', 'bookshelf' );
		md_test_set_option( 'marketers_delight', array(
			'bookshelf' => array( 'byline' => array( 'builder' => array(
				'parent-archive' => array( 'builder_area' => 'archives', 'builder_type' => 'date' ),
				'parent-single' => array( 'builder_area' => 'single', 'builder_type' => 'author' )
			) ) ),
			'book_quote' => array( 'byline' => array( 'builder' => array(
				'child-single' => array( 'builder_area' => 'single', 'builder_type' => 'bookshelf_author' )
			) ) )
		) );

		$this->assertSame( array(
			'parent-archive' => array( 'builder_area' => 'archives', 'builder_type' => 'date' ),
			'child-single' => array( 'builder_area' => 'single', 'builder_type' => 'bookshelf_author' )
		), md_get_byline_builder( 'book_quote' ) );
	}

	public function test_child_builder_inherits_when_it_has_no_items() {
		md_test_set_settings_parent( 'book_quote', 'bookshelf' );
		$builder = array(
			'parent-archive' => array( 'builder_area' => 'archives', 'builder_type' => 'date' ),
			'parent-single' => array( 'builder_area' => 'single', 'builder_type' => 'author' )
		);
		md_test_set_option( 'marketers_delight', array(
			'bookshelf' => array( 'byline' => array( 'builder' => $builder ) ),
			'book_quote' => array( 'layout' => array( 'sidebar' => 'none' ) )
		) );

		$this->assertSame( $builder, md_get_byline_builder( 'book_quote' ) );
	}

	public function test_saved_builder_replaces_default_rows_in_the_same_area() {
		md_test_set_filter( 'md_setting_defaults', array(
			'book_quote' => array( 'byline' => array( 'builder' => array(
				'default-single' => array( 'builder_area' => 'single', 'builder_type' => 'author' ),
				'default-archive' => array( 'builder_area' => 'archives', 'builder_type' => 'date' )
			) ) )
		) );
		md_setting_defaults( true );
		md_test_set_option( 'marketers_delight', array(
			'book_quote' => array( 'byline' => array( 'builder' => array(
				'saved-single' => array( 'builder_area' => 'single', 'builder_type' => 'bookshelf_author' )
			) ) )
		) );

		$this->assertSame( array(
			'default-archive' => array( 'builder_area' => 'archives', 'builder_type' => 'date' ),
			'saved-single' => array( 'builder_area' => 'single', 'builder_type' => 'bookshelf_author' )
		), md_get_byline_builder( 'book_quote' ) );
	}

	public function test_items_can_be_scoped_to_post_types() {
		md_test_set_filter( 'md_byline', array(
			'date' => array(
				'title' => 'Date'
			),
			'book_rating' => array(
				'title' => 'Book Rating',
				'post_types' => array( 'bookshelf' )
			)
		) );

		$this->assertSame( array( 'date', 'book_rating' ), array_keys( md_byline_items() ) );
		$this->assertSame( array( 'date', 'book_rating' ), array_keys( md_byline_items( 'bookshelf' ) ) );
		$this->assertSame( array( 'date' ), array_keys( md_byline_items( 'post' ) ) );
		$this->assertSame( array( 'date' ), array_keys( md_byline_items( '' ) ) );
	}

	public function test_byline_item_uses_a_registered_template_callback() {
		md_test_set_filter( 'md_byline', array(
			'custom' => array(
				'template' => function( $fields ) {
					echo $fields['label'];
				}
			)
		) );

		ob_start();
		md_byline_item( 'custom', array( 'label' => 'Custom item' ) );

		$this->assertSame( 'Custom item', ob_get_clean() );
	}

	public function test_render_keeps_native_items_missing_from_the_frontend_registry() {
		md_test_set_filter( 'md_byline', array(
			'share' => array(
				'title' => 'Share'
			),
			'book_rating' => array(
				'title' => 'Book Rating',
				'post_types' => array( 'bookshelf' )
			)
		) );

		md_byline( 'before_title', array(
			'loop' => array(
				'post_type' => 'post',
				'remove_byline' => array()
			),
			'items' => array(
				'date' => array(),
				'share' => array(),
				'book_rating' => array()
			)
		) );

		$this->assertSame( array( 'date', 'share' ), $GLOBALS['__test_rendered_byline_items'] );
	}

}
