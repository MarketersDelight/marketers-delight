<?php
/**
 * Pins down md_sanitize's null-vs-blank return convention per field type:
 * null means "no value, don't save"; '' / false / empty array are real,
 * storable "cleared" values. See the docblock on md_validate::validate_field()
 * for the full convention this was written to protect.
 *
 * @since 6.0
 */

class SanitizeTest extends MD_TestCase {

	private $sanitize;

	protected function setUp(): void {
		parent::setUp();
		$this->sanitize = new md_sanitize;
	}

	// checkbox()

	public function test_checkbox_group_all_unchecked_returns_empty_array() {
		$this->assertSame( array(), $this->sanitize->checkbox( null, array( 'options' => array( 'a', 'b' ) ) ) );
	}

	public function test_checkbox_group_keeps_only_checked_keys() {
		$input = array( 'a' => true, 'b' => false );
		$this->assertSame( array( 'a' => true ), $this->sanitize->checkbox( $input, array( 'options' => array( 'a', 'b' ) ) ) );
	}

	public function test_single_checkbox_unchecked_returns_false_not_null() {
		$this->assertFalse( $this->sanitize->checkbox( null ) );
	}

	public function test_single_checkbox_checked_returns_true() {
		$this->assertTrue( $this->sanitize->checkbox( '1' ) );
	}

	// text()

	public function test_text_blank_returns_empty_string_not_null() {
		$this->assertSame( '', $this->sanitize->text( '' ) );
	}

	public function test_text_sanitizes_via_wp_kses_post() {
		$this->assertSame( 'hello', $this->sanitize->text( 'hello' ) );
	}

	public function test_text_map_mode_routes_through_ids() {
		$result = $this->sanitize->text( '1,2,3', array( 'map' => true ) );

		$this->assertSame( '1,2,3', $result['value'] );
		$this->assertSame( array( '1', '2', '3' ), $result['values'] );
	}

	// number()

	public function test_number_strips_non_digits() {
		$this->assertSame( '42', $this->sanitize->number( 'a4b2c' ) );
	}

	public function test_number_blank_returns_empty_string() {
		$this->assertSame( '', $this->sanitize->number( '' ) );
	}

	// url()

	public function test_url_blank_returns_empty_string() {
		$this->assertSame( '', $this->sanitize->url( '' ) );
	}

	// select()

	public function test_select_invalid_single_value_returns_empty_string() {
		$this->assertSame( '', $this->sanitize->select( 'nope', array( 'a', 'b' ) ) );
	}

	public function test_select_valid_single_value_passes_through() {
		$this->assertSame( 'a', $this->sanitize->select( 'a', array( 'a', 'b' ) ) );
	}

	public function test_select_multi_drops_invalid_values() {
		$this->assertSame( array( 'a' ), $this->sanitize->select( array( 'a', 'nope' ), array( 'a', 'b' ) ) );
	}

	public function test_select_dynamic_bypasses_options_whitelist() {
		$this->assertSame( 'anything', $this->sanitize->select( 'anything', array(), true ) );
	}

	public function test_select_non_array_options_falls_back_to_empty_list() {
		$this->assertSame( '', $this->sanitize->select( 'a', null ) );
	}

	// color()

	// A color field represents "no override" as omitting the field
	// entirely (null), not as an empty string — unlike text/number/url,
	// where '' is itself a legitimate stored "cleared" value. Blank input
	// with no 'default' configured matches the implicit '' default, so
	// it's treated as "no override to store."

	public function test_color_blank_with_no_default_returns_null() {
		$this->assertNull( $this->sanitize->color( '' ) );
	}

	public function test_color_matching_explicit_default_returns_null() {
		$this->assertNull( $this->sanitize->color( '#AE2525', array( 'default' => '#AE2525' ) ) );
	}

	public function test_color_differing_from_default_returns_sanitized_value() {
		$this->assertSame( '#FF0000', $this->sanitize->color( '#FF0000', array( 'default' => '#AE2525' ) ) );
	}

	public function test_color_valid_hex_passes_through() {
		$this->assertSame( '#FF0000', $this->sanitize->color( '#FF0000' ) );
	}

	public function test_color_invalid_hex_returns_empty_string_not_null() {
		$this->assertSame( '', $this->sanitize->color( 'not-a-color' ) );
	}

	public function test_color_inherit_reference_returns_sanitized_key() {
		md_test_set_filter( 'md_color_palette', array( 'primary' => array( 'hex' => '#AE2525', 'name' => 'Primary' ) ) );

		$this->assertSame( 'primary', $this->sanitize->color( array( 'inherit' => 'primary' ) ) );
	}

	public function test_color_inherit_matching_fields_default_returns_empty_string() {
		md_test_set_filter( 'md_color_palette', array( 'primary' => array( 'hex' => '#AE2525', 'name' => 'Primary' ) ) );

		$result = $this->sanitize->color( array( 'inherit' => 'primary' ), array( 'inherit' => 'primary' ) );

		$this->assertSame( '', $result );
	}

	// upload()

	public function test_upload_wrong_upload_type_returns_null() {
		$this->assertNull( $this->sanitize->upload( array( 'id' => '5' ), array( 'upload_type' => 'file' ) ) );
	}

	public function test_upload_empty_id_returns_empty_string_not_null() {
		$this->assertSame( '', $this->sanitize->upload( array( 'id' => '' ), array( 'upload_type' => 'media' ) ) );
	}

	public function test_upload_valid_id_returns_array() {
		$result = $this->sanitize->upload( array( 'id' => '42' ), array( 'upload_type' => 'media' ) );

		$this->assertSame( '42', $result['id'] );
	}

	public function test_upload_defaults_to_media_type_when_unset() {
		$result = $this->sanitize->upload( array( 'id' => '42' ), array() );

		$this->assertSame( '42', $result['id'] );
	}

}
