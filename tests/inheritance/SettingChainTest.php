<?php
/**
 * Tests the raw storage primitives the whole inheritance system is built on:
 * md_setting(), md_post_type_field(), md_taxonomy_field(), md_term_meta().
 * These are exercised directly (not re-implemented) from functions/meta-functions.php.
 *
 * @since 6.0
 */

#[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
class SettingChainTest extends MD_InheritanceTestCase {

	// md_setting() falls back to the md_setting_defaults filter when the
	// option itself has no value, and the stored option wins when both exist.

	public function test_setting_falls_back_to_global_defaults_filter() {
		md_test_set_filter( 'md_setting_defaults', array( 'loop' => array( 'columns' => 2 ) ) );

		$this->assertSame( 2, md_setting( array( 'loop', 'columns' ) ) );
	}

	public function test_setting_option_value_overrides_global_default() {
		md_test_set_filter( 'md_setting_defaults', array( 'loop' => array( 'columns' => 2 ) ) );
		md_test_set_option( 'marketers_delight', array( 'loop' => array( 'columns' => 4 ) ) );

		$this->assertSame( 4, md_setting( array( 'loop', 'columns' ) ) );
	}

	public function test_setting_preserves_stored_falsey_values() {
		md_test_set_option( 'marketers_delight', array(
			'disabled' => false,
			'count' => 0,
			'value' => '0'
		) );

		$this->assertFalse( md_setting( 'disabled', true ) );
		$this->assertSame( 0, md_setting( 'count', 10 ) );
		$this->assertSame( '0', md_setting( 'value', 'fallback' ) );
	}

	public function test_setting_uses_caller_default_for_missing_path() {
		md_test_set_option( 'marketers_delight', array() );

		$this->assertSame( 'fallback', md_setting( array( 'missing', 'value' ), 'fallback' ) );
	}

	public function test_custom_option_merges_its_own_defaults_and_stored_values() {
		md_test_set_filter( 'md_setting_defaults_client_portal', array(
			'panel' => array( 'enabled' => true, 'columns' => 2 )
		) );
		md_test_set_option( 'client_portal', array(
			'panel' => array( 'enabled' => false )
		) );

		$this->assertFalse( md_option( 'client_portal', array( 'panel', 'enabled' ), true ) );
		$this->assertSame( 2, md_option( 'client_portal', array( 'panel', 'columns' ) ) );
	}

	// md_post_type_field() reads marketers_delight[$post_type][...] and falls
	// through to the given $default when nothing is stored there.

