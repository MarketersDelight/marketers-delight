<?php
/**
 * Pins down md_sanitize's null-vs-blank return convention per field type:
 * null means "invalid or not submitted"; '' / false / empty arrays are
 * clearing signals removed by md_save.
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

	public function test_checkbox_group_rejects_unregistered_keys() {
		$input = array( 'a' => true, 'injected' => true );
		$this->assertSame( array( 'a' => true ), $this->sanitize->checkbox( $input, array( 'options' => array( 'a', 'b' ) ) ) );
	}

	public function test_checkbox_group_accepts_associative_option_keys() {
		$input = array( 'a' => true );
		$fields = array( 'options' => array( 'a' => 'Option A', 'b' => 'Option B' ) );
		$this->assertSame( array( 'a' => true ), $this->sanitize->checkbox( $input, $fields ) );
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

	// number()

	public function test_number_strips_non_digits() {
		$this->assertSame( 42, $this->sanitize->number( 'a4b2c' ) );
	}

	public function test_number_blank_returns_empty_string() {
		$this->assertSame( '', $this->sanitize->number( '' ) );
	}

	public function test_number_zero_returns_integer() {
		$this->assertSame( 0, $this->sanitize->number( '0' ) );
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

	public function test_select_normalizes_numeric_ids_to_strings() {
		$this->assertSame( '123', $this->sanitize->select( '123', array( 123 ) ) );
		$this->assertSame( array( '123' ), $this->sanitize->select( array( 123 ), array( '123' ) ) );
	}

	public function test_select_does_not_match_false_to_zero() {
		$this->assertSame( '', $this->sanitize->select( false, array( 0 ) ) );
	}

	public function test_select_multi_rejects_nested_and_boolean_values() {
		$this->assertSame(
			array( '0' ),
			$this->sanitize->select( array( array( 'a' ), false, '0' ), array( 'a', 0 ) )
		);
	}

	// color()

	// A color matching its default is a clearing signal so md_save can
	// remove a previously stored override.

	public function test_color_blank_with_no_default_returns_empty_string() {
		$this->assertSame( '', $this->sanitize->color( '' ) );
	}

	public function test_color_matching_explicit_default_returns_empty_string() {
		$this->assertSame( '', $this->sanitize->color( '#AE2525', array( 'default' => '#AE2525' ) ) );
	}

	public function test_color_differing_from_default_returns_sanitized_value() {
		$this->assertSame( '#ff0000', $this->sanitize->color( '#FF0000', array( 'default' => '#AE2525' ) ) );
	}

	public function test_color_valid_hex_passes_through() {
		$this->assertSame( '#ff0000', $this->sanitize->color( '#FF0000' ) );
	}

	public function test_color_valid_rgba_passes_through() {
		$this->assertSame( 'rgba(255, 10, 0, 0.5)', $this->sanitize->color( 'rgba(255, 10, 0, 0.5)' ) );
	}

	public function test_color_rejects_invalid_rgba() {
		$this->assertSame( '', $this->sanitize->color( 'rgba(256, 10, 0, 0.5)' ) );
		$this->assertSame( '', $this->sanitize->color( 'rgba(255, 10, 0, 2)' ) );
		$this->assertSame( '', $this->sanitize->color( 'rgba(255, 10, 0, 0.5)junk' ) );
	}

	public function test_color_invalid_hex_returns_empty_string_not_null() {
		$this->assertSame( '', $this->sanitize->color( 'not-a-color' ) );
	}

	public function test_color_scalar_palette_reference_returns_sanitized_key() {
		md_test_set_filter( 'md_color_palette', array( 'primary' => array( 'hex' => '#AE2525', 'name' => 'Primary' ) ) );

		$this->assertSame( 'primary', $this->sanitize->color( 'primary' ) );
	}

	public function test_color_palette_mode_returns_sanitized_key() {
		md_test_set_filter( 'md_color_palette', array( 'primary' => array( 'hex' => '#AE2525', 'name' => 'Primary' ) ) );

		$this->assertSame( 'primary', $this->sanitize->color( array(
			'mode' => 'palette',
			'palette' => 'primary'
		) ) );
	}

	public function test_color_palette_matching_field_default_returns_empty_string() {
		md_test_set_filter( 'md_color_palette', array( 'primary' => array( 'hex' => '#AE2525', 'name' => 'Primary' ) ) );

		$result = $this->sanitize->color( array(
			'mode' => 'palette',
			'palette' => 'primary'
		), array( 'palette' => 'primary' ) );

		$this->assertSame( '', $result );
	}

	public function test_color_default_mode_returns_empty_string() {
		$this->assertSame( '', $this->sanitize->color( array( 'mode' => 'default' ) ) );
	}

	public function test_color_custom_mode_returns_color_value() {
		$this->assertSame( '#123456', $this->sanitize->color( array(
			'mode' => 'custom',
			'custom' => '#123456'
		) ) );
	}

	public function test_color_rejects_unknown_selection_mode() {
		$this->assertSame( '', $this->sanitize->color( array(
			'mode' => 'inherit',
			'palette' => 'primary',
			'custom' => '#123456'
		) ) );
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

		$this->assertSame( 42, $result['id'] );
	}

	public function test_upload_defaults_to_media_type_when_unset() {
		$result = $this->sanitize->upload( array( 'id' => '42' ), array() );

		$this->assertSame( 42, $result['id'] );
	}

	public function test_upload_invalid_id_returns_empty_string() {
		$this->assertSame( '', $this->sanitize->upload( array( 'id' => 'invalid' ), array() ) );
	}

	public function test_upload_rejects_non_array_input() {
		$this->assertSame( '', $this->sanitize->upload( '42', array() ) );
	}

	public function test_upload_multiple_preserves_id_list_contract() {
		$result = $this->sanitize->upload( array( 'id' => '4,7' ), array( 'multiple' => true ) );

		$this->assertSame( '4,7', $result['id'] );
		$this->assertSame( array( 4, 7 ), $result['ids'] );
	}

}
