<?php
/**
 * Tests layout context, visibility inheritance, and footer columns.
 *
 * @since 6.0
 */

class LayoutTest extends MD_InheritanceTestCase {

	public function test_layout_context_classifies_common_wordpress_views() {
		md_test_set_query( array( 'is_tag' => true ) );
		$this->assertSame( 'term', md_layout_context() );

		md_test_reset();
		md_test_set_query( array( 'is_author' => true ) );
		$this->assertSame( 'author', md_layout_context() );

		md_test_reset();
		md_test_set_query( array( 'is_search' => true ) );
		$this->assertSame( 'archive', md_layout_context() );

		md_test_reset();
		md_test_set_query( array( 'is_404' => true ) );
		$this->assertSame( 'single', md_layout_context() );
	}

	public function test_in_inner_only_inside_the_column() {
		$this->assertFalse( md_in_inner() );

		$GLOBALS['__test_did_actions'] = array( 'md_hook_content_box_top' => 1 );
		$GLOBALS['__test_doing_actions'] = array( 'md_hook_content_box_top' );
		$this->assertFalse( md_in_inner(), 'the header cover title prints during content_box_top, outside the column' );

		$GLOBALS['__test_doing_actions'] = array();
		$this->assertTrue( md_in_inner() );

		md_test_set_post_meta( array( 'layout' => array( 'content' => array( 'builder' => true ) ) ), get_the_ID() );
		$this->assertFalse( md_in_inner(), 'builder pages have no .inner on the column' );

		md_test_set_post_meta( array(), get_the_ID() );
		$GLOBALS['__test_did_actions']['md_hook_content_box_bottom'] = 1;
		$this->assertFalse( md_in_inner() );
	}

	private function viewed_post( $post_id = 5 ) {
		md_test_set_query( array( 'is_singular' => true, 'the_id' => $post_id, 'queried_object_id' => $post_id, 'post_type' => 'post' ) );
	}

	public function test_boxes_show_until_a_checkbox_removes_them() {
		$this->viewed_post();

		$this->assertSame( 'box', md_loop_style() );
		$this->assertSame( 'box', md_loop_style( array( 'body' => true ) ) );
	}

	public function test_site_checkbox_removes_boxes_everywhere() {
		md_test_set_option( 'marketers_delight', array( 'effects' => array( 'box_style' => array( 'remove' => true ) ) ) );
		$this->viewed_post();

		$this->assertSame( 'plain', md_loop_style() );
		$this->assertSame( 'plain', md_loop_style( array( 'body' => true ) ) );
		$this->assertSame( 'plain', md_loop_style( array( 'post_id' => 5, 'post_type' => 'post' ) ) );
	}

