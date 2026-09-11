<?php
/**
 * Tests md_fields::get_context() — the single shared branching for
 * post/term/user meta vs. admin-group settings pages (with nested child
 * fields and taxonomy tabs) — plus get_field(), module(), and the inherited
 * arguments field() passes to its renderer.
 *
 * @since 6.0
 */

class MD_Fields_Capture extends md_fields {

	public $rendered = array();

	protected function render_field( $name, $id, $option, $args, $wrap_classes ) {
		$this->rendered = compact( 'name', 'id', 'option', 'args', 'wrap_classes' );
	}

}

class FieldsContextTest extends MD_TestCase {

	private function make_fields( $screen, $clean_id = 'my_page', $option = 'marketers_delight' ) {
		$fields = new md_fields( array(
			'id' => $clean_id,
			'clean_id' => $clean_id,
			'prefix' => 'md',
			'option' => $option,
		) );

		$fields->_get_screen = array_merge( array(
			'is_post' => false,
			'is_term' => false,
			'is_user' => false,
			'is_taxonomy' => false,
			'page' => $clean_id,
			'screen_id' => null,
			'md_tab' => '',
		), $screen );

		return $fields;
	}

	private function get_context( $fields ) {
		return $this->call( $fields, 'get_context' );
	}

	private function make_capturing_fields( $screen, $clean_id = 'my_page' ) {
		$fields = new MD_Fields_Capture( array(
			'id' => $clean_id,
			'clean_id' => $clean_id,
			'prefix' => 'md',
		) );
		$fields->_get_screen = array_merge( array(
			'is_post' => false,
			'is_term' => false,
			'is_user' => false,
			'is_taxonomy' => false,
			'page' => $clean_id,
			'screen_id' => null,
			'md_tab' => '',
		), $screen );

		return $fields;
	}

	// Post/term/user screens short-circuit before any admin-group lookup.

	public function test_post_screen_resolves_is_post_only() {
		$context = $this->get_context( $this->make_fields( array( 'is_post' => true ) ) );

		$this->assertTrue( $context['is_post'] );
		$this->assertFalse( $context['is_group'] );
	}

	public function test_term_screen_resolves_is_term_only() {
		$context = $this->get_context( $this->make_fields( array( 'is_term' => true ) ) );

		$this->assertTrue( $context['is_term'] );
		$this->assertFalse( $context['is_group'] );
	}

	public function test_user_screen_resolves_is_user_only() {
		$context = $this->get_context( $this->make_fields( array( 'is_user' => true ) ) );

		$this->assertTrue( $context['is_user'] );
		$this->assertFalse( $context['is_group'] );
	}

	// Plain admin settings page, not a registered admin group.

	public function test_non_group_admin_page_resolves_no_group() {
		md_test_set_filter( 'md_admin_groups', array() );

		$context = $this->get_context( $this->make_fields( array( 'page' => 'my_page' ) ) );

		$this->assertFalse( $context['is_group'] );
		$this->assertNull( $context['page_id'] );
	}

	// Registered admin group, root-level fields (clean_id === page_id).

	public function test_admin_group_root_is_not_child() {
		md_test_set_filter( 'md_admin_groups', array( 'my_page' => array( 'label' => 'My Page' ) ) );

		$context = $this->get_context( $this->make_fields( array( 'page' => 'my_page' ), 'my_page' ) );

		$this->assertTrue( $context['is_group'] );
		$this->assertSame( 'my_page', $context['page_id'] );
		$this->assertFalse( $context['is_child'] );
	}

	// Registered admin group, nested child field (clean_id !== page_id).

	public function test_admin_group_child_field_is_child() {
		md_test_set_filter( 'md_admin_groups', array( 'my_page' => array( 'label' => 'My Page' ) ) );

		$context = $this->get_context( $this->make_fields( array( 'page' => 'my_page' ), 'child_field' ) );

		$this->assertTrue( $context['is_group'] );
		$this->assertSame( 'my_page', $context['page_id'] );
		$this->assertTrue( $context['is_child'] );
	}

	// Taxonomy tab layered on top of an admin group only registers when
	// is_taxonomy is set, and reads its value from md_tab.

	public function test_admin_group_taxonomy_tab_resolves_taxonomy() {
		md_test_set_filter( 'md_admin_groups', array( 'my_page' => array( 'label' => 'My Page' ) ) );

		$context = $this->get_context( $this->make_fields( array(
			'page' => 'my_page',
			'is_taxonomy' => true,
			'md_tab' => 'category',
		), 'my_page' ) );

		$this->assertSame( 'category', $context['taxonomy'] );
	}

