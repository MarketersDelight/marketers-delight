<?php
/**
 * Tests md_save's merge logic (merge_recursive/merge_fields/merge_clone_items)
 * directly via reflection, since they're private and the public entry points
 * (admin_save/admin_save_custom) add nonce/$_POST plumbing that's orthogonal
 * to the merge behavior itself.
 *
 * @since 6.0
 */

class SaveTest extends MD_TestCase {

	private $save;

	protected function setUp(): void {
		parent::setUp();
		$this->save = new md_save;
	}

	private function merge( $old, $new ) {
		md_test_set_filter( 'md_register', array( 'admin_pages' => $this->schema ) );

		return $this->call( $this->save, 'merge_recursive', array( $old, $new ) );
	}

	private function merge_meta( $old, $new, $settings = 'meta_boxes' ) {
		md_test_set_filter( 'md_register', array( $settings => $this->schema ) );
		$merged = $this->call( $this->save, 'merge_recursive', array( $old, $new, $settings ) );

		return $this->call( $this->save, 'prune_empty', array( $merged ) );
	}

	private $schema = array();

	public function test_admin_save_preserves_unsubmitted_runtime_and_internal_branches() {
		$this->schema = array(
			'settings' => array( 'fields' => array(
				'title' => array( 'type' => 'text' )
			) )
		);
		md_test_set_filter( 'md_register', array( 'admin_pages' => $this->schema ) );
		md_test_set_option( 'marketers_delight', array(
			'settings' => array( 'title' => 'Old title', 'head' => array( 'blocks' => true ) ),
			'license' => array( 'status' => 'valid' ),
			'integrations' => array( 'enabled' => array( 'mailchimp' => true ) ),
			'dropins' => array( 'installed' => array( 'share' => array( 'name' => 'Share' ) ) ),
			'custom_icons' => array( 'star' )
		) );
		$_POST['option_page'] = 'marketers_delight';
		$_POST['action'] = 'update';

		$result = $this->save->admin_save( array(
			'settings' => array( 'title' => 'New title' )
		) );

		$this->assertSame( 'New title', $result['settings']['title'] );
		$this->assertSame( array( 'blocks' => true ), $result['settings']['head'] );
		$this->assertSame( array( 'status' => 'valid' ), $result['license'] );
		$this->assertSame( array( 'enabled' => array( 'mailchimp' => true ) ), $result['integrations'] );
		$this->assertArrayHasKey( 'share', $result['dropins']['installed'] );
		$this->assertSame( array( 'star' ), $result['custom_icons'] );
	}

	public function test_admin_save_custom_saves_fields_and_preserves_unrelated_data() {
		$this->schema = array(
			'portal' => array(
				'_option' => 'client_portal',
				'fields' => array(
				'title' => array( 'type' => 'text' ),
				'layout' => array( 'type' => 'text' )
				)
			)
		);
		md_test_set_filter( 'md_register', array( 'admin_pages' => $this->schema ) );
		md_test_set_option( 'client_portal', array(
			'portal' => array(
				'title' => 'Old title',
				'layout' => 'sidebar'
			),
			'internal' => array( 'version' => 2 )
		) );
		$_POST['option_page'] = 'client_portal';
		$_POST['action'] = 'update';

		$result = $this->save->admin_save_custom( array(
			'portal' => array(
				'title' => 'New title',
				'layout' => 'sidebar'
			)
		) );

		$this->assertSame( 'New title', $result['portal']['title'] );
		$this->assertSame( 'sidebar', $result['portal']['layout'] );
		$this->assertSame( array( 'version' => 2 ), $result['internal'] );
	}

	public function test_settings_form_only_accepts_fields_owned_by_its_option() {
		$this->schema = array(
			'settings' => array( 'fields' => array(
				'title' => array( 'type' => 'text' )
			) ),
			'dropins' => array(
				'_option' => 'marketers_delight_dropins',
				'fields' => array(
					'installed' => array( 'type' => 'group' )
				)
			)
		);
		md_test_set_filter( 'md_register', array( 'admin_pages' => $this->schema ) );
		md_test_set_option( 'marketers_delight', array(
			'settings' => array( 'title' => 'Old title' ),
			'dropins' => array( 'active' => array( 'share' ) )
		) );
		$_POST['option_page'] = 'marketers_delight';
		$_POST['action'] = 'update';

		$result = $this->save->admin_save( array(
			'settings' => array( 'title' => 'New title' ),
			'dropins' => array( 'installed' => array() )
		) );

		$this->assertSame( 'New title', $result['settings']['title'] );
		$this->assertSame( array( 'active' => array( 'share' ) ), $result['dropins'] );
	}

