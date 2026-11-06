<?php
/**
 * Tests md_save::apply(), the one save path for settings screens, post
 * type and taxonomy settings, meta boxes, terms and users: every scope,
 * form and patch modes, null resets and dry runs.
 *
 * @since 6.2
 */

class SaveApplyTest extends MD_TestCase {

	private $save;

	private $fields = array(
		'title' => array( 'type' => 'text' ),
		'style' => array( 'type' => 'select', 'options' => array( 'box', 'plain' ) ),
		'show' => array( 'type' => 'checkbox', 'options' => array( 'date', 'author' ) ),
		'count' => array( 'type' => 'number' ),
		'rows' => array( 'type' => 'builder', 'fields' => array( 'name' => array( 'type' => 'text' ) ) )
	);

	protected function setUp(): void {
		parent::setUp();
		$this->save = new md_save;
	}

	private function register( $settings, $groups ) {
		md_test_set_filter( 'md_register', array( $settings => $groups ) );
	}

	private function apply( $scope, $group, $input, $args = array() ) {
		return $this->save->apply( $scope, $group, $input, array_merge( array( 'compile' => false ), $args ) );
	}

	// Scopes

	public function test_scope_strings_parse_to_type_name_and_id() {
		$cases = array(
			'site' => array( 'site', '', '', 0 ),
			'post_type:docs' => array( 'post_type', 'docs', 'docs', 0 ),
			'taxonomy:post/category' => array( 'taxonomy', 'category', 'post', 0 ),
			'term:12' => array( 'term', '', '', 12 ),
			'post:7' => array( 'post', '', '', 7 ),
			'user:3' => array( 'user', '', '', 3 ),
			'post' => array( 'post', '', '', 0 )
		);

		foreach ( $cases as $string => $expected ) {
			$scope = md_scope::parse( $string );
			$this->assertSame( $expected, array( $scope->type, $scope->name, $scope->post_type, $scope->id ), $string );
		}

		foreach ( array( '', 'sites', 'site:x', 'post:0', 'post:abc', 'term:', 'taxonomy:category', 'taxonomy:Bad Name', 'post_type:', 'taxonomy:post/', 'taxonomy:Bad/category' ) as $string )
			$this->assertTrue( is_wp_error( md_scope::parse( $string ) ), "$string is rejected" );
	}

	public function test_saving_to_an_object_needs_its_id() {
		$this->register( 'meta_boxes', array( 'layout' => array( 'fields' => $this->fields ) ) );

		$this->assertSame( 'md_save_scope', $this->apply( 'post', 'layout', array( 'title' => 'A' ) )->get_error_code() );
	}

	public function test_site_scope_saves_the_group_branch_and_keeps_the_rest() {
		$this->register( 'admin_pages', array( 'layout' => array( 'fields' => $this->fields ) ) );
		md_test_set_option( 'marketers_delight', array(
			'layout' => array( 'title' => 'Old', 'style' => 'box' ),
			'license' => array( 'status' => 'valid' )
		) );

		$result = $this->apply( 'site', 'layout', array( 'title' => 'New' ) );
		$option = get_option( 'marketers_delight' );

		$this->assertSame( array( 'title' => 'New', 'style' => 'box' ), $option['layout'] );
		$this->assertSame( array( 'status' => 'valid' ), $option['license'] );
		$this->assertSame( array( array( 'key' => 'title', 'before' => 'Old', 'after' => 'New' ) ), $result['changed'] );
		$this->assertSame( 'site', $result['scope'] );
		$this->assertSame( 'layout', $result['group'] );
	}

	public function test_site_scope_writes_a_custom_option_through_its_save_filter() {
		$this->register( 'admin_pages', array( 'portal' => array( '_option' => 'client_portal', 'fields' => $this->fields ) ) );
		md_test_set_option( 'client_portal', array( 'internal' => array( 'version' => 2 ) ) );
		md_test_set_filter( 'md_save_client_portal', array( 'filtered' => true ) );

		$this->apply( 'site', 'portal', array( 'title' => 'Portal' ) );

		$this->assertSame( array( 'filtered' => true ), get_option( 'client_portal' ) );
		$this->assertFalse( get_option( 'marketers_delight' ) );
	}