	public function test_admin_group_without_taxonomy_flag_has_blank_taxonomy() {
		md_test_set_filter( 'md_admin_groups', array( 'my_page' => array( 'label' => 'My Page' ) ) );

		$context = $this->get_context( $this->make_fields( array(
			'page' => 'my_page',
			'is_taxonomy' => false,
			'md_tab' => 'category',
		), 'my_page' ) );

		$this->assertSame( '', $context['taxonomy'] );
	}

	// get_field() — post/term/user meta paths.

	public function test_get_field_post_screen_reads_post_meta() {
		md_test_set_post_meta( array( 'title' => 'Hello' ) );

		$fields = $this->make_fields( array( 'is_post' => true ) );

		$this->assertSame( 'Hello', $fields->get_field( 'title' ) );
	}

	public function test_get_field_term_screen_reads_term_meta() {
		md_test_set_term_meta( array( 'title' => 'Term Title' ) );

		$fields = $this->make_fields( array( 'is_term' => true ) );

		$this->assertSame( 'Term Title', $fields->get_field( 'title' ) );
	}

	public function test_get_field_user_screen_reads_user_meta() {
		md_test_set_user_meta( array( 'title' => 'User Title' ) );

		$fields = $this->make_fields( array( 'is_user' => true ) );

		$this->assertSame( 'User Title', $fields->get_field( 'title' ) );
	}

	// get_field() — admin group nesting, root vs. child vs. taxonomy tab.

	public function test_get_field_non_group_reads_top_level_setting() {
		md_test_set_filter( 'md_admin_groups', array() );
		md_test_set_settings( array( 'title' => 'Plain Setting' ) );

		$fields = $this->make_fields( array( 'page' => 'my_page' ) );

		$this->assertSame( 'Plain Setting', $fields->get_field( 'title' ) );
	}

	public function test_get_field_non_group_reads_only_stored_custom_option_values() {
		md_test_set_filter( 'md_admin_groups', array() );
		md_test_set_filter( 'md_setting_defaults_client_portal', array(
			'title' => 'Default Title',
			'enabled' => true,
		) );
		md_test_set_option( 'client_portal', array( 'enabled' => false ) );

		$fields = $this->make_fields( array( 'page' => 'my_page' ), 'my_page', 'client_portal' );

		$this->assertNull( $fields->get_field( 'title' ) );
		$this->assertFalse( $fields->get_field( 'enabled', true ) );
	}

	public function test_get_field_preserves_falsey_setting_values() {
		md_test_set_filter( 'md_admin_groups', array() );
		md_test_set_settings( array( 'disabled' => false, 'count' => 0, 'value' => '0' ) );

		$fields = $this->make_fields( array( 'page' => 'my_page' ) );

		$this->assertFalse( $fields->get_field( 'disabled', true ) );
		$this->assertSame( 0, $fields->get_field( 'count', 10 ) );
		$this->assertSame( '0', $fields->get_field( 'value', 'fallback' ) );
	}

	public function test_get_field_group_child_reads_nested_page_setting() {
		md_test_set_filter( 'md_admin_groups', array( 'my_page' => array( 'label' => 'My Page' ) ) );
		md_test_set_settings( array( 'my_page' => array( 'title' => 'Child Setting' ) ) );

		$fields = $this->make_fields( array( 'page' => 'my_page' ), 'child_field' );

		$this->assertSame( 'Child Setting', $fields->get_field( 'title' ) );
	}

	public function test_get_field_group_root_with_taxonomy_reads_taxonomy_nested_setting() {
		md_test_set_filter( 'md_admin_groups', array( 'my_page' => array( 'label' => 'My Page' ) ) );
		md_test_set_settings( array( 'my_page' => array( 'category' => array( 'title' => 'Tax Setting' ) ) ) );

		$fields = $this->make_fields( array(
			'page' => 'my_page',
			'is_taxonomy' => true,
			'md_tab' => 'category',
		), 'my_page' );

		$this->assertSame( 'Tax Setting', $fields->get_field( 'title' ) );
	}

	// Note: for a child field under a taxonomy tab, get_field() narrows to
	// option[page_id][taxonomy] and stops there — it does NOT nest one level
	// further by clean_id the way field()/module() do. That's existing
	// behavior (present before this refactor), preserved verbatim here.