	public function test_taxonomy_save_updates_selected_taxonomy_only() {
		$this->schema = array(
			'book' => array( 'fields' => array(
				'layout' => array( 'type' => 'text' )
			) )
		);
		md_test_set_filter( 'md_register', array( 'admin_pages' => $this->schema ) );
		md_test_set_option( 'marketers_delight', array(
			'book' => array(
				'genre' => array( 'layout' => 'old-layout' ),
				'topic' => array( 'layout' => 'keep-topic' )
			),
			'colors' => array( 'site' => '#ffffff' )
		) );
		$_POST['md_save_taxonomy_post_type'] = 'book';
		$_POST['md_save_taxonomy'] = 'genre';

		$result = $this->save->admin_save( array(
			'book' => array(
				'genre' => array( 'layout' => 'new-layout' )
			)
		) );

		$this->assertSame( 'new-layout', $result['book']['genre']['layout'] );
		$this->assertSame( 'keep-topic', $result['book']['topic']['layout'] );
		$this->assertSame( array( 'site' => '#ffffff' ), $result['colors'] );
	}

	public function test_taxonomy_save_removes_empty_taxonomy_and_preserves_siblings() {
		$this->schema = array(
			'book' => array( 'fields' => array(
				'layout' => array( 'type' => 'text' )
			) )
		);
		md_test_set_filter( 'md_register', array( 'admin_pages' => $this->schema ) );
		md_test_set_option( 'marketers_delight', array(
			'book' => array(
				'genre' => array( 'layout' => 'old-layout' ),
				'topic' => array( 'layout' => 'keep-topic' )
			)
		) );
		$_POST['md_save_taxonomy_post_type'] = 'book';
		$_POST['md_save_taxonomy'] = 'genre';

		$result = $this->save->admin_save( array(
			'book' => array(
				'genre' => array( 'layout' => '' )
			)
		) );

		$this->assertArrayNotHasKey( 'genre', $result['book'] );
		$this->assertSame( 'keep-topic', $result['book']['topic']['layout'] );
	}

	public function test_dropins_page_save_preserves_complete_inventory_and_toggles_status() {
		$dropin_fields = array(
			'name' => array( 'type' => 'text' ),
			'version' => array( 'type' => 'text' ),
			'status' => array(
				'type' => 'checkbox',
				'options' => array( 'enable' )
			),
			'plugin_name' => array( 'type' => 'text' )
		);
		$this->schema = array(
			'dropins' => array( 'fields' => array(
				'installed' => array(
					'type' => 'group',
					'fields' => $dropin_fields
				)
			) )
		);
		md_test_set_filter( 'md_register', array( 'admin_pages' => $this->schema ) );
		md_test_set_option( 'marketers_delight', array(
			'dropins' => array( 'installed' => array(
				'share' => array(
					'name' => 'Share',
					'version' => '2.0',
					'status' => array( 'enable' => true ),
					'plugin_name' => ''
				),
				'bookshelf' => array(
					'name' => 'Bookshelf',
					'version' => '1.0',
					'status' => array(),
					'plugin_name' => ''
				)
			) ),
			'license' => array( 'updates' => array( 'dropins' => array() ) )
		) );
		$_POST['option_page'] = 'marketers_delight';
		$_POST['action'] = 'update';

		$result = $this->save->admin_save( array(
			'dropins' => array( 'installed' => array(
				'share' => array(
					'name' => 'Share',
					'version' => '2.0',
					'plugin_name' => ''
				),
				'bookshelf' => array(
					'name' => 'Bookshelf',
					'version' => '1.0',
					'status' => array( 'enable' => '1' ),
					'plugin_name' => ''
				)
			) )
		) );

		$this->assertCount( 2, $result['dropins']['installed'] );
		$this->assertArrayNotHasKey( 'status', $result['dropins']['installed']['share'] );
		$this->assertSame( array( 'enable' => true ), $result['dropins']['installed']['bookshelf']['status'] );
		$this->assertSame( '2.0', $result['dropins']['installed']['share']['version'] );
		$this->assertSame( array( 'updates' => array( 'dropins' => array() ) ), $result['license'] );
	}

