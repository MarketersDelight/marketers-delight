<?php
/**
 * Tests md_validate::validate() against representative schemas. Several of
 * these pin down bugs found live this session — see the comment on each
 * regression test for what broke and why.
 *
 * @since 6.0
 */

class ValidateTest extends MD_TestCase {

	private $validate;

	protected function setUp(): void {
		parent::setUp();
		$this->validate = new md_validate;
	}

	private function register_schema( $schema ) {
		md_test_set_filter( 'md_register', array( 'admin_pages' => $schema ) );
	}

	// Leaf fields

	public function test_leaf_field_absent_from_input_is_omitted_not_nulled_out() {
		$this->register_schema( array(
			'page' => array( 'fields' => array(
				'title' => array( 'type' => 'text' )
			) )
		) );

		$save = $this->validate->validate( 'admin_pages', array( 'page' => array() ) );

		$this->assertArrayNotHasKey( 'title', $save['page'] );
	}

	public function test_leaf_field_blank_saves_as_blank() {
		$this->register_schema( array(
			'page' => array( 'fields' => array(
				'title' => array( 'type' => 'text' )
			) )
		) );

		$save = $this->validate->validate( 'admin_pages', array( 'page' => array( 'title' => '' ) ) );

		$this->assertSame( '', $save['page']['title'] );
	}

	// Regression: emptying a type=>'group' field (e.g. Custom Colors) didn't
	// persist the deletion, because validate() only wrote the group's cloned
	// result into $save when it was non-empty — so deleting every item never
	// reached the merge, and the old items silently survived.

	public function test_emptying_a_group_field_still_saves_empty_result() {
		$this->register_schema( array(
			'colors' => array( 'fields' => array(
				'custom' => array(
					'type' => 'group',
					'fields' => array(
						'hex' => array( 'type' => 'color' ),
						'name' => array( 'type' => 'text' )
					)
				)
			) )
		) );

		$save = $this->validate->validate( 'admin_pages', array( 'colors' => array( 'custom' => array() ) ) );

		$this->assertSame( array(), $save['colors']['custom'] );
	}

	// Regression: a checkbox inside a clone-style group item (e.g. the
	// dropins "Activate" checkbox) never unchecked, because clone_groups()
	// iterated the *submitted* POST keys instead of the item's field schema
	// — an unchecked checkbox submits nothing at all, so its key never
	// appeared in the loop and the old `true` value survived untouched.

	public function test_unchecked_checkbox_inside_clone_item_is_still_validated() {
		$this->register_schema( array(
			'dropins' => array( 'fields' => array(
				'installed' => array(
					'type' => 'group',
					'fields' => array(
						'status' => array( 'type' => 'checkbox' ),
						'name' => array( 'type' => 'text' )
					)
				)
			) )
		) );

		// The checkbox is unchecked, so the form submits nothing for
		// 'status' at all — only 'name' comes through in $_POST.

		$save = $this->validate->validate( 'admin_pages', array(
			'dropins' => array( 'installed' => array(
				'my-dropin' => array( 'name' => 'My Dropin' )
			) )
		) );

		$this->assertSame( false, $save['dropins']['installed']['my-dropin']['status'] );
	}

	public function test_checked_checkbox_inside_clone_item_validates_true() {
		$this->register_schema( array(
			'dropins' => array( 'fields' => array(
				'installed' => array(
					'type' => 'group',
					'fields' => array(
						'status' => array( 'type' => 'checkbox' ),
						'name' => array( 'type' => 'text' )
					)
				)
			) )
		) );

		$save = $this->validate->validate( 'admin_pages', array(
			'dropins' => array( 'installed' => array(
				'my-dropin' => array( 'status' => '1', 'name' => 'My Dropin' )
			) )
		) );

		$this->assertTrue( $save['dropins']['installed']['my-dropin']['status'] );
	}