	public function test_get_field_group_child_with_taxonomy_reads_taxonomy_nested_setting() {
		md_test_set_filter( 'md_admin_groups', array( 'my_page' => array( 'label' => 'My Page' ) ) );
		md_test_set_settings( array( 'my_page' => array( 'category' => array( 'title' => 'Tax Setting' ) ) ) );

		$fields = $this->make_fields( array(
			'page' => 'my_page',
			'is_taxonomy' => true,
			'md_tab' => 'category',
		), 'child_field' );

		$this->assertSame( 'Tax Setting', $fields->get_field( 'title' ) );
	}

	// module() — post/term meta paths prefix keys with clean_id.

	public function test_module_post_screen_reads_post_meta_prefixed_with_clean_id() {
		md_test_set_post_meta( array( 'my_page' => array( 'enabled' => true ) ) );

		$fields = $this->make_fields( array( 'is_post' => true ) );

		$this->assertTrue( $fields->module( array( 'enabled' ) ) );
	}

	public function test_module_term_screen_reads_term_meta_when_screen_id_present() {
		md_test_set_term_meta( array( 'my_page' => array( 'enabled' => true ) ) );

		$fields = $this->make_fields( array( 'is_term' => true, 'screen_id' => 5 ) );

		$this->assertTrue( $fields->module( array( 'enabled' ) ) );
	}

	// module() — admin group prefix building: page_id, then taxonomy (if
	// active), then clean_id (only when this is a nested child field).

	public function test_module_non_group_prefixes_with_clean_id_only() {
		md_test_set_filter( 'md_admin_groups', array() );
		md_test_set_settings( array( 'my_page' => array( 'enabled' => true ) ) );

		$fields = $this->make_fields( array( 'page' => 'my_page' ) );

		$this->assertTrue( $fields->module( array( 'enabled' ) ) );
	}

	public function test_module_non_group_reads_custom_option() {
		md_test_set_filter( 'md_admin_groups', array() );
		md_test_set_option( 'client_portal', array( 'my_page' => array( 'enabled' => true ) ) );

		$fields = $this->make_fields( array( 'page' => 'my_page' ), 'my_page', 'client_portal' );

		$this->assertTrue( $fields->module( array( 'enabled' ) ) );
	}

	public function test_module_group_root_prefixes_with_page_id_only() {
		md_test_set_filter( 'md_admin_groups', array( 'my_page' => array( 'label' => 'My Page' ) ) );
		md_test_set_settings( array( 'my_page' => array( 'enabled' => true ) ) );

		$fields = $this->make_fields( array( 'page' => 'my_page' ), 'my_page' );

		$this->assertTrue( $fields->module( array( 'enabled' ) ) );
	}

	public function test_module_group_child_prefixes_with_page_id_then_clean_id() {
		md_test_set_filter( 'md_admin_groups', array( 'my_page' => array( 'label' => 'My Page' ) ) );
		md_test_set_settings( array( 'my_page' => array( 'child_field' => array( 'enabled' => true ) ) ) );

		$fields = $this->make_fields( array( 'page' => 'my_page' ), 'child_field' );

		$this->assertTrue( $fields->module( array( 'enabled' ) ) );
	}

	public function test_module_group_root_with_taxonomy_prefixes_page_id_then_taxonomy() {
		md_test_set_filter( 'md_admin_groups', array( 'my_page' => array( 'label' => 'My Page' ) ) );
		md_test_set_settings( array( 'my_page' => array( 'category' => array( 'enabled' => true ) ) ) );

		$fields = $this->make_fields( array(
			'page' => 'my_page',
			'is_taxonomy' => true,
			'md_tab' => 'category',
		), 'my_page' );

		$this->assertTrue( $fields->module( array( 'enabled' ) ) );
	}

	public function test_module_group_child_with_taxonomy_prefixes_page_id_taxonomy_clean_id() {
		md_test_set_filter( 'md_admin_groups', array( 'my_page' => array( 'label' => 'My Page' ) ) );
		md_test_set_settings( array( 'my_page' => array( 'category' => array( 'child_field' => array( 'enabled' => true ) ) ) ) );

		$fields = $this->make_fields( array(
			'page' => 'my_page',
			'is_taxonomy' => true,
			'md_tab' => 'category',
		), 'child_field' );

		$this->assertTrue( $fields->module( array( 'enabled' ) ) );
	}

	// Inherited field presentation resolves the tier above the current screen.