	// A submitted blank clears the old field without storing empty data.

	public function test_leaf_field_blank_removes_existing_value() {
		$this->schema = array( 'page' => array( 'fields' => array(
			'title' => array( 'type' => 'text' )
		) ) );

		$result = $this->merge(
			array( 'page' => array( 'title' => 'old' ) ),
			array( 'page' => array( 'title' => '' ) )
		);

		$this->assertArrayNotHasKey( 'page', $result );
	}

	public function test_zero_values_remain_stored() {
		$this->schema = array( 'page' => array( 'fields' => array(
			'number_string' => array( 'type' => 'number' ),
			'number_int' => array( 'type' => 'number' )
		) ) );

		$result = $this->merge(
			array(),
			array( 'page' => array( 'number_string' => '0', 'number_int' => 0 ) )
		);

		$this->assertSame( '0', $result['page']['number_string'] );
		$this->assertSame( 0, $result['page']['number_int'] );
	}

	public function test_empty_nested_parents_are_removed() {
		$this->schema = array( 'page' => array( 'fields' => array(
			'section' => array(
				'title' => array( 'type' => 'text' )
			)
		) ) );

		$result = $this->merge(
			array( 'page' => array( 'section' => array( 'title' => 'Old title' ) ) ),
			array( 'page' => array( 'section' => array( 'title' => '' ) ) )
		);

		$this->assertSame( array(), $result );
	}

	public function test_standalone_meta_values_are_pruned_recursively() {
		$result = $this->call( $this->save, 'prune_empty', array(
			array(
				'title' => '',
				'count' => '0',
				'section' => array(
					'enabled' => false,
					'label' => ''
				)
			)
		) );

		$this->assertSame( array( 'count' => '0' ), $result );
	}

	public function test_post_meta_save_preserves_unsubmitted_internal_branches() {
		$this->schema = array( 'page' => array( 'fields' => array(
			'title' => array( 'type' => 'text' )
		) ) );

		$result = $this->merge_meta(
			array(
				'page' => array( 'title' => 'Old title' ),
				'internal' => array( 'generated' => 'keep' )
			),
			array( 'page' => array( 'title' => 'New title' ) )
		);

		$this->assertSame( 'New title', $result['page']['title'] );
		$this->assertSame( 'keep', $result['internal']['generated'] );
	}

	public function test_post_meta_clear_removes_field_and_legacy_empty_branches() {
		$this->schema = array( 'page' => array( 'fields' => array(
			'title' => array( 'type' => 'text' )
		) ) );

		$result = $this->merge_meta(
			array(
				'page' => array( 'title' => 'Old title' ),
				'legacy' => array( 'blank' => '', 'disabled' => false )
			),
			array( 'page' => array( 'title' => '' ) )
		);

		$this->assertSame( array(), $result );
	}

	public function test_term_and_user_meta_use_their_registered_schemas() {
		$this->schema = array( 'profile' => array( 'fields' => array(
			'label' => array( 'type' => 'text' )
		) ) );

		$term = $this->merge_meta(
			array( 'profile' => array( 'label' => 'Old' ), 'internal' => array( 'id' => 7 ) ),
			array( 'profile' => array( 'label' => 'Term' ) ),
			'terms'
		);
		$user = $this->merge_meta(
			array( 'profile' => array( 'label' => 'Old' ), 'internal' => array( 'id' => 8 ) ),
			array( 'profile' => array( 'label' => 'User' ) ),
			'user_meta'
		);

		$this->assertSame( 'Term', $term['profile']['label'] );
		$this->assertSame( 7, $term['internal']['id'] );
		$this->assertSame( 'User', $user['profile']['label'] );
		$this->assertSame( 8, $user['internal']['id'] );
	}

	public function test_submitted_default_is_not_added_to_raw_storage() {
		$this->schema = array(
			'settings' => array( 'fields' => array(
				'title' => array( 'type' => 'text', 'default' => 'Default title' )
			) )
		);
		md_test_set_filter( 'md_register', array( 'admin_pages' => $this->schema ) );
		md_test_set_option( 'marketers_delight', array() );
		$_POST['option_page'] = 'marketers_delight';
		$_POST['action'] = 'update';

		$result = $this->save->admin_save( array(
			'settings' => array( 'title' => '' )
		) );

		$this->assertSame( array(), $result );
	}