	public function test_post_type_scope_saves_the_host_branch_or_one_child_group() {
		$this->register( 'admin_pages', array( 'docs' => array( 'has_groups' => true, 'fields' => array(
			'archives_title' => array( 'type' => 'text' ),
			'layout' => $this->fields
		) ) ) );
		md_test_set_option( 'marketers_delight', array( 'docs' => array(
			'archives_title' => 'Docs',
			'layout' => array( 'style' => 'box' ),
			'docs_category' => array( 'layout' => array( 'style' => 'plain' ) )
		) ) );

		$this->apply( 'post_type:docs', 'layout', array( 'title' => 'Docs layout' ) );
		$this->apply( 'post_type:docs', 'docs', array( 'archives_title' => 'Handbook' ) );
		$option = get_option( 'marketers_delight' );

		$this->assertSame( array( 'style' => 'box', 'title' => 'Docs layout' ), $option['docs']['layout'] );
		$this->assertSame( 'Handbook', $option['docs']['archives_title'] );
		$this->assertSame( array( 'layout' => array( 'style' => 'plain' ) ), $option['docs']['docs_category'], 'Taxonomy settings inside the host stay' );
	}

	public function test_post_type_scope_rejects_a_field_or_unknown_name_as_group() {
		$this->register( 'admin_pages', array( 'docs' => array( 'has_groups' => true, 'fields' => array(
			'archives_title' => array( 'type' => 'text' ),
			'layout' => $this->fields
		) ) ) );

		$this->assertTrue( is_wp_error( $this->apply( 'post_type:docs', 'archives_title', array() ) ) );
		$this->assertTrue( is_wp_error( $this->apply( 'post_type:docs', 'missing', array() ) ) );
		$this->assertTrue( is_wp_error( $this->apply( 'post_type:books', 'layout', array() ) ) );
	}

	public function test_taxonomy_scope_saves_under_its_post_type_and_prunes() {
		$this->register( 'admin_pages', array( 'post' => array( 'has_groups' => true, 'fields' => array( 'layout' => $this->fields ) ) ) );
		md_test_set_filter( 'md_taxonomy_groups', array( 'md_post' => array( 'category' => true ) ) );
		md_test_set_option( 'marketers_delight', array( 'post' => array(
			'layout' => array( 'style' => 'box' ),
			'category' => array( 'layout' => array( 'style' => 'plain', 'title' => 'Old' ) )
		) ) );

		$this->apply( 'taxonomy:post/category', 'layout', array( 'title' => '' ) );
		$this->assertSame( array( 'style' => 'plain' ), get_option( 'marketers_delight' )['post']['category']['layout'] );

		$this->apply( 'taxonomy:post/category', 'layout', array( 'style' => null ) );
		$option = get_option( 'marketers_delight' );

		$this->assertArrayNotHasKey( 'category', $option['post'], 'An emptied taxonomy branch is removed' );
		$this->assertSame( array( 'style' => 'box' ), $option['post']['layout'] );
	}

	public function test_a_taxonomy_scope_names_its_post_type() {
		$this->register( 'admin_pages', array(
			'book' => array( 'has_groups' => true, 'fields' => array( 'layout' => $this->fields ) ),
			'quote' => array( 'has_groups' => true, 'fields' => array( 'layout' => $this->fields ) )
		) );
		md_test_set_filter( 'md_taxonomy_groups', array(
			'md_book' => array( 'topic' => true ),
			'md_quote' => array( 'topic' => true )
		) );

		$this->assertSame( 'md_save_scope', $this->apply( 'taxonomy:topic', 'layout', array( 'title' => 'A' ) )->get_error_code() );
		$this->assertSame( 'md_save_scope', $this->apply( 'taxonomy:docs/topic', 'layout', array( 'title' => 'A' ) )->get_error_code(), 'A taxonomy its post type has no settings for' );

		$this->apply( 'taxonomy:quote/topic', 'layout', array( 'title' => 'Quotes' ) );

		$this->assertSame( array( 'quote' => array( 'topic' => array( 'layout' => array( 'title' => 'Quotes' ) ) ) ), get_option( 'marketers_delight' ) );
	}

