<?php
/**
 * Tests the native design data generated for theme.json.
 *
 * @since 6.0
 */

class ThemeJsonTest extends MD_TestCase {

	private $json;

	protected function setUp(): void {
		parent::setUp();
		$this->json = ( new md_theme_json )->build();
	}

	private function presets_by_slug( $presets ) {
		$indexed = array();

		foreach ( $presets as $preset )
			$indexed[$preset['slug']] = $preset;

		return $indexed;
	}

	public function test_uses_the_wordpress_seven_version_three_schema() {
		$this->assertSame( 'https://schemas.wp.org/wp/7.0/theme.json', $this->json['$schema'] );
		$this->assertSame( 3, $this->json['version'] );
		$this->assertArrayNotHasKey( 'defaultFontSizes', $this->json['settings']['typography'] );
	}

	public function test_typography_presets_are_fluid_and_used_by_elements() {
		$font_sizes = $this->presets_by_slug( $this->json['settings']['typography']['fontSizes'] );

		$this->assertSame( array( 'min' => '18px', 'max' => '20px' ), $font_sizes['normal']['fluid'] );
		$this->assertSame( array( 'min' => '34px', 'max' => '45px' ), $font_sizes['h-1']['fluid'] );

		foreach ( array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) as $heading ) {
			$slug = 'h-' . substr( $heading, 1 );

			$this->assertArrayHasKey( $slug, $font_sizes );
			$this->assertSame(
				"var:preset|font-size|{$slug}",
				$this->json['styles']['elements'][$heading]['typography']['fontSize']
			);
		}

		$this->assertIsString( $this->json['styles']['elements']['h1']['typography']['lineHeight'] );
	}

	public function test_spacing_and_button_styles_use_md_tokens() {
		$settings = $this->json['settings']['spacing'];
		$spacing = $this->presets_by_slug( $settings['spacingSizes'] );
		$button = $this->json['styles']['elements']['button'];

		$this->assertTrue( $settings['blockGap'] );
		$this->assertTrue( $settings['margin'] );
		$this->assertTrue( $settings['padding'] );
		$this->assertFalse( $settings['customSpacingSize'] );
		$this->assertFalse( $settings['defaultSpacingSizes'] );
		$this->assertSame( 0, $settings['spacingScale']['steps'] );
		$this->assertCount( 8, $spacing );
		$this->assertSame( '32px', $spacing['single']['size'] );
		$this->assertSame( 'var:preset|border-radius|rounded', $button['border']['radius'] );
		$this->assertSame( '#22A340', $button['color']['background'] );
		$this->assertSame( 'var:preset|spacing|half', $button['spacing']['padding']['top'] );
		$this->assertSame(
			'calc(var(--wp--preset--spacing--half) + var(--wp--preset--spacing--third))',
			$button['spacing']['padding']['right']
		);
		$this->assertArrayNotHasKey( 'border', $this->json['styles']['blocks']['core/button'] );
	}

	public function test_dimension_controls_expose_aspect_ratio_only() {
		$this->assertTrue( $this->json['settings']['dimensions']['aspectRatio'] );
		$this->assertArrayNotHasKey( 'minHeight', $this->json['settings']['dimensions'] );
	}

	public function test_effect_presets_use_the_md_radius_and_shadow_scale() {
		$radii = $this->presets_by_slug( $this->json['settings']['border']['radiusSizes'] );
		$shadows = $this->presets_by_slug( $this->json['settings']['shadow']['presets'] );

		$this->assertTrue( $this->json['settings']['border']['radius'] );
		$this->assertSame( '8px', $radii['rounded']['size'] );
		$this->assertFalse( $this->json['settings']['shadow']['defaultPresets'] );
		$this->assertCount( 4, $shadows );
		$this->assertSame( '0 1px 3px rgba(0, 0, 0, 0.15)', $shadows['small']['shadow'] );
		$this->assertSame( '0 16px 48px rgba(0, 0, 0, 0.20)', $shadows['huge']['shadow'] );
	}

	public function test_layout_uses_md_post_and_alignwide_widths() {
		$this->assertSame( '672px', $this->json['settings']['layout']['contentSize'] );
		$this->assertSame( '896px', $this->json['settings']['layout']['wideSize'] );
	}

}
