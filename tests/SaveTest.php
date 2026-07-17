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

	private $schema = array();

	// A leaf field wholesale-replaces, even blank — clearing a field and
	// saving must persist as cleared, not silently keep the old value.

	public function test_leaf_field_replaces_wholesale_including_blank() {
		$this->schema = array( 'page' => array( 'fields' => array(
			'title' => array( 'type' => 'text' )
		) ) );

		$result = $this->merge(
			array( 'page' => array( 'title' => 'old' ) ),
			array( 'page' => array( 'title' => '' ) )
		);

		$this->assertSame( '', $result['page']['title'] );
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

	// A top-level key with no registered schema (version, integrations, etc)
	// is replaced wholesale, matching how validate() passes it through.

	public function test_unregistered_key_replaces_wholesale() {
		$this->schema = array();

		$result = $this->merge(
			array( 'integrations' => array( 'foo' => 'old', 'bar' => 'keep-out' ) ),
			array( 'integrations' => array( 'foo' => 'new' ) )
		);

		$this->assertSame( array( 'foo' => 'new' ), $result['integrations'] );
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
		$this->assertFalse( $result['dropins']['installed']['my-dropin']['status'] );
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