	public function test_term_scope_writes_term_meta_and_deletes_it_when_empty() {
		$this->register( 'terms', array( 'layout' => array( 'fields' => $this->fields ) ) );
		update_term_meta( 5, 'marketers_delight', array( 'layout' => array( 'title' => 'Old' ), 'internal' => array( 'id' => 7 ) ) );

		$this->apply( 'term:5', 'layout', array( 'style' => 'plain' ) );
		$this->assertSame( array( 'layout' => array( 'title' => 'Old', 'style' => 'plain' ), 'internal' => array( 'id' => 7 ) ), get_term_meta( 5, 'marketers_delight', true ) );

		update_term_meta( 6, 'marketers_delight', array( 'layout' => array( 'title' => 'Old' ) ) );
		$this->apply( 'term:6', 'layout', array( 'title' => null ) );
		$this->assertSame( '', get_term_meta( 6, 'marketers_delight', true ) );
	}

	public function test_term_scope_needs_manage_options() {
		$this->register( 'terms', array( 'layout' => array( 'fields' => $this->fields ) ) );
		md_test_set_capability( 'manage_options', false );

		$this->assertSame( 'md_save_forbidden', $this->apply( 'term:5', 'layout', array( 'title' => 'A' ) )->get_error_code() );
		$this->assertSame( '', get_term_meta( 5, 'marketers_delight', true ) );
	}

	public function test_user_scope_writes_user_meta() {
		$this->register( 'user_meta', array( 'author' => array( 'fields' => $this->fields ) ) );

		$this->apply( 'user:3', 'author', array( 'title' => 'Editor' ) );

		$this->assertSame( array( 'author' => array( 'title' => 'Editor' ) ), get_user_meta( 3, 'marketers_delight', true ) );
	}

	public function test_post_scope_writes_post_meta_and_skips_groups_the_editor_cant_change() {
		$this->register( 'meta_boxes', array(
			'page_cover' => array( 'edit_post' => true, 'fields' => $this->fields ),
			'layout' => array( 'fields' => $this->fields )
		) );
		md_test_set_post( array( 'ID' => 10, 'post_type' => 'page' ) );
		md_test_set_capability( 'manage_options', false );

		$result = $this->apply( 'post:10', array( 'page_cover', 'layout' ), array(
			'page_cover' => array( 'title' => 'Cover' ),
			'layout' => array( 'style' => 'plain' )
		) );

		$this->assertSame( array( 'page_cover' => array( 'title' => 'Cover' ) ), get_post_meta( 10, 'marketers_delight', true ) );
		$this->assertCount( 1, $result['warnings'] );
		$this->assertSame( 'page_cover.title', $result['changed'][0]['key'], 'A list of groups keys each change by its group' );
	}

	public function test_post_scope_form_hands_validated_groups_to_the_save_filter() {
		$this->register( 'meta_boxes', array( 'layout' => array( 'fields' => $this->fields ) ) );
		md_test_set_post( array( 'ID' => 10, 'post_type' => 'post' ) );
		md_test_set_filter( 'md_post_meta_save', array(
			'layout' => array( 'title' => 'Filtered' ),
			'table_of_contents' => array( 'list' => array( 'h2' => 'Intro' ) )
		) );

		$this->apply( 'post:10', array( 'layout' ), array( 'layout' => array( 'title' => 'Typed' ) ) );

		$this->assertSame( array(
			'layout' => array( 'title' => 'Filtered' ),
			'table_of_contents' => array( 'list' => array( 'h2' => 'Intro' ) )
		), get_post_meta( 10, 'marketers_delight', true ), 'Groups the filter adds without fields are stored as given' );
	}