	public function test_parent_value_taxonomy_settings_reads_post_type_tier() {
		md_test_set_filter( 'md_admin_groups', array( 'post' => array( 'label' => 'Posts' ) ) );
		md_test_set_settings( array( 'post' => array( 'loop' => array( 'date' => array( 'group' => true ) ) ) ) );

		$fields = $this->make_fields( array(
			'page' => 'post',
			'is_taxonomy' => true,
			'md_tab' => 'category',
		), 'loop' );
		$parent = $this->call( $fields, 'parent_value', array( array( 'date', 'group' ), false ) );

		$this->assertTrue( $parent['available'] );
		$this->assertTrue( $parent['value'] );
	}

	public function test_parent_value_post_screen_reads_post_type_tier() {
		md_test_set_settings( array( 'post' => array( 'loop' => array( 'date' => array( 'group' => true ) ) ) ) );

		$fields = $this->make_fields( array(
			'is_post' => true,
			'post_type' => 'post',
		), 'loop' );
		$parent = $this->call( $fields, 'parent_value', array( array( 'date', 'group' ), false ) );

		$this->assertTrue( $parent['available'] );
		$this->assertTrue( $parent['value'] );
	}

	public function test_parent_value_term_screen_prefers_falsey_taxonomy_tier() {
		md_test_set_settings( array(
			'post' => array(
				'loop' => array( 'date' => array( 'group' => true ) ),
				'category' => array( 'loop' => array( 'date' => array( 'group' => 0 ) ) )
			)
		) );

		$fields = $this->make_fields( array(
			'is_term' => true,
			'post_type' => 'post',
			'taxonomy' => 'category',
		), 'loop' );
		$parent = $this->call( $fields, 'parent_value', array( array( 'date', 'group' ), false ) );

		$this->assertTrue( $parent['available'] );
		$this->assertSame( 0, $parent['value'] );
	}

	public function test_inherited_checkbox_prepares_inverse_parent_action() {
		md_test_set_filter( 'md_admin_groups', array( 'post' => array( 'label' => 'Posts' ) ) );
		md_test_set_settings( array( 'post' => array( 'loop' => array( 'date' => array( 'group' => true ) ) ) ) );

		$fields = $this->make_fields( array(
			'page' => 'post',
			'is_taxonomy' => true,
			'md_tab' => 'category',
		), 'loop' );
		$args = $this->call( $fields, 'prepare_inheritance', array(
			'date',
			array( 'group' => 0 ),
			array(
				'type' => 'checkbox',
				'options' => array( 'group' => 'Group posts by month' ),
				'inherit' => array( 'group' => array(
					'on' => 'Group posts by month',
					'off' => 'Do not group posts by month',
				) )
			)
		) );

		$this->assertSame( 0, $args['_inherit']['group']['value'] );
		$this->assertSame( 'Do not group posts by month', $args['_inherit']['group']['label'] );
		$this->assertTrue( $args['_inherit']['group']['checked'] );
	}

	public function test_inherited_checkbox_can_enable_falsey_parent() {
		md_test_set_filter( 'md_admin_groups', array( 'post' => array( 'label' => 'Posts' ) ) );
		md_test_set_settings( array( 'post' => array( 'loop' => array( 'date' => array( 'group' => 0 ) ) ) ) );

		$fields = $this->make_fields( array(
			'page' => 'post',
			'is_taxonomy' => true,
			'md_tab' => 'category',
		), 'loop' );
		$args = $this->call( $fields, 'prepare_inheritance', array(
			'date',
			array(),
			array(
				'type' => 'checkbox',
				'options' => array( 'group' => 'Group posts by month' ),
				'inherit' => array( 'group' => array(
					'on' => 'Group posts by month',
					'off' => 'Do not group posts by month',
				) )
			)
		) );

		$this->assertSame( 1, $args['_inherit']['group']['value'] );
		$this->assertSame( 0, $args['_inherit']['group']['parent'] );
		$this->assertSame( 'Group posts by month', $args['_inherit']['group']['label'] );
		$this->assertFalse( $args['_inherit']['group']['checked'] );
	}

	public function test_inherited_number_uses_parent_as_placeholder() {
		md_test_set_filter( 'md_admin_groups', array( 'post' => array( 'label' => 'Posts' ) ) );
		md_test_set_settings( array( 'post' => array( 'loop' => array( 'columns' => 3 ) ) ) );

		$fields = $this->make_fields( array(
			'page' => 'post',
			'is_taxonomy' => true,
			'md_tab' => 'category',
		), 'loop' );
		$args = $this->call( $fields, 'prepare_inheritance', array(
			'columns',
			'',
			array( 'type' => 'number', 'inherit' => array( 'default' => 1 ) )
		) );

		$this->assertSame( 3, $args['placeholder'] );
	}