	// A sibling field not present in $save (never submitted on this page)
	// survives untouched — this is the shallow-merge-wipes-siblings fix.

	public function test_sibling_field_not_in_save_survives() {
		$this->schema = array( 'page' => array( 'fields' => array(
			'title' => array( 'type' => 'text' ),
			'subtitle' => array( 'type' => 'text' )
		) ) );

		$result = $this->merge(
			array( 'page' => array( 'title' => 'old', 'subtitle' => 'keep-me' ) ),
			array( 'page' => array( 'title' => 'new' ) )
		);

		$this->assertSame( 'keep-me', $result['page']['subtitle'] );
	}

	public function test_settings_form_rejects_unregistered_top_level_key() {
		$this->schema = array(
			'settings' => array( 'fields' => array(
				'title' => array( 'type' => 'text' )
			) )
		);
		md_test_set_filter( 'md_register', array( 'admin_pages' => $this->schema ) );
		$_POST['option_page'] = 'marketers_delight';
		$_POST['action'] = 'update';

		$result = $this->call( $this->save, 'settings_input', array(
			array(
				'settings' => array( 'title' => 'Keep' ),
				'unregistered' => array( 'unsafe' => '<script>alert(1)</script>' )
			),
			'marketers_delight'
		) );

		unset( $_POST['option_page'], $_POST['action'] );

		$this->assertSame( array( 'settings' => array( 'title' => 'Keep' ) ), $result );
	}

	public function test_settings_form_rejects_registered_key_without_fields() {
		$this->schema = array(
			'integrations' => array(
				'name' => 'Integrations'
			)
		);
		md_test_set_filter( 'md_register', array( 'admin_pages' => $this->schema ) );
		$_POST['option_page'] = 'marketers_delight';
		$_POST['action'] = 'update';

		$result = $this->call( $this->save, 'settings_input', array(
			array( 'integrations' => array( 'unsafe' => '<script>alert(1)</script>' ) ),
			'marketers_delight'
		) );

		unset( $_POST['option_page'], $_POST['action'] );

		$this->assertSame( array(), $result );
	}

	public function test_programmatic_update_preserves_internal_top_level_key() {
		md_test_set_filter( 'md_register', array( 'admin_pages' => array() ) );

		$result = $this->save->admin_save( array(
			'license' => array( 'status' => 'valid' ),
			'integrations' => array( 'enabled' => array( 'mailchimp' => true ) ),
			'custom_icons' => array( 'star' )
		) );

		$this->assertSame(
			array(
				'license' => array( 'status' => 'valid' ),
				'integrations' => array( 'enabled' => array( 'mailchimp' => true ) ),
				'custom_icons' => array( 'star' )
			),
			$result
		);
	}

	// Clone item list: deleting/reordering is authoritative from $save.

	public function test_clone_item_deleted_from_save_is_removed() {
		$this->schema = array( 'colors' => array( 'fields' => array(
			'custom' => array( 'type' => 'group', 'fields' => array(
				'hex' => array( 'type' => 'color' ),
				'name' => array( 'type' => 'text' )
			) )
		) ) );

		$result = $this->merge(
			array( 'colors' => array( 'custom' => array(
				'item-1' => array( 'hex' => '#FFFFFF', 'name' => 'White' ),
				'item-2' => array( 'hex' => '#000000', 'name' => 'Black' )
			) ) ),
			array( 'colors' => array( 'custom' => array(
				'item-1' => array( 'hex' => '#FFFFFF', 'name' => 'White' )
			) ) )
		);

		$this->assertArrayNotHasKey( 'item-2', $result['colors']['custom'] );
	}

	// Regression: a clone item's field NOT resubmitted for that item
	// (display-only data set programmatically elsewhere, e.g. a dropin's
	// plugin_name/version) must survive, not get wiped by a wholesale
	// item replace. This was the "Requires plugin" false-positive bug.