	// Regression: the admin JS submits a template row (e.g. "{clone:md_cta_forms}")
	// for the "add new item" button's own clone source, alongside real items.
	// This used to only be stripped when the key was exactly '{clone}' — the
	// real submitted key is parametrized per field ('{clone:$field_id}', or
	// nested one level deeper for a clone-inside-a-clone), so the old check
	// never matched and the blank template row got saved and later rendered
	// on the frontend as if it were a real item.

	public function test_clone_template_placeholder_key_is_stripped() {
		$this->register_schema( array(
			'cta' => array( 'fields' => array(
				'forms' => array(
					'type' => 'group',
					'fields' => array(
						'name' => array( 'type' => 'text' )
					)
				)
			) )
		) );

		$save = $this->validate->validate( 'admin_pages', array(
			'cta' => array( 'forms' => array(
				'{clone:md_cta_forms}' => array( 'name' => '' ),
				'primary' => array( 'name' => 'Sign up' )
			) )
		) );

		$this->assertArrayNotHasKey( '{clone:md_cta_forms}', $save['cta']['forms'] );
		$this->assertSame( 'Sign up', $save['cta']['forms']['primary']['name'] );
	}

	// Nested clone (e.g. a link inside a CTA item) uses a doubly-parametrized
	// key -- confirm the prefix match still catches it at the nested level.

	public function test_nested_clone_template_placeholder_key_is_stripped() {
		$this->register_schema( array(
			'cta' => array( 'fields' => array(
				'forms' => array(
					'type' => 'group',
					'fields' => array(
						'name' => array( 'type' => 'text' ),
						'links' => array(
							'type' => 'group',
							'fields' => array(
								'name' => array( 'type' => 'text' )
							)
						)
					)
				)
			) )
		) );

		$save = $this->validate->validate( 'admin_pages', array(
			'cta' => array( 'forms' => array(
				'primary' => array( 'name' => 'Sign up', 'links' => array(
					'{clone:md_cta_forms_{clone:md_cta_forms}_links}' => array( 'name' => '' ),
					'link-1' => array( 'name' => 'Get started' )
				) )
			) )
		) );

		$links = $save['cta']['forms']['primary']['links'];

		$this->assertArrayNotHasKey( '{clone:md_cta_forms_{clone:md_cta_forms}_links}', $links );
		$this->assertSame( 'Get started', $links['link-1']['name'] );
	}

	// Builder fields always wrote their cloned result even when empty
	// (unlike group, before the fix above) — confirm that still holds.

	public function test_emptying_a_builder_field_saves_empty_result() {
		$this->register_schema( array(
			'header' => array( 'fields' => array(
				'builder' => array(
					'type' => 'builder',
					'fields' => array(
						'name' => array( 'type' => 'text' )
					)
				)
			) )
		) );

		$save = $this->validate->validate( 'admin_pages', array( 'header' => array( 'builder' => array() ) ) );

		$this->assertSame( array(), $save['header']['builder'] );
	}

	// Sibling-leaf protection: a type-less structural grouping recurses
	// field-by-field, so one field's schema absence doesn't drop siblings.

	public function test_sibling_leaf_survives_when_group_has_no_type() {
		$this->register_schema( array(
			'page' => array( 'fields' => array(
				'section' => array(
					'field_a' => array( 'type' => 'text' ),
					'field_b' => array( 'type' => 'text' )
				)
			) )
		) );

		$save = $this->validate->validate( 'admin_pages', array(
			'page' => array( 'section' => array( 'field_a' => 'value-a' ) )
		) );

		$this->assertSame( 'value-a', $save['page']['section']['field_a'] );
		$this->assertArrayNotHasKey( 'field_b', $save['page']['section'] );
	}

	// color/select/upload used to have their "should this even save"
	// business logic living in validate_field() itself instead of their
	// sanitizer — confirm end-to-end dispatch still behaves the same now
	// that logic moved into md_sanitize::color()/select()/upload().

