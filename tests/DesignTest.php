<?php
/**
 * Tests the inherited typography scale and its body/h1 safety constraints.
 *
 * @since 6.0
 */

class DesignTest extends MD_TestCase {

	private $design;

	protected function setUp(): void {
		parent::setUp();
		$this->design = new md_design;
	}

	private function sizes( $typography, $device ) {
		$sizes = array( $typography['body']['font_size'][$device] );

		foreach ( array( 'h6', 'h5', 'h4', 'h3', 'h2', 'h1' ) as $heading )
			$sizes[] = $typography[$heading]['font_size'][$device];

		return $sizes;
	}

	public function test_default_scale_uses_body_and_h1_as_geometric_anchors() {
		$typography = $this->design->defaults()['typography'];

		$this->assertEquals( array( 20, 23, 26, 30, 34, 39, 45 ), $this->sizes( $typography, 'desktop' ) );
		$this->assertEquals( array( 18, 20, 22, 25, 28, 31, 34 ), $this->sizes( $typography, 'mobile' ) );
	}

	public function test_alignwide_width_preserves_the_frontend_breakout_ratio() {
		$values = $this->design->values();
		$widths = $this->design->widths( $values );

		$this->assertSame( 896.0, $widths['alignwide_width'] );
	}

	public function test_font_inheritance_is_resolved_for_compilers() {
		$fonts = $this->design->fonts();

		$this->assertSame( 'normal', $fonts['body']['font_weight'] );
		$this->assertSame( 'bold', $fonts['body']['bold'] );
		$this->assertSame( $fonts['body']['font_family'], $fonts['heading']['font_family'] );
		$this->assertSame( 'bold', $fonts['heading']['font_weight'] );
		$this->assertArrayNotHasKey( 'heading_overrides', $fonts );
	}

	public function test_saved_heading_fonts_override_body_inheritance() {
		md_test_set_settings( array(
			'typography' => array(
				'body' => array(
					'font_family' => 'Body Font',
					'font_weight' => '400',
					'bold' => '800'
				),
				'h1' => array(
					'font_family' => 'Heading Font',
					'font_weight' => '600'
				),
				'h2' => array(
					'font_family' => 'Secondary Heading Font'
				),
				'h3' => array(
					'font_weight' => '300'
				)
			)
		) );

		$fonts = $this->design->fonts();

		$this->assertSame( 'Body Font', $fonts['body']['font_family'] );
		$this->assertSame( '400', $fonts['body']['font_weight'] );
		$this->assertSame( '800', $fonts['body']['bold'] );
		$this->assertSame( 'Heading Font', $fonts['heading']['font_family'] );
		$this->assertSame( '600', $fonts['heading']['font_weight'] );
		$this->assertSame( array( 'font_family' => 'Secondary Heading Font' ), $fonts['heading_overrides']['h2'] );
		$this->assertSame( array( 'font_weight' => '300' ), $fonts['heading_overrides']['h3'] );
		$this->assertArrayNotHasKey( 'h4', $fonts['heading_overrides'] );
	}

	public function test_close_h1_values_are_given_room_for_six_ordered_steps() {
		md_test_set_settings( array(
			'typography' => array(
				'body' => array(
					'font_size' => array(
						'desktop' => 20,
						'mobile' => 18
					)
				),
				'h1' => array(
					'font_size' => array(
						'desktop' => 22,
						'mobile' => 19
					)
				)
			)
		) );

		$typography = $this->design->values()['typography'];

		$this->assertEquals( array( 20, 21, 22, 23, 24, 25, 26 ), $this->sizes( $typography, 'desktop' ) );
		$this->assertEquals( array( 18, 19, 20, 21, 22, 23, 24 ), $this->sizes( $typography, 'mobile' ) );
	}

	public function test_zero_anchors_fall_back_without_division_by_zero() {
		md_test_set_settings( array(
			'typography' => array(
				'body' => array(
					'font_size' => array(
						'desktop' => 0,
						'mobile' => 0
					)
				),
				'h1' => array(
					'font_size' => array(
						'desktop' => 0,
						'mobile' => 0
					)
				)
			)
		) );

		$typography = $this->design->values()['typography'];

		$this->assertEquals( array( 20, 21, 22, 23, 24, 25, 26 ), $this->sizes( $typography, 'desktop' ) );
		$this->assertEquals( array( 18, 19, 20, 21, 22, 23, 24 ), $this->sizes( $typography, 'mobile' ) );
	}

	public function test_positive_body_sizes_below_the_supported_minimum_are_clamped() {
		md_test_set_settings( array(
			'typography' => array(
				'body' => array(
					'font_size' => array(
						'desktop' => 5,
						'mobile' => 8
					)
				),
				'h1' => array(
					'font_size' => array(
						'desktop' => 10,
						'mobile' => 10
					)
				)
			)
		) );

		$typography = $this->design->values()['typography'];

		$this->assertEquals( array( 16, 17, 18, 19, 20, 21, 22 ), $this->sizes( $typography, 'desktop' ) );
		$this->assertEquals( array( 16, 17, 18, 19, 20, 21, 22 ), $this->sizes( $typography, 'mobile' ) );
	}

}