	public function test_post_scope_moves_standalone_fields_to_their_own_keys_and_clears_them() {
		$this->register( 'meta_boxes', array( 'bookshelf' => array( 'fields' => array(
			'review_title' => array( 'type' => 'text' ),
			'book_author' => array( 'type' => 'text', 'standalone' => true )
		) ) ) );
		md_test_set_post( array( 'ID' => 25, 'post_type' => 'bookshelf' ) );

		$result = $this->apply( 'post:25', 'bookshelf', array( 'review_title' => 'Great', 'book_author' => 'Le Guin' ) );

		$this->assertSame( 'Le Guin', get_post_meta( 25, 'book_author', true ) );
		$this->assertSame( array( 'bookshelf' => array( 'review_title' => 'Great' ) ), get_post_meta( 25, 'marketers_delight', true ) );

		$result = $this->apply( 'post:25', 'bookshelf', array( 'book_author' => null ) );

		$this->assertSame( '', get_post_meta( 25, 'book_author', true ) );
		$this->assertSame( array( array( 'key' => 'book_author', 'before' => 'Le Guin', 'after' => null ) ), $result['changed'] );
	}

	// Modes

	public function test_a_form_posts_empty_partners_for_unchecked_boxes_and_keeps_fields_it_left_out() {
		$this->register( 'admin_pages', array( 'layout' => array( 'fields' => $this->fields ) ) );
		md_test_set_option( 'marketers_delight', array( 'layout' => array(
			'title' => 'Old',
			'show' => array( 'date' => true ),
			'rows' => array( 'a' => array( 'name' => 'Row' ) )
		) ) );

		$this->apply( 'site', 'layout', array( 'style' => 'plain', 'show' => array( 'date' => '', 'author' => '' ), 'rows' => '' ) );

		$this->assertSame( array( 'layout' => array( 'title' => 'Old', 'style' => 'plain' ) ), get_option( 'marketers_delight' ), 'Empty partners clear, a field left out keeps its value' );
	}

	public function test_only_given_keys_change() {
		$this->register( 'admin_pages', array( 'layout' => array( 'fields' => $this->fields ) ) );
		md_test_set_option( 'marketers_delight', array( 'layout' => array(
			'title' => 'Old',
			'show' => array( 'date' => true ),
			'count' => 4
		) ) );

		$this->apply( 'site', 'layout', array( 'show' => array( 'author' => '1' ) ) );

		$this->assertSame( array( 'title' => 'Old', 'show' => array( 'author' => true ), 'count' => 4 ), get_option( 'marketers_delight' )['layout'] );
	}

	public function test_patch_null_resets_a_key_and_empties_its_parents() {
		$this->register( 'admin_pages', array( 'page' => array( 'fields' => array(
			'section' => array(
				'title' => array( 'type' => 'text' ),
				'style' => array( 'type' => 'text' )
			),
			'name' => array( 'type' => 'text' )
		) ) ) );
		md_test_set_option( 'marketers_delight', array( 'page' => array( 'section' => array( 'title' => 'A' ), 'name' => 'Keep' ) ) );

		$this->apply( 'site', 'page', array( 'section' => array( 'title' => null ) ) );

		$this->assertSame( array( 'page' => array( 'name' => 'Keep' ) ), get_option( 'marketers_delight' ) );
	}

	public function test_patch_reports_unknown_and_invalid_keys_without_saving_them() {
		$this->register( 'admin_pages', array( 'layout' => array( 'fields' => array_merge( $this->fields, array(
			'logo' => array( 'type' => 'upload', 'upload_type' => 'file' ),
			'section' => array( 'title' => array( 'type' => 'text' ) )
		) ) ) ) );

		$result = $this->apply( 'site', 'layout', array(
			'title' => 'Kept',
			'nope' => 'x',
			'logo' => array( 'id' => 3 ),
			'section' => 'not a set'
		) );

		$this->assertSame( array( 'layout' => array( 'title' => 'Kept' ) ), get_option( 'marketers_delight' ) );
		$this->assertCount( 3, $result['warnings'] );
	}

	public function test_patch_replaces_a_builder_with_the_given_rows() {
		$this->register( 'admin_pages', array( 'layout' => array( 'fields' => $this->fields ) ) );
		md_test_set_option( 'marketers_delight', array( 'layout' => array( 'rows' => array(
			'a' => array( 'name' => 'A' ),
			'b' => array( 'name' => 'B' )
		) ) ) );

		$this->apply( 'site', 'layout', array( 'rows' => array( 'b' => array( 'name' => 'B2' ), 'c' => array( 'name' => 'C' ) ) ) );

		$this->assertSame( array( 'b' => array( 'name' => 'B2' ), 'c' => array( 'name' => 'C' ) ), get_option( 'marketers_delight' )['layout']['rows'] );
	}

