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
		), md_get_post_type_builder( 'byline', 'book_quote' ) );
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

		$this->assertSame( $builder, md_get_post_type_builder( 'byline', 'book_quote' ) );
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
		), md_get_post_type_builder( 'byline', 'book_quote' ) );
	}

	public function test_child_defaults_and_saved_rows_each_replace_their_own_area() {
		md_test_set_settings_parent( 'book_quote', 'bookshelf' );
		md_test_set_filter( 'md_setting_defaults', array(
			'book_quote' => array( 'byline' => array( 'builder' => array(
				'default-single' => array( 'builder_area' => 'single', 'builder_type' => 'bookshelf_reference' ),
				'default-archive' => array( 'builder_area' => 'archives', 'builder_type' => 'date' )
			) ) )
		) );
		md_setting_defaults( true );
		md_test_set_option( 'marketers_delight', array(
			'bookshelf' => array( 'byline' => array( 'builder' => array(
				'parent-single' => array( 'builder_area' => 'single', 'builder_type' => 'bookshelf_author' ),
				'parent-archive' => array( 'builder_area' => 'archives', 'builder_type' => 'bookshelf_rating' )
			) ) ),
			'book_quote' => array( 'byline' => array( 'builder' => array(
				'saved-archive' => array( 'builder_area' => 'archives', 'builder_type' => 'category' )
			) ) )
		) );

		$this->assertSame( array(
			'default-single' => array( 'builder_area' => 'single', 'builder_type' => 'bookshelf_reference' ),
			'saved-archive' => array( 'builder_area' => 'archives', 'builder_type' => 'category' )
		), md_get_post_type_builder( 'byline', 'book_quote' ) );
	}

	public function test_taxonomy_archive_keeps_child_builder_areas_replaced() {
		md_test_set_settings_parent( 'book_quote', 'bookshelf' );
		md_test_set_object_taxonomies( 'book_quote', array( 'bookshelf_topic' ) );
		md_test_set_filter( 'md_setting_defaults', array(
			'bookshelf' => array( 'byline' => array( 'builder' => array(
				'parent-archive' => array(
					'builder_area' => 'archives',
					'builder_type' => 'bookshelf_rating',
					'position' => 'after_title'
				)
			) ) ),
			'book_quote' => array( 'byline' => array( 'builder' => array(
				'child-archive' => array(
					'builder_area' => 'archives',
					'builder_type' => 'category',
					'position' => 'entry_footer'
				)
			) ) )
		) );
		md_setting_defaults( true );
		md_test_set_query( array(
			'is_tax' => true,
			'post_type' => 'book_quote',
			'queried_object' => (object) array( 'taxonomy' => 'bookshelf_topic' )
		) );

		$args = array(
			'context' => 'archives',
			'loop' => array(
				'post_type' => 'book_quote',
				'remove_byline' => array()
			)
		);

		$this->assertSame( array(), md_get_byline( 'after_title', $args ) );
		$this->assertSame( array( 'category' ), array_keys( md_get_byline( 'entry_footer', $args ) ) );
	}

	public function test_taxonomy_and_term_builder_rows_replace_their_configured_areas() {
		md_test_set_object_taxonomies( 'book_quote', array( 'bookshelf_topic' ) );
		md_test_set_filter( 'md_setting_defaults', array(
			'book_quote' => array(
				'byline' => array( 'builder' => array(
					'default-single' => array( 'builder_area' => 'single', 'builder_type' => 'bookshelf_reference' ),
					'default-archive' => array( 'builder_area' => 'archives', 'builder_type' => 'date' )
				) )
			)
		) );
		md_setting_defaults( true );
		md_test_set_option( 'marketers_delight', array(
			'book_quote' => array(
				'bookshelf_topic' => array( 'byline' => array( 'builder' => array(
					'taxonomy-archive' => array( 'builder_area' => 'archives', 'builder_type' => 'category' )
				) ) )
			)
		) );
		md_test_set_query( array(
			'is_tax' => true,
			'queried_object_id' => 42,
			'queried_object' => (object) array( 'taxonomy' => 'bookshelf_topic' )
		) );

		$this->assertSame( array(
			'default-single' => array( 'builder_area' => 'single', 'builder_type' => 'bookshelf_reference' ),
			'taxonomy-archive' => array( 'builder_area' => 'archives', 'builder_type' => 'category' )
		), md_get_post_type_builder( 'byline', 'book_quote' ) );

		md_test_set_term_meta( 42, array( 'byline' => array( 'builder' => array(
			'term-archive' => array( 'builder_area' => 'archives', 'builder_type' => 'edit' )
		) ) ) );

		$this->assertSame( array(
			'default-single' => array( 'builder_area' => 'single', 'builder_type' => 'bookshelf_reference' ),
			'term-archive' => array( 'builder_area' => 'archives', 'builder_type' => 'edit' )
		), md_get_post_type_builder( 'byline', 'book_quote' ) );
	}

	public function test_taxonomy_builder_layers_do_not_apply_to_an_unrelated_post_type() {
		md_test_set_object_taxonomies( 'post', array( 'category' ) );
		md_test_set_filter( 'md_setting_defaults', array(
			'post' => array(
				'byline' => array( 'builder' => array(
					'post-archive' => array( 'builder_area' => 'archives', 'builder_type' => 'date' )
				) ),
				'bookshelf_topic' => array( 'byline' => array( 'builder' => array(
					'topic-archive' => array( 'builder_area' => 'archives', 'builder_type' => 'category' )
				) ) )
			)
		) );
		md_setting_defaults( true );
		md_test_set_term_meta( 42, array( 'byline' => array( 'builder' => array(
			'term-archive' => array( 'builder_area' => 'archives', 'builder_type' => 'edit' )
		) ) ) );
		md_test_set_query( array(
			'is_tax' => true,
			'queried_object_id' => 42,
			'queried_object' => (object) array( 'taxonomy' => 'bookshelf_topic' )
		) );

		$this->assertSame( array(
			'post-archive' => array( 'builder_area' => 'archives', 'builder_type' => 'date' )
		), md_get_post_type_builder( 'byline', 'post' ) );
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

	public function test_before_content_selects_content_byline_items() {
		md_test_set_option( 'marketers_delight', array(
			'book_quote' => array( 'byline' => array( 'builder' => array(
				'reference' => array(
					'builder_area' => 'single',
					'builder_type' => 'bookshelf_reference',
					'position' => 'before_content'
				),
				'rating' => array(
					'builder_area' => 'single',
					'builder_type' => 'bookshelf_rating',
					'position' => 'after_title'
				)
			) ) )
		) );

		$args = array(
			'context' => 'single',
			'loop' => array(
				'post_type' => 'book_quote',
				'remove_byline' => array()
			)
		);

		$this->assertSame( array( 'bookshelf_reference' ), array_keys( md_get_byline( 'before_content', $args ) ) );

		$args['loop']['remove_byline']['before_content'] = true;

		$this->assertSame( array(), md_get_byline( 'before_content', $args ) );
	}

}
