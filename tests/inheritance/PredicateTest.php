<?php
/**
 * Tests page and comment predicate return contracts.
 *
 * @since 6.0
 */

class PredicateTest extends MD_InheritanceTestCase {

	public function test_builder_returns_boolean() {
		$this->assertSame( false, md_has_builder() );

		md_test_set_post_meta( array(
			'layout' => array(
				'content' => array(
					'builder' => 1
				)
			)
		) );

		$this->assertSame( true, md_has_builder() );
	}

	public function test_breadcrumbs_return_boolean() {
		md_test_set_query( array( 'post_type' => 'post' ) );
		$this->assertSame( false, md_has_breadcrumbs() );

		md_test_set_option( 'marketers_delight', array(
			'post' => array(
				'layout' => array(
					'breadcrumbs' => array(
						'add' => true
					)
				)
			)
		) );
		$this->assertSame( true, md_has_breadcrumbs() );

		md_test_set_post_meta( array(
			'layout' => array(
				'breadcrumbs' => array(
					'remove' => true
				)
			)
		) );
		$this->assertSame( false, md_has_breadcrumbs() );
	}

	public function test_author_box_returns_boolean() {
		md_test_set_query( array( 'post_type' => 'post' ) );
		$this->assertSame( false, md_has_author_box() );

		md_test_set_query( array( 'is_singular' => true ) );
		md_test_set_option( 'marketers_delight', array(
			'post' => array(
				'layout' => array(
					'content' => array(
						'add_author_box' => true
					)
				)
			)
		) );
		$this->assertSame( true, md_has_author_box() );

		md_test_set_post_meta( array(
			'layout' => array(
				'content' => array(
					'author_box' => true
				)
			)
		) );
		$this->assertSame( false, md_has_author_box() );
	}

	public function test_post_navigation_returns_boolean() {
		md_test_set_query( array(
			'is_singular' => true,
			'post_type' => 'post',
			'previous_post' => true
		) );
		$this->assertSame( true, md_has_post_nav() );

		md_test_set_query( array( 'is_page' => true ) );
		$this->assertSame( false, md_has_post_nav() );
	}

	public function test_comments_return_boolean() {
		$this->assertSame( false, md_has_comments() );

		md_test_set_query( array( 'comments_open' => true ) );
		$this->assertSame( true, md_has_comments() );

		md_test_set_query( array( 'post_password_required' => true ) );
		$this->assertSame( false, md_has_comments() );

		md_test_set_query( array(
			'comments_open' => false,
			'comments_number' => 1,
			'post_password_required' => false
		) );
		$this->assertSame( true, md_has_comments() );
	}

	public function test_post_content_returns_boolean() {
		$this->assertSame( true, md_has_post_content() );

		md_test_set_filter( 'md_filter_has_the_content', 0 );
		$this->assertSame( false, md_has_post_content() );

		md_test_set_query( array(
			'is_singular' => true,
			'post_type' => 'post'
		) );
		md_test_set_post_meta( array(
			'layout' => array(
				'content' => array(
					'the_content' => true
				)
			)
		) );
		md_test_set_filter( 'md_filter_has_the_content', true );

		$this->assertSame( false, md_has_post_content() );
	}

	public function test_template_filter_preserves_exact_false_contract() {
		$this->assertSame( true, md_filter_template() );

		md_test_set_filter( 'md_filter_has_template', false );
		$this->assertSame( false, md_filter_template() );

		md_test_set_filter( 'md_filter_has_template', 0 );
		$this->assertSame( true, md_filter_template() );

		md_test_set_filter( 'md_filter_has_template', '' );
		$this->assertSame( true, md_filter_template() );
	}

}