	public function test_color_field_matching_default_is_omitted() {
		$this->register_schema( array(
			'colors' => array( 'fields' => array(
				'primary' => array( 'type' => 'color', 'default' => '#AE2525' )
			) )
		) );

		$save = $this->validate->validate( 'admin_pages', array( 'colors' => array( 'primary' => '#AE2525' ) ) );

		$this->assertArrayNotHasKey( 'primary', $save['colors'] );
	}

	public function test_color_field_override_saves() {
		$this->register_schema( array(
			'colors' => array( 'fields' => array(
				'primary' => array( 'type' => 'color', 'default' => '#AE2525' )
			) )
		) );

		$save = $this->validate->validate( 'admin_pages', array( 'colors' => array( 'primary' => '#FF0000' ) ) );

		$this->assertSame( '#FF0000', $save['colors']['primary'] );
	}

	public function test_upload_field_with_wrong_type_is_omitted() {
		$this->register_schema( array(
			'logo' => array( 'fields' => array(
				'image' => array( 'type' => 'upload', 'upload_type' => 'file' )
			) )
		) );

		$save = $this->validate->validate( 'admin_pages', array( 'logo' => array( 'image' => array( 'id' => '5' ) ) ) );

		$this->assertArrayNotHasKey( 'image', $save['logo'] );
	}

	// Pin-down tests for the 3-level hand-unrolled validate()/clone_groups()
	// shape, written BEFORE the recursive validate_fields() refactor so any
	// behavior change during that refactor shows up as a red test here.

	// Depth-2 clone field: fields.group.option_name, where option_name is
	// itself type=>'group' (the second clone_groups() call site).

	public function test_depth_2_clone_field_validates() {
		$this->register_schema( array(
			'page' => array( 'fields' => array(
				'section' => array(
					'items' => array(
						'type' => 'group',
						'fields' => array(
							'name' => array( 'type' => 'text' )
						)
					)
				)
			) )
		) );

		$save = $this->validate->validate( 'admin_pages', array(
			'page' => array( 'section' => array( 'items' => array(
				'item-1' => array( 'name' => 'First' )
			) ) )
		) );

		$this->assertSame( 'First', $save['page']['section']['items']['item-1']['name'] );
	}

	// A clone item's own field can itself be type=>'group' — and it's ALWAYS
	// a dynamically-keyed repeater too, same as at the top level, so a
	// repeater can nest inside another repeater's item. This is a real,
	// live pattern — see 'links' inside the 'bars' builder in
	// optins/floating-bars/floating-bars.php (and the identical pattern in
	// optins/cta/cta.php's 'forms'), each holding a free-form list of link
	// configs. There is no separate "nested group = fixed object" concept;
	// a single fixed embedded sub-object is expressed as a typeless
	// structural grouping instead (see
	// test_sibling_leaf_survives_when_group_has_no_type).

	public function test_clone_item_with_nested_group_field_validates_as_repeater() {
		$this->register_schema( array(
			'page' => array( 'fields' => array(
				'items' => array(
					'type' => 'group',
					'fields' => array(
						'links' => array(
							'type' => 'group',
							'fields' => array(
								'label' => array( 'type' => 'text' )
							)
						)
					)
				)
			) )
		) );

		$save = $this->validate->validate( 'admin_pages', array(
			'page' => array( 'items' => array(
				'item-1' => array( 'links' => array(
					'link-1' => array( 'label' => 'First' ),
					'link-2' => array( 'label' => 'Second' )
				) )
			) )
		) );

		$this->assertSame( 'First', $save['page']['items']['item-1']['links']['link-1']['label'] );
		$this->assertSame( 'Second', $save['page']['items']['item-1']['links']['link-2']['label'] );
	}

	// group_key_lowercase (used by 'sidebars'/'panels' in admin/dashboard.php)
	// lowercases a submitted clone item's key on save.