	public function test_select_values_are_validated() {
		$this->register( 'admin_pages', array( 'layout' => array( 'fields' => $this->fields ) ) );

		$this->apply( 'site', 'layout', array( 'style' => 'fancy', 'title' => 'T' ) );
		$this->assertSame( array( 'layout' => array( 'title' => 'T' ) ), get_option( 'marketers_delight' ) );
	}

	// Results

	public function test_dry_run_returns_the_diff_and_writes_nothing() {
		$this->register( 'admin_pages', array( 'layout' => array( 'fields' => $this->fields ) ) );
		md_test_set_option( 'marketers_delight', array( 'layout' => array( 'title' => 'Old', 'rows' => array( 'a' => array( 'name' => 'A' ) ) ) ) );

		$result = $this->apply( 'site', 'layout', array( 'title' => 'New', 'rows' => array() ), array( 'dry_run' => true ) );

		$this->assertSame( array(
			array( 'key' => 'title', 'before' => 'Old', 'after' => 'New' ),
			array( 'key' => 'rows.a.name', 'before' => 'A', 'after' => null )
		), $result['changed'] );
		$this->assertSame( array( 'layout' => array( 'title' => 'Old', 'rows' => array( 'a' => array( 'name' => 'A' ) ) ) ), get_option( 'marketers_delight' ) );
	}

	public function test_unchanged_values_report_no_changes() {
		$this->register( 'admin_pages', array( 'layout' => array( 'fields' => $this->fields ) ) );
		md_test_set_option( 'marketers_delight', array( 'layout' => array( 'title' => 'Same' ) ) );

		$this->assertSame( array(), $this->apply( 'site', 'layout', array( 'title' => 'Same' ) )['changed'] );
	}

	public function test_unknown_groups_and_mixed_options_are_errors() {
		$this->register( 'admin_pages', array(
			'layout' => array( 'fields' => $this->fields ),
			'portal' => array( '_option' => 'client_portal', 'fields' => $this->fields ),
			'integrations' => array( 'name' => 'Integrations' )
		) );

		$this->assertSame( 'md_save_group', $this->apply( 'site', 'missing', array() )->get_error_code() );
		$this->assertSame( 'md_save_group', $this->apply( 'site', 'integrations', array() )->get_error_code(), 'A screen without fields has nothing to save' );
		$this->assertSame( 'md_save_option', $this->apply( 'site', array( 'layout', 'portal' ), array() )->get_error_code() );
		$this->assertFalse( get_option( 'marketers_delight' ) );
	}

	public function test_site_settings_need_edit_theme_options() {
		$this->register( 'admin_pages', array( 'layout' => array( 'fields' => $this->fields ) ) );
		md_test_set_capability( 'edit_theme_options', false );

		$this->assertSame( 'md_save_forbidden', $this->apply( 'site', 'layout', array( 'title' => 'A' ) )->get_error_code() );
		$this->assertFalse( get_option( 'marketers_delight' ) );
	}

	// Settings screens: fields are named md[scope][group][...]

	private function save_screen( $posted ) {
		$_POST['md'] = $posted;

		return $this->call( new md_save_settings( $this->save ), 'save_screen' );
	}

	public function test_settings_screen_saves_each_group_to_the_scope_it_names() {
		$this->register( 'admin_pages', array(
			'docs' => array( 'has_groups' => true, 'fields' => array( 'layout' => $this->fields ) ),
			'share' => array( 'fields' => $this->fields )
		) );

		$results = $this->save_screen( array(
			'post_type:docs' => array( 'docs' => array( 'layout' => array( 'title' => 'Docs' ) ) ),
			'site' => array( 'share' => array( 'title' => 'Share' ) )
		) );

		$this->assertSame( 'post_type:docs', $results[0]['scope'] );
		$this->assertSame( 'site', $results[1]['scope'] );
		$this->assertSame( array(
			'docs' => array( 'layout' => array( 'title' => 'Docs' ) ),
			'share' => array( 'title' => 'Share' )
		), get_option( 'marketers_delight' ) );
	}