	public function test_post_type_checkbox_and_a_post_showing_its_box_again() {
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'layout' => array( 'content' => array( 'box_style' => true ) ) ) ) );
		md_test_set_post_meta( array( 'layout' => array( 'content' => array( 'box_style' => 0 ) ) ), 7 );
		$this->viewed_post();

		$this->assertSame( 'plain', md_loop_style() );
		$this->assertSame( 'box', md_loop_style( array( 'post_id' => 7, 'post_type' => 'post' ) ) );
		$this->assertSame( 'plain', md_loop_style( array( 'post_id' => 7, 'post_type' => 'post', 'exclude_single' => true ) ) );

		$this->viewed_post( 7 );
		$this->assertSame( 'box', md_loop_style() );
	}

	public function test_post_shows_its_box_when_the_site_removes_them() {
		md_test_set_option( 'marketers_delight', array( 'effects' => array( 'box_style' => array( 'remove' => true ) ) ) );
		md_test_set_post_meta( array( 'layout' => array( 'content' => array( 'box_style' => 0 ) ) ), 7 );
		$this->viewed_post( 7 );

		$this->assertSame( 'box', md_loop_style() );
	}

	public function test_unsaved_old_post_values_still_read() {
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'layout' => array( 'content' => array( 'box_style' => true ) ) ) ) );
		md_test_set_post_meta( array( 'layout' => array( 'content_style' => 'plain' ) ), 7 );
		md_test_set_post_meta( array( 'layout' => array( 'content_style' => 'border' ) ), 8 );

		$this->assertSame( 'plain', md_loop_style( array( 'post_id' => 7, 'post_type' => 'post' ) ) );
		$this->assertSame( 'plain', md_loop_style( array( 'post_id' => 8, 'post_type' => 'post' ) ) );

		$this->viewed_post( 8 );
		$this->assertSame( 'plain', md_loop_style() );
	}

	public function test_single_add_overrides_page_type_disable() {
		md_test_set_query( array(
			'is_singular' => true,
			'queried_object_id' => 1,
			'post_type' => 'post'
		) );
		md_test_set_option( 'marketers_delight', array(
			'post' => array(
				'layout' => array(
					'sidebar' => array( 'global' => true ),
					'sidebar_single_show' => array( 'disable' => true )
				)
			)
		) );
		md_test_set_post_meta( array(
			'layout' => array(
				'sidebar' => array( 'add' => true )
			)
		) );

		$this->assertTrue( md_has_layout( 'sidebar' ) );
	}

	public function test_global_and_page_type_visibility_matrix() {
		md_test_set_query( array(
			'is_singular' => true,
			'queried_object_id' => 1,
			'post_type' => 'post'
		) );

		$this->assertFalse( md_has_layout( 'sidebar' ) );

		md_test_set_option( 'marketers_delight', array(
			'post' => array(
				'layout' => array(
					'sidebar_single_show' => array( 'enable' => true )
				)
			)
		) );
		$this->assertTrue( md_has_layout( 'sidebar' ) );

		md_test_set_option( 'marketers_delight', array(
			'post' => array(
				'layout' => array(
					'sidebar' => array( 'global' => true )
				)
			)
		) );
		$this->assertTrue( md_has_layout( 'sidebar' ) );

		md_test_set_option( 'marketers_delight', array(
			'post' => array(
				'layout' => array(
					'sidebar' => array( 'global' => true ),
					'sidebar_single_show' => array( 'disable' => true )
				)
			)
		) );
		$this->assertFalse( md_has_layout( 'sidebar' ) );
	}

	public function test_single_remove_overrides_page_type_enable() {
		md_test_set_query( array(
			'is_singular' => true,
			'queried_object_id' => 1,
			'post_type' => 'post'
		) );
		md_test_set_option( 'marketers_delight', array(
			'post' => array(
				'layout' => array(
					'sidebar_single_show' => array( 'enable' => true )
				)
			)
		) );
		md_test_set_post_meta( array(
			'layout' => array(
				'sidebar' => array( 'remove' => true )
			)
		) );

		$this->assertFalse( md_has_layout( 'sidebar' ) );
	}

	public function test_exclude_single_returns_inherited_visibility() {
		md_test_set_query( array(
			'is_singular' => true,
			'queried_object_id' => 1,
			'post_type' => 'post'
		) );
		md_test_set_option( 'marketers_delight', array(
			'post' => array(
				'layout' => array(
					'sidebar' => array( 'global' => true )
				)
			)
		) );
		md_test_set_post_meta( array(
			'layout' => array(
				'sidebar' => array( 'remove' => true )
			)
		) );

		$this->assertTrue( md_has_layout( 'sidebar', array( 'exclude_single' => true ) ) );
	}

	public function test_footer_columns_returns_only_active_columns() {
		md_test_set_active_sidebars( array( 'md-footer-col-1', 'md-footer-col-3' ) );

		$this->assertSame( array( 1, 3 ), md_footer_columns() );
		$this->assertTrue( md_has_footer_columns() );
	}

	public function test_individual_layout_id_wins_over_other_tiers() {
		md_test_set_query( array(
			'is_singular' => true,
			'queried_object_id' => 1,
			'post_type' => 'post'
		) );
		md_test_set_option( 'marketers_delight', array(
			'settings' => array(
				'sidebars' => array(
					'sidebar-post' => array( 'name' => 'Post' ),
					'sidebar-global' => array( 'name' => 'Global' )
				)
			),
			'post' => array(
				'layout' => array(
					'sidebar_single' => 'sidebar-global'
				)
			)
		) );
		md_test_set_post_meta( array(
			'layout' => array(
				'custom_sidebar' => 'sidebar-post'
			)
		) );

		$this->assertSame( 'sidebar-post', md_get_layout_id( 'sidebar' ) );
	}

	public function test_associated_term_layout_id_wins_over_post_type_context() {
		md_test_set_query( array(
			'is_singular' => true,
			'queried_object_id' => 1,
			'post_type' => 'post'
		) );
		md_test_set_option( 'marketers_delight', array(
			'settings' => array(
				'sidebars' => array(
					'sidebar-term' => array( 'name' => 'Term' ),
					'sidebar-global' => array( 'name' => 'Global' )
				)
			),
			'post' => array(
				'layout' => array(
					'sidebar_single' => 'sidebar-global'
				)
			)
		) );
		md_test_set_taxonomy( 'category' );
		md_test_set_object_taxonomies( 'post', array( 'category' ) );
		md_test_set_post_terms( 1, 'category', array( 10 ) );
		md_test_set_term_meta( 10, array( 'layout' => array( 'entries_sidebar' => 'sidebar-term' ) ) );

		$this->assertSame( 'sidebar-term', md_get_layout_id( 'sidebar' ) );
	}

	public function test_stale_layout_id_falls_back_to_default() {
		md_test_set_query( array(
			'is_post_type_archive' => true,
			'post_type' => 'post'
		) );
		md_test_set_option( 'marketers_delight', array(
			'post' => array(
				'layout' => array(
					'sidebar_archive' => 'deleted-sidebar'
				)
			)
		) );

		$this->assertSame( 'sidebar-main', md_get_layout_id( 'sidebar' ) );
	}

	public function test_deepest_term_layout_wins() {
		md_test_set_query( array( 'post_type' => 'post' ) );
		md_test_set_taxonomy( 'category' );
		md_test_set_object_taxonomies( 'post', array( 'category' ) );
		md_test_set_post_terms( 1, 'category', array( 10, 20 ) );
		md_test_set_ancestors( 20, 'category', array( 10 ) );
		md_test_set_term_meta( 10, array( 'layout' => array( 'entries_sidebar' => 'sidebar-parent' ) ) );
		md_test_set_term_meta( 20, array( 'layout' => array( 'entries_sidebar' => 'sidebar-child' ) ) );

		$this->assertSame( 'sidebar-child', md_get_layout_term( 'sidebar', 1 ) );
	}

	public function test_taxonomy_order_is_filterable() {
		md_test_set_query( array( 'post_type' => 'post' ) );
		md_test_set_taxonomy( 'category' );
		md_test_set_taxonomy( 'topic' );
		md_test_set_object_taxonomies( 'post', array( 'category', 'topic' ) );
		md_test_set_post_terms( 1, 'category', array( 10 ) );
		md_test_set_post_terms( 1, 'topic', array( 20 ) );
		md_test_set_term_meta( 10, array( 'layout' => array( 'entries_sidebar' => 'sidebar-category' ) ) );
		md_test_set_term_meta( 20, array( 'layout' => array( 'entries_sidebar' => 'sidebar-topic' ) ) );

		$this->assertSame( 'sidebar-category', md_get_layout_term( 'sidebar', 1 ) );

		md_test_set_filter( 'md_layout_taxonomies', array( 'topic', 'category' ) );

		$this->assertSame( 'sidebar-topic', md_get_layout_term( 'sidebar', 1 ) );
	}

	public function test_has_filter_can_override_false_state() {
		md_test_set_option( 'marketers_delight', array(
			'layout' => array(
				'content' => array( 'remove' => true )
			)
		) );
		md_test_set_filter( 'md_filter_has_content_box', true );

		$this->assertTrue( md_has_content_box() );
	}

	public function test_layout_predicate_filters_return_booleans() {
		md_test_set_filter( 'md_filter_has_content_box', 0 );
		$this->assertSame( false, md_has_content_box() );
		md_test_set_filter( 'md_filter_has_content_box', 1 );
		$this->assertSame( true, md_has_content_box() );

		md_test_set_filter( 'md_filter_has_sidebar', 0 );
		$this->assertSame( false, md_has_sidebar() );
		md_test_set_filter( 'md_filter_has_sidebar', 1 );
		$this->assertSame( true, md_has_sidebar() );

		md_test_set_filter( 'md_filter_has_panel', 0 );
		$this->assertSame( false, md_has_panel() );
		md_test_set_filter( 'md_filter_has_panel', 1 );
		$this->assertSame( true, md_has_panel() );

		md_test_set_filter( 'md_filter_has_footer', 0 );
		$this->assertSame( false, md_has_footer() );
		md_test_set_filter( 'md_filter_has_footer', 1 );
		$this->assertSame( true, md_has_footer() );

		md_test_set_filter( 'md_filter_has_footer_columns', 0 );
		$this->assertSame( false, md_has_footer_columns() );
		md_test_set_filter( 'md_filter_has_footer_columns', 1 );
		$this->assertSame( true, md_has_footer_columns() );
	}

}