	public function test_clone_item_field_not_resubmitted_survives() {
		$this->schema = array( 'dropins' => array( 'fields' => array(
			'installed' => array( 'type' => 'group', 'fields' => array(
				'status' => array( 'type' => 'checkbox' ),
				'plugin_name' => array( 'type' => 'text' )
			) )
		) ) );

		$result = $this->merge(
			array( 'dropins' => array( 'installed' => array(
				'my-dropin' => array( 'status' => true, 'plugin_name' => 'WooCommerce' )
			) ) ),
			// Only 'status' is resubmitted for this item (toggling the checkbox) —
			// 'plugin_name' is display-only data, not part of this page's form.
			array( 'dropins' => array( 'installed' => array(
				'my-dropin' => array( 'status' => false )
			) ) )
		);

		$this->assertSame( 'WooCommerce', $result['dropins']['installed']['my-dropin']['plugin_name'] );
		$this->assertArrayNotHasKey( 'status', $result['dropins']['installed']['my-dropin'] );
	}

	// A brand-new clone item (not in $old at all) is taken as-is.

	public function test_new_clone_item_not_in_old_is_added_as_is() {
		$this->schema = array( 'colors' => array( 'fields' => array(
			'custom' => array( 'type' => 'group', 'fields' => array(
				'hex' => array( 'type' => 'color' ),
				'name' => array( 'type' => 'text' )
			) )
		) ) );

		$result = $this->merge(
			array( 'colors' => array( 'custom' => array() ) ),
			array( 'colors' => array( 'custom' => array(
				'item-1' => array( 'hex' => '#FF0000', 'name' => 'Red' )
			) ) )
		);

		$this->assertSame( array( 'hex' => '#FF0000', 'name' => 'Red' ), $result['colors']['custom']['item-1'] );
	}

	// A type-less structural grouping recurses field-by-field instead of
	// replacing wholesale, so a sibling leaf survives one field's omission.

	public function test_structural_grouping_recurses_and_preserves_siblings() {
		$this->schema = array( 'page' => array( 'fields' => array(
			'section' => array(
				'field_a' => array( 'type' => 'text' ),
				'field_b' => array( 'type' => 'text' )
			)
		) ) );

		$result = $this->merge(
			array( 'page' => array( 'section' => array( 'field_a' => 'old-a', 'field_b' => 'keep-b' ) ) ),
			array( 'page' => array( 'section' => array( 'field_a' => 'new-a' ) ) )
		);

		$this->assertSame( 'new-a', $result['page']['section']['field_a'] );
		$this->assertSame( 'keep-b', $result['page']['section']['field_b'] );
	}

	// Real live pattern: floating-bars.php's 'bars' items each have a
	// 'links' field that is itself type=>'group' — a genuine nested
	// repeater (0-to-N link configs per bar), not a fixed single object.
	// merge_fields() must treat it via merge_clone_items() same as any
	// other repeater, at any depth: item-list membership is authoritative
	// from $save (so a new link item can be added), but a field within an
	// existing item not resubmitted for that item still survives — exactly
	// like the top-level clone-item guarantees already proven above.

	public function test_nested_group_inside_clone_item_merges_as_repeater() {
		$this->schema = array( 'floating_bars' => array( 'fields' => array(
			'bars' => array( 'type' => 'group', 'fields' => array(
				'name' => array( 'type' => 'text' ),
				'links' => array(
					'type' => 'group',
					'fields' => array(
						'name' => array( 'type' => 'text' ),
						'url' => array( 'type' => 'url' )
					)
				)
			) )
		) ) );

		$old = array( 'floating_bars' => array( 'bars' => array(
			'bar-1' => array( 'name' => 'Bar One', 'links' => array(
				'link-1' => array( 'name' => 'Click here', 'url' => 'https://example.com' )
			) )
		) ) );

		// link-1.name is edited but link-1.url is not resubmitted (should
		// survive); link-2 is a brand-new item being added.

		$save = array( 'floating_bars' => array( 'bars' => array(
			'bar-1' => array( 'links' => array(
				'link-1' => array( 'name' => 'New link text' ),
				'link-2' => array( 'name' => 'Another link', 'url' => 'https://two.example.com' )
			) )
		) ) );

		$result = $this->merge( $old, $save );
		$links = $result['floating_bars']['bars']['bar-1']['links'];

		$this->assertSame( 'New link text', $links['link-1']['name'] );
		$this->assertSame( 'https://example.com', $links['link-1']['url'] );
		$this->assertSame( 'Another link', $links['link-2']['name'] );
		$this->assertSame( 'https://two.example.com', $links['link-2']['url'] );
		$this->assertSame( 'Bar One', $result['floating_bars']['bars']['bar-1']['name'] );
	}

}