	public function test_taxonomy_tab_saves_under_its_post_type() {
		$this->register( 'admin_pages', array( 'docs' => array( 'has_groups' => true, 'fields' => array( 'layout' => $this->fields ) ) ) );
		md_test_set_filter( 'md_taxonomy_groups', array( 'md_docs' => array( 'docs_category' => true ) ) );

		$this->save_screen( array( 'taxonomy:docs/docs_category' => array( 'docs' => array( 'layout' => array( 'title' => 'Category' ) ) ) ) );

		$this->assertSame( array( 'docs' => array( 'docs_category' => array( 'layout' => array( 'title' => 'Category' ) ) ) ), get_option( 'marketers_delight' ) );
	}

	public function test_screens_skip_object_scopes_and_groups_without_fields() {
		$this->register( 'admin_pages', array(
			'layout' => array( 'fields' => $this->fields ),
			'integrations' => array( 'name' => 'Integrations' )
		) );
		$this->register( 'meta_boxes', array( 'layout' => array( 'fields' => $this->fields ) ) );

		$results = $this->save_screen( array(
			'post' => array( 'layout' => array( 'title' => 'Forged' ) ),
			'site' => array( 'integrations' => array( 'key' => 'x' ) )
		) );

		$this->assertSame( array(), $results );
		$this->assertFalse( get_option( 'marketers_delight' ) );
	}

	public function test_scope_read_returns_only_a_groups_stored_values() {
		$this->register( 'admin_pages', array( 'layout' => array( 'fields' => array( 'sidebar' => array( 'type' => 'text' ) ) ) ) );
		$GLOBALS['__test_options']['marketers_delight'] = array( 'layout' => array( 'sidebar' => 'left' ), 'header' => array( 'x' => 1 ) );

		$this->assertSame( array( 'sidebar' => 'left' ), md_scope::parse( 'site' )->read( 'layout' ) );
		$this->assertSame( 'md_save_group', md_scope::parse( 'site' )->read( 'missing' )->get_error_code() );
	}

	public function test_merge_items_keeps_others_updates_adds_and_removes_by_id() {
		$fields = array( 'list' => array( 'type' => 'group', 'fields' => array( 'item' => array( 'type' => 'text' ) ) ) );
		$stored = array( 'list' => array( 'a' => array( 'item' => 'A' ), 'b' => array( 'item' => 'B' ), 'c' => array( 'item' => 'C' ) ) );

		$input = md_save::merge_items( $stored, array( 'list' => array( 'b' => array( 'item' => 'B2' ), 'c' => null, 'd' => array( 'item' => 'D' ) ) ), $fields );

		$this->assertSame( array( 'a', 'b', 'c', 'd' ), array_keys( $input['list'] ) );
		$this->assertSame( 'B2', $input['list']['b']['item'] );
		$this->assertNull( $input['list']['c'] );
	}

	public function test_merge_items_orders_a_list_with_or_without_new_values() {
		$fields = array( 'list' => array( 'type' => 'group', 'fields' => array( 'item' => array( 'type' => 'text' ) ) ) );
		$stored = array( 'list' => array( 'a' => array( 'item' => 'A' ), 'b' => array( 'item' => 'B' ), 'c' => array( 'item' => 'C' ) ) );

		$moved = md_save::merge_items( $stored, array(), $fields, array( 'list' => array( 'c', 'a', 'gone' ) ) );

		$this->assertSame( array( 'c', 'a', 'b' ), array_keys( $moved['list'] ) );

		$added = md_save::merge_items( $stored, array( 'list' => array( 'd' => array( 'item' => 'D' ) ) ), $fields, array( 'list' => array( 'a', 'd', 'b', 'c' ) ) );

		$this->assertSame( array( 'a', 'd', 'b', 'c' ), array_keys( $added['list'] ) );
	}

	public function test_merge_items_keeps_a_checkboxs_other_options() {
		$fields = array( 'sidebar' => array( 'type' => 'checkbox', 'options' => array( 'global', 'remove' ) ) );
		$input = md_save::merge_items( array( 'sidebar' => array( 'global' => true ) ), array( 'sidebar' => array( 'remove' => true ) ), $fields );

		$this->assertSame( array( 'global' => true, 'remove' => true ), $input['sidebar'] );
	}

}