	public function test_post_type_field_reads_its_own_namespace() {
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'loop' => array( 'posts_per_page' => 6 ) ) ) );

		$this->assertSame( 6, md_post_type_field( array( 'loop', 'posts_per_page' ), 12, 'post' ) );
	}

	public function test_unknown_post_type_has_no_settings_parent() {
		$this->assertNull( md_post_type_settings_parent( 'missing_post_type' ) );
	}

	public function test_post_type_field_falls_through_to_default_when_unset() {
		md_test_set_option( 'marketers_delight', array() );

		$this->assertSame( 12, md_post_type_field( array( 'loop', 'posts_per_page' ), 12, 'post' ) );
	}

	public function test_post_type_field_without_keys_returns_full_namespace() {
		$post_type = array(
			'loop' => array( 'posts_per_page' => 6 ),
			'layout' => array( 'sidebar' => true )
		);
		md_test_set_option( 'marketers_delight', array( 'post' => $post_type ) );

		$this->assertSame( $post_type, md_post_type_field( null, array(), 'post' ) );
	}

	public function test_post_type_field_inherits_parent_settings() {
		md_test_set_settings_parent( 'book_quote', 'bookshelf' );
		md_test_set_option( 'marketers_delight', array(
			'bookshelf' => array( 'layout' => array( 'content_box' => 'plain' ) )
		) );

		$this->assertSame( 'plain', md_post_type_field( array( 'layout', 'content_box' ), null, 'book_quote' ) );
	}

	public function test_post_type_field_merges_child_over_parent() {
		md_test_set_settings_parent( 'book_quote', 'bookshelf' );
		md_test_set_option( 'marketers_delight', array(
			'bookshelf' => array(
				'layout' => array( 'content_box' => 'plain', 'sidebar' => 'right' ),
				'loop' => array( 'columns' => 5 )
			),
			'book_quote' => array(
				'layout' => array( 'sidebar' => 'none' )
			)
		) );

		$this->assertSame( array(
			'layout' => array( 'content_box' => 'plain', 'sidebar' => 'none' ),
			'loop' => array( 'columns' => 5 )
		), md_post_type_field( null, array(), 'book_quote' ) );
	}

	public function test_post_type_field_child_defaults_override_parent_values() {
		md_test_set_settings_parent( 'book_quote', 'bookshelf' );
		md_test_set_filter( 'md_setting_defaults', array(
			'book_quote' => array( 'loop' => array( 'columns' => 1 ) )
		) );
		md_test_set_option( 'marketers_delight', array(
			'bookshelf' => array( 'loop' => array( 'columns' => 5 ) )
		) );

		$this->assertSame( 1, md_post_type_field( array( 'loop', 'columns' ), null, 'book_quote' ) );
	}

	public function test_post_type_field_preserves_falsey_child_overrides() {
		md_test_set_settings_parent( 'book_quote', 'bookshelf' );
		md_test_set_option( 'marketers_delight', array(
			'bookshelf' => array( 'enabled' => true, 'count' => 10 ),
			'book_quote' => array( 'enabled' => false, 'count' => 0 )
		) );

		$this->assertFalse( md_post_type_field( 'enabled', true, 'book_quote' ) );
		$this->assertSame( 0, md_post_type_field( 'count', 10, 'book_quote' ) );
	}

	public function test_post_type_field_stops_circular_parent_chains() {
		md_test_set_settings_parent( 'book_quote', 'bookshelf' );
		md_test_set_settings_parent( 'bookshelf', 'book_quote' );
		md_test_set_option( 'marketers_delight', array(
			'bookshelf' => array( 'layout' => array( 'sidebar' => 'right' ) ),
			'book_quote' => array( 'layout' => array( 'sidebar' => 'none' ) )
		) );

		$this->assertSame( 'none', md_post_type_field( array( 'layout', 'sidebar' ), null, 'book_quote' ) );
	}

	public function test_collection_builder_replaces_defaults_in_its_configured_area() {
		md_test_set_filter( 'md_setting_defaults', array(
			'bookshelf' => array( 'archive_meta' => array( 'builder' => array(
				'default-meta' => array( 'builder_area' => 'archives', 'builder_type' => 'post_count' )
			) ) )
		) );
		md_setting_defaults( true );
		md_test_set_option( 'marketers_delight', array(
			'bookshelf' => array( 'archive_meta' => array( 'builder' => array(
				'custom-meta' => array( 'builder_area' => 'archives', 'builder_type' => 'last_added' )
			) ) )
		) );

		$this->assertSame( array(
			'custom-meta' => array( 'builder_area' => 'archives', 'builder_type' => 'last_added' )
		), md_get_post_type_builder( 'archive_meta', 'bookshelf' ) );
	}

	public function test_stored_empty_builder_falls_back_to_post_type_defaults() {
		md_test_set_filter( 'md_setting_defaults', array(
			'bookshelf' => array( 'archive_sections' => array( 'builder' => array(
				'default-highlight' => array(
					'builder_area' => 'before_loop',
					'builder_type' => 'bookshelf_highlight'
				)
			) ) )
		) );
		md_setting_defaults( true );
		md_test_set_option( 'marketers_delight', array(
			'bookshelf' => array( 'archive_sections' => array( 'builder' => array() ) )
		) );

		$this->assertSame( array(
			'default-highlight' => array(
				'builder_area' => 'before_loop',
				'builder_type' => 'bookshelf_highlight'
			)
		), md_get_post_type_builder( 'archive_sections', 'bookshelf' ) );
	}

	public function test_term_meta_preserves_explicit_empty_builder() {
		md_test_set_term_meta( 42, array(
			'archive_sections' => array( 'builder' => array() )
		) );

		$this->assertSame( array(), md_term_meta( array( 'archive_sections', 'builder' ), 42, null ) );
	}

	// md_taxonomy_field() reads marketers_delight[$post_type][$taxonomy][...] —
	// a distinct storage path one level deeper than the post-type tier, matching
	// where md_save::save_taxonomy() writes taxonomy-tab submissions.

	public function test_taxonomy_field_is_a_distinct_storage_path_from_post_type() {
		md_test_set_option( 'marketers_delight', array(
			'post' => array(
				'loop' => array( 'posts_per_page' => 6 ),
				'category' => array( 'loop' => array( 'posts_per_page' => 9 ) )
			)
		) );

		// Setting the taxonomy tier doesn't leak into a post-type-tier read...

		$this->assertSame( 6, md_post_type_field( array( 'loop', 'posts_per_page' ), null, 'post' ) );

		// ...and the post-type tier's value doesn't leak into a taxonomy-tier
		// read either — each is looked up independently, not merged automatically.

		$this->assertSame( 9, md_taxonomy_field( array( 'loop', 'posts_per_page' ), null, 'post', 'category' ) );
	}

	public function test_taxonomy_field_falls_through_to_default_when_unset() {
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'loop' => array( 'posts_per_page' => 6 ) ) ) );

		// No 'category' key stored at all — must return the passed default,
		// not silently inherit the post-type tier's value (that inheritance is
		// the caller's job, e.g. loop_query_vars(), not md_taxonomy_field()'s).

		$this->assertSame( 12, md_taxonomy_field( array( 'loop', 'posts_per_page' ), 12, 'post', 'category' ) );
	}

	// md_term_meta() reads a completely separate storage location (term meta,
	// not the marketers_delight option), so option-tier writes never satisfy it.

	public function test_term_meta_is_independent_of_the_options_table() {
		md_test_set_option( 'marketers_delight', array( 'post' => array( 'category' => array( 'loop' => array( 'posts_per_page' => 9 ) ) ) ) );
		md_test_set_term_meta( 42, array( 'loop' => array( 'posts_per_page' => 3 ) ) );

		$this->assertSame( 3, md_term_meta( array( 'loop', 'posts_per_page' ), 42, null ) );
	}

	public function test_term_meta_falls_through_to_default_when_unset() {
		md_test_set_term_meta( 42, array() );

		$this->assertSame( 9, md_term_meta( array( 'loop', 'posts_per_page' ), 42, 9 ) );
	}

	public function test_meta_readers_accept_single_multiple_and_omitted_keys() {
		$meta = array(
			'profile' => array(
				'name' => 'Alex'
			)
		);
		md_test_set_post_meta( $meta );
		md_test_set_term_meta( 42, $meta );
		md_test_set_user_meta( 7, $meta );

		$this->assertSame( $meta['profile'], md_post_meta( 'profile', 1 ) );
		$this->assertSame( 'Alex', md_term_meta( array( 'profile', 'name' ), 42 ) );
		$this->assertSame( $meta, md_user_meta( null, 7 ) );
	}

	public function test_post_meta_true_selects_queried_object_only() {
		md_test_set_query( array( 'queried_object_id' => 42 ) );
		md_test_set_post_meta( array( 'source' => 'current' ), 1 );
		md_test_set_post_meta( array( 'source' => 'queried' ), 42 );

		$this->assertSame( 'queried', md_post_meta( 'source', true ) );
		$this->assertSame( 'current', md_post_meta( 'source', false ) );
		$this->assertSame( 'current', md_post_meta( 'source', array( 42 ) ) );
	}

}