	public function test_group_key_lowercase_lowercases_clone_item_key() {
		$this->register_schema( array(
			'layout' => array( 'fields' => array(
				'sidebars' => array(
					'type' => 'group',
					'group_key_lowercase' => true,
					'fields' => array(
						'name' => array( 'type' => 'text' )
					)
				)
			) )
		) );

		$save = $this->validate->validate( 'admin_pages', array(
			'layout' => array( 'sidebars' => array(
				'Main-Sidebar' => array( 'name' => 'Main' )
			) )
		) );

		$this->assertArrayHasKey( 'main-sidebar', $save['layout']['sidebars'] );
		$this->assertArrayNotHasKey( 'Main-Sidebar', $save['layout']['sidebars'] );
	}

	// Depth-3 leaf: fields.group.option_name.val_name, all typeless above
	// the leaf — the innermost hard-coded level in the current code.

	public function test_depth_3_leaf_field_validates() {
		$this->register_schema( array(
			'page' => array( 'fields' => array(
				'group' => array(
					'option' => array(
						'val' => array( 'type' => 'text' )
					)
				)
			) )
		) );

		$save = $this->validate->validate( 'admin_pages', array(
			'page' => array( 'group' => array( 'option' => array( 'val' => 'deep-value' ) ) )
		) );

		$this->assertSame( 'deep-value', $save['page']['group']['option']['val'] );
	}

	// Payoff: the old hand-unrolled validate() had a hard ceiling — a clone
	// item's own fields (inside 'installed', depth 2) couldn't themselves
	// contain further typeless structural nesting at all; only a single
	// flat validate_field() dispatch per item field. The recursive
	// validate_fields() walker has no such ceiling — a clone item's fields
	// can nest typeless structural groupings arbitrarily deep. This also
	// round-trips the validated output through md_save::merge_fields() —
	// the actual consumer — confirming the output shape still matches what
	// save.php expects at arbitrary depth.

	public function test_depth_4_structural_nesting_inside_clone_item_has_no_ceiling() {
		$item_schema = array(
			'label' => array( 'type' => 'text' ),
			'meta' => array(
				'details' => array(
					'value' => array( 'type' => 'text' )
				)
			)
		);

		$this->register_schema( array(
			'dropins' => array( 'fields' => array(
				'installed' => array(
					'type' => 'group',
					'fields' => $item_schema
				)
			) )
		) );

		$input = array(
			'dropins' => array( 'installed' => array(
				'my-dropin' => array(
					'label' => 'My Dropin',
					'meta' => array( 'details' => array( 'value' => 'Deep Value' ) )
				)
			) )
		);

		$save = $this->validate->validate( 'admin_pages', $input );

		$this->assertSame(
			'Deep Value',
			$save['dropins']['installed']['my-dropin']['meta']['details']['value']
		);

		// Round-trip through md_save's merge — the actual consumer of this
		// output — to confirm the shape is still what it expects at depth.

		$merge_schema = array( 'dropins' => array( 'fields' => array(
			'installed' => array( 'type' => 'group', 'fields' => $item_schema )
		) ) );

		md_test_set_filter( 'md_register', array( 'admin_pages' => $merge_schema ) );

		$save_class = new md_save;
		$merge = $this->call( $save_class, 'merge_recursive', array( array(), $save ) );

		$this->assertSame(
			'Deep Value',
			$merge['dropins']['installed']['my-dropin']['meta']['details']['value']
		);
	}

	// The md_save half of this (merge_fields treating a nested group as a
	// repeater too, merged via merge_clone_items()) is covered in
	// SaveTest.php — test_nested_group_inside_clone_item_merges_as_repeater.

	// Top-level keys without a registered schema pass through unchanged.

	public function test_unregistered_top_level_key_passes_through_as_is() {
		$this->register_schema( array() );

		$save = $this->validate->validate( 'admin_pages', array( 'integrations' => array( 'foo' => 'bar' ) ) );

		$this->assertSame( array( 'foo' => 'bar' ), $save['integrations'] );
	}

}