	public function test_inherited_select_labels_parent_option() {
		md_test_set_filter( 'md_admin_groups', array( 'post' => array( 'label' => 'Posts' ) ) );
		md_test_set_settings( array( 'post' => array( 'loop' => array( 'order' => 'ASC' ) ) ) );

		$fields = $this->make_fields( array(
			'page' => 'post',
			'is_taxonomy' => true,
			'md_tab' => 'category',
		), 'loop' );
		$args = $this->call( $fields, 'prepare_inheritance', array(
			'order',
			'',
			array(
				'type' => 'select',
				'empty_label' => 'Descending',
				'options' => array( 'ASC' => 'Ascending' ),
				'inherit' => array( 'default' => 'DESC' )
			)
		) );

		$this->assertSame( 'Use default (Ascending)', $args['empty_label'] );
	}

	public function test_inherited_select_supports_custom_label_format() {
		md_test_set_filter( 'md_admin_groups', array( 'post' => array( 'label' => 'Posts' ) ) );
		md_test_set_settings( array( 'post' => array( 'layout' => array( 'position' => 'inline' ) ) ) );

		$fields = $this->make_fields( array(
			'page' => 'post',
			'is_taxonomy' => true,
			'md_tab' => 'category',
		), 'layout' );
		$args = $this->call( $fields, 'prepare_inheritance', array(
			'position',
			'',
			array(
				'type' => 'select',
				'empty_label' => 'Top of post',
				'options' => array( 'inline' => 'Top of post' ),
				'inherit' => array( 'default' => 'inline', 'format' => 'Show default (%s)' )
			)
		) );

		$this->assertSame( 'Show default (Top of post)', $args['empty_label'] );
	}

	public function test_inherited_select_keeps_fallback_for_unknown_parent_option() {
		md_test_set_filter( 'md_admin_groups', array( 'post' => array( 'label' => 'Posts' ) ) );
		md_test_set_settings( array( 'post' => array( 'loop' => array( 'order' => 'STALE' ) ) ) );

		$fields = $this->make_fields( array(
			'page' => 'post',
			'is_taxonomy' => true,
			'md_tab' => 'category',
		), 'loop' );
		$args = $this->call( $fields, 'prepare_inheritance', array(
			'order',
			'',
			array(
				'type' => 'select',
				'empty_label' => 'Descending',
				'options' => array( 'ASC' => 'Ascending' ),
				'inherit' => true
			)
		) );

		$this->assertSame( 'Descending', $args['empty_label'] );
	}

	public function test_root_number_uses_declared_inherit_default_as_placeholder() {
		md_test_set_filter( 'md_admin_groups', array() );

		$fields = $this->make_fields( array(), 'loop' );
		$args = $this->call( $fields, 'prepare_inheritance', array(
			'offset',
			'',
			array( 'type' => 'number', 'inherit' => array( 'default' => 0 ) )
		) );

		$this->assertSame( 0, $args['placeholder'] );
	}

	public function test_field_passes_raw_zero_override_and_prepared_inheritance_to_renderer() {
		md_test_set_filter( 'md_admin_groups', array( 'post' => array( 'label' => 'Posts' ) ) );
		md_test_set_settings( array(
			'post' => array(
				'loop' => array( 'date' => array( 'group' => true ) ),
				'category' => array( 'loop' => array( 'date' => array( 'group' => 0 ) ) )
			)
		) );

		$fields = $this->make_capturing_fields( array(
			'page' => 'post',
			'is_taxonomy' => true,
			'md_tab' => 'category',
		), 'loop' );
		$fields->field( 'date', array(
			'type' => 'checkbox',
			'options' => array( 'group' => 'Group posts by month' ),
			'inherit' => array( 'group' => array(
				'on' => 'Group posts by month',
				'off' => 'Do not group posts by month',
			) )
		) );

		$this->assertSame( 'marketers_delight[post][category][loop][date]', $fields->rendered['name'] );
		$this->assertSame( array( 'group' => 0 ), $fields->rendered['option'] );
		$this->assertSame( 0, $fields->rendered['args']['_inherit']['group']['value'] );
		$this->assertTrue( $fields->rendered['args']['_inherit']['group']['checked'] );
	}

}
