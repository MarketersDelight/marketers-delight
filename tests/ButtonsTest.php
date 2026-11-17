<?php
/**
 * Basic buttons (6.2.3): variants, link field mapping and the buttons group.
 */

require_once dirname( __DIR__ ) . '/functions/link-functions.php';

if ( ! function_exists( 'md_get_visibility_classes' ) ) {
	function md_get_visibility_classes( $values ) {
		return array();
	}
}

if ( ! function_exists( 'esc_url' ) ) {
	function esc_url( $value = '', ...$args ) {
		return (string) $value;
	}
}

if ( ! function_exists( 'md_check_condition' ) ) {
	function md_check_condition( $values ) {
		return true;
	}
}

class ButtonsTest extends MD_TestCase {

	private function link( $fields ) {
		return md_get_link( array_merge( array( 'name' => 'Go', 'url' => 'https://example.com', 'style' => 'button' ), $fields ) );
	}

	public function test_variants_are_secondary_outline_and_text() {
		$this->assertSame( array( 'secondary', 'outline', 'text' ), array_keys( md_button_types() ) );
	}

	public function test_primary_is_plain_button() {
		$html = $this->link( array() );

		$this->assertStringContainsString( 'class="link button"', $html );
	}

	public function test_variant_prints_its_class() {
		$this->assertStringContainsString( 'button-secondary', $this->link( array( 'button_type' => 'secondary' ) ) );
		$this->assertStringContainsString( 'button-text', $this->link( array( 'button_type' => 'text' ) ) );
		$this->assertStringNotContainsString( 'button-nope', $this->link( array( 'button_type' => 'nope' ) ) );
	}

	public function test_old_outline_and_frame_read_as_outline() {
		$this->assertStringContainsString( 'button-outline', $this->link( array( 'button_style' => array( 'outline' => true ) ) ) );
		$this->assertStringContainsString( 'button-outline', $this->link( array( 'button_style' => array( 'frame' => true ) ) ) );
		$this->assertStringNotContainsString( 'button-frame', $this->link( array( 'button_style' => array( 'frame' => true ) ) ) );
	}

	public function test_new_key_wins_over_old_outline() {
		$html = $this->link( array( 'button_type' => 'secondary', 'button_style' => array( 'outline' => true ) ) );

		$this->assertStringContainsString( 'button-secondary', $html );
		$this->assertStringNotContainsString( 'button-outline', $html );
	}

	public function test_colour_override_tints_text_on_outline_and_fills_others() {
		$outline = $this->link( array( 'button_type' => 'outline', 'color' => '#123456' ) );
		$solid = $this->link( array( 'button_type' => 'secondary', 'color' => '#123456' ) );

		$this->assertMatchesRegularExpression( '/style="[^"]*color: ?#123456/i', $outline );
		$this->assertDoesNotMatchRegularExpression( '/background-color: ?#123456/i', $outline );
		$this->assertMatchesRegularExpression( '/background-color: ?#123456/i', $solid );
	}

	public function test_button_defaults_print_nothing_new() {
		$buttons = ( new md_design )->buttons();

		$this->assertSame( 'var(--md-border-radius)', $buttons['radius'] );
		$this->assertSame( 'var(--md-box-shadow-small)', $buttons['shadow'] );
		$this->assertSame( '', $buttons['font_weight'] );
		$this->assertSame( '', $buttons['text_transform'] );
		$this->assertSame( '', $buttons['form'] );
	}

	public function test_button_settings_read_from_effects_and_ignore_bad_values() {
		md_test_set_settings( array( 'effects' => array( 'button_radius' => 30, 'button_weight' => '700', 'button_case' => 'shout', 'button_shadow' => array( 'remove' => true ), 'form_button' => 'outline' ) ) );
		$buttons = ( new md_design )->buttons();

		$this->assertSame( '30px', $buttons['radius'] );
		$this->assertSame( 'none', $buttons['shadow'] );
		$this->assertSame( '700', $buttons['font_weight'] );
		$this->assertSame( '', $buttons['text_transform'] );
		$this->assertSame( 'outline', $buttons['form'] );
	}

	public function test_variant_css_is_variables_only() {
		$css = ( new md_design )->button_vars( 'outline' );

		$this->assertSame( ' --md-button-bg: transparent; --md-button-text: var(--md-action-primary); --md-button-border: 3px solid currentColor;', $css );
		$this->assertSame( '', ( new md_design )->button_vars( 'nope' ) );
	}

}
